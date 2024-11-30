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
                header("Location: MyAlbums.php");
                exit();                  
            } 
        }         
    }      
?>
<div class="container">
<h1>Log In <svg style="width: 50px; height: auto;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-51.2 -51.2 614.40 614.40" xml:space="preserve" fill="#ffffff" stroke="#ffffff" stroke-width="0.00512" transform="matrix(1, 0, 0, 1, 0, 0)rotate(0)"><g id="SVGRepo_bgCarrier" stroke-width="0" transform="translate(0,0), scale(1)"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#ffffff" stroke-width="35.839999999999996"> <g id="bee"> <g> <polygon style="fill:#FEC738;" points="102,256 77,256 77,282 51,282 51,307 77,307 77,333 102,333 102,358 128,358 128,332.8 128,307.2 128,281.6 128,256 128,230 102,230 "></polygon> <polygon style="fill:#FEC738;" points="179,256 154,256 154,281.6 154,307.2 154,332.8 154,358.4 154,384 179.2,384 205,384 205,358.4 205,332.8 205,307.2 205,282 179,282 "></polygon> <polygon style="fill:#FEC738;" points="333,282 333,256 333,230 307,230 307,256 307,281.6 307,307.2 307,332.8 307,358 333,358 333,332.8 333,307 358,307 358,282 "></polygon> <polygon style="fill:#FEC738;" points="230,307 230,332.8 230,358.4 230,384 256,384 282,384 282,358.4 282,332.8 282,307 256,307 "></polygon> </g> <polygon style="fill:#BFC0C0;" points="307,154 307,128 307,102 281.6,102 256,102 230,102 230,128 256,128 256,154 282,154 282,179 307,179 307,204.8 307,230 333,230 333,204.8 333,179.2 333,154 "></polygon> <rect x="128" y="230" style="fill:#070000;" width="26" height="25.68"></rect> <rect x="128" y="255.68" style="fill:#070000;" width="26" height="25.68"></rect> <polygon style="fill:#FFFFFF;" points="384,230 384,256 384,282 410,282 410,256 410,230 "></polygon> <rect x="128" y="281.36" style="fill:#070000;" width="26" height="25.68"></rect> <rect x="204.8" y="281.6" style="fill:#070000;" width="25.6" height="25.6"></rect> <polygon style="fill:#A0A09C;" points="282,179 282,154 256,154 256,128 230.4,128 205,128 205,102 179.2,102 153.6,102 128,102 128,128 102,128 102,153.6 102,179 128,179 128,204.8 128,230 154,230 154,256 179,256 179,282 204.8,282 230,282 230,307 256,307 282,307 282,282 307,282 307,256 307,230.4 307,204.8 307,179 "></polygon> <rect x="281.6" y="281.6" style="fill:#070000;" width="25.6" height="25.6"></rect> <rect x="128" y="307.04" style="fill:#070000;" width="26" height="25.68"></rect> <rect x="204.8" y="307.2" style="fill:#070000;" width="25.6" height="25.6"></rect> <rect x="281.6" y="307.2" style="fill:#070000;" width="25.6" height="25.6"></rect> <rect x="128" y="332.72" style="fill:#070000;" width="26" height="25.68"></rect> <rect x="204.8" y="332.8" style="fill:#070000;" width="25.6" height="25.6"></rect> <rect x="281.6" y="332.8" style="fill:#070000;" width="25.6" height="25.6"></rect> <g> <polygon style="fill:#070000;" points="435,281.6 435,256 435,230 410,230 410,256 410,282 384,282 384,256 384,230 410,230 410,205 384,205 358.4,205 333,205 333,230.4 333,256 333,282 358,282 358,307 384,307 384,333 409.6,333 435,333 435,307.2 "></polygon> <rect x="435" y="333" style="fill:#070000;" width="26" height="25"></rect> </g> <rect x="204.8" y="358.4" style="fill:#070000;" width="25.6" height="25.6"></rect> <rect x="281.6" y="358.4" style="fill:#070000;" width="25.6" height="25.6"></rect> <g> <rect x="102" y="358" style="fill:#9F7424;" width="26" height="26"></rect> <rect x="77" y="384" style="fill:#9F7424;" width="25" height="26"></rect> <rect x="179" y="384" style="fill:#9F7424;" width="26" height="26"></rect> <rect x="256" y="384" style="fill:#9F7424;" width="26" height="26"></rect> <rect x="154" y="410" style="fill:#9F7424;" width="25" height="25"></rect> <rect x="230" y="410" style="fill:#9F7424;" width="26" height="25"></rect> </g> </g> <g id="Layer_1"> </g> </g><g id="SVGRepo_iconCarrier"> <g id="bee"> <g> <polygon style="fill:#FEC738;" points="102,256 77,256 77,282 51,282 51,307 77,307 77,333 102,333 102,358 128,358 128,332.8 128,307.2 128,281.6 128,256 128,230 102,230 "></polygon> <polygon style="fill:#FEC738;" points="179,256 154,256 154,281.6 154,307.2 154,332.8 154,358.4 154,384 179.2,384 205,384 205,358.4 205,332.8 205,307.2 205,282 179,282 "></polygon> <polygon style="fill:#FEC738;" points="333,282 333,256 333,230 307,230 307,256 307,281.6 307,307.2 307,332.8 307,358 333,358 333,332.8 333,307 358,307 358,282 "></polygon> <polygon style="fill:#FEC738;" points="230,307 230,332.8 230,358.4 230,384 256,384 282,384 282,358.4 282,332.8 282,307 256,307 "></polygon> </g> <polygon style="fill:#BFC0C0;" points="307,154 307,128 307,102 281.6,102 256,102 230,102 230,128 256,128 256,154 282,154 282,179 307,179 307,204.8 307,230 333,230 333,204.8 333,179.2 333,154 "></polygon> <rect x="128" y="230" style="fill:#070000;" width="26" height="25.68"></rect> <rect x="128" y="255.68" style="fill:#070000;" width="26" height="25.68"></rect> <polygon style="fill:#FFFFFF;" points="384,230 384,256 384,282 410,282 410,256 410,230 "></polygon> <rect x="128" y="281.36" style="fill:#070000;" width="26" height="25.68"></rect> <rect x="204.8" y="281.6" style="fill:#070000;" width="25.6" height="25.6"></rect> <polygon style="fill:#A0A09C;" points="282,179 282,154 256,154 256,128 230.4,128 205,128 205,102 179.2,102 153.6,102 128,102 128,128 102,128 102,153.6 102,179 128,179 128,204.8 128,230 154,230 154,256 179,256 179,282 204.8,282 230,282 230,307 256,307 282,307 282,282 307,282 307,256 307,230.4 307,204.8 307,179 "></polygon> <rect x="281.6" y="281.6" style="fill:#070000;" width="25.6" height="25.6"></rect> <rect x="128" y="307.04" style="fill:#070000;" width="26" height="25.68"></rect> <rect x="204.8" y="307.2" style="fill:#070000;" width="25.6" height="25.6"></rect> <rect x="281.6" y="307.2" style="fill:#070000;" width="25.6" height="25.6"></rect> <rect x="128" y="332.72" style="fill:#070000;" width="26" height="25.68"></rect> <rect x="204.8" y="332.8" style="fill:#070000;" width="25.6" height="25.6"></rect> <rect x="281.6" y="332.8" style="fill:#070000;" width="25.6" height="25.6"></rect> <g> <polygon style="fill:#070000;" points="435,281.6 435,256 435,230 410,230 410,256 410,282 384,282 384,256 384,230 410,230 410,205 384,205 358.4,205 333,205 333,230.4 333,256 333,282 358,282 358,307 384,307 384,333 409.6,333 435,333 435,307.2 "></polygon> <rect x="435" y="333" style="fill:#070000;" width="26" height="25"></rect> </g> <rect x="204.8" y="358.4" style="fill:#070000;" width="25.6" height="25.6"></rect> <rect x="281.6" y="358.4" style="fill:#070000;" width="25.6" height="25.6"></rect> <g> <rect x="102" y="358" style="fill:#9F7424;" width="26" height="26"></rect> <rect x="77" y="384" style="fill:#9F7424;" width="25" height="26"></rect> <rect x="179" y="384" style="fill:#9F7424;" width="26" height="26"></rect> <rect x="256" y="384" style="fill:#9F7424;" width="26" height="26"></rect> <rect x="154" y="410" style="fill:#9F7424;" width="25" height="25"></rect> <rect x="230" y="410" style="fill:#9F7424;" width="26" height="25"></rect> </g> </g> <g id="Layer_1"> </g> </g></svg></h1>
<h3>You need to <a href='NewUser.php'>sign up</a> if you are a new user <3</h3>
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

<div class="phishing-pamphlet">
    <img src="Common/img/internet-safety-tips.png" alt="Phishing Pamphlet">
</div>
<?php include('./common/footer.php'); ?>
