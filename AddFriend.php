<?php 
    include_once 'EntityClassLib.php';
    include_once('Functions.php');
    session_start();
    //check whether the user is logged in
    if(isset($_SESSION["user"])){
        $user = $_SESSION["user"];
    } else {
        header("Location: Login.php");
        exit();           
    }
    
    $status = 'request';
    $friendIdErr = "";
    $successMsg = "";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $friendId = $_POST['friendId'];
        if(isset($_POST["submitBtn"])){
            $friendIdErr = ValidateFriendId($user->getUserId(), $friendId);

            if(empty($friendIdErr)){
                try {
                    $friend = getFriend($friendId);
                    $friendName = getFriend($friendId)->getFriendName();                    
                    $successMsg = sendFriendRequest($user->getUserId(), $friendId, $friendName, $status);                  
                }
                catch (Exception $e)
                {
                    die("The system is currently not available, try again later");
                }
            }         
        }      
    }   
    include("./common/header.php");  
?>
<div class="container">
<h1 class="center">My Friends</h1>
<h2>Welcome <strong><?php echo $user->getName(); ?></strong>! (not you? change user <a href="Logout.php">here</a>)</h2>
<br>
<h3>Enter the ID of the user you want to be friend with:</h3>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <div class="row form-group">
        <label for="friendId" class="col-sm-1 col-form-label">ID: </label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="friendId" name="friendId" value="<?php echo isset($friendId)? $friendId : ''; ?>">
        </div>
        <?php
          if (!empty($friendIdErr)){
              echo "<div class=\"text-danger col-md-5\">
                        $friendIdErr
                    </div>";                          
          }
          if (!empty($successMsg)){
              echo "<div class=\"text-danger col-md-5\">
                        $successMsg
                    </div>";                          
          }          
        ?> 
        
    </div>     
    <button type="submit" name="submitBtn" class="btn btn-primary">Send Friend Request</button> 
</form>
<br>
</div>
<?php include('./common/footer.php'); ?>