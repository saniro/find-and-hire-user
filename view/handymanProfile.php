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
<head>
  <?php 
      include("head.php");
  ?> 
   
</head>

<body>
 <?php
    include("top-nav.php");
  ?>

    <div class="container-fluid">
      <div class="row">
        <?php
        	include("handymanSidebar.php");
          
            $firstName = $middleName = $lastName = $houseNo = $street = $barangay = $city = $bday = $contact = $gender = $age = '';
            $con = new PDO("mysql:host=localhost; dbname=handyman", "root", "");
            $con -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $query = "SELECT *,DATE_FORMAT(birthDate,'%b %d, %Y') AS Bday, DATE_FORMAT(birthDate,'%Y-%m-%d') as birthDate,
                (YEAR(NOW()) - YEAR(birthDate)) AS age,
                CASE WHEN gender = 1 THEN 'Male'
                    ELSE 'Female' END AS gender 
                FROM users u INNER JOIN address a ON u.addressID = a.addressID 
                INNER JOIN forgotpassword fp ON u.userID = fp.userID WHERE u.userID = :userID";
            $stmt = $con->prepare($query);
            $stmt ->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_INT);
            $stmt ->execute();
            $results = $stmt -> fetch();
            $rowCnt = $stmt -> rowCount();
            if($rowCnt > 0){
                $firstName = $results['firstName'];
                $middleName = $results['middleName'];
                $lastName = $results['lastName'];
                $houseNo = $results['houseNo'];
                $street = $results['street'];
                $barangay = $results['barangay'];
                $city = $results['city'];
                $bday = $results['Bday'];
                $contact = $results['contact'];
                $contactPerson = $results['emergencyPerson'];
                $contactPersonNo = $results['emergencyNumber'];
                $contactPersonRel = $results['emergencyRelations'];
                $gender = $results['gender'];
                $age = $results['age'];
                $editBday = $results['birthDate'];
                $email = $results['email'];
                $securityQuestion = $results['questionID'];
                $answer = $results['answer'];
                $profilePic = $results['profilepicture'];
            }
        ?>

        <main style="background-image: url(images/me.jpg);" data-stellar-background-ratio="0.22" role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                  <center>
                    <div class="wrapper">
                        <div class="profileContainer">
                                <img style = "width: 150px; height: 150px;" class = "profilePicture" src ="<?php echo $profilePic?>" alt = 'Profile Picture'/>
                        </div>
                        <div>
                          <table align="left" class="information1">
                            <tr>
                                <td class="label-1">Name:</td>
                                <td class="label-1"><?php echo $lastName . ", " . $firstName . " " . $middleName;?></td>
                            </tr>
                            <tr>
                                <td class="label-1">Address:</td>
                                <td class="label-1"><?php echo $houseNo . ", " . $street . ", " . $barangay . ", " . $city;?></td>
                            </tr>  
                            <tr>
                                <td class="label-1">Age:</td>
                                <td class="label-1"><?php echo $age;?></td>
                            </tr>  
                            <tr>
                                <td class="label-1">Birthday:</td>
                                <td class="label-1"><?php echo $bday;?></td>
                            </tr>
                            <tr>
                                <td class="label-1">Contact:</td>
                                <td class="label-1"><?php echo $contact;?></td>
                            </tr>
                           </table>  
                            <button type="button" id="btn-profileedit" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myLogin">EDIT</button>
                        </div>
                      </div>    
                    </center>
                                                <!-- login modal -->
                              <div class="modal fade" id="myLogin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header p-3 mb-2 bg-success">
                                      <h2 class="modal-title" id="exampleModalLabel">EDIT</h2>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      <nav class="navbar navbar-light bg-light">
                                         <nav>
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                              <a class="nav-item nav-link active" id="nav-personalinformation-tab" data-toggle="tab" href="#nav-personalinformation" role="tab" aria-controls="nav-home" aria-selected="true">Personal Information</a>

                                              <a class="nav-item nav-link" id="nav-otherinformation-tab" data-toggle="tab" href="#nav-otherinformation" role="tab" aria-controls="nav-profile" aria-selected="false">Address and Contact Information</a>

                                              <a class="nav-item nav-link" id="nav-accountsetting-tab" data-toggle="tab" href="#nav-accountsetting" role="tab" aria-controls="nav-contact" aria-selected="false">Account Setting</a>
                                            </div>

                                          </nav>

                                          <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="nav-personalinformation" role="tabpanel" aria-labelledby="nav-personalinformation-tab"> 
                                                <form method = "post">
                                                    <div class="form-group">
                                                        <label>Name:</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name = "firstName" placeholder="First" value = "<?php echo $firstName ?>">
                                                            <input type="text" class="form-control" name = "middleName" placeholder="Middle" value = "<?php echo $middleName ?>">
                                                            <input type="text" class="form-control" name = "lastName" placeholder="Last" value = "<?php echo $lastName ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Birth Date:</label>
                                                        <div class="input-group">
                                                            <input type="date" class="form-control" name = "bday" value = "<?php echo $editBday?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Gender:</label><br>
                                                        <div class="input-group">
                                                            <input type="radio" name="gender" value="1" <?php echo($gender == 'Male')?  'checked' : ''?>> Male<br>
                                                            <input type="radio" name="gender" value="0" <?php echo($gender == 'Female')? 'checked' : '' ?>> Female<br>
                                                        </div>
                                                    </div>
                                                    <center><button type="submit" name = "saveName" class="btn btn-primary">Save</button></center>
                                                </form>
                                                <?php
                                                    if(isset($_POST['saveName'])){
                                                        $firstName = $_POST['firstName'];
                                                        $middleName = $_POST['middleName'];
                                                        $lastName = $_POST['lastName'];
                                                        $gender = $_POST['gender'];
                                                        $Bday = $_POST['bday'];
                                                        
                                                        $con = new PDO("mysql:host=localhost; dbname=handyman", "root", "");
                                                        $con -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                                        $query = "UPDATE users SET firstName = :firstName, middleName = :middleName, lastName = :lastName, gender = :gender,
                                                            birthDate = :bday WHERE userID = :userID";
                                                        $stmt = $con->prepare($query);
                                                        $stmt -> bindParam(':firstName', $firstName, PDO::PARAM_STR);
                                                        $stmt -> bindParam(':middleName', $middleName, PDO::PARAM_STR);
                                                        $stmt -> bindParam(':lastName', $lastName, PDO::PARAM_STR);
                                                        $stmt -> bindParam(':gender', $gender, PDO::PARAM_INT);
                                                        $stmt -> bindParam(':bday', $Bday, PDO::PARAM_STR);
                                                        $stmt -> bindParam(':userID', $_SESSION['userID'], PDO::PARAM_INT);
                                                        $result = $stmt -> execute();
                                                        if($result){
                                                            echo "<script> 
                                                                    alert('Profile successfully updated.');
                                                                    window.location.href = 'index?route=handyman_profile';
                                                                </script>";
                                                        }else{
                                                            echo "<script> 
                                                                    alert('There was a problem updating your profile.');
                                                                </script>";
                                                        }
                                                    }
                                                ?>
                                            </div>
                                            <div class="tab-pane fade" id="nav-otherinformation" role="tabpanel" aria-labelledby="nav-otherinformation-tab">
                                                <form method = "post">
                                                    <div class="form-group">
                                                        <label>Address:</label>
                                                        <div class="input-group">
                                                            <input type="text" name = "houseNo" class="form-control" placeholder="House No." value = "<?php echo $houseNo?>">
                                                            <input type="text" name = "street" class="form-control" placeholder="Street" value = "<?php echo $street?>">
                                                            <input type="text" name = "barangay" class="form-control" placeholder="Barangay" value = "<?php echo $barangay?>">
                                                            <input type="text" name = "city" class="form-control" placeholder="City" value = "<?php echo $city?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Contact:</label>
                                                        <div class="input-group">
                                                            <input type="text" name = "contact" class="form-control" placeholder="Mobile no." value = "<?php echo $contact?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Contact Person:</label>
                                                        <div class="input-group">
                                                            <input type="text" name = "contactPerson" class="form-control" placeholder="Contact Person" value = "<?php echo $contactPerson; ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Contact Person Number:</label>
                                                        <div class="input-group">
                                                            <input type="text" name = "contactPersonNo" class="form-control" placeholder="Contact Person Number" value = "<?php echo $contactPersonNo; ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Contact Person Relationship:</label>
                                                        <div class="input-group">
                                                            <input type="text" name = "contactPersonRel" class="form-control" placeholder="Contact Person Relationship" value = "<?php echo $contactPersonRel; ?>" required>
                                                        </div>
                                                    </div>
                                                    <center><button type="submit" name = "saveAddress" class="btn btn-primary">Save</button></center>
                                                </form>
                                                <?php
                                                    if(isset($_POST['saveAddress'])){
                                                        $houseNo = $_POST['houseNo'];
                                                        $street = $_POST['street'];
                                                        $barangay = $_POST['barangay'];
                                                        $city = $_POST['city'];
                                                        $contact = $_POST['contact'];
                                                        $u_contactPerson = $_POST['contactPerson'];
                                                        $u_contactPersonNo = $_POST['contactPersonNo'];
                                                        $u_contactPersonRel = $_POST['contactPersonRel'];
                                                        
                                                        $con = new PDO("mysql:host=localhost; dbname=handyman", "root", "");
                                                        $con -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                                        $query = "UPDATE users u INNER JOIN address a ON u.addressID = a.addressID 
                                                            SET houseNo = :houseNo, street = :street, barangay = :barangay, city = :city, contact = :contact, emergencyPerson = :contactPerson, emergencyNumber = :contactPersonNo, emergencyRelations = :contactPersonRel 
                                                            WHERE userID = :userID";
                                                        $stmt = $con->prepare($query);
                                                        $stmt -> bindParam(':houseNo', $houseNo, PDO::PARAM_STR);
                                                        $stmt -> bindParam(':street', $street, PDO::PARAM_STR);
                                                        $stmt -> bindParam(':barangay', $barangay, PDO::PARAM_STR);
                                                        $stmt -> bindParam(':city', $city, PDO::PARAM_STR);
                                                        $stmt -> bindParam(':contact', $contact, PDO::PARAM_STR);
                                                        $stmt -> bindParam(':contactPerson', $u_contactPerson, PDO::PARAM_STR);
                                                        $stmt -> bindParam(':contactPersonNo', $u_contactPersonNo, PDO::PARAM_STR);
                                                        $stmt -> bindParam(':contactPersonRel', $u_contactPersonRel, PDO::PARAM_STR);
                                                        $stmt -> bindParam(':userID', $_SESSION['userID'], PDO::PARAM_INT);
                                                        $result = $stmt -> execute();
                                                        if($result){
                                                            echo "<script> 
                                                                    alert('Address and contact information successfully updated.');
                                                                    window.location.href = 'index?route=handyman_profile';
                                                                </script>";
                                                        }else{
                                                            echo "<script> 
                                                                    alert('There was a problem updating your profile.');
                                                                </script>";
                                                        }
                                                    }
                                                ?>
                                            </div>
                                            <div class="tab-pane fade" id="nav-accountsetting" role="tabpanel" aria-labelledby="nav-accountsetting-tab">
                                                <form method = "post" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label>Email:</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name = "email" placeholder="email" value = "<?php echo $email?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Password:</label>
                                                        <div class="input-group">
                                                            <input type="password" name = "password" class="form-control" placeholder="password">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Security Question:</label>
                                                        <div class="input-group">
                                                          <select name = "securityQuestion">
                                                            <option disabled selected> Please select a security question...</option>
                                                            <?php
                                                                $query = "SELECT * FROM question WHERE flag = 1";
                                                                $stmt = $con -> prepare($query);
                                                                $stmt -> execute();
                                                                $result = $stmt -> fetchAll();
                                                                $rowCnt = $stmt -> rowCount();
                                                                if($rowCnt > 0){
                                                                    foreach($result as $data){
                                                                        $id = $data['questionID'];
                                                                        $question = $data['question'];
                                                            ?>
                                                                <option value = "<?php echo $id?>"> <?php echo $question?></option>
                                                            <?php
                                                                    }   
                                                                }
                                                            ?>
                                                          </select> 

                                                        <input type="password" class="form-control" name = "currentAnswer" placeholder="Current Answer">
                                                        <input type="password" class="form-control" name = "newAnswer" placeholder="New Answer">
                                                        <input type="password" class="form-control" name = "confirmAnswer" placeholder="Confirm Answer">
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Change Display Picture:</label>
                                                        <div class="input-group">
                                                        <input type="file" class="form-control" name="fileupload">
                                                      </div>
                                                    </div> 
                                                    <center><button type="submit" name = "saveAccount" class="btn btn-primary">Save</button></center>
                                                </form> 
                                                <?php
                                                    if(isset($_POST['saveAccount'])){
                                                        $email = $_POST['email'];
                                                        $validPic = false;
                                                        $validForgotPassword = false;
                                                        $queryForgotPassword = 'UPDATE forgotpassword SET ';
                                                        $query = "UPDATE users SET email = :email ";
                                                        
                                                        if(!empty($_POST['password'])){
                                                            $password = $_POST['password'];
                                                            $query .= ", password = :password ";
                                                            
                                                        }
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
                                                                $query .= ', requestPic = :requestPic ';
                                                                $validPic = true;
                                                            }
                                                        }
                                                        
                                                        $query .= " WHERE userID = :userID";
                                                        $stmt = $con->prepare($query);
                                                        if(!empty($_POST['password'])){
                                                            $stmt -> bindParam(':password', $password, PDO::PARAM_STR);
                                                        }
                                                        if($validPic){
                                                            echo "<script>alert('a')</script>";
                                                            $stmt -> bindParam(':requestPic', $path_filename_for_database, PDO::PARAM_STR);
                                                        }
                                                        $stmt -> bindParam(':email', $email, PDO::PARAM_STR);
                                                        $stmt -> bindParam(':userID', $_SESSION['userID'], PDO::PARAM_INT);
                                                        $result = $stmt -> execute();
                                                        
                                                        if(!empty($_POST['newAnswer']) || !empty($_POST['confirmAnswer'])){
                                                            if(!empty($_POST['currentAnswer'])){
                                                                if($_POST['currentAnswer'] == $answer){
                                                                    if($_POST['newAnswer'] == $_POST['confirmAnswer']){  
                                                                        $newAnswer = $_POST['newAnswer'];
                                                                        $validForgotpassword = true;
                                                                        $validNewAnswer = true;
                                                                        $queryForgotPassword .= " answer = :answer ";
                                                                    }else{                                                                      
                                                                        $validForgotpassword = false;
                                                                        echo "
                                                                        <script> 
                                                                            alert('Current answer do not match.');
                                                                        </script>";
                                                                    }
                                                                }else{                                         
                                                                    $validForgotpassword = false;
                                                                    echo "
                                                                    <script> 
                                                                        alert('Current answer do not match.');
                                                                    </script>";
                                                                }
                                                            }else{                                         
                                                                $validForgotpassword = false;
                                                                echo "
                                                                <script> 
                                                                    alert('Please fill up the current answer field.');
                                                                </script>";
                                                            }
                                                        }
                                                        
                                                        if(isset($_POST['securityQuestion'])){                                                                    
                                                            $validForgotpassword = true;
                                                            $questionID = $_POST['securityQuestion'];
                                                            if($validNewAnswer){
                                                                $queryForgotPassword .= ",";
                                                            }
                                                            $queryForgotPassword .= " questionID = :questionID ";
                                                            $validNewQuestion = true;
                                                        }
                                                        
                                                        if($validForgotpassword){
                                                            $queryForgotPassword .= " WHERE userID = :userID";
                                                            $stmt = $con->prepare($queryForgotPassword);
                                                            if($validNewQuestion){
                                                                $stmt -> bindParam(':questionID', $questionID, PDO::PARAM_STR);
                                                            }
                                                            if($validNewAnswer){
                                                                $stmt -> bindParam(':answer', $newAnswer, PDO::PARAM_STR);
                                                            }
                                                            $stmt -> bindParam(':userID', $_SESSION['userID'], PDO::PARAM_INT);
                                                            $result = $stmt -> execute();
                                                        }
                                                        
                                                        if($result){
                                                            echo "<script> 
                                                                    alert('Account settings successfully updated.');
                                                                    window.location.href = 'index?route=handyman_profile';
                                                                </script>";
                                                        }else{
                                                            echo "<script> 
                                                                    alert('There was a problem updating your profile.');
                                                                </script>";
                                                        }
                                                    }
                                                ?>
                                            </div>
                                          </div>
                                      </nav>
                                  </div>
                                </div>
                              </div>
                            </div>
      <!-- Modals -->

                    
                
          <canvas class="my-4 w-100" id="myChart" width="900" height="145"></canvas>

        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="./js/node_modules/jquery/dist/jquery.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="./js/bootstrap.min.js"></script>

    <!-- Icons -->
    <script src="./js/feather.min.js"></script>
    <script>
      feather.replace()
    </script>

  </body>
</html>
