<?php
    //Include database connection
    require("../connection/connection.php");
    session_start();
    if(isset($_POST['rowID'])){
        $id = $_POST['rowID']; 
            $query = "SELECT *,b.Amount AS amount,s.name AS service,ss.description AS selectedDescription,
                CONCAT(us.firstName,' ', us.middleName,' ',us.lastName) AS Handyman,
                CONCAT(u.firstName,' ', u.middleName,' ', u.lastName) AS Customer
                FROM transaction t 
                INNER JOIN booking b ON b.bookingID = t.bookingID 
                INNER JOIN services s ON s.serviceID = b.serviceID
                INNER JOIN users u ON u.userID = b.customerID
                INNER JOIN users us ON us.userID = t.handymanID
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
            foreach($results as $data){
                $date = $data['date'];
                $name = ($_SESSION['userID'] == $data['customerID'])? 
                    $data['Handyman']: 
                    $data['Customer'];
                $service = $data['service'];
                $amount = $data['amount'];
                $remarks = $data['remarks'];
                $timeIn = $data['timeIn'];
                $timeOut = $data['timeOut'];
                $title = $data['Title'];
                $choices[$title][] = $data['selectedDescription'];
            }
            echo " 
            <div class='modal-header'>
                <h3 id ='modalDate'> ". $date ."</h3>
            <button type='button' class='close' data-dismiss='modal'>&times;</button>
            </div>
            <div class='modal-body'>
                <div class='form-group'>
                    <label>Customer:</label>
                    <div class='input-group'>
                        <input id = 'modalProfile' type='text' class='form-control'disabled value = '".$name."'>
                    </div>
                </div>
            <div class='form-group'>
                <label id = 'modalService'>Service Provided: <h6 style ='display: inline;'>".$service."</h6></label>
                <div> ";
                    foreach($choices as $key => $value){
                        echo "<label>$key</label>";
                        echo "<ul>";
                        foreach($value as $data){
                            echo "<li> ".$data."</li>";
                        }
                        echo "</ul>";
                    }
            echo    "
                </div>
            </div>
            <div class='form-group'>
                <div class='input-group input-group-sm mb-3'>
                    <div class='input-group-prepend'>
                        <span class='input-group-text' id='inputGroup-sizing-sm'>Time In:</span>
                    </div>
                    <input id = 'modalTimeIn' type='text' class='form-control' aria-label='Sizing example input' aria-describedby='inputGroup-sizing-sm' disabled value = '".$timeIn."'>
                    <div class='input-group-prepend'>
                        <span class='input-group-text' id='inputGroup-sizing-sm'>Time Out:</span>
                    </div>
                    <input id = 'modalTimeOut' type='text' class='form-control' aria-label='Sizing example input' aria-describedby='inputGroup-sizing-sm' disabled value = '".$timeOut."'>
                </div>
            </div>
            <div class='form-group'>
                <label>Cost:</label>
                <div class='input-group'>
                    <input id = 'modalCost' type='text' class='form-control'disabled value = '".$amount."'>
                </div>
            </div>
            <div class='form-group'>
                <label>Remarks:</label>
                <div class='input-group'>
                    <input id = 'modalRemarks' type='text' class='form-control'disabled value = '".$remarks."'>
                </div>
            </div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
            </div>
            ";
        }
    }
    
?>
