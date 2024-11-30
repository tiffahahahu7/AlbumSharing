<?php

include_once 'EntityClassLib.php';

// basic PDO and user management
function getPDO() {
    $dbConnection = parse_ini_file("Database.ini");
    extract($dbConnection);
    return new PDO($dsn, $scriptUser, $scriptPassword);
}

function getAdminPDO() {
    $dbConnection = parse_ini_file("Database.ini");
    extract($dbConnection);
    return new PDO($dsn, $adminUser, $scriptPassword);
}

function getUserByIdAndPassword($userId, $password) {
    if ($userId == 'admin1')
    {
        $pdo = getAdminPDO();
    }
    else
    {
        $pdo = getPDO();
    }

    $sql = "SELECT UserId, Name, Phone, isAdmin FROM User WHERE UserId = :userId AND Password = :password";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $userId, 'password' => $password]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return new User($row['UserId'], $row['Name'], $row['Phone'], $row['isAdmin']);
    } else {
        return null;
    }
}

function addNewUser($userId, $name, $phone, $password) {
    //$pdo = getPDO();
    
    if ($userId == 'admin1')
    {
        $pdo = getAdminPDO();
    }
    else
    {
        $pdo = getPDO();
    }

    try {
        $sql = "INSERT INTO User (UserId, Name, Phone, Password) VALUES( :userId, :name, :phone, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userId' => $userId, 'name' => $name, 'phone' => $phone, 'password' => $password]);
        echo "added";
    } catch (Exception $ex) {
        echo "$ex";
    }
}

// validation for NewUser.php
function ValidateUserId($userId) {
    $trimmeduserId = trim($userId);
    $userIdErr = "";

    if (empty($trimmeduserId)) {
        $userIdErr = "User ID cannot be blank.";
    } else {
        //$pdo = getPDO();
        
        if ($userId == 'admin1')
        {
            $pdo = getAdminPDO();
        }
        else
        {
            $pdo = getPDO();
        }

        // Use prepared statement to prevent SQL injection
        $stmt = $pdo->prepare("SELECT UserId FROM User WHERE UserId = :userId");
        $stmt->bindParam(':userId', $trimmeduserId);
        $stmt->execute();

        // Check if the query was successful
        if ($stmt !== false) {
            // Check the number of rows returned
            if ($stmt->rowCount() > 0) {
                $userIdErr = "A user with this ID has already signed up.";
            }
        } else {
            // Handle the case where the query failed
            $userIdErr = "Error executing the query.";
        }
    }

    return $userIdErr;
}

function ValidateName($name) {
    $trimmedName = trim($name);
    if (empty($trimmedName)) {
        $nameErr = "Name cannot be blank.";
    } else {
        $nameErr = "";
    }
    return $nameErr;
}

function ValidatePhone($phoneNumber) {
    $trimmedPhone = trim($phoneNumber);
    if (empty($trimmedPhone)) {
        $phoneNumberErr = "Phone number cannot be blank.";
    } elseif (!preg_match("/^(\d{3})-?(\d{3})-?(\d{4})$/", $trimmedPhone)) {
        $phoneNumberErr = "Incorrect phone number format.";
    } else {
        $phoneNumberErr = "";
    }
    return $phoneNumberErr;
}

function ValidatePassword($password) {
    $trimmedpassword = trim($password);
    if (empty($trimmedpassword)) {
        $passwordErr = "Password cannot be blank.";
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{6,}$/", $trimmedpassword)) {
        $passwordErr = "Incorrect password format.";
    } else {
        $passwordErr = "";
    }
    return $passwordErr;
}

function ValidateRePassword($password, $rePassword) {
    if ($rePassword == $password && $rePassword == "") {
        $rePasswordErr = "Password cannot be blank.";
    } elseif ($rePassword != $password) {
        $rePasswordErr = "Password must be matched.";
    } else {
        $rePasswordErr = "";
    }
    return $rePasswordErr;
}

