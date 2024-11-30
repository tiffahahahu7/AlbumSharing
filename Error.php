<?php 
include_once 'EntityClassLib.php';
include_once 'Functions.php';
session_start();

if(isset($_SESSION["user"])){
    $user = $_SESSION["user"];
}

include("./common/header.php"); 


?>


<div class="container">
    <br>
    <h1>Error Page</h1>
    <p>You might have wandered off too far.  Please navigate back to your pages using the top Navigation Bar</p>
    <br>
</div>


<?php include('./common/footer.php'); ?>
