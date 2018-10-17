<?php
    //Include database connection
    require("../connection/connection.php");
    session_start();
    if(isset($_POST['rowID'])){
        $id = $_POST['rowID']; 
            $query = "SELECT file, DATE_FORMAT(expirationDate, '%M %d, %Y') AS expirationDate FROM requirements WHERE requirementID = :requirementID";
        $stmt = $con->prepare($query);
        $stmt ->bindParam(':requirementID', $id, PDO::PARAM_INT);
        $stmt ->execute();
        $results = $stmt -> fetch();
        $rowCnt = $stmt -> rowCount();
        if($rowCnt > 0){
            $data = array("file" => "../CAPSTONE - Admin/".$results['file'], "expiration_date" => $results['expirationDate']);
            $output = json_encode($data);
            echo $output;
        }
    }
    
?>