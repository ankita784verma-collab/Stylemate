<?php

session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];


/*
|--------------------------------------------------------------------------
| FIND YOUR WARDROBE TABLE AUTOMATICALLY
|--------------------------------------------------------------------------
| This checks common table names so we don't have to change your database.
*/

function findWardrobeTable($conn)
{
    $possibleTables = [
        "wardrobe",
        "wardrobe_items",
        "clothes",
        "clothing",
        "clothing_items",
        "items"
    ];

    foreach ($possibleTables as $table) {

        $safeTable = $conn->real_escape_string($table);

        $result = $conn->query("SHOW TABLES LIKE '$safeTable'");

        if ($result && $result->num_rows > 0) {
            return $table;
        }
    }

    return null;
}


function findColumn($conn, $table, $possibleColumns)
{
    $safeTable = $conn->real_escape_string($table);

    $result = $conn->query("SHOW COLUMNS FROM `$safeTable`");

    if (!$result) {
        return null;
    }

    $existingColumns = [];

    while ($row = $result->fetch_assoc()) {
        $existingColumns[] = strtolower($row["Field"]);
    }

    foreach ($possibleColumns as $column) {

        if (in_array(strtolower($column), $existingColumns)) {
            return $column;
        }
    }

    return null;
}


$wardrobeTable = findWardrobeTable($conn);


/*
|--------------------------------------------------------------------------
| DEFAULT DATA
|--------------------------------------------------------------------------
*/

$totalItems = 0;
$totalCategories = 0;

$topColor = "Not available";
$mainStyle = "Not available";

$categoryData = [];
$colorData = [];
$styleData = [];
$seasonData = [];
$occasionData = [];


/*
|--------------------------------------------------------------------------
| ANALYZE WARDROBE
|--------------------------------------------------------------------------
*/

if ($wardrobeTable) {

    $userColumn = findColumn(
        $conn,
        $wardrobeTable,
        ["user_id", "userid", "userId"]
    );

    $categoryColumn = findColumn(
        $conn,
        $wardrobeTable,
        ["category", "type", "clothing_category"]
    );

    $colorColumn = findColumn(
        $conn,
        $wardrobeTable,
        ["color", "colour"]
    );

    $styleColumn = findColumn(
        $conn,
        $wardrobeTable,
        ["style", "clothing_style"]
    );

    $seasonColumn = findColumn(
        $conn,
        $wardrobeTable,
        ["season"]
    );

    $occasionColumn = findColumn(
        $conn,
        $wardrobeTable,
        ["occasion", "occasions"]
    );


    /*
    |--------------------------------------------------------------------------
    | TOTAL ITEMS
    |--------------------------------------------------------------------------
    */

    if ($userColumn) {

        $sql = "
            SELECT COUNT(*) AS total
            FROM `$wardrobeTable`
            WHERE `$userColumn` = ?
        ";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $totalItems = (int)($row["total"] ?? 0);

            $stmt->close();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CATEGORY ANALYSIS
    |--------------------------------------------------------------------------
    */

    if ($userColumn && $categoryColumn) {

        $sql = "
            SELECT `$categoryColumn` AS value, COUNT(*) AS total
            FROM `$wardrobeTable`
            WHERE `$userColumn` = ?
              AND `$categoryColumn` IS NOT NULL
              AND `$categoryColumn` != ''
            GROUP BY `$categoryColumn`
            ORDER BY total DESC
        ";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {

                $value = trim($row["value"]);

                if ($value !== "") {
                    $categoryData[$value] = (int)$row["total"];
                }
            }

            $stmt->close();
        }

        $totalCategories = count($categoryData);
    }


    /*
    |--------------------------------------------------------------------------
    | COLOR ANALYSIS
    |--------------------------------------------------------------------------
    */

    if ($userColumn && $colorColumn) {

        $sql = "
            SELECT `$colorColumn` AS value, COUNT(*) AS total
            FROM `$wardrobeTable`
            WHERE `$userColumn` = ?
              AND `$colorColumn` IS NOT NULL
              AND `$colorColumn` != ''
            GROUP BY `$colorColumn`
            ORDER BY total DESC
        ";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {

                $value = trim($row["value"]);

                if ($value !== "") {
                    $colorData[$value] = (int)$row["total"];
                }
            }

            $stmt->close();
        }

        if (!empty($colorData)) {
            $topColor = array_key_first($colorData);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | STYLE ANALYSIS
    |--------------------------------------------------------------------------
    */

    if ($userColumn && $styleColumn) {

        $sql = "
            SELECT `$styleColumn` AS value, COUNT(*) AS total
            FROM `$wardrobeTable`
            WHERE `$userColumn` = ?
              AND `$styleColumn` IS NOT NULL
              AND `$styleColumn` != ''
            GROUP BY `$styleColumn`
            ORDER BY total DESC
        ";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {

                $value = trim($row["value"]);

                if ($value !== "") {
                    $styleData[$value] = (int)$row["total"];
                }
            }

            $stmt->close();
        }

        if (!empty($styleData)) {
            $mainStyle = array_key_first($styleData);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SEASON ANALYSIS
    |--------------------------------------------------------------------------
    */

    if ($userColumn && $seasonColumn) {

        $sql = "
            SELECT `$seasonColumn` AS value, COUNT(*) AS total
            FROM `$wardrobeTable`
            WHERE `$userColumn` = ?
              AND `$seasonColumn` IS NOT NULL
              AND `$seasonColumn` != ''
            GROUP BY `$seasonColumn`
            ORDER BY total DESC
        ";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {

                $value = trim($row["value"]);

                if ($value !== "") {
                    $seasonData[$value] = (int)$row["total"];
                }
            }

            $stmt->close();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | OCCASION ANALYSIS
    |--------------------------------------------------------------------------
    */

    if ($userColumn && $occasionColumn) {

        $sql = "
            SELECT `$occasionColumn` AS value, COUNT(*) AS total
            FROM `$wardrobeTable`
            WHERE `$userColumn` = ?
              AND `$occasionColumn` IS NOT NULL
              AND `$occasionColumn` != ''
            GROUP BY `$occasionColumn`
            ORDER BY total DESC
        ";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {

                $value = trim($row["value"]);

                if ($value !== "") {
                    $occasionData[$value] = (int)$row["total"];
                }
            }

            $stmt->close();
        }
    }
}


