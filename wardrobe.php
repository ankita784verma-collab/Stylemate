<?php

session_start();

require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT 
        clothing_items.*,
        categories.name AS category_name
    FROM clothing_items
    INNER JOIN categories 
        ON clothing_items.category_id = categories.id
    WHERE clothing_items.user_id = ?
    ORDER BY clothing_items.created_at DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Wardrobe - StyleMate</title>

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

            <a href="dashboard.php" class="btn btn-outline-dark me-2">
                Dashboard
            </a>

            <a href="logout.php" class="btn btn-dark">
                Logout
            </a>

        </div>

    </div>

</nav>


<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-5">

        <div>

            <h1 class="fw-bold">
                My Wardrobe
            </h1>

            <p class="text-muted mb-0">
                Everything you own, organized in one place.
            </p>

        </div>

        <a href="add_clothing.php" class="btn btn-dark">
            + Add Clothing
        </a>

    </div>


    <?php if ($result->num_rows == 0): ?>

        <div class="text-center py-5">

            <div style="font-size: 70px;">
                👗
            </div>

            <h3 class="mt-3">
                Your wardrobe is empty
            </h3>

            <p class="text-muted">
                Start by adding your first clothing item.
            </p>

            <a href="add_clothing.php" class="btn btn-dark">
                Add Your First Item
            </a>

        </div>

    <?php else: ?>

        <div class="row g-4">

            <?php while ($item = $result->fetch_assoc()): ?>

                <div class="col-6 col-md-4 col-lg-3">

                    <div class="wardrobe-card">

                        <div class="wardrobe-image">

                            <img
                                src="<?php echo htmlspecialchars($item["image"]); ?>"
                                alt="<?php echo htmlspecialchars($item["name"]); ?>"
                            >

                        </div>


                        <div class="p-3">

                            <span class="small text-muted">
                                <?php echo htmlspecialchars($item["category_name"]); ?>
                            </span>

                            <h5 class="mt-1 mb-2">
                                <?php echo htmlspecialchars($item["name"]); ?>
                            </h5>


                            <?php if (!empty($item["color"])): ?>

                                <p class="small mb-1">
                                    <strong>Color:</strong>
                                    <?php echo htmlspecialchars($item["color"]); ?>
                                </p>

                            <?php endif; ?>


                            <?php if (!empty($item["style"])): ?>

                                <p class="small mb-1">
                                    <strong>Style:</strong>
                                    <?php echo htmlspecialchars($item["style"]); ?>
                                </p>

                            <?php endif; ?>


                            <?php if (!empty($item["occasion"])): ?>

                                <p class="small mb-3">
                                    <strong>For:</strong>
                                    <?php echo htmlspecialchars($item["occasion"]); ?>
                                </p>

                            <?php endif; ?>


                            <a
                                href="delete_clothing.php?id=<?php echo $item["id"]; ?>"
                                class="btn btn-sm btn-outline-danger w-100"
                                onclick="return confirm('Delete this clothing item?');"
                            >
                                Delete
                            </a>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    <?php endif; ?>

</div>

</body>
</html>