<?php
	require 'dompdf/autoload.inc.php';
	use Dompdf\Dompdf;
    if($_GET['id']){
        $id = $_GET['id']; 
        $con = new PDO("mysql:host=localhost; dbname=handyman", "root", "");
        $con -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $query = "SELECT *,b.Amount AS amount,s.name AS service,ss.description AS selectedDescription,s.amount AS ServiceAmount,
                CONCAT(fc.Amount,' ',COALESCE(CONCAT('/',fc.unit),'')) AS ChoiceAmount,
                CONCAT(us.firstName,' ', us.middleName,' ',us.lastName) AS Handyman,
                CONCAT(u.firstName,' ', u.middleName,' ', u.lastName) AS Customer
                FROM transaction t 
                INNER JOIN booking b ON b.bookingID = t.bookingID 
                INNER JOIN services s ON s.serviceID = b.serviceID
                INNER JOIN users u ON u.userID = b.customerID
                INNER JOIN users us ON us.userID = t.handymanID
                INNER JOIN address a ON a.addressID = u.addressID
                INNER JOIN selected ss ON ss.groupChoicesID = b.groupChoicesID
                INNER JOIN formchoices fc ON fc.formChoicesID = ss.choicesID
                INNER JOIN form f ON f.formID = fc.formID
                WHERE transactionID = :transactionID";
        $stmt = $con->prepare($query);
        $stmt ->bindParam(':transactionID', $id, PDO::PARAM_INT);
        $stmt ->execute();
        $results = $stmt -> fetchAll();
        $rowCnt = $stmt -> rowCount();
        
        if($rowCnt > 0){
            $choices = array();
            $selected = new stdClass();
            foreach($results as $data){
                $date = $data['date'];
                $customer = $data['Customer'];
                $handyman = $data['Handyman'];
                $address = $data['houseNo'] .' '. $data['street'] .' '. $data['barangay'] .' '. $data['city'];
                $email = $data['email'];
                $service = $data['service'];
                $serviceAmount = $data['ServiceAmount'];
                $amount = $data['amount'];
                $remarks = $data['remarks'];
                $timeIn = $data['timeIn'];
                $timeOut = $data['timeOut'];
                $title = $data['Title'];
                
                $choices[$title][] = $data['selectedDescription'] .'//'.$data['ChoiceAmount'];
            }
            $html = "
            <!DOCTYPE html>
                <html lang='en'>
                  <head>
                    <meta charset='utf-8'>
                    <title>Receipt</title>
                    <link rel='stylesheet' href='css/printStyles.css'>
                  </head>
                  <body>
                    <header class='clearfix'>
                      <div id='logo'>
                        <img src='images/print/sample-logo.png'>
                      </div>
                      <h1>RECEIPT</h1>
                      <div id='company' class='clearfix'>
                        <div>Company Name</div>
                        <div>455 Foggy Heights,<br /> AZ 85004, US</div>
                        <div>(602) 519-0450</div>
                        <div><a href='mailto:company@example.com'>company@example.com</a></div>
                      </div>
                      <div id='project'>
                        <div><span>CUSTOMER</span> $customer </div>
                        <div><span>HANDYMAN</span> $handyman </div>
                        <div><span>SERVICE</span> $service </div>
                        <div><span>DATE</span> $date </div>
                        <div><span>TIME IN</span> $timeIn </div>
                        <div><span>TIME OUT</span> $timeOut </div>
                      </div>
                    </header>
                    <main>
                      <table>
                        <thead>
                          <tr>
                            <th class='service'>Title</th>
                            <th class='desc'>Selected</th>
                            <th class='desc'>Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                            <tr> 
                                <td> $service </td>
                                <td></td>
                                <td> Php $serviceAmount </td>
                            </tr>";
                            
                            foreach($choices as $key => $value){
                                $formChoices = explode('//',$value[0]);
                                $html .= "
                                <tr>
                                    <td> $key </td>
                                    <td> $formChoices[0]</td>>
                                    <td> Php $formChoices[1]</td>
                                </tr>"; 
                                for($ctr = 1;$ctr<count($value);$ctr++){
                                    $formChoices = explode('//',$value[$ctr]);
                                    $html .= "
                                        <tr>
                                            <td> </td>
                                            <td> $formChoices[0] </td> 
                                            <td> Php $formChoices[1] </td> 
                                        </tr>";
                                }
                            }
            
         $html .= "      <tr>
                            <td> </td>
                            <td class = 'total'> Total: </td> 
                            <td class = 'total'> $amount </td> 
                        </tr>
                        </tbody>
                      </table>
                      <!-- <div id='notices'>
                        <div>NOTICE:</div>
                        <div class='notice'>A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
                      </div> -->
                    </main>
                    <footer>
                      Invoice was created on a computer and is valid without the signature and seal.
                    </footer>
                  </body>
                </html>";
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('letter', 'portrait');
            $dompdf->render();
            $dompdf->stream("Report", array("Attachment" => false));
            exit(0);
        }
    }
?>