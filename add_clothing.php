<<<<<<< HEAD
<?php

session_start();

require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$message = "";
$message_type = "danger";


$categories = $conn->query("
    SELECT id, name 
    FROM categories 
    ORDER BY name ASC
");


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $category_id = intval($_POST["category_id"]);

    $color = trim($_POST["color"]);
    $secondary_color = trim($_POST["secondary_color"]);
    $pattern = trim($_POST["pattern"]);
    $style = trim($_POST["style"]);
    $season = trim($_POST["season"]);
    $occasion = trim($_POST["occasion"]);


    if ($name == "" || $category_id <= 0) {

        $message = "Please enter the clothing name and category.";

    } elseif (!isset($_FILES["image"]) || $_FILES["image"]["error"] != 0) {

        $message = "Please upload a clothing image.";

    } else {

        $file = $_FILES["image"];

        $allowed_types = [
            "image/jpeg",
            "image/png",
            "image/webp"
        ];

        $max_size = 5 * 1024 * 1024;


        if (!in_array($file["type"], $allowed_types)) {

            $message = "Only JPG, PNG and WEBP images are allowed.";

        } elseif ($file["size"] > $max_size) {

            $message = "Image must be smaller than 5 MB.";

        } else {

            $image_info = getimagesize($file["tmp_name"]);

            if ($image_info === false) {

                $message = "Invalid image file.";

            } else {

                $extension = strtolower(
                    pathinfo($file["name"], PATHINFO_EXTENSION)
                );

                $new_filename =
                    uniqid("clothing_", true) . "." . $extension;

                $upload_directory = "uploads/";

                $image_path =
                    $upload_directory . $new_filename;


                if (move_uploaded_file(
                    $file["tmp_name"],
                    $image_path
                )) {

                    $stmt = $conn->prepare("
                        INSERT INTO clothing_items
                        (
                            user_id,
                            category_id,
                            name,
                            image,
                            color,
                            secondary_color,
                            pattern,
                            style,
                            season,
                            occasion
                        )
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");

                    $stmt->bind_param(
                        "iissssssss",
                        $user_id,
                        $category_id,
                        $name,
                        $image_path,
                        $color,
                        $secondary_color,
                        $pattern,
                        $style,
                        $season,
                        $occasion
                    );


                    if ($stmt->execute()) {

                        header("Location: wardrobe.php");
                        exit();

                    } else {

                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }

                        $message = "Could not save clothing item.";

                    }

                } else {

                    $message = "Could not upload image.";

                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Add Clothing - StyleMate</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="css/style.css">

</head>

<body>


<nav class="navbar navbar-expand-lg bg-white shadow-sm">

    <div class="container">

        <a class="navbar-brand fw-bold" href="dashboard.php">
            StyleMate
        </a>

        <div>

            <a
                href="wardrobe.php"
                class="btn btn-outline-dark me-2"
            >
                My Wardrobe
            </a>

            <a
                href="logout.php"
                class="btn btn-dark"
            >
                Logout
            </a>

        </div>

    </div>

</nav>


<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="bg-white rounded-4 shadow-sm p-4 p-md-5">

                <h2 class="fw-bold">
                    Add Clothing
                </h2>

                <p class="text-muted mb-4">
                    Add an item from your real wardrobe.
                </p>


                <?php if ($message != ""): ?>

                    <div class="alert alert-<?php echo $message_type; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>

                <?php endif; ?>


                <form
                    method="POST"
                    enctype="multipart/form-data"
                >


                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Clothing Photo
                        </label>

                        <input
                            type="file"
                            name="image"
                            class="form-control"
                            accept="image/jpeg,image/png,image/webp"
                            required
                        >

                        <small class="text-muted">
                            JPG, PNG or WEBP — maximum 5 MB
                        </small>

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Clothing Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Example: White Oversized Top"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Category
                        </label>

                        <select
                            name="category_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select category
                            </option>

                            <?php while ($category = $categories->fetch_assoc()): ?>

                                <option
                                    value="<?php echo $category["id"]; ?>"
                                >
                                    <?php echo htmlspecialchars($category["name"]); ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Main Color
                            </label>

                            <input
                                type="text"
                                name="color"
                                class="form-control"
                                placeholder="White"
                            >

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Secondary Color
                            </label>

                            <input
                                type="text"
                                name="secondary_color"
                                class="form-control"
                                placeholder="Optional"
                            >

                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Pattern
                            </label>

                            <select
                                name="pattern"
                                class="form-select"
                            >

                                <option value="">
                                    Select pattern
                                </option>

                                <option value="Plain">
                                    Plain
                                </option>

                                <option value="Printed">
                                    Printed
                                </option>

                                <option value="Striped">
                                    Striped
                                </option>

                                <option value="Checked">
                                    Checked
                                </option>

                                <option value="Floral">
                                    Floral
                                </option>

                                <option value="Other">
                                    Other
                                </option>

                            </select>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Style
                            </label>

                            <select
                                name="style"
                                class="form-select"
                            >

                                <option value="">
                                    Select style
                                </option>

                                <option value="Casual">
                                    Casual
                                </option>

                                <option value="Formal">
                                    Formal
                                </option>

                                <option value="Party">
                                    Party
                                </option>

                                <option value="Traditional">
                                    Traditional
                                </option>

                                <option value="Sporty">
                                    Sporty
                                </option>

                                <option value="Streetwear">
                                    Streetwear
                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Season
                            </label>

                            <select
                                name="season"
                                class="form-select"
                            >

                                <option value="">
                                    Select season
                                </option>

                                <option value="Summer">
                                    Summer
                                </option>

                                <option value="Winter">
                                    Winter
                                </option>

                                <option value="Spring">
                                    Spring
                                </option>

                                <option value="Autumn">
                                    Autumn
                                </option>

                                <option value="All Season">
                                    All Season
                                </option>

                            </select>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Suitable For
                            </label>

                            <select
                                name="occasion"
                                class="form-select"
                            >

                                <option value="">
                                    Select occasion
                                </option>

                                <option value="College">
                                    College
                                </option>

                                <option value="Work">
                                    Work
                                </option>

                                <option value="Casual">
                                    Casual Outing
                                </option>

                                <option value="Party">
                                    Party
                                </option>

                                <option value="Date">
                                    Date
                                </option>

                                <option value="Travel">
                                    Travel
                                </option>

                                <option value="Traditional Event">
                                    Traditional Event
                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="d-flex gap-2 mt-4">

                        <a
                            href="wardrobe.php"
                            class="btn btn-outline-dark"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="btn btn-dark px-4"
                        >
                            Save to Wardrobe
                        </button>

                    </div>


                </form>

            </div>

        </div>

    </div>

</div>


</body>
=======
<?php

session_start();

require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$message = "";
$message_type = "danger";


$categories = $conn->query("
    SELECT id, name 
    FROM categories 
    ORDER BY name ASC
");


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $category_id = intval($_POST["category_id"]);

    $color = trim($_POST["color"]);
    $secondary_color = trim($_POST["secondary_color"]);
    $pattern = trim($_POST["pattern"]);
    $style = trim($_POST["style"]);
    $season = trim($_POST["season"]);
    $occasion = trim($_POST["occasion"]);


    if ($name == "" || $category_id <= 0) {

        $message = "Please enter the clothing name and category.";

    } elseif (!isset($_FILES["image"]) || $_FILES["image"]["error"] != 0) {

        $message = "Please upload a clothing image.";

    } else {

        $file = $_FILES["image"];

        $allowed_types = [
            "image/jpeg",
            "image/png",
            "image/webp"
        ];

        $max_size = 5 * 1024 * 1024;


        if (!in_array($file["type"], $allowed_types)) {

            $message = "Only JPG, PNG and WEBP images are allowed.";

        } elseif ($file["size"] > $max_size) {

            $message = "Image must be smaller than 5 MB.";

        } else {

            $image_info = getimagesize($file["tmp_name"]);

            if ($image_info === false) {

                $message = "Invalid image file.";

            } else {

                $extension = strtolower(
                    pathinfo($file["name"], PATHINFO_EXTENSION)
                );

                $new_filename =
                    uniqid("clothing_", true) . "." . $extension;

                $upload_directory = "uploads/";

                $image_path =
                    $upload_directory . $new_filename;


                if (move_uploaded_file(
                    $file["tmp_name"],
                    $image_path
                )) {

                    $stmt = $conn->prepare("
                        INSERT INTO clothing_items
                        (
                            user_id,
                            category_id,
                            name,
                            image,
                            color,
                            secondary_color,
                            pattern,
                            style,
                            season,
                            occasion
                        )
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");

                    $stmt->bind_param(
                        "iissssssss",
                        $user_id,
                        $category_id,
                        $name,
                        $image_path,
                        $color,
                        $secondary_color,
                        $pattern,
                        $style,
                        $season,
                        $occasion
                    );


                    if ($stmt->execute()) {

                        header("Location: wardrobe.php");
                        exit();

                    } else {

                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }

                        $message = "Could not save clothing item.";

                    }

                } else {

                    $message = "Could not upload image.";

                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Add Clothing - StyleMate</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="css/style.css">

</head>

<body>


<nav class="navbar navbar-expand-lg bg-white shadow-sm">

    <div class="container">

        <a class="navbar-brand fw-bold" href="dashboard.php">
            StyleMate
        </a>

        <div>

            <a
                href="wardrobe.php"
                class="btn btn-outline-dark me-2"
            >
                My Wardrobe
            </a>

            <a
                href="logout.php"
                class="btn btn-dark"
            >
                Logout
            </a>

        </div>

    </div>

</nav>


<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="bg-white rounded-4 shadow-sm p-4 p-md-5">

                <h2 class="fw-bold">
                    Add Clothing
                </h2>

                <p class="text-muted mb-4">
                    Add an item from your real wardrobe.
                </p>


                <?php if ($message != ""): ?>

                    <div class="alert alert-<?php echo $message_type; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>

                <?php endif; ?>


                <form
                    method="POST"
                    enctype="multipart/form-data"
                >


                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Clothing Photo
                        </label>

                        <input
                            type="file"
                            name="image"
                            class="form-control"
                            accept="image/jpeg,image/png,image/webp"
                            required
                        >

                        <small class="text-muted">
                            JPG, PNG or WEBP — maximum 5 MB
                        </small>

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Clothing Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Example: White Oversized Top"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Category
                        </label>

                        <select
                            name="category_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select category
                            </option>

                            <?php while ($category = $categories->fetch_assoc()): ?>

                                <option
                                    value="<?php echo $category["id"]; ?>"
                                >
                                    <?php echo htmlspecialchars($category["name"]); ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Main Color
                            </label>

                            <input
                                type="text"
                                name="color"
                                class="form-control"
                                placeholder="White"
                            >

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Secondary Color
                            </label>

                            <input
                                type="text"
                                name="secondary_color"
                                class="form-control"
                                placeholder="Optional"
                            >

                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Pattern
                            </label>

                            <select
                                name="pattern"
                                class="form-select"
                            >

                                <option value="">
                                    Select pattern
                                </option>

                                <option value="Plain">
                                    Plain
                                </option>

                                <option value="Printed">
                                    Printed
                                </option>

                                <option value="Striped">
                                    Striped
                                </option>

                                <option value="Checked">
                                    Checked
                                </option>

                                <option value="Floral">
                                    Floral
                                </option>

                                <option value="Other">
                                    Other
                                </option>

                            </select>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Style
                            </label>

                            <select
                                name="style"
                                class="form-select"
                            >

                                <option value="">
                                    Select style
                                </option>

                                <option value="Casual">
                                    Casual
                                </option>

                                <option value="Formal">
                                    Formal
                                </option>

                                <option value="Party">
                                    Party
                                </option>

                                <option value="Traditional">
                                    Traditional
                                </option>

                                <option value="Sporty">
                                    Sporty
                                </option>

                                <option value="Streetwear">
                                    Streetwear
                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Season
                            </label>

                            <select
                                name="season"
                                class="form-select"
                            >

                                <option value="">
                                    Select season
                                </option>

                                <option value="Summer">
                                    Summer
                                </option>

                                <option value="Winter">
                                    Winter
                                </option>

                                <option value="Spring">
                                    Spring
                                </option>

                                <option value="Autumn">
                                    Autumn
                                </option>

                                <option value="All Season">
                                    All Season
                                </option>

                            </select>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Suitable For
                            </label>

                            <select
                                name="occasion"
                                class="form-select"
                            >

                                <option value="">
                                    Select occasion
                                </option>

                                <option value="College">
                                    College
                                </option>

                                <option value="Work">
                                    Work
                                </option>

                                <option value="Casual">
                                    Casual Outing
                                </option>

                                <option value="Party">
                                    Party
                                </option>

                                <option value="Date">
                                    Date
                                </option>

                                <option value="Travel">
                                    Travel
                                </option>

                                <option value="Traditional Event">
                                    Traditional Event
                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="d-flex gap-2 mt-4">

                        <a
                            href="wardrobe.php"
                            class="btn btn-outline-dark"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="btn btn-dark px-4"
                        >
                            Save to Wardrobe
                        </button>

                    </div>


                </form>

            </div>

        </div>

    </div>

</div>


</body>
>>>>>>> 44c54392920554fc489fa0b5fa2377643cfac17d
</html>