<<<<<<< HEAD
<?php

session_start();

require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$item_id = isset($_GET["id"])
    ? intval($_GET["id"])
    : 0;


if ($item_id > 0) {

    $stmt = $conn->prepare("
        SELECT image
        FROM clothing_items
        WHERE id = ? AND user_id = ?
    ");

    $stmt->bind_param(
        "ii",
        $item_id,
        $user_id
    );

    $stmt->execute();

    $result = $stmt->get_result();


    if ($result->num_rows == 1) {

        $item = $result->fetch_assoc();

        $image = $item["image"];


        $delete = $conn->prepare("
            DELETE FROM clothing_items
            WHERE id = ? AND user_id = ?
        ");

        $delete->bind_param(
            "ii",
            $item_id,
            $user_id
        );

        $delete->execute();


        if ($delete->affected_rows > 0) {

            if (file_exists($image)) {
                unlink($image);
            }

        }
    }
}


header("Location: wardrobe.php");
exit();

=======
<?php

session_start();

require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$item_id = isset($_GET["id"])
    ? intval($_GET["id"])
    : 0;


if ($item_id > 0) {

    $stmt = $conn->prepare("
        SELECT image
        FROM clothing_items
        WHERE id = ? AND user_id = ?
    ");

    $stmt->bind_param(
        "ii",
        $item_id,
        $user_id
    );

    $stmt->execute();

    $result = $stmt->get_result();


    if ($result->num_rows == 1) {

        $item = $result->fetch_assoc();

        $image = $item["image"];


        $delete = $conn->prepare("
            DELETE FROM clothing_items
            WHERE id = ? AND user_id = ?
        ");

        $delete->bind_param(
            "ii",
            $item_id,
            $user_id
        );

        $delete->execute();


        if ($delete->affected_rows > 0) {

            if (file_exists($image)) {
                unlink($image);
            }

        }
    }
}


header("Location: wardrobe.php");
exit();

>>>>>>> 44c54392920554fc489fa0b5fa2377643cfac17d
?>