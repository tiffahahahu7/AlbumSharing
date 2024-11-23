<?php

class User {

    private $userId;
    private $name;
    private $phone;
    private $messages;
    private $isAdmin;

    public function __construct($userId, $name, $phone, $isAdmin) {
        $this->userId = $userId;
        $this->name = $name;
        $this->phone = $phone;
        $this->isAdmin = $isAdmin;

        $this->messages = array();
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getName() {
        return $this->name;
    }

    public function getPhone() {
        return $this->phone;
    }
    
    public function getIsAdmin() {
        return $this->isAdmin;
    }
}

class Accessibility {

    private $code;
    private $description;

    public function __construct($code, $description) {
        $this->code = $code;
        $this->description = $description;
    }

    public function getCode() {
        return $this->code;
    }

    public function getDescription() {
        return $this->description;
    }
}

class Album {

    private $albumId;
    private $title;
    private $description;
    private $userId;
    private $accessibilityCode;

    public function __construct($albumId, $title, $description, $userId, $accessibilityCode) {
        $this->albumId = $albumId;
        $this->title = $title;
        $this->description = $description;
        $this->userId = $userId;
        $this->accessibilityCode = $accessibilityCode;
    }

    public function getAlbumId() {
        return $this->albumId;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getAccessibilityCode() {
        return $this->accessibilityCode;
    }
}

class Picture {

    private $pictureId;
    private $albumId;
    private $fileName;
    private $title;
    private $description;

    public function __construct($pictureId, $albumId, $fileName, $title, $description) {
        $this->pictureId = $pictureId;
        $this->albumId = $albumId;
        $this->fileName = $fileName;
        $this->title = $title;
        $this->description = $description;
    }

    public function getPictureId() {
        return $this->pictureId;
    }

    public function getAlbumId() {
        return $this->albumId;
    }

    public function getFileName() {
        return $this->fileName;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }
}

class Comment {

    private $commentId;
    private $commentText;
    private $userId;
    private $name;

    public function __construct($commentId, $commentText, $userId, $name) {
        $this->commentId = $commentId;
        $this->commentText = $commentText;
        $this->userId = $userId;
        $this->name = $name;
    }

    public function getCommentId() {
        return $this->commentId;
    }

    public function getCommentText() {
        return $this->commentText;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getUserName() {
        return $this->name;
    }
}

class Friend {

    private $friendId;
    private $friendName;

    public function __construct($friendId, $friendName) {
        $this->friendId = $friendId;
        $this->friendName = $friendName;
    }

    public function getFriendId() {
        return $this->friendId;
    }

    public function getFriendName() {
        return $this->friendName;
    }

}

class Friendship {

    private $requesterId;
    private $requesteeId;
    private $requesterName;
    private $status;

    public function __construct($requesterId, $requesteeId, $requesterName, $status) {
        $this->requesterId = $requesterId;
        $this->requesteeId = $requesteeId;
        $this->requesterName = $requesterName;
        $this->status = $status;
    }

    public function getRequesterId() {
        return $this->requesterId;
    }

    public function getRequesteeId() {
        return $this->requesteeId;
    }
    
    public function getRequesterName() {
        return $this->requesterName;
    }

    public function getStatus() {
        return $this->status;
    }    
}

class FriendshipStatus {

    private $code;
    private $description;

    public function __construct($code, $description) {
        $this->code = $code;
        $this->description = $description;
    }

    public function getCode() {
        return $this->code;
    }

    public function getDescription() {
        return $this->description;
    }
}
