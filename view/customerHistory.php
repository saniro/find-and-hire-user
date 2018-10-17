<?php
    if(!isset($_SESSION['userID'])){
        header('location: index.php');
    }
    if($_SESSION['userType'] != 1){
        header('location: index?route=handyman_profile');
    }
?>
<!doctype html>
<html lang="en">
 <?php 
 	include("head.php");
 ?>
    <link rel="stylesheet" type="text/css" href="./js/DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css">
   
  <body>
    <?php
        include("top-nav.php");
    ?>

    <div class="container-fluid">
      <div class="row">
        <?php
        	include("customerSidebar.php");
        ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div class="btn-toolbar mb-2 mb-md-0">
              <label style="margin-left: 25px; margin-right: 5px;">From:</label>
              <input class="btn btn-sm btn-outline-secondary dropdown-toggle" type="date" style="background-color: #FAEBD7;">
              <label style="margin-left: 25px; margin-right: 5px;">To:</label>
              <input class="btn btn-sm btn-outline-secondary dropdown-toggle" type="date" style="background-color: #FAEBD7;">
              <button style="margin-left: 25px;" type="button" class="btn btn-success">Search</button>
            </div>      
          </div>
            
        <div class="historyTable">
            <table id ="tableHistory" class="display" style = "width: 113%">
              <thead>
                 <tr>
                    <th>Service</th>
                    <th>Handyman</th>
                    <th>Date:</th>
                    <th>Time in:</th>
                    <th>Time out:</th>
                    <th>Cost:</th>
                    <th>Action:</th>
                  </tr>
              </thead>
              <tbody>
                   <?php
                    $userID = $_SESSION['userID'];
                    $query = "SELECT *,b.amount AS total, CONCAT(lastName, ', ', firstName, ' ', COALESCE(CONCAT(SUBSTRING(middleName,1,1),'.'),'')) AS handymanName FROM transaction t 
                        INNER JOIN booking b ON b.bookingID = t.bookingID
                        INNER JOIN users u ON u.userID = b.customeriD 
                        INNER JOIN services s ON s.serviceID = b.serviceID WHERE u.userID = :userID AND status = 1";
                    if(isset($_POST['btnSearch'])){
                        $minDate = $_POST['minDate'];
                        $maxDate = $_POST['maxDate'];
                        $query .= " AND b.date BETWEEN '$minDate' AND '$maxDate'";
                    }
                    $query .= " ORDER BY b.date DESC";
                    $stmt = $con -> prepare($query);
                    $stmt -> bindParam(':userID',$_SESSION['userID'],PDO::PARAM_INT);
                    $stmt -> execute();
                    $result = $stmt -> fetchAll();
                    foreach($result as $data){
                  ?>  
                        <tr>
                            <td> <?php echo $data['name']; ?></td>
                            <td> <?php echo $data['handymanName']; ?></td>
                            <td> <?php echo $data['date']; ?></td>
                            <td> <?php echo $data['timeIn'];?></td>
                            <td> <?php echo $data['timeOut'];?></td>
                            <td> <?php echo $data['total']; ?></td>
                            <td>
                                <button class="btn btn-success btnView" type="button" data-toggle="modal" data-target="#myModal" data-id="<?php echo $data['transactionID']?>">View</button> 
                                <a href = "index?route=receipt&&id=<?php echo $data['transactionID']?>" target = "_blank"><button class="btn btn-success btnPrint" data-id="<?php echo $data['transactionID']?>">Print</button></a>
                            </td>
                        </tr>
                    <?php
                        }
                    ?>
              </tbody>
            </table>
        </div>
        

              <!-- view modal -->
                    <div class="modal fade" id="myModal" role="dialog">
                      <div class="modal-dialog modal-md">
                        <div class="modal-content" id = "modalContent">
                        </div>
                      </div>
                    </div>  
              <!-- End view modal -->
            <canvas class="my-4 w-100" id="myChart" width="900" height="260"></canvas>

        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="./js/node_modules/jquery/dist/jquery.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/node_modules/jquery/dist/jquery.min.js"></script>   
    <script src="./js/DataTables/datatables.min.js"> </script>
    <!-- Icons -->
    <script src="./js/feather.min.js"></script>
    <script>
      feather.replace();
        
        $('.btnView').click(function(e){
            var id = $(this).attr('data-id');
            $.ajax({
                url: 'view/fetchModal.php',
                type: 'post',
                data: 'rowID=' + id,
                success: function(results){
                    $('#modalContent').html(results);
                }
            })
        })
        
        var tableHistory = $('#tableHistory').DataTable({
            "columnDefs": [
                {
                    "targets": [ 6 ],
                    "orderable": false
                }
            ],
            "pagingType": "full_numbers",
            "language": {
                "lengthMenu": "Display  _MENU_  records per page",
                "zeroRecords": "No record found",
                "info": "Showing page _PAGE_ of _PAGES_",
                "infoEmpty": "",
                "infoFiltered": "",
            }
        });
    </script>

  </body>
</html>