/*
|--------------------------------------------------------------------------
| INSIGHTS
|--------------------------------------------------------------------------
*/

$insights = [];

if ($totalItems > 0 && $topColor !== "Not available") {

    $insights[] =
        "Your wardrobe currently has the most items in " .
        $topColor . ".";
}

if ($totalItems > 0 && $mainStyle !== "Not available") {

    $insights[] =
        "Your wardrobe is mainly focused on " .
        $mainStyle . " style.";
}

if ($totalItems >= 10) {

    $insights[] =
        "You have a good-sized wardrobe. Your clothing collection has enough variety for multiple outfit combinations.";

} elseif ($totalItems > 0) {

    $insights[] =
        "Your wardrobe is still growing. Adding more clothing categories will give you more outfit combinations.";
}


if ($totalCategories >= 4) {

    $insights[] =
        "You have good variety across different clothing categories.";

} elseif ($totalItems > 0) {

    $insights[] =
        "Try adding more clothing categories to increase wardrobe variety.";
}


if (!empty($occasionData)) {

    $leastOccasion = array_key_last($occasionData);

    $insights[] =
        "Your wardrobe has fewer items for " .
        $leastOccasion .
        " occasions. Consider adding more options for variety.";
}


/*
|--------------------------------------------------------------------------
| JSON DATA FOR CHARTS
|--------------------------------------------------------------------------
*/

$categoryLabels = array_keys($categoryData);
$categoryValues = array_values($categoryData);

$colorLabels = array_keys($colorData);
$colorValues = array_values($colorData);

$styleLabels = array_keys($styleData);
$styleValues = array_values($styleData);

$seasonLabels = array_keys($seasonData);
$seasonValues = array_values($seasonData);

$occasionLabels = array_keys($occasionData);
$occasionValues = array_values($occasionData);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Style Analytics - StyleMate</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #050505;
    color: #ffffff;
    font-family: Arial, Helvetica, sans-serif;
}

