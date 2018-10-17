<!-- login modal -->
<div class="modal fade" id="myLogin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header p-3 mb-2 bg-success">
        <h5 class="modal-title mx-auto" id="exampleModalLabel">WELCOME</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method = "post">
            <div class = inputs>
                <div class = username>
                    <strong><b>Email: </b></strong><br>
                    <input type="email" name="email" placeholder="Email" required><br>
                </div>
                <div class = password>
                    <strong><b>Password: </b></strong><br>
                    <input type="password" name="password" placeholder="Password" required><br>
                </div>
            </div> 
        <center><button type="submit" class="btn btn-primary" name = "btnLogin">Log-in</button></center>
        <a href="index?route=create" class="text-success">Create Account</a>
        </form>
    </div>
  </div>
</div>
</div>
<?php
  if(isset($_POST['btnLogin'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = (:email) AND password = (:password)";
    $stmt = $con -> prepare($query);
    $stmt -> bindParam(':email',$email,PDO::PARAM_STR);
    $stmt -> bindParam(':password',$password,PDO::PARAM_STR);
    $stmt -> execute();
    $result = $stmt -> fetch();
    $rowCnt = $stmt -> rowCount();
    if($rowCnt > 0){
        $_SESSION['userID'] = $result['userID'];
        $_SESSION['userType'] = $result['Type'];
        if($result['Type'] == 2){
            echo '<script> window.location.href = \'index?route=handyman_profile\'</script>';
        }else{
            echo '<script> window.location.href = \'index?route=customer_profile\' </script>';
        }
    }else{
        echo '<script> alert(Invalid credentials.) </script>';
    }
  }
?>
			<!-- Modals -->