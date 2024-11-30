<?php
session_start();
if (isset($_SESSION["user"])) {
    $user = $_SESSION["user"];
    header("Location: MyAlbums.php");
    exit();
}

include("./common/header.php");
include_once 'EntityClassLib.php';
include_once('Functions.php');
$userIdErr = $nameErr = $phoneNumberErr = $passwordErr = $rePasswordErr = "";

extract($_POST);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$hashedPassword = hash("sha256", $password);

if (isset($clearBtn)) {
    $userId = $name = $phoneNumber = $password = $rePassword = "";
}

if (isset($submitBtn)) {
    $userIdErr = ValidateUserId($userId);
    $nameErr = ValidateName($name);
    $phoneNumberErr = ValidatePhone($phoneNumber);
    $passwordErr = ValidatePassword($password);
    $rePasswordErr = ValidateRePassword($password, $rePassword);

    if (empty($userIdErr) && empty($nameErr) && empty($phoneNumberErr) && empty($passwordErr) && empty($rePasswordErr)) {
        try {
            addNewUser($userId, $name, $phoneNumber, $hashedPassword);
            $user = getUserByIdAndPassword($userId, $hashedPassword);
        } catch (Exception $e) {
            die("The system is currently not available, try again later");
        }
        if ($user != null) {
            $_SESSION['user'] = $user;
            header("Location: MyAlbums.php");
            exit();
        }
    }
}
?>
<div class="container">
    <h1>Sign Up</h1>
    <h3>All fields are required</h3>
    <br>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="row form-group">
            <label for="userId" class="col-md-3 col-form-label">User ID: </label>
            <div class="col-md-4">
                <input type="text" class="form-control" id="userId" name="userId" value="<?php echo isset($userId) ? $userId : ''; ?>">
            </div>
            <?php
            if (!empty($userIdErr)) {
                echo "<div class=\"text-danger col-md-5\">
                        $userIdErr
                    </div>";
            }
            ?>         
        </div>
        <div class="row form-group">
            <label for="name" class="col-md-3 col-form-label">Name: </label>
            <div class="col-md-4">
                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>">
            </div>
            <?php
            if (!empty($nameErr)) {
                echo "<div class=\"text-danger col-md-5\">
                        $nameErr
                    </div>";
            }
            ?>           
        </div>    
        <div class="row form-group">
            <label for="phoneNumber" class="col-md-3 col-form-label">Phone Number <small>(nnn-nnn-nnnn)</small>: </label>
            <div class="col-md-4">
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo isset($phoneNumber) ? $phoneNumber : ''; ?>">
            </div>
            <?php
            if (!empty($phoneNumberErr)) {
                echo "<div class=\"text-danger col-md-5\">
                        $phoneNumberErr
                    </div>";
            }
            ?>         
        </div> 
        <div class="row form-group">
            <label for="password" class="col-md-3 col-form-label">Password: </label>
            <div class="col-md-4">
                <input type="password" class="form-control" id="password" name="password" value="<?php echo isset($password) ? $password : ''; ?>">
            </div>
            <?php
            if (!empty($passwordErr)) {
                echo "<div class=\"text-danger col-md-5\">
                        $passwordErr
                    </div>";
            }
            ?>         
        </div> 
        <div class="row form-group">
            <label for="rePassword" class="col-md-3 col-form-label">Password Again: </label>
            <div class="col-md-4">
                <input type="password" class="form-control" id="rePassword" name="rePassword" value="<?php echo isset($rePassword) ? $rePassword : ''; ?>">
            </div>
            <?php
            if (!empty($rePasswordErr)) {
                echo "<div class=\"text-danger col-md-5\">
                        $rePasswordErr
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