// validation for Login.php
function ValidateLoginUserId($userId) {
    $trimmeduserId = trim($userId);
    if (empty($trimmeduserId)) {
        $userIdErr = "User ID cannot be blank.";
    } else {
        $userIdErr = "";
    }
    return $userIdErr;
}

function ValidateLoginPassword($password) {
    $trimmedpassword = trim($password);
    if (empty($trimmedpassword)) {
        $passwordErr = "Password cannot be blank.";
    } else {
        $passwordErr = "";
    }
    return $passwordErr;
}

function ValidateLoginCredential($userId, $password) {
    //$pdo = getPDO();
    if ($userId == 'admin1')
    {
        $pdo = getAdminPDO();
    }
    else
    {
        $pdo = getPDO();
    }
    $sql = "SELECT * FROM User WHERE UserId = :userId AND Password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $userId, 'password' => $password]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $credentialErr = "";
    } else {
        $credentialErr = "Incorrect user ID and/or Password!";
    }
    return $credentialErr;
}

// data control and validation for AddAlbum.php
function getAccessibilities() {
    $accessibilities = array();

    $pdo = getPDO();
    

    $sql = "SELECT * FROM Accessibility";

    $resultSet = $pdo->query($sql);

    foreach ($resultSet as $row) {
        $accessibility = new Accessibility($row['Accessibility_Code'], $row['Description']);
        $accessibilities[] = $accessibility;
    }

    return $accessibilities;
}

function ValidateTitle($title) {
    $trimmedTitle = trim($title);
    if (empty($trimmedTitle)) {
        $titleErr = "Title cannot be blank.";
    } else {
        $titleErr = "";
    }
    return $titleErr;
}

function addAlbum($title, $userId, $description, $aCode) {
    //$pdo = getPDO();
    if ($userId == 'admin1')
    {
        $pdo = getAdminPDO();
    }
    else
    {
        $pdo = getPDO();
    }

    $sql = "INSERT INTO Album (Title, Description, Owner_Id, Accessibility_Code) VALUES( :title, :description, :userId, :accessibilityCode)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['title' => $title, 'description' => $description, 'userId' => $userId, 'accessibilityCode' => $aCode]);
}

// for MyAlbum.php
function getMyOwnAlbums($userId) {
    $albums = array();

    //$pdo = getPDO();
    if ($userId == 'admin1')
    {
        $pdo = getAdminPDO();
    }
    else
    {
        $pdo = getPDO();
    }

    $sql = "SELECT * FROM Album WHERE Owner_Id = :userId ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $userId]);

    foreach ($stmt as $row) {
        $album = new Album($row['Album_Id'], $row['Title'], $row['Description'], $row['Owner_Id'], $row['Accessibility_Code']);
        $albums[] = $album;
    }

    return $albums;
}

// for AdminPage.php
function getAllAlbums() {
    $albums = array();

    $pdo = getPDO();
    

    $sql = "SELECT * FROM Album";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    foreach ($stmt as $row) {
        $album = new Album($row['Album_Id'], $row['Title'], $row['Description'], $row['Owner_Id'], $row['Accessibility_Code']);
        $albums[] = $album;
    }

    return $albums;
}

function getFriendsAlbums($userId) {
    $albums = array();

    //$pdo = getPDO();
    if ($userId == 'admin1')
    {
        $pdo = getAdminPDO();
    }
    else
    {
        $pdo = getPDO();
    }

    $sql = "SELECT * FROM Album WHERE Owner_Id = :userId And Accessibility_Code ='shared'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $userId]);

    foreach ($stmt as $row) {
        $album = new Album($row['Album_Id'], $row['Title'], $row['Description'], $row['Owner_Id'], $row['Accessibility_Code']);
        $albums[] = $album;
    }

    return $albums;
}

function updateAlbum($aCode, $albumId) {
    $pdo = getPDO();
    

    $sql = "UPDATE Album SET Accessibility_Code = :accessibilityCode WHERE Album_Id = :albumId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['accessibilityCode' => $aCode, 'albumId' => $albumId]);
}

