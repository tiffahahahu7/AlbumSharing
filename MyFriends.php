<?php
include_once 'EntityClassLib.php';
include_once('Functions.php');
session_start();
//check whether the user is logged in
if (isset($_SESSION["user"])) {
    $user = $_SESSION["user"];
    if ($user->getIsAdmin())
    {
        header("Location: Admin.php");
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

$friendShipsRequested = getFriendRequestersToAUser($user->getUserId());
$friends = getFriendList($user->getUserId());

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);
    if (isset($_POST["redirectBtn"])) {
        $_SESSION["friendId"] = $redirectedfriendId;
        header("Location: FriendPictures.php");
        exit();
    }
    if (isset($_POST["acceptBtn"])) {
        $selectedRequesterIdList = $_POST['selectedRequesterIdList'];
        foreach ($selectedRequesterIdList as $requesterId) {
            acceptFriendRequest($requesterId, $user->getUserId());
            insertAcceptedFriendshipRecordForRequester($requesterId, $user->getUserId());
            $friends = getFriendList($user->getUserId());
            $friendShipsRequested = getFriendRequestersToAUser($user->getUserId());
        }
    }
    if (isset($_POST["denyBtn"])) {
        $selectedRequesterIdList = $_POST['selectedRequesterIdList'];
        foreach ($selectedRequesterIdList as $requesterId) {
            denyFriendRequest($requesterId, $user->getUserId());
            $friendShipsRequested = getFriendRequestersToAUser($user->getUserId());
        }
    }
    if (isset($_POST["defriendBtn"])) {
        $selectedDefriendList = $_POST["selectedFriendList"];
        foreach ($selectedDefriendList as $defriendID) {
            deleteFriend($defriendID, $user->getUserId());
            $friends = getFriendList($user->getUserId());
        }
    }
}


include("./common/header.php");
?>
<div class="container">
    <br>
    <h1 class="center">My Friends</h1>
    <br>
    <p>Welcome <strong><?php echo $user->getName(); ?></strong>! (not you? change user <a href="Logout.php">here</a>)</p>
    <br>
    <div class="row">
        <div class="col-xs-6">
            <span class="text-left text-warning"><strong>Friends:</strong></span>
        </div>
        <div class="col-xs-6">
            <span class="pull-right"><a href="AddFriend.php">Add Friends</a></span>
        </div>
    </div>
    <form id="friendForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Shared Albums</th>
                    <th>Defriend</th>
                </tr>
            </thead>    
            <tbody>
                <?php
                global $friends;
                if (!empty($friends)) {
                    foreach ($friends as $fd) {
                        $friendName = $fd->getRequesterName();
                        $friendId = $fd->getRequesterId();
                        $albumNum = getNumbersOfSharedAlbumsOfFriends($friendId);
                        echo '<tr>';
                        if ($albumNum > 0) {
                            echo "<td><a href=\"javascript:void(0);\" onclick=\"redirectPictures('$friendId')\">$friendName</a></td>";
                        } else {
                            echo "<td>$friendName</td>";
                        }
                        echo "<td>$albumNum</td>";
                        echo "<td><input type='checkbox' name='selectedFriendList[]' value=$friendId></td>";
                        echo '</tr>';
                    }
                }
                ?>
            <input type="hidden" name="redirectedfriendId" id="redirectedfriendId">
            </tbody>        
        </table>

        <button type="submit" name="redirectBtn" id="redirectBtn" hidden>Redirect to Pictures</button> 
        <button type="submit" name="defriendBtn" id="defriendBtn" class="btn btn-primary">Defriend Selected</button> 
        <br>
        <br>
        <br>
        <div class="row">
            <div class="col-xs-6">
                <span class="text-left text-warning"><strong>Friend Requests:</strong></span>
            </div>
        </div>    
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Accept or Deny</th>
                </tr>
            </thead>    
            <tbody>

                <?php
                global $friendShipsRequested;
                if (!empty($friendShipsRequested)) {
                    foreach ($friendShipsRequested as $friendship) {
                        $requesterName = $friendship->getRequesterName();
                        $requesterId = $friendship->getRequesterId();
                        $request = $friendship->getStatus();
                        echo '<tr>';
                        echo "<td>$requesterName</td>";
                        echo "<td><input type='checkbox' name='selectedRequesterIdList[]' value=$requesterId></td>";
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>        
        </table>     
        <button type="submit" name="acceptBtn" class="btn btn-primary">Accept Selected</button> 
        <button type="submit" name="denyBtn" id="denyBtn" class="btn btn-primary">Deny Selected</button> 
    </form>
    <br>
</div>
<?php include('./common/footer.php'); ?>

<script type="text/javascript">
    const redirectedfriendId = document.getElementById("redirectedfriendId");
    const redirectBtn = document.getElementById("redirectBtn");
    const defriendBtn = document.getElementById("defriendBtn");
    const denyBtn = document.getElementById("denyBtn");
    function redirectPictures(friendId) {
        console.log(friendId);
        redirectedfriendId.value = friendId;
        redirectBtn.click();
        return false;
    }

    defriendBtn.addEventListener('click', function (e) {
        if (confirm("The selected friends will be defriended!") === true) {
            document.getElementById("friendForm").submit();
        } else {
            e.preventDefault();
        }
    })
    denyBtn.addEventListener('click', function (e) {
        if (confirm("The selected friend requests will be denied!") === true) {
            document.getElementById("friendForm").submit();
        } else {
            e.preventDefault();
        }
    })
</script>