<?php 
    session_start();
    
    if(isset($_SESSION["user"])){
        $user = $_SESSION["user"];
    }
    
    unset($_SESSION['fileName']);
    unset($_SESSION["selectedPicture"]);
    unset($_SESSION["comments"]);
    unset($_SESSION["albumId"]);
   
    include("./common/header.php"); 
?>
<div class="container">
<div style="display: flex; align-items: center;">
    <h1 style="margin-right: 20px">Welcome to Bee Yourself</h1>
    <img src="Common/img/logo.jpeg" alt="Bee Yourself logo" style="max-width:80px; max-height:100%;"/>
</div>
<br>
<?php 
    if (!$user){
        print "<p>If you have never used this before, you have to <a href='NewUser.php'>sign up</a> first.</p>";
        print "<p>If you have already signed up, you can <a href='Login.php'>log in</a> now.</p>";
    } else {
        print "You're already logged in.";
    }
?>
</div>
<?php include('./common/footer.php'); ?>