function getPictureNumByAlbumId($ablumId) {
    $pdo = getPDO();
    
    $sql = "SELECT COUNT(*) FROM Picture WHERE Album_Id = :albumId";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['albumId' => $ablumId]);

    $pictureNum = $stmt->fetchColumn();

    return $pictureNum;
}

function deleteAlbum($albumId, $userId) {
    $pictures = getAllPicturessByAlbumId($albumId);
    //$pdo = getPDO();
    if ($userId == 'admin1')
    {
        $pdo = getAdminPDO();
    }
    else
    {
        $pdo = getPDO();
    }
    

    foreach ($pictures as $picture) {
        $pictureId = $picture->getPictureId();

        $sql1 = "DELETE FROM Comment WHERE Picture_Id = :pictureId";
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute(['pictureId' => $pictureId]);

        $sql2 = "DELETE FROM Picture WHERE Picture_Id = :pictureId";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute(['pictureId' => $pictureId]);
    }

    $sql3 = "DELETE FROM Album WHERE Album_Id = :albumId;";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute(['albumId' => $albumId]);
}

function addPicture($albumId, $fileName, $title, $description) {
    $pdo = getPDO();
    
    $sql = "INSERT INTO Picture (Album_Id, File_Name, Title, Description) VALUES( :Album_Id, :File_Name, :Title, :Description)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['Album_Id' => $albumId, 'File_Name' => $fileName, 'Title' => $title, 'Description' => $description]);
}

function getAllPicturessByAlbumId($albumId) {
    $pictures = array();
    $pdo = getPDO();
    
    $sql = "SELECT * FROM Picture WHERE Album_Id = :albumId";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['albumId' => $albumId]);

    foreach ($stmt as $row) {
        $picture = new Picture($row['Picture_Id'], $row['Album_Id'], $row['File_Name'], $row['Title'], $row['Description']);
        $pictures[] = $picture;
    }
    return $pictures;
}

function getPictureByFileName($fileName) {
    $pdo = getPDO();
    
    $sql = "SELECT * FROM Picture WHERE File_Name = :fileName";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['fileName' => $fileName]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $picture = new Picture($row['Picture_Id'], $row['Album_Id'], $row['File_Name'], $row['Title'], $row['Description']);
        return $picture;
    } else {
        return null;
    }
}

function getAllCommentsForSelectedPictureOnMyPicturePage($pictureId) {
    $comments = array();
    $pdo = getPDO();
    
    $sql = $sql = "SELECT Comment_Id, Comment_Text, UserId, Name FROM Comment "
            . "INNER JOIN User ON Comment.Author_Id = User.UserId WHERE Picture_Id = :pictureId"
    ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['pictureId' => $pictureId]);

    foreach ($stmt as $row) {
        $comment = new Comment($row['Comment_Id'], $row['Comment_Text'], $row['UserId'], $row['Name']);
        $comments[] = $comment;
    }
    return $comments;
}

function addCommentOnMyPicturePage($authorId, $pictureId, $commentText) {
    $pdo = getPDO();
    
    $sql = "INSERT INTO Comment (Author_Id, Picture_Id, Comment_Text) VALUES( :authorId, :pictureId, :commentText)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['authorId' => $authorId, 'pictureId' => $pictureId, 'commentText' => $commentText]);
}

