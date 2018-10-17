<?php
	require './dompdf/autoload.inc.php';
	use Dompdf\Dompdf;
    if(isset($_GET['id'])){
        $id = $_GET['id']; 
        $con = new PDO("mysql:host=localhost; dbname=handyman", "root", "");
        $con -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $query = "SELECT *,DATE_FORMAT(Date,'%M %d,%Y') as Date,DATE_FORMAT(NOW(),'%M %d,%Y') as DateNow FROM topuphistory tp INNER JOIN users u ON u.userID = tp.userID WHERE topupHistoryID = :ID";
        $stmt = $con->prepare($query);
        $stmt ->bindParam(':ID', $id, PDO::PARAM_INT);
        $stmt ->execute();
        $results = $stmt -> fetch();
        $rowCnt = $stmt -> rowCount();
            
        if($rowCnt > 0){
            $topupID = $results['topupHistoryID'];
            $dateNow = $results['DateNow'];
            $date = $results['Date'];
            $value = $results['Value'];
            $name = $results['firstName'] .' '. $results['middleName'] .' '. $results['lastName'];
            $numberFormat = new NumberFormatter('en',NumberFormatter::SPELLOUT);
            $moneyWords = $numberFormat -> format($value);
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
                        <div><span>TPH #:</span> TPH# $topupID </div>
                        <div><span>DATE:</span>  $dateNow </div>
                        <div style='margin-top:20px;'>
                       <p style='font-size: 18px; margin-left: 80px;'>
                                Received from: <u> $name </u> <br><br> 
                                Sum of: <u> $moneyWords </u> Pesos, PHP <u> $value </u><br><br>
                        as payment for the Topup requested on <u> $date </u>.
                       </p>
                       <br>
                       <p style='float:right; font-size: 17px; padding-right:30px;'>Find & Hire<br> $name <br>
                       Received by:</p>
                        </div>
                    </div>
                    </header>
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