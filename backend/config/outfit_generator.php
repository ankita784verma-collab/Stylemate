<?php

session_start();

require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$occasion = $_GET["occasion"] ?? "";
$season = $_GET["season"] ?? "";

$items = [];

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

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}


/* -----------------------------------------
   HELPER FUNCTIONS
----------------------------------------- */

function normalizeColor($color)
{
    return strtolower(trim($color));
}


function colorsMatch($color1, $color2)
{
    $color1 = normalizeColor($color1);
    $color2 = normalizeColor($color2);

    if ($color1 == "" || $color2 == "") {
        return 0;
    }

    if ($color1 == $color2) {
        return 10;
    }

    $neutralColors = [
        "black",
        "white",
        "grey",
        "gray",
        "beige",
        "cream",
        "brown",
        "navy"
    ];

    if (
        in_array($color1, $neutralColors) ||
        in_array($color2, $neutralColors)
    ) {
        return 8;
    }

    $compatible = [
        "blue" => ["white", "black", "beige", "grey", "brown"],
        "red" => ["black", "white", "blue", "beige"],
        "green" => ["white", "black", "beige", "brown"],
        "pink" => ["white", "black", "grey", "blue"],
        "yellow" => ["white", "black", "blue", "brown"],
        "purple" => ["white", "black", "grey"],
        "orange" => ["white", "black", "blue"],
    ];

    if (
        isset($compatible[$color1]) &&
        in_array($color2, $compatible[$color1])
    ) {
        return 9;
    }

    if (
        isset($compatible[$color2]) &&
        in_array($color1, $compatible[$color2])
    ) {
        return 9;
    }

    return 5;
}


function styleMatch($style1, $style2)
{
    $style1 = strtolower(trim($style1));
    $style2 = strtolower(trim($style2));

    if ($style1 == "" || $style2 == "") {
        return 0;
    }

    if ($style1 == $style2) {
        return 10;
    }

    $compatibleStyles = [
        "casual" => ["streetwear", "sporty"],
        "streetwear" => ["casual", "sporty"],
        "sporty" => ["casual", "streetwear"],
        "formal" => ["casual"],
        "party" => ["casual"],
        "traditional" => ["casual"]
    ];

    if (
        isset($compatibleStyles[$style1]) &&
        in_array($style2, $compatibleStyles[$style1])
    ) {
        return 7;
    }

    return 4;
}


function getScore($item1, $item2, $occasion, $season)
{
    $score = 0;

    /* Color */
    $score += colorsMatch(
        $item1["color"],
        $item2["color"]
    );

    /* Style */
    $score += styleMatch(
        $item1["style"],
        $item2["style"]
    );

    /* Occasion */
    if (
        $occasion != "" &&
        (
            strtolower($item1["occasion"]) == strtolower($occasion) ||
            strtolower($item2["occasion"]) == strtolower($occasion)
        )
    ) {
        $score += 10;
    }

    /* Season */
    if (
        $season != "" &&
        (
            strtolower($item1["season"]) == strtolower($season) ||
            strtolower($item2["season"]) == strtolower($season) ||
            strtolower($item1["season"]) == "all season" ||
            strtolower($item2["season"]) == "all season"
        )
    ) {
        $score += 10;
    }

    return min($score, 40);
}


/* -----------------------------------------
   CREATE OUTFIT
----------------------------------------- */

$recommendation = null;

