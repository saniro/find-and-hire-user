<!DOCTYPE html>
	<html lang="zxx" class="no-js">
	<head>
		<!-- Mobile Specific Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Favicon-->
		<link rel="shortcut icon" href="landingpage/img/fav.png">
		<!-- Author Meta -->
		<meta name="author" content="CodePixar">
		<!-- Meta Description -->
		<meta name="description" content="">
		<!-- Meta Keyword -->
		<meta name="keywords" content="">
		<!-- meta character set -->
		<meta charset="UTF-8">
		<!-- Site Title -->
		<title>Find & Hire</title>

		<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet"> 
			<!--
			CSS
			============================================= -->
			<link rel="stylesheet" href="landingpage/css/linearicons.css">
			<link rel="stylesheet" href="landingpage/css/font-awesome.min.css">
			<link rel="stylesheet" href="landingpage/css/jquery.DonutWidget.min.css">
			<link rel="stylesheet" href="landingpage/css/bootstrap.css">
			<link rel="stylesheet" href="landingpage/css/owl.carousel.css">
			<link rel="stylesheet" href="landingpage/css/main.css">
			<link rel="stylesheet" href="css/admin-loginStyles.css">
			<link rel="stylesheet" type="text/css" href="style.css" >
		</head>
		<body>

			<?php 
				include("navbar.php");
			?>

			<!-- Create Account -->
			
				<form method = "post" enctype="multipart/form-data">
					<div style="width: 500px; margin-left:100px; ">
						<h3>Personal Information</h3>
						<div class="form-group">
						    <label>First Name:</label>
						    <input type="text" class="form-control" id="Fname" name = "firstName" placeholder="First Name" required/>
						</div>

						<div class="form-group">
						    <label>Middle Name:</label>
						    <input type="text" class="form-control" id="Mname" name = "middleName" placeholder="Middle Name" required/>
						</div>

						<div class="form-group">
						    <label>Last Name:</label>
						    <input type="text" class="form-control" id="Lname" name = "lastName" placeholder="Last Name" required/>
						</div>
						<div class="form-group">
						    <label>Birth Date:</label>
						    <input type="date" class="form-control" id="Bday" name = "birthDay" required/>
						</div>
						<div class="form-group">
						    <label>Gender:</label><br>
						   	<input class = "radioButton" type="radio" name="gender" value="1"> Male<br>
							<input class = "radioButton" type="radio" name="gender" value="0"> Female<br>
						</div>
                        
						<h3>Address</h3>
						<div class="form-group">
						    <label>House No:</label>
						    <input type="text" class="form-control" id="houseNo." name = "houseNo" placeholder="House No." required/>
						</div>
                        <div class="form-group">
						    <label>Street:</label>
						    <input type="text" class="form-control" id="street" name = "street" placeholder="Street" required/>
						</div>
                        <div class="form-group">
						    <label>Barangay:</label>
						    <input type="text" class="form-control" id="barangay" name = "barangay" placeholder="Barangay" required/>
						</div>
                        <div class="form-group">
						    <label>City:</label>
						    <input type="text" class="form-control" id="city" name = "city" placeholder="City" required/>
						</div>
						<h3>Contact Information</h3>
						<div class="form-group">
						    <label>Email:</label>
						    <input type="text" class="form-control" id="Email" name = "email" placeholder="Email" required/>
						</div>
						<div class="form-group">
						    <label>Contact Number:</label>
						    <input type="text" class="form-control" id="contactNumber" name = "contactNumber" placeholder="Mobile No./Tel. No." required/>
						</div>
						<div class="form-group">
	                        <label>Contact Person:</label>
	                        <input type="text" class="form-control" id="contactPerson" name = "contactPerson" placeholder="Contact Person" required/>
	                    </div>
	                    <div class="form-group">
	                        <label>Contact Person Number:</label>
	                        <input type="text" class="form-control" id="contactPersonNo" name = "contactPersonNo" placeholder="Mobile No./Tel. No." required/>
	                    </div>
	                    <div class="form-group">
	                        <label>Contact Person Relationship:</label>
	                        <input type="text" class="form-control" id="contactPersonRel" name = "contactPersonRel" placeholder="Contact Person Relationship" required/>
	                    </div>
                        <h3>Account</h3>
						<div class="form-group">
						    <label>Password:</label>
						    <input type="password" class="form-control" id="Email" name = "password" placeholder="Password" required/>
						</div>
						<div class="form-group">
						    <label for="securityQuestion">Security Question</label>
                            <select class="form-control" id="securityQuestion" name = "securityQuestion" required>
                                <option disabled selected> Please select a security question...</option>
                                <?php
                                    $con = new PDO("mysql:host=localhost; dbname=handyman", "root", "");
                                    $con -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
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
						</div>
                        <div class="form-group">
						    <label>Answer:</label>
						    <input type="password" class="form-control" id="Answer" name = "answer" placeholder="Answer" required/>
						</div>
                        <div class="form-group">
                            <label> Upload Display Picture:</label>
                            <input type="file" class="form-control" name="fileupload" required/>
                        </div>  
                        <div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" name = "termCondition"> I Agree to the Terms and Conditon of this Website.</label>
                            </div>
                        </div>
						<div>
							<button type = "submit" name = "btnSubmit" class="btn btn-success">
								Apply Now
							</button>
						</div>
					</div>
				</form>
		
                <?php
                    
                    if(isset($_POST['btnSubmit'])){
                        if(isset($_POST['termCondition'])){
                            if(($_FILES['fileupload']['name']!="")){
                                $filetype = $_FILES['fileupload']['type'];
                                if ($filetype == 'image/jpeg' or $filetype = 'image/jpg' or $filetype == 'image/png'){
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
                                        $firstName = $_POST['firstName'];
                                        $middleName = $_POST['middleName'];
                                        $lastName = $_POST['lastName'];
                                        $birthDay = $_POST['birthDay'];
                                        $houseNo = $_POST['houseNo'];
                                        $street = $_POST['street'];
                                        $barangay = $_POST['barangay'];
                                        $city = $_POST['city'];
                                        $gender = $_POST['gender'];
                                        $email = $_POST['email'];
                                        $contactNumber = $_POST['contactNumber'];
                                        $contactPerson = $_POST['contactPerson'];
                                        $contactPersonNo = $_POST['contactPersonNo'];
                                        $contactPersonRel = $_POST['contactPersonRel'];
                                        $password = $_POST['password'];
                                        $securityQuestion = $_POST['securityQuestion'];
                                        $answer = $_POST['answer'];
                                        //$services = $_POST['services'];

                                        $con = new PDO("mysql:host=localhost; dbname=handyman", "root", "");
                                        $con -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                        $query = "INSERT INTO address (houseNo,street,barangay,city) VALUES (:houseNo,:street,:barangay,:city)";
                                        $stmt = $con->prepare($query);
                                        $stmt->bindParam(':houseNo', $houseNo, PDO::PARAM_STR);
                                        $stmt->bindParam(':street', $street, PDO::PARAM_STR);
                                        $stmt->bindParam(':barangay', $barangay, PDO::PARAM_STR);
                                        $stmt->bindParam(':city', $city, PDO::PARAM_STR);                    
                                        $result = $stmt->execute();
                                        if($result){
                                            $addressID = $con -> lastInsertId();
                                            $query = "INSERT INTO users (firstName, middleName, lastName,addressID,gender,birthDate,email,contact,password,profilepicture,emergencyPerson,emergencyNumber,emergencyRelations,lastLogin,Type) VALUES " .
                                                    "(:firstName, :middleName, :lastName,:addressID,:gender,:birthDate,:email,:contact,:password,:profilePicture,:contactPerson, :contactPersonNo, :contactPersonRel, NOW(),1)";
                                            $stmt = $con->prepare($query);
                                            $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
                                            $stmt->bindParam(':middleName', $middleName, PDO::PARAM_STR);
                                            $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
                                            $stmt->bindParam(':addressID', $addressID, PDO::PARAM_STR);
                                            $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
                                            $stmt->bindParam(':birthDate', $birthDay, PDO::PARAM_STR);
                                            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                                            $stmt->bindParam(':contact', $contactNumber, PDO::PARAM_STR);
                                            $stmt->bindParam(':contactPerson', $contactPerson, PDO::PARAM_STR);
                                            $stmt->bindParam(':contactPersonNo', $contactPersonNo, PDO::PARAM_STR);
                                            $stmt->bindParam(':contactPersonRel', $contactPersonRel, PDO::PARAM_STR);
                                            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                                            $stmt->bindParam(':profilePicture', $path_filename_for_database, PDO::PARAM_STR);
                                            $result = $stmt->execute();

                                            if($result){
                                                $userID = $con -> lastInsertId();
                                                $query = "INSERT INTO forgotpassword(questionID,userID,answer) VALUES (:questionID,:userID,:answer)";
                                                $stmt = $con->prepare($query);
                                                $stmt->bindParam(':questionID', $securityQuestion, PDO::PARAM_STR);
                                                $stmt->bindParam(':userID', $userID, PDO::PARAM_STR);
                                                $stmt->bindParam(':answer', $answer, PDO::PARAM_STR);
                                                $result = $stmt->execute();

                                                if($result){
                                                    echo '<script> alert(\'Account successfully created\')</script>';
                                                }
                                            }
                                        }
                                    }
                            }
                        }
                    }
                ?>
			
			<!-- Create Account -->

			<!-- start footer Area -->		
			<footer class="footer-area section-gap">
				<div class="container">
					<div class="row">
						<div class="col-lg-3  col-md-12">
							<div class="single-footer-widget">
								<h6>Top Products</h6>
								<ul class="footer-nav">
									<li><a href="#">Managed Website</a></li>
									<li><a href="#">Manage Reputation</a></li>
									<li><a href="#">Power Tools</a></li>
									<li><a href="#">Marketing Service</a></li>
								</ul>
							</div>
						</div>
						<div class="col-lg-6  col-md-12">
							<div class="single-footer-widget newsletter">
								<h6>Newsletter</h6>
								<p>You can trust us. we only send promo offers, not a single spam.</p>
								<div id="mc_embed_signup">
									<form target="_blank" novalidate="true" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01" method="get" class="form-inline">

										<div class="form-group row" style="width: 100%">
											<div class="col-lg-8 col-md-12">
												<input name="EMAIL" placeholder="Enter Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Email '" required="" type="email">
												<div style="position: absolute; left: -5000px;">
													<input name="b_36c4fd991d266f23781ded980_aefe40901a" tabindex="-1" value="" type="text">
												</div>
											</div> 
										
											<div class="col-lg-4 col-md-12">
												<button class="nw-btn primary-btn">Subscribe<span class="lnr lnr-arrow-right"></span></button>
											</div> 
										</div>		
										<div class="info"></div>
									</form>
								</div>		
							</div>
						</div>
						<div class="col-lg-3  col-md-12">
							<div class="single-footer-widget mail-chimp">
								<h6 class="mb-20">Connect with Us</h6>
								<ul class="instafeed d-flex flex-wrap">
									<li><a href="https://www.facebook.com/actscleaningservices/"><i class="fa fa-facebook"></i></a></li>
								</ul>
							</div>
						</div>						
					</div>
				</div>
			</footer>
			<!-- End footer Area -->	
				
			<?php 
				include("login.php");
			?>
			<!-- End of Modals -->

			<script src="landingpage/js/vendor/jquery-2.2.4.min.js"></script>
			<script src="landingpage/js/vendor/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
			<script src="landingpage/js/vendor/bootstrap.min.js"></script>
			<script src="landingpage/js/jquery.ajaxchimp.min.js"></script>
			<script src="landingpage/js/parallax.min.js"></script>			
			<script src="landingpage/js/owl.carousel.min.js"></script>			
			<script src="landingpage/js/jquery.sticky.js"></script>
			<script src="landingpage/js/jquery.DonutWidget.min.js"></script>
			<script src="landingpage/js/jquery.magnific-popup.min.js"></script>			
			<script src="landingpage/js/main.js"></script>	
			<div id="myLogin" class="modal fade" role="dialog">
            </div>					  
		</body>
	</html>