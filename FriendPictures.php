<?php
include_once 'EntityClassLib.php';
include_once 'Functions.php';
session_start();

//check the friend is selected and its ID is accessible
if (isset($_SESSION["friendId"])) {
    $friendId = $_SESSION["friendId"];
} else {
    header("Location: MyFriends.php");
    exit();
}

if (isset($_SESSION["user"])) {
    $user = $_SESSION["user"];
} else {
    header("Location: Login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);

    if (isset($_POST["albumChangeBtn"])) {
        if ($albumId != -1) {
            $pictures = getAllPicturessByAlbumId($albumId);
            if (empty($pictures)) {
                $noPictures = true;
            } else {
                $noPictures = false;
            }
            $_SESSION["albumId"] = $albumId;
        } else {
            $_SESSION["albumId"] = '';
        }
        unset($_SESSION['fileName']);
        unset($_SESSION["selectedPicture"]);
        unset($_SESSION["comments"]);
    }

    if (isset($_POST['thumbnailChangeBtn'])) {
        $fileName = $_POST['selectedImageFileName'];
        $_SESSION['fileName'] = $fileName;

        $selectedPicture = getPictureByFileName('./originals/' . $fileName);
        $_SESSION["selectedPicture"] = $selectedPicture;

        $comments = getAllCommentsForSelectedPictureOnMyPicturePage($selectedPicture->getPictureId());
        $_SESSION['comments'] = $comments;
    }

    if (isset($_POST['commentBtn'])) {
        if (isset($_SESSION["selectedPicture"])) {
            $selectedPicture = $_SESSION["selectedPicture"];
            addCommentOnMyPicturePage($user->getUserId(), $selectedPicture->getPictureId(), $_POST['commentText']);
            $comments = getAllCommentsForSelectedPictureOnMyPicturePage($selectedPicture->getPictureId());
            $_SESSION['comments'] = $comments;
        } else {
            if (isset($_SESSION['albumId'])) {
                $pictures = getAllPicturessByAlbumId($_SESSION["albumId"]);
                addCommentOnMyPicturePage($user->getUserId(), $pictures[0]->getPictureId(), $_POST['commentText']);
            }
        }
    }
}

include("./common/header.php");
?>
<div class="container">
    <h1 class="text-center">Your friend <?php echo getFriend($friendId)->getFriendName(); ?>'s Shared Pictures</h1>
    <br>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="albumForm">
        <div class="row form-group">
            <div class="col-md-7">
                <select class="form-control" name="albumId" onchange="AlbumSelected()">
                    <?php
                    $albums = getFriendsAlbums($friendId);
                    $selectedAlbumId = isset($_SESSION['albumId']) ? $_SESSION['albumId'] : -1;
                    echo '<option value="-1" ' . ($selectedAlbumId == -1 ? 'selected' : '') . '>Select any album...</option>';
                    for ($i = 0; $i < count($albums); $i++) {
                        echo '<option value="' . $albums[$i]->getAlbumId() . '" ' . ($albums[$i]->getAlbumId() == $selectedAlbumId ? 'selected' : '') . '>'
                        . $albums[$i]->getTitle() . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-7">
                <?php
                if (isset($_SESSION['fileName']) && isset($_SESSION["selectedPicture"])) {
                    $selectedPicture = $_SESSION["selectedPicture"];
                    $selectedPictureTitle = $selectedPicture->getTitle();
                    echo "<h4 class=\"text-center\">$selectedPictureTitle</h4>";
                    echo '<img class="img-responsive" src="./images/' . $_SESSION['fileName'] . '">';
                } else {
                    if (isset($_SESSION["albumId"])) {
                        $pictures = getAllPicturessByAlbumId($_SESSION["albumId"]);
                        // Check if the array is not empty before accessing its elements
                        if (!empty($pictures)) {
                            $firstPictureTitle = $pictures[0]->getTitle();
                            $originalPath = $pictures[0]->getFileName();
                            $imagesPath = preg_replace('/^\.\/originals\//', './images/', $originalPath);

                            echo "<h4 class=\"text-center\">$firstPictureTitle</h4>";
                            echo '<img class="img-responsive" src="' . $imagesPath . '">';
                        } else {
                            echo "<p>No pictures found in the album.</p>";
                        }
                    }
                }
                ?>
                <br>
                <div class="thumbnail-bar">
                    <div class="thumbnails-container">
                        <?php
                        if (isset($_SESSION["albumId"])) {
                            $pictures = getAllPicturessByAlbumId($_SESSION["albumId"]);
                            if (isset($_SESSION["selectedPicture"])) {
                                $selectedPicture = $_SESSION["selectedPicture"];
                                $selectedPictureFileName = $selectedPicture->getFileName();
                            }

                            foreach ($pictures as $index => $picture) {
                                global $selectedPictureFileName;
                                $originalPath = $picture->getFileName();
                                $thumbnailsPath = preg_replace('/^\.\/originals\//', './thumbnails/', $originalPath);
                                $thumbnailClass = ($originalPath == $selectedPictureFileName) ? 'thumbnail clicked' : 'thumbnail';
                                echo "<div class='$thumbnailClass' data-index='$index' data-path='$thumbnailsPath' onclick='highlightThumbnail(this)'>"
                                . "<img src='$thumbnailsPath'>"
                                . "</div>";
                            }
                        }
                        ?>
                    </div>
                </div>
                <input type="text" name="selectedImageFileName" id="imageFileName" hidden/>
            </div>

            <?php global $noPictures;
            if (!$noPictures) : ?>
                <div class="col-md-5">
                    <?php
                    if (isset($_SESSION["selectedPicture"])) {
                        $selectedPicture = $_SESSION["selectedPicture"];
                        $selectedPictureDescription = $selectedPicture->getDescription();
                        if (!empty($selectedPictureDescription)) {
                            echo '<label class="col-form-label">Description:</label>';
                            echo "<p>$selectedPictureDescription</p>";
                        }
                        echo '<label class="col-form-label"> Comments:</label>';
                        if (isset($_SESSION['comments']) && is_array($_SESSION['comments'])) {
                            $comments = $_SESSION['comments'];

                            for ($i = count($comments) - 1; $i >= 0; $i--) {
                                echo '<p>';
                                echo '<span class="text-primary">' . $comments[$i]->getUserName() . '</span>' . ': ';
                                echo $comments[$i]->getCommentText();
                                echo '</p>';
                            }
                        }
                        echo '<textarea name="commentText" class="form-control" placeholder="Leave a comment..."></textarea><br>';
                        echo '<button type="submit" name="commentBtn" class="btn btn-primary">Add comment</button>';
                    } else {
                        if (isset($_SESSION['albumId'])) {
                            $pictures = getAllPicturessByAlbumId($_SESSION["albumId"]);
                            // Check if the array is not empty before accessing its elements
                            if (!empty($pictures)) {
                                $firstPictureDescription = $pictures[0]->getDescription();
                                $comments = getAllCommentsForSelectedPictureOnMyPicturePage($pictures[0]->getPictureId());
                                if (!empty($firstPictureDescription)) {
                                    echo '<label class="col-form-label">Description:</label>';
                                    echo "<p>$firstPictureDescription</p>";
                                }
                                echo '<label class="col-form-label"> Comments:</label>';
                                for ($i = count($comments) - 1; $i >= 0; $i--) {
                                    echo '<p>';
                                    echo '<span class="text-primary">' . $comments[$i]->getUserName() . '</span>' . ': ';
                                    echo $comments[$i]->getCommentText();
                                    echo '</p>';
                                }
                                echo '<textarea name="commentText" class="form-control" placeholder="Leave a comment..."></textarea><br>';
                                echo '<button type="submit" name="commentBtn" class="btn btn-primary">Add comment</button>';
                            }
                        }
                    }
                    ?>
                    <?php endif; ?>
                    <button type="submit" name="albumChangeBtn" id="albumSelectionChange" hidden></button>
                    <button type="submit" name="thumbnailChangeBtn" id="thumbnailChange" hidden></button>
                </div>
        </div>
    </form>
</div>

<?php include('./common/footer.php'); ?>