if ($occasion != "" || $season != "") {

    $tops = [];
    $bottoms = [];
    $shoes = [];
    $jackets = [];
    $dresses = [];

    foreach ($items as $item) {

        $category = strtolower($item["category_name"]);

        if ($category == "tops") {
            $tops[] = $item;
        }

        elseif (
            $category == "jeans" ||
            $category == "pants" ||
            $category == "skirts"
        ) {
            $bottoms[] = $item;
        }

        elseif ($category == "shoes") {
            $shoes[] = $item;
        }

        elseif ($category == "jackets") {
            $jackets[] = $item;
        }

        elseif ($category == "dresses") {
            $dresses[] = $item;
        }
    }


    $bestScore = -1;


    /* TOP + BOTTOM */

    foreach ($tops as $top) {

        foreach ($bottoms as $bottom) {

            $score = getScore(
                $top,
                $bottom,
                $occasion,
                $season
            );

            if ($score > $bestScore) {

                $bestScore = $score;

                $recommendation = [
                    "type" => "top_bottom",
                    "top" => $top,
                    "bottom" => $bottom,
                    "shoes" => null,
                    "jacket" => null,
                    "score" => $score
                ];
            }
        }
    }


    /* ADD SHOES */

    if ($recommendation != null && count($shoes) > 0) {

        $bestShoeScore = -1;
        $bestShoe = null;

        foreach ($shoes as $shoe) {

            $score = colorsMatch(
                $recommendation["top"]["color"],
                $shoe["color"]
            );

            if ($score > $bestShoeScore) {

                $bestShoeScore = $score;
                $bestShoe = $shoe;
            }
        }

        $recommendation["shoes"] = $bestShoe;
    }


    /* ADD JACKET */

    if ($recommendation != null && count($jackets) > 0) {

        $bestJacketScore = -1;
        $bestJacket = null;

        foreach ($jackets as $jacket) {

            $score = colorsMatch(
                $recommendation["top"]["color"],
                $jacket["color"]
            );

            if ($score > $bestJacketScore) {

                $bestJacketScore = $score;
                $bestJacket = $jacket;
            }
        }

        $recommendation["jacket"] = $bestJacket;
    }


    /* Convert basic score to percentage */

    if ($recommendation != null) {

        $percentage = ($recommendation["score"] / 40) * 100;

        $recommendation["percentage"] =
            min(98, max(50, round($percentage)));
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

    <title>Outfit Ideas - StyleMate</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>

<body>


<nav class="navbar navbar-expand-lg bg-white shadow-sm">

    <div class="container">

        <a
            class="navbar-brand fw-bold"
            href="dashboard.php"
        >
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


    <div class="mb-5">

        <h1 class="fw-bold">
            ✨ Outfit Ideas
        </h1>

        <p class="text-muted">
            Let StyleMate create an outfit from your wardrobe.
        </p>

    </div>


    <!-- FILTER -->

    <div class="bg-white shadow-sm rounded-4 p-4 mb-5">

        <form method="GET">

            <div class="row g-3 align-items-end">


                <div class="col-md-5">

                    <label class="form-label fw-semibold">
                        Occasion
                    </label>

                    <select
                        name="occasion"
                        class="form-select"
                    >

                        <option value="">
                            Any Occasion
                        </option>

                        <option
                            value="College"
                            <?php if ($occasion == "College") echo "selected"; ?>
                        >
                            College
                        </option>

                        <option
                            value="Work"
                            <?php if ($occasion == "Work") echo "selected"; ?>
                        >
                            Work
                        </option>

                        <option
                            value="Casual"
                            <?php if ($occasion == "Casual") echo "selected"; ?>
                        >
                            Casual Outing
                        </option>

                        <option
                            value="Party"
                            <?php if ($occasion == "Party") echo "selected"; ?>
                        >
                            Party
                        </option>

                        <option
                            value="Date"
                            <?php if ($occasion == "Date") echo "selected"; ?>
                        >
                            Date
                        </option>

                        <option
                            value="Travel"
                            <?php if ($occasion == "Travel") echo "selected"; ?>
                        >
                            Travel
                        </option>

                    </select>

                </div>


                <div class="col-md-5">

                    <label class="form-label fw-semibold">
                        Season
                    </label>

                    <select
                        name="season"
                        class="form-select"
                    >

                        <option value="">
                            Any Season
                        </option>

                        <option
                            value="Summer"
                            <?php if ($season == "Summer") echo "selected"; ?>
                        >
                            Summer
                        </option>

                        <option
                            value="Winter"
                            <?php if ($season == "Winter") echo "selected"; ?>
                        >
                            Winter
                        </option>

                        <option
                            value="Spring"
                            <?php if ($season == "Spring") echo "selected"; ?>
                        >
                            Spring
                        </option>

                        <option
                            value="Autumn"
                            <?php if ($season == "Autumn") echo "selected"; ?>
                        >
                            Autumn
                        </option>

                    </select>

                </div>


                <div class="col-md-2">

                    <button
                        type="submit"
                        class="btn btn-dark w-100"
                    >
                        Generate
                    </button>

                </div>


            </div>

        </form>

    </div>


    <?php if ($recommendation != null): ?>


        <div class="mb-4">

            <h2 class="fw-bold">
                Your Recommended Outfit
            </h2>

            <p class="text-muted">
                Based on your wardrobe and selected preferences.
            </p>

        </div>


        <div class="row g-4">


            <!-- TOP -->

            <div class="col-md-3">

                <div class="wardrobe-card">

                    <div class="wardrobe-image">

                        <img
                            src="<?php echo htmlspecialchars($recommendation["top"]["image"]); ?>"
                            alt="Top"
                        >

                    </div>

                    <div class="p-3">

                        <small class="text-muted">
                            TOP
                        </small>

                        <h5>
                            <?php echo htmlspecialchars($recommendation["top"]["name"]); ?>
                        </h5>

                    </div>

                </div>

            </div>


            <!-- BOTTOM -->

            <div class="col-md-3">

                <div class="wardrobe-card">

                    <div class="wardrobe-image">

                        <img
                            src="<?php echo htmlspecialchars($recommendation["bottom"]["image"]); ?>"
                            alt="Bottom"
                        >

                    </div>

                    <div class="p-3">

                        <small class="text-muted">
                            BOTTOM
                        </small>

                        <h5>
                            <?php echo htmlspecialchars($recommendation["bottom"]["name"]); ?>
                        </h5>

                    </div>

                </div>

            </div>


            <!-- SHOES -->

            <?php if ($recommendation["shoes"] != null): ?>

                <div class="col-md-3">

                    <div class="wardrobe-card">

                        <div class="wardrobe-image">

                            <img
                                src="<?php echo htmlspecialchars($recommendation["shoes"]["image"]); ?>"
                                alt="Shoes"
                            >

                        </div>

                        <div class="p-3">

                            <small class="text-muted">
                                SHOES
                            </small>

                            <h5>
                                <?php echo htmlspecialchars($recommendation["shoes"]["name"]); ?>
                            </h5>

                        </div>

                    </div>

                </div>

            <?php endif; ?>


            <!-- JACKET -->

            <?php if ($recommendation["jacket"] != null): ?>

                <div class="col-md-3">

                    <div class="wardrobe-card">

                        <div class="wardrobe-image">

                            <img
                                src="<?php echo htmlspecialchars($recommendation["jacket"]["image"]); ?>"
                                alt="Jacket"
                            >

                        </div>

                        <div class="p-3">

                            <small class="text-muted">
                                JACKET
                            </small>

                            <h5>
                                <?php echo htmlspecialchars($recommendation["jacket"]["name"]); ?>
                            </h5>

                        </div>

                    </div>

                </div>

            <?php endif; ?>


        </div>


        <!-- SCORE -->

        <div class="bg-white shadow-sm rounded-4 p-4 mt-4 text-center">

            <h3 class="fw-bold">
                <?php echo $recommendation["percentage"]; ?>% Match
            </h3>

            <p class="text-muted mb-0">
                StyleMate selected this combination based on
                color, style, occasion and season.
            </p>

        </div>


    <?php else: ?>


        <div class="text-center py-5">

            <div style="font-size: 70px;">
                ✨
            </div>

            <h3 class="mt-3">
                Ready to create an outfit?
            </h3>

            <p class="text-muted">
                Select an occasion or season above and click Generate.
            </p>

            <a
                href="wardrobe.php"
                class="btn btn-outline-dark"
            >
                Manage My Wardrobe
            </a>

        </div>


    <?php endif; ?>


</div>


</body>

</html>