function resamplePicture($filePath, $destinationPath, $maxWidth, $maxHeight) {
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath);
    }

    $imageDetails = getimagesize($filePath);

    $originalResource = null;
    if ($imageDetails[2] == IMAGETYPE_JPEG) {
        $originalResource = imagecreatefromjpeg($filePath);
    } elseif ($imageDetails[2] == IMAGETYPE_PNG) {
        $originalResource = imagecreatefrompng($filePath);
    } elseif ($imageDetails[2] == IMAGETYPE_GIF) {
        $originalResource = imagecreatefromgif($filePath);
    }
    $widthRatio = $imageDetails[0] / $maxWidth;
    $heightRatio = $imageDetails[1] / $maxHeight;
    $ratio = max($widthRatio, $heightRatio);

    $newWidth = $imageDetails[0] / $ratio;
    $newHeight = $imageDetails[1] / $ratio;

    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    $success = imagecopyresampled($newImage, $originalResource, 0, 0, 0, 0,
            $newWidth, $newHeight, $imageDetails[0], $imageDetails[1]);

    if (!$success) {
        imagedestroy(newImage);
        imagedestroy(originalResource);
        return "";
    }
    $pathInfo = pathinfo($filePath);
    $newFilePath = $destinationPath . "/" . $pathInfo['filename'];
    if ($imageDetails[2] == IMAGETYPE_JPEG) {
        $newFilePath .= ".jpg";
        $success = imagejpeg($newImage, $newFilePath, 100);
    } elseif ($imageDetails[2] == IMAGETYPE_PNG) {
        $newFilePath .= ".png";
        $success = imagepng($newImage, $newFilePath, 0);
    } elseif ($imageDetails[2] == IMAGETYPE_GIF) {
        $newFilePath .= ".gif";
        $success = imagegif($newImage, $newFilePath);
    }

    imagedestroy($newImage);
    imagedestroy($originalResource);

    if (!$success) {
        return "";
    } else {
        return $newFilePath;
    }
}

//Add Friends

function getFriendshipStatus() {
    $friendshipStatus = array();

    $pdo = getPDO();
    
    $sql = "SELECT * FROM FriendshipStatus";

    $resultSet = $pdo->query($sql);

    foreach ($resultSet as $row) {
        $fs = new FriendshipStatus($row['Status_Code'], $row['Description']);
        $friendshipStatus[] = $fs;
    }

    return $friendshipStatus;
}

function getFriend($friendId) {
    $pdo = getPDO();

    $sql = "SELECT UserId, Name FROM User WHERE UserId = :friendId";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['friendId' => $friendId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return new Friend($row['UserId'], $row['Name']);
    } else {
        return null;
    }
}

function ValidateFriendId($userId, $friendId) {
    //$pdo = getPDO();
    if ($userId == 'admin1')
    {
        $pdo = getAdminPDO();
    }
    else
    {
        $pdo = getPDO();
    }
    $sql1 = "SELECT * FROM User WHERE UserId = :friendId";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute(['friendId' => $friendId]);
    $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);    

    $sql2 = "SELECT * FROM Friendship 
            WHERE (Friend_RequesterId = :userId AND Friend_RequesteeId = :friendId)
               OR (Friend_RequesterId = :friendId AND Friend_RequesteeId = :userId)
               AND Status = 'accepted'";  
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute(['userId' => $userId, 'friendId' => $friendId]);
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);    
    
    if ($userId == $friendId) {
        $friendIdErr = "You cannot send a friend request to yourself!";
    } elseif(!$row1){
        $friendIdErr = "The user ID doesn't exist!";
    } elseif($row2){
        $friendIdErr = "You are already friends!";
    } else {
        $friendIdErr = "";
    }
    return $friendIdErr;
}

function insertAcceptedFriendshipRecordForRequester($requesterId, $userId) {
    //$pdo = getPDO();
    if ($userId == 'admin1')
    {
        $pdo = getAdminPDO();
    }
    else
    {
        $pdo = getPDO();
    }
    $sql = "INSERT INTO Friendship (Friend_RequesterId, Friend_RequesteeId, Status) VALUES( :userId, :requesterId, 'accepted')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['requesterId' => $requesterId, 'userId' => $userId]);
}

function sendFriendRequest($userId, $friendId, $friendName, $status) {
    //$pdo = getPDO();
    if ($userId == 'admin1')
    {
        $pdo = getAdminPDO();
    }
    else
    {
        $pdo = getPDO();
    }
    $sql = "INSERT INTO Friendship (Friend_RequesterId, Friend_RequesteeId, Status) VALUES( :userId, :friendId, :status)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $userId, 'friendId' => $friendId, 'status' => $status]);     

    $sql1 = "SELECT * FROM Friendship 
            WHERE Friend_RequesterId = :friendId AND Friend_RequesteeId = :userId AND Status = 'request'";  
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute(['userId' => $userId, 'friendId' => $friendId]);
    $row1 = $stmt1->fetch(PDO::FETCH_ASSOC); 
    
    if($row1){
        acceptFriendRequest($friendId, $userId);
        acceptFriendRequest($userId, $friendId);
        $successMsg = "You are friends now!";
    } else {
        $successMsg = "Your request has been sent to $friendName (ID: $friendId). Once $friendName accepts your request, you and $friendName will be friends and be able to view each other's albums.";
    }
    return $successMsg;
}

