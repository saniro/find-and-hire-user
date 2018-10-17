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
          </div>
           <div class="historyTable">
              <div>
                <table id="tableHistory" class="table table-striped">
                  
                  <thead>
                     <tr>
                        <th>Requirement:</th>
                        <th>Expiration Date:</th>
                        <th>Status:</th>
                        <th colspan = 2>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                       <?php
                        $userID = $_SESSION['userID'];
                        $query = "SELECT RE.requirementTypeID AS requirement_type_id, 
                                RE.name AS requirement_type, 
                                (CASE WHEN (SELECT count(RS.requirementID) FROM requirements AS RS WHERE RE.requirementTypeID = RS.requirementTypeID AND RS.userID = :userID AND RS.expirationDate > CURDATE()) = 0 THEN NULL WHEN (SELECT count(RS.requirementID) FROM requirements AS RS WHERE RE.requirementTypeID = RS.requirementTypeID AND RS.userID = :userID AND RS.expirationDate > CURDATE()) > 1 THEN (SELECT RS.requirementID FROM requirements AS RS WHERE RE.requirementTypeID = RS.requirementTypeID AND RS.userID = :userID AND RS.expirationDate > CURDATE() AND submitted = 1) ELSE (SELECT RS.requirementID FROM requirements AS RS WHERE RE.requirementTypeID = RS.requirementTypeID AND RS.userID = :userID AND RS.expirationDate > CURDATE()) END) AS requirement_id, 
                                (CASE WHEN (SELECT RS.expirationDate FROM requirements AS RS WHERE RE.requirementTypeID = RS.requirementTypeID AND RS.submitted = 1 AND RS.userID = :userID) IS NULL Then 'None' ELSE (SELECT DATE_FORMAT(RS.expirationDate, '%M %d, %Y') FROM requirements AS RS WHERE RE.requirementTypeID = RS.requirementTypeID AND RS.submitted = 1 AND RS.userID = :userID) END) AS expiration_date, 
                                (CASE WHEN (SELECT count(RS.userID) FROM requirements AS RS WHERE RE.requirementTypeID = RS.requirementTypeID AND RS.submitted = 1 AND RS.userID = :userID) > 0 THEN 'Accepted' WHEN (SELECT count(RS.userID) FROM requirements AS RS 
                                WHERE RE.requirementTypeID = RS.requirementTypeID AND RS.submitted = 0 AND RS.expirationDate > CURDATE() AND RS.userID = :userID) > 0 THEN 'Submitted' ELSE 'Not passed' END) AS status FROM requirementtype AS RE WHERE RE.flag = 1";
                        $stmt = $con -> prepare($query);
                        $stmt -> bindParam(':userID', $userID, PDO::PARAM_INT);
                        $stmt -> execute();
                        $result = $stmt -> fetchAll();
                        foreach($result as $data){
                      ?>  
                            <tr>
                                <td> <?php echo $data['requirement_type']; ?></td>
                                <td> <?php echo $data['expiration_date']; ?> </td>
                                <td> <?php echo $data['status']; ?> </td>
                                <td>
                                    <?php if($data['status'] == 'Accepted' || $data['status'] == 'Submitted'){ ?>
                                      <button class = "btn btn-success btnView" data-id="<?php echo $data['requirement_id']; ?>" data-toggle="modal" data-target="#viewModal">VIEW</button>
                                    <?php
                                      }
                                      else{ ?>
                                        <button class="btn btn-success btnUpload" type="button" data-toggle="modal" data-id="<?php echo $data['requirement_type_id']; ?>" data-target="#myModal">UPLOAD</button> 
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
                    <div class="modal fade" id="viewModal" role="dialog">
                      <div class="modal-dialog modal-md">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h3>View Requirement</h3>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                            <form method = "post" enctype="multipart/form-data">
                                <div class="modal-body">
                                <h5>Preview</h5>
                                 <div class="preview">
                                    <img id = "v_output" height=265 width=465>
                                 </div>
                                 <br>
                                  <div class="form-group">
                                    <label>Expiration Date</label>
                                    <label type="date" class="form-control" id="v_expiration_date" name="v_expiration_date"></label>
                                  </div>
                                </div>
                            </form>
                        </div>
                      </div>
                    </div>

                    <div class="modal fade" id="myModal" role="dialog">
                      <div class="modal-dialog modal-md">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h3>Upload Requirement</h3>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                            <form method = "post" enctype="multipart/form-data" onsubmit = "return validateDate();">
                                <div class="modal-body">
                                <h5>Preview</h5>
                                <div class='input-group'>
                                    <input id = 'fileID' type='text' class='form-control' readonly name = "fileID">
                                </div>
                                 <div class="preview">
                                    <img id = "output"/>
                                 </div>
                                 <br>
                                  <div class="form-group">
                                    <input id = "fileupload" type="file" class="form-control" name="fileupload" onchange = "readFile(event)">
                                  </div>
                                  <div class="form-group">
                                    <label>Expiration Date</label>
                                    <input type="date" class="form-control" id="expiration_date" name="expiration_date">
                                  </div>
                                </div>
                                  <div class="modal-footer">
                                    <button id = "submit" type="submit" name = "btnUpload" class="btn btn-success mx-auto" on>Upload</button>
                                  </div>
                            </form>
                        </div>
                      </div>
                    </div>
                    <?php 
                        if(isset($_POST['btnUpload'])&&(isset($_POST['expiration_date']))){
                            if(($_FILES['fileupload']['name']!="")){
                                $expiration_date = $_POST['expiration_date'];
                                $filetype = $_FILES['fileupload']['type'];
                                if($filetype == 'image/jpeg' or $filetype = 'image/jpg' or $filetype == 'image/png'){
                                    $target_dir = "../CAPSTONE - Admin/Requirements/";
                                    $file = $_FILES['fileupload']['name'];
                                    $path = pathinfo($file);
                                    $filename = $path['filename'];

                                    $temp = explode(".", $_FILES['fileupload']['name']);
                                    $newfilename = round(microtime(true)) . '.' . end($temp);

                                    $ext = $path['extension'];
                                    $temp_name = $_FILES['fileupload']['tmp_name'];
                                    $path_filename_ext = $target_dir.$newfilename;
                                    $path_filename_for_database = "Requirements/".$newfilename;
                                    move_uploaded_file($temp_name,$path_filename_ext);
                                    $requirementTypeID = $_POST['fileID'];
                                    $query = "INSERT INTO requirements (requirementTypeID, userID, file, submissionDate, expirationDate, submitted) VALUES (:requirementTypeID, :userID, :requirementPic, CURDATE(), :expirationDate, 0)";
                                    $stmt = $con->prepare($query);
                                    $stmt->bindParam(':requirementTypeID', $requirementTypeID, PDO::PARAM_INT);
                                    $stmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_INT);
                                    $stmt->bindParam(':requirementPic', $path_filename_for_database, PDO::PARAM_STR);
                                    $stmt->bindParam(':expirationDate', $expiration_date, PDO::PARAM_STR);
                                    // $stmt->bindParam(':ID', $topupID, PDO::PARAM_INT);
                                    $result = $stmt->execute();
                                    //echo "<script> alert('".$topupID."')</script>";
                                    if($result){
                                            echo '<script> alert(\'Successfully\'); window.location.href = "index?route=customer_requirements"</script>';
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
        $('.btnUpload').click(function(e){
            id = $(this).attr('data-id');
            $('#fileID').val(id).hide();
        });

        $('.btnView').click(function(e){
          var id = $(this).attr('data-id');
          $.ajax({
              url: 'functions/view_function.php',
              type: 'post',
              data: 'rowID=' + id,
              success: function(results){
                  var data = JSON.parse(results);
                  $('#v_output').attr('src', data.file);
                  $('#v_expiration_date').html(data.expiration_date);
              }
          })
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
        
        function validateDate(){
          var date = document.getElementById("expiration_date").value;
          var today = new Date();
          var dd = today.getDate();
          var mm = today.getMonth()+1; //January is 0!
          var yyyy = today.getFullYear();

          if(dd<10) {
              dd = '0'+dd
          } 

          if(mm<10) {
              mm = '0'+mm
          } 

          today = yyyy + '-' + mm + '-' + dd;
          if(date <= today){
            alert('Expiration date already passed.');
            return false;
          }
          else{
            return true;
          }
        }
    </script>

  </body>
</html>