.analytics-container {
    width: 94%;
    max-width: 1500px;
    margin: 35px auto 60px;
}


/* HEADER */

.analytics-header {
    background: linear-gradient(
        135deg,
        #111111,
        #090909
    );

    border: 1px solid #252525;
    border-radius: 22px;

    padding: 28px 38px;

    margin-bottom: 20px;

    position: relative;
}

.analytics-header small {
    color: #22d3ee;
    font-size: 15px;
    font-weight: 700;
    letter-spacing: 1px;
}

.analytics-header h1 {
    font-size: 48px;
    margin: 10px 0 8px;
    font-weight: 800;

    background: linear-gradient(
        90deg,
        #22d3ee,
        #818cf8,
        #a855f7
    );

    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.analytics-header p {
    color: #b6b6b6;
    font-size: 18px;
    margin: 0;
}


/* HEADER BUTTONS */

.header-actions {
    position: absolute;
    top: 25px;
    right: 25px;
    display: flex;
    gap: 10px;
}

.header-actions a {
    text-decoration: none;
    padding: 11px 18px;
    border-radius: 12px;
    font-weight: 700;
    color: white;
    border: 1px solid #333;
}

.header-actions a:first-child {
    color: #22d3ee;
}

.header-actions a:last-child {
    background: #111;
}


/* STAT CARDS */

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 14px;
}

.stat-card {
    background: #0d0d0d;
    border: 1px solid #252525;
    border-radius: 16px;
    padding: 24px;
    min-height: 145px;
}

.stat-card .stat-title {
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 1px;
    margin-bottom: 18px;
}

.stat-card .stat-number {
    font-size: 32px;
    font-weight: 800;
    margin-bottom: 8px;
}

.stat-card p {
    color: #aaa;
    margin: 0;
}

.stat-blue .stat-title,
.stat-blue .stat-number {
    color: #22d3ee;
}

.stat-green .stat-title,
.stat-green .stat-number {
    color: #22c55e;
}

.stat-purple .stat-title,
.stat-purple .stat-number {
    color: #a855f7;
}

.stat-orange .stat-title,
.stat-orange .stat-number {
    color: #f59e0b;
}


/* CHART GRID */

.chart-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}

.chart-card {
    background: #0d0d0d;
    border: 1px solid #252525;
    border-radius: 16px;
    padding: 20px;
    min-height: 300px;
}

.chart-card h3 {
    font-size: 19px;
    margin: 0 0 8px;
}

.chart-card p {
    color: #aaa;
    margin: 0 0 15px;
}

.chart-wrapper {
    height: 220px;
    position: relative;
}


/* ACCENT TITLES */

.blue-title {
    color: #22d3ee;
}

.green-title {
    color: #22c55e;
}

.purple-title {
    color: #a855f7;
}

.orange-title {
    color: #f59e0b;
}

.yellow-title {
    color: #facc15;
}


/* INSIGHTS */

.insights-card {
    background: #0d0d0d;
    border: 1px solid #252525;
    border-radius: 16px;
    padding: 20px;
    min-height: 300px;
}

.insights-card h3 {
    color: #facc15;
    margin-bottom: 8px;
}

.insights-card > p {
    color: #aaa;
}

.insight-item {
    display: flex;
    gap: 12px;
    margin: 18px 0;
    color: #eee;
    line-height: 1.4;
}

.insight-icon {
    color: #facc15;
    font-size: 19px;
    flex-shrink: 0;
}


/* EMPTY */

.empty-message {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 220px;
    color: #777;
    text-align: center;
}


/* RESPONSIVE */

@media (max-width: 1100px) {

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .chart-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}


@media (max-width: 700px) {

    .analytics-container {
        width: 92%;
    }

    .analytics-header {
        padding: 25px;
    }

    .analytics-header h1 {
        font-size: 34px;
    }

    .header-actions {
        position: static;
        margin-top: 20px;
    }

    .stats-grid,
    .chart-grid {
        grid-template-columns: 1fr;
    }

}

</style>

</head>


<body>