function deleteFriend($friendId, $userId){
    //$pdo=getPDO();
    if ($userId == 'admin1')
    {
        $pdo = getAdminPDO();
    }
    else
    {
        $pdo = getPDO();
    }
    $sql = "DELETE FROM Friendship "
                    . "WHERE ((Friend_RequesterId = :userId AND Friend_RequesteeId= :friendId) "
                    . "  OR (Friend_RequesterId = :friendId AND Friend_RequesteeId= :userId)) "
                    . "    AND Status='accepted'";
    $stmt=$pdo->prepare($sql);
    $stmt->execute(['friendId' => $friendId, 'userId' => $userId]);  
}

function getFriendRequestersToAUser($userId) {
    $friendShipsRequested = array();

    //$pdo = getPDO();
    if ($userId == 'admin1')
    {
        $pdo = getAdminPDO();
    }
    else
    {
        $pdo = getPDO();
    }
    $sql = "SELECT Friend_RequesterId, Friend_RequesteeId, User.Name, Status FROM Friendship as fs
        inner join User on Friend_RequesterId = User.UserId
        WHERE Friend_RequesteeId = :userId AND Status = 'request'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $userId]);
    foreach ($stmt as $row) {
        $friendShipRequested = new Friendship($row['Friend_RequesterId'], $row['Friend_RequesteeId'], $row['Name'], $row['Status']);
        $friendShipsRequested[] = $friendShipRequested;
    }
    return $friendShipsRequested;
}

function acceptFriendRequest($requesterId, $userId) {
    //$pdo = getPDO();
    if ($userId == 'admin1')
    {
        $pdo = getAdminPDO();
    }
    else
    {
        $pdo = getPDO();
    }
    $sql = "UPDATE Friendship SET Status = 'accepted' WHERE Friend_RequesterId = :requesterId AND Friend_RequesteeId = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['requesterId' => $requesterId, 'userId' => $userId]);
}

function denyFriendRequest($requesterId, $userId) {
    //$pdo = getPDO();
    if ($userId == 'admin1')
    {
        $pdo = getAdminPDO();
    }
    else
    {
        $pdo = getPDO();
    }
    $sql = "DELETE FROM Friendship WHERE Friend_RequesterId = :requesterId AND Friend_RequesteeId = :userId AND Status='request'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['requesterId' => $requesterId, 'userId' => $userId]);
}

function getFriendList($userId) {
    $friends = array();
    //$pdo = getPDO();
    if ($userId == 'admin1')
    {
        $pdo = getAdminPDO();
    }
    else
    {
        $pdo = getPDO();
    }
    $sql = "SELECT Friend_RequesterId, Friend_RequesteeId, User.Name, Status FROM Friendship as fs
        inner join User on Friend_RequesterId = User.UserId
        WHERE (Friend_RequesteeId = :userId) AND Status = 'accepted'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $userId]);
    foreach ($stmt as $row) {
        $friend = new Friendship($row['Friend_RequesterId'], $row['Friend_RequesteeId'], $row['Name'], $row['Status']);
        $friends[] = $friend;
    }
    return $friends;
}

function getNumbersOfSharedAlbumsOfFriends ($friendId) {
    $pdo = getPDO();

    $sql = "SELECT COUNT(*) FROM Album WHERE Owner_Id = :friendId AND Accessibility_Code= 'shared'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['friendId' => $friendId]);

    $albumNum = $stmt->fetchColumn();

    return $albumNum;
}