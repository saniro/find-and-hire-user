<?php
  if(!isset($_SESSION['userID'])){
      header('location: index');
  }
  if($_SESSION['userType'] != 2){
      header('location: index?route=customer_profile');
  }
?>
<!doctype html>
<html lang="en">
 <?php 
 	include("head.php");
 ?> 
<body>
 <?php
    include("top-nav.php");
  ?>

    <div class="container-fluid">
      <div class="row">
        
        <?php
        	include("handymanSidebar.php");
        ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div class="btn-toolbar mb-2 mb-md-0">
                <form method ="post">
                  <label style="margin-left: 25px; margin-right: 5px;">From:</label>
                  <input name = "minDate" class="btn btn-sm btn-outline-secondary dropdown-toggle" type="date" style="background-color: #FAEBD7;">
                  <label style="margin-left: 25px; margin-right: 5px;">To:</label>
                  <input name = "maxDate" class="btn btn-sm btn-outline-secondary dropdown-toggle" type="date" style="background-color: #FAEBD7;">
                  <button style="margin-left: 25px;" name = "btnSearch" type="submit" class="btn btn-success">Search</button>
                </form>
            </div>      
          </div>
           <div class="historyTable">
              <div>
                <table id="tableHistory" class="table table-striped">
                  
                  <thead>
                     <tr>
                        <th>Date:</th>
                        <th>Amount:</th>
                        <th colspan = 2>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                       <?php
                        $userID = $_SESSION['userID'];
                        $query = "SELECT * FROM topuphistory WHERE userID = :userID AND status <> 2";
                        if(isset($_POST['btnSearch'])){
                            $minDate = $_POST['minDate'];
                            $maxDate = $_POST['maxDate'];
                            $query .= " AND date BETWEEN '$minDate' AND '$maxDate'";
                        }
                        $query .= " ORDER BY date DESC";
                        $stmt = $con -> prepare($query);
                        $stmt -> bindParam(':userID',$_SESSION['userID'],PDO::PARAM_INT);
                        $stmt -> execute();
                        $result = $stmt -> fetchAll();
                        foreach($result as $data){
                      ?>  
                            <tr>
                                <td> <?php echo $data['Date']; ?></td>
                                <td> <?php echo $data['Value']; ?></td>
                                <td>
                                    <?php if($data['status'] == 1){ ?>
                                        <a href = "index?route=topup_receipt&&id=<?php echo $data['topupHistoryID']?>" target="_blank"><button class="btn btn-success btnPrint" data-id="<?php echo $data['topupHistoryID']?>">DOWNLOAD RECEIPT</button></a>
                                    <?php
                                        }else{ ?>
                                        <button class="btn btn-success btnView" type="button" data-toggle="modal" data-target="#myModal" data-id="<?php echo $data['topupHistoryID']?>">UPLOAD</button> 
                                    <?php
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php
                            }
                        ?>
                  </tbody>

                </table>
              </div>
            </div>
            <canvas class="my-4 w-100" id="myChart" width="900" height="255"></canvas>

        </main>
      </div>
    </div>

    <!-- topup modal -->
                    <div class="modal fade" id="myModal" role="dialog">
                      <div class="modal-dialog modal-md">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h3>Upload Receipt</h3>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                            <form method = "post" enctype="multipart/form-data">
                                <div class="modal-body">
                                <h5>Preview</h5>
                                <div class='input-group'>
                                    <input id = 'fileID' type='text' class='form-control' readonly name = "fileID">
                                </div>
                                 <div class="preview">
                                    <img id = "output"/>
                                 </div>
                                  <div class="form-group">
                                    <input id = "fileupload" type="file" class="form-control" name="fileupload" onchange = "readFile(event)">
                                  </div>
                                </div>
                                  <div class="modal-footer">
                                    <button id = "submit" type="submit" name = "btnUpload" class="btn btn-success mx-auto">Upload</button>
                                  </div>
                            </form>
                        </div>
                      </div>
                    </div>
                    <?php 
                        if(isset($_POST['btnUpload'])){
                            if(($_FILES['fileupload']['name']!="")){
                                $filetype = $_FILES['fileupload']['type'];
                                if($filetype == 'image/jpeg' or $filetype = 'image/jpg' or $filetype == 'image/png'){
                                    $target_dir = "../Images/";
                                    $file = $_FILES['fileupload']['name'];
                                    $path = pathinfo($file);
                                    $filename = $path['filename'];

                                    $temp = explode(".", $_FILES['fileupload']['name']);
                                    $newfilename = round(microtime(true)) . '.' . end($temp);

                                    $ext = $path['extension'];
                                    $temp_name = $_FILES['fileupload']['tmp_name'];
                                    $path_filename_ext = $target_dir.$newfilename;
                                    $path_filename_for_database = "../Images/".$newfilename;
                                    move_uploaded_file($temp_name,$path_filename_ext);
                                    $topupID = $_POST['fileID'];
                                    $query = "UPDATE topuphistory SET paymentPic = :paymentPic WHERE topupHistoryID = :ID";
                                    $stmt = $con->prepare($query);
                                    $stmt->bindParam(':paymentPic', $path_filename_for_database, PDO::PARAM_STR);
                                    $stmt->bindParam(':ID', $topupID, PDO::PARAM_INT);
                                    $result = $stmt->execute();
                                    echo "<script> alert(\'$topupID\')</script>";
                                    if($result){
                                            echo '<script> alert(\'Successfully\')</script>';
                                    }
                                }else{
                                    echo '<script> alert(\'Format Problem\')</script>';
                                }
                            }else{
                                echo '<script> alert(\'File Problem\')</script>';
                            }
                        }
                    ?>
    <script src="./js/node_modules/jquery/dist/jquery.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="./js/DataTables/datatables.min.js"> </script>
    <!-- Icons -->
    <script src="./js/feather.min.js"></script>
    <script>
      feather.replace()
        var id = '';
        $('.btnView').click(function(e){
            id = $(this).attr('data-id');
            $('#fileID').val(id).hide();
        })
                   
        var readFile = function(event){
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function(){
                var dataURL = reader.result;
                var output = document.getElementById('output');
                output.src = dataURL;
                output.width = 465;
                output.height = 265;
            };
            reader.readAsDataURL(input.files[0]);
        }
        
    </script>

  </body>
</html>
