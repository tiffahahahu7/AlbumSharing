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
      
    $accessibilities = getAccessibilities();
    $titleErr = "";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
       $title = $_POST["title"];  
       $accessibilityCode = $_POST["accessibility"];
       $description = $_POST["description"];  
       //clear all input fields
       if(isset($_POST["clearBtn"])){ 
           $title = $description = "";
       }
       //validate the input fields and if passed, add the album to the database
       if(isset($_POST["submitBtn"])){ 
           $titleErr = ValidateUserId($title);
           if(empty($titleErr)){
                try {
                    addAlbum($title, $user->getUserId(), $description, $accessibilityCode);
                    header("Location: MyAlbums.php");
                    exit();                      
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
<h1 class="center">Create New Album</h1>
<h2>Welcome <strong><?php echo $user->getName(); ?></strong>! (not you? change user <a href="Logout.php">here</a>)</h2>
<br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <div class="row form-group">
      <label for="title" class="col-md-3 col-form-label">Title: </label>
      <div class="col-md-4">
        <input type="text" class="form-control" id="title" name="title"
            <?php 
            if (isset($title)){
                echo "value = \"$title\"";
            }
            ?>                                   
        >
      </div>
      <?php
        if (!empty($titleErr)){
            echo "<div class=\"text-danger col-md-5\">
                      $titleErr
                  </div>";                          
        }
      ?>                          
    </div> 
    <div class="row form-group">
      <label for="accessibility" class="col-md-3 col-form-label">Accessibility: </label>
      <div class="col-md-4">
        <select class="form-control" name="accessibility" id="accessibility">
            <?php        
              foreach($accessibilities as $a){
                  echo '<option value="'.$a->getCode().'"'.(isset($accessibilityCode) && $accessibilityCode == $a->getCode() ? 'selected': '').'>'.$a->getDescription().'</option>';
              }            
            ?>    
        </select> 
      </div>                       
    </div>     
    <div class="row form-group">
      <label for="description" class="col-md-3 col-form-label">Description: </label>
      <div class="col-md-4">
        <textarea class="form-control" id="description" name="description"<?php if (isset($description)){echo " value = \"$description\"";} ?>></textarea>    
      </div>
    </div>
    <button type="submit" name="submitBtn" class="btn btn-primary">Submit</button> 
    <button type="submit" name="clearBtn" class="btn btn-danger">Clear</button> 
</form>
<br>
</div>
<?php include('./common/footer.php'); ?>