<div class="analytics-container">


    <!-- HEADER -->

    <section class="analytics-header">

        <div class="header-actions">

            <a href="dashboard.php">
                ← Dashboard
            </a>

            <a href="logout.php">
                Logout
            </a>

        </div>

        <small>
            STYLE ANALYTICS
        </small>

        <h1>
            Understand Your Wardrobe
        </h1>

        <p>
            Analyze your clothing collection, discover patterns
            and understand your personal style.
        </p>

    </section>


    <!-- STATISTICS -->

    <section class="stats-grid">


        <div class="stat-card stat-blue">

            <div class="stat-title">
                TOTAL ITEMS
            </div>

            <div class="stat-number">
                <?php echo $totalItems; ?>
            </div>

            <p>
                Clothing pieces
            </p>

        </div>


        <div class="stat-card stat-green">

            <div class="stat-title">
                CATEGORIES
            </div>

            <div class="stat-number">
                <?php echo $totalCategories; ?>
            </div>

            <p>
                Different types
            </p>

        </div>


        <div class="stat-card stat-purple">

            <div class="stat-title">
                TOP COLOR
            </div>

            <div class="stat-number">
                <?php echo htmlspecialchars($topColor); ?>
            </div>

            <p>
                Most used color
            </p>

        </div>


        <div class="stat-card stat-orange">

            <div class="stat-title">
                MAIN STYLE
            </div>

            <div class="stat-number">
                <?php echo htmlspecialchars($mainStyle); ?>
            </div>

            <p>
                Dominant style
            </p>

        </div>


    </section>


    <!-- CHARTS -->

    <section class="chart-grid">


        <!-- CATEGORY -->

        <div class="chart-card">

            <h3 class="blue-title">
                Wardrobe by Category
            </h3>

            <p>
                See how your clothing is distributed.
            </p>

            <div class="chart-wrapper">

                <?php if (!empty($categoryData)): ?>

                    <canvas id="categoryChart"></canvas>

                <?php else: ?>

                    <div class="empty-message">
                        Add clothes to your wardrobe to see category analysis.
                    </div>

                <?php endif; ?>

            </div>

        </div>


        <!-- COLOR -->

        <div class="chart-card">

            <h3 class="purple-title">
                Color Analysis
            </h3>

            <p>
                Your most common wardrobe colors.
            </p>

            <div class="chart-wrapper">

                <?php if (!empty($colorData)): ?>

                    <canvas id="colorChart"></canvas>

                <?php else: ?>

                    <div class="empty-message">
                        Add clothing colors to see color analysis.
                    </div>

                <?php endif; ?>

            </div>

        </div>


        <!-- STYLE -->

        <div class="chart-card">

            <h3 class="orange-title">
                Style Distribution
            </h3>

            <p>
                Understand your overall fashion style.
            </p>

            <div class="chart-wrapper">

                <?php if (!empty($styleData)): ?>

                    <canvas id="styleChart"></canvas>

                <?php else: ?>

                    <div class="empty-message">
                        Add style information to your clothes.
                    </div>

                <?php endif; ?>

            </div>

        </div>


        <!-- SEASON -->

        <div class="chart-card">

            <h3 class="green-title">
                Seasonal Wardrobe
            </h3>

            <p>
                See which seasons your wardrobe supports.
            </p>

            <div class="chart-wrapper">

                <?php if (!empty($seasonData)): ?>

                    <canvas id="seasonChart"></canvas>

                <?php else: ?>

                    <div class="empty-message">
                        Add season information to your wardrobe.
                    </div>

                <?php endif; ?>

            </div>

        </div>


        <!-- OCCASION -->

        <div class="chart-card">

            <h3 class="blue-title">
                Occasion Analysis
            </h3>

            <p>
                Where your wardrobe can be used.
            </p>

            <div class="chart-wrapper">

                <?php if (!empty($occasionData)): ?>

                    <canvas id="occasionChart"></canvas>

                <?php else: ?>

                    <div class="empty-message">
                        Add occasion information to see this analysis.
                    </div>

                <?php endif; ?>

            </div>

        </div>


        <!-- INSIGHTS -->

        <div class="insights-card">

            <h3>
                Style Insights
            </h3>

            <p>
                Automatically generated from your wardrobe.
            </p>

            <?php foreach ($insights as $insight): ?>

                <div class="insight-item">

                    <span class="insight-icon">
                        ✓
                    </span>

                    <span>
                        <?php echo htmlspecialchars($insight); ?>
                    </span>

                </div>

            <?php endforeach; ?>

            <?php if (empty($insights)): ?>

                <div class="insight-item">

                    <span class="insight-icon">
                        ✓
                    </span>

                    <span>
                        Add clothing items to your wardrobe to generate personalized style insights.
                    </span>

                </div>

            <?php endif; ?>

        </div>


    </section>


