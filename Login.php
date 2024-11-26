<?php 
    session_start();

    if(isset($_SESSION["user"])){
        $user = $_SESSION["user"];
        header("Location: MyAlbums.php");
        exit();           
    }
    
    include("./common/header.php"); 
    include_once 'EntityClassLib.php';
    include_once('Functions.php');
    $userIdErr = $passwordErr = $credentialErr = "";
    
    extract($_POST);    
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $hashedPassword = hash("sha256", $password);
    
    if(isset($clearBtn)){
        $userId = $password = "";
    }

    if(isset($submitBtn)){
        $userIdErr = ValidateLoginUserId($userId);
        $passwordErr = ValidateLoginPassword($password);  
        $credentialErr = ValidateLoginCredential($userId, $hashedPassword);

        if(empty($userIdErr) && empty($passwordErr) && empty($credentialErr)){
            try {
                $user = getUserByIdAndPassword($userId, $hashedPassword);
            }
            catch (Exception $e)
            {
                die("The system is currently not available, try again later");
            }
            if ($user != null){
                $_SESSION['user'] = $user; 
                
                if ($user->getIsAdmin())
                {
                    header("Location: Admin.php");
                    exit();
                }
                else
                {
                    header("Location: MyAlbums.php");
                    exit();                  
                }
                
            } 
        }         
    }      
?>
<div class="container">
<br>
<h1>Log In</h1>
<br>
<p>You need to <a href='NewUser.php'>sign up</a> if you are a new user</p>
<br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post"> 
    <?php
      if (!empty($credentialErr)){
          echo "<div class=\"text-danger\">
                    $credentialErr
                </div><br>";                          
      }
    ?>      
    <div class="row form-group">
        <label for="userId" class="col-md-3 col-form-label">User ID: </label>
        <div class="col-md-4">
            <input type="text" class="form-control" id="userId" name="userId" value="<?php echo isset($userId)? $userId : ''; ?>">
        </div>
        <?php
          if (!empty($userIdErr)){
              echo "<div class=\"text-danger col-md-5\">
                        $userIdErr
                    </div>";                          
          }
        ?>         
    </div>  
    <div class="row form-group">
        <label for="password" class="col-md-3 col-form-label">Password: </label>
        <div class="col-md-4">
            <input type="password" class="form-control" id="password" name="password" value="<?php echo isset($password)? $password : ''; ?>">
        </div>
        <?php
          if (!empty($passwordErr)){
              echo "<div class=\"text-danger col-md-5\">
                        $passwordErr
                    </div>";                          
          }
        ?>         
    </div>  
    <button type="submit" name="submitBtn" class="btn btn-primary">Submit</button> 
    <button type="submit" name="clearBtn" class="btn btn-danger">Clear</button>    
</form>
</div>
<?php include('./common/footer.php'); ?>