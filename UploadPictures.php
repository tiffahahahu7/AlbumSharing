<?php
include_once 'EntityClassLib.php';
include_once('Functions.php');
session_start();
//check whether the user is logged in
if (isset($_SESSION["user"])) {
    $user = $_SESSION["user"];
    if ($user->getIsAdmin())
    {
        header("Location: AdminPage.php");
        exit();
    }
} else {
    header("Location: Login.php");
    exit();
}

unset($_SESSION['fileName']);
unset($_SESSION["selectedPicture"]);
unset($_SESSION["comments"]);
unset($_SESSION["albumId"]);

$Msg = "";

//define constants for convenience
define("ORIGINAL_IMAGE_DESTINATION", "./originals");

define("IMAGE_DESTINATION", "./images");
define("IMAGE_MAX_WIDTH", 800);
define("IMAGE_MAX_HEIGHT", 600);

define("THUMB_DESTINATION", "./thumbnails");
define("THUMB_MAX_WIDTH", 100);
define("THUMB_MAX_HEIGHT", 100);

//Use an array to hold supported image types for convenience
$supportedImageTypes = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["uploadBtn"])) {
        $destination = './originals';

        if (!file_exists($destination)) {
            mkdir($destination);
        }

        extract($_POST);
        if (isset($_FILES['pictureUpload']) && is_array($_FILES['pictureUpload']['tmp_name'])) {
            for ($j = 0; $j < count($_FILES['pictureUpload']['tmp_name']); $j++) {
                if ($_FILES['pictureUpload']['error'][$j] === UPLOAD_ERR_OK) {
                    $fileTempPath = $_FILES['pictureUpload']['tmp_name'][$j];
                    $filePath = $destination . "/" . $_FILES['pictureUpload']['name'][$j];
                    $pathInfo = pathinfo($filePath);
                    $dir = $pathInfo['dirname'];
                    $fileName = $pathInfo['filename'];
                    $ext = $pathInfo['extension'];

                    $i = "";
                    while (file_exists($filePath)) {
                        $i++;
                        $filePath = $dir . "/" . $fileName . "_" . $i . "." . $ext;
                    }
                    move_uploaded_file($fileTempPath, $filePath);

                    $imageDetails = getimagesize($filePath);

                    if ($imageDetails && in_array($imageDetails[2], $supportedImageTypes)) {
                        resamplePicture($filePath, IMAGE_DESTINATION, IMAGE_MAX_WIDTH, IMAGE_MAX_HEIGHT);
                        resamplePicture($filePath, THUMB_DESTINATION, THUMB_MAX_WIDTH, THUMB_MAX_HEIGHT);
                    } else {
                        $error = "Uploaded file is not a supported type";
                        unlink($filePath);
                    }

                    try {
                        addPicture($_POST['albumId'], $filePath, $_POST['pictureTitle'], $_POST['pictureDescription']);
                        $Msg = "File(s) uploaded";
                    } catch (Exception $ex) {
                        // Log the error or provide a more user-friendly error message
                        error_log("Error while adding picture: " . $ex->getMessage());
                        $Msg = "An error occurred while processing your request. Please try again later.";
                    }
                } elseif ($_FILES['pictureUpload']['error'][$j] == 1) {
                    $Msg = "$fileName is too large";
                } elseif ($_FILES['pictureUpload']['error'][$j] == 4) {
                    $Msg = "No upload file specified ";
                }
            }
        } else {
            $Msg = "Error happened while uploading the file(s). Try again later.";
        }
    }
}

include("./common/header.php");
?>
<div class="container">
        <h1 class="text-center">Upload pictures</h1>
        <p>Accepted picture types: JPG(JPEG), GIF and PNG</p>
        <p>You can upload multiple pictures at a time by pressing the shift key while selecting pictures. </p>
        <p>When uploading multiple pictures, the title and description fields will be applied to all pictures.</p>
        <br>
        <form  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="row form-group">
                <label for="albumId" class="col-md-3 col-form-label">Upload to Album:</label>
                <div class="col-md-4">
                    <select class="form-control col-6" name="albumId">
                    <?php
                    $albums = getMyOwnAlbums($user->getUserId());

                    for ($i = 0; $i < count($albums); $i++) {
                        echo '<option value="' . $albums[$i]->getAlbumId() . '">' . $albums[$i]->getTitle() . '</option>';
                    }
                    ?>
                    </select>                    
                </div>
            </div>
            <div class="row form-group">
                <label for="pictureUpload" class="col-md-3 col-form-label">File to upload:</label>
                <div class="col-md-4">
                    <input type="file" class="form-control" name="pictureUpload[]" accept="image/*"  multiple>
                </div>
            </div>
            <div class="row form-group">
                <label for="pictureTitle" class="col-md-3 col-form-label">Title:</label>
                <div class="col-md-4">
                    <input type="text" name="pictureTitle" class="form-control">
                </div>
            </div>
            <div class="row form-group">
                <label for="pictureDescription" class="col-md-3 col-form-label">Description:</label>
                <div class="col-md-4">
                    <textarea name="pictureDescription" class="form-control"></textarea>
                </div>
            </div>
            <button type="submit" name="uploadBtn" class="btn btn-primary mx-4">Submit</button>
            <button type="submit" name="clearBtn" class="btn btn-danger mx-4">Clear</button> 
        </form>
        <br>
        <?php
        global $Msg;
        echo "<p class='text-danger fw-bold'>$Msg</p>";
        ?>
</div>


<?php include('./common/footer.php'); ?>