</div>


<script>


const chartColors = [
    "#22d3ee",
    "#22c55e",
    "#a855f7",
    "#f59e0b",
    "#3b82f6",
    "#14b8a6",
    "#ec4899",
    "#facc15"
];


const commonOptions = {

    responsive: true,

    maintainAspectRatio: false,

    plugins: {

        legend: {
            labels: {
                color: "#eeeeee"
            }
        }

    },

    scales: {

        x: {
            ticks: {
                color: "#bbbbbb"
            },

            grid: {
                color: "#222222"
            }
        },

        y: {
            ticks: {
                color: "#bbbbbb"
            },

            grid: {
                color: "#222222"
            },

            beginAtZero: true
        }

    }

};


/* CATEGORY */

<?php if (!empty($categoryData)): ?>

new Chart(
    document.getElementById("categoryChart"),
    {
        type: "doughnut",

        data: {

            labels: <?php echo json_encode($categoryLabels); ?>,

            datasets: [{

                data: <?php echo json_encode($categoryValues); ?>,

                backgroundColor: chartColors,

                borderColor: "#0d0d0d",

                borderWidth: 3

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    position: "right",

                    labels: {
                        color: "#eeeeee"
                    }

                }

            }

        }

    }
);

<?php endif; ?>


/* COLOR */

<?php if (!empty($colorData)): ?>

new Chart(
    document.getElementById("colorChart"),
    {
        type: "bar",

        data: {

            labels: <?php echo json_encode($colorLabels); ?>,

            datasets: [{

                data: <?php echo json_encode($colorValues); ?>,

                backgroundColor: "#a855f7",

                borderRadius: 4

            }]

        },

        options: commonOptions

    }
);

<?php endif; ?>


/* STYLE */

<?php if (!empty($styleData)): ?>

new Chart(
    document.getElementById("styleChart"),
    {
        type: "doughnut",

        data: {

            labels: <?php echo json_encode($styleLabels); ?>,

            datasets: [{

                data: <?php echo json_encode($styleValues); ?>,

                backgroundColor: chartColors,

                borderColor: "#0d0d0d",

                borderWidth: 3

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    position: "right",

                    labels: {
                        color: "#eeeeee"
                    }

                }

            }

        }

    }
);

<?php endif; ?>


/* SEASON */

<?php if (!empty($seasonData)): ?>

new Chart(
    document.getElementById("seasonChart"),
    {
        type: "bar",

        data: {

            labels: <?php echo json_encode($seasonLabels); ?>,

            datasets: [{

                data: <?php echo json_encode($seasonValues); ?>,

                backgroundColor: "#22c55e",

                borderRadius: 4

            }]

        },

        options: commonOptions

    }
);

<?php endif; ?>


/* OCCASION */

<?php if (!empty($occasionData)): ?>

new Chart(
    document.getElementById("occasionChart"),
    {
        type: "bar",

        data: {

            labels: <?php echo json_encode($occasionLabels); ?>,

            datasets: [{

                data: <?php echo json_encode($occasionValues); ?>,

                backgroundColor: "#22d3ee",

                borderRadius: 4

            }]

        },

        options: {

            indexAxis: "y",

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    display: false
                }

            },

            scales: {

                x: {
                    beginAtZero: true,

                    ticks: {
                        color: "#bbbbbb"
                    },

                    grid: {
                        color: "#222222"
                    }

                },

                y: {

                    ticks: {
                        color: "#bbbbbb"
                    },

                    grid: {
                        color: "#222222"
                    }

                }

            }

        }

    }
);

<?php endif; ?>


</script>


</body>

</html>