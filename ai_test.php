<<<<<<< HEAD
<?php

session_start();

require_once "db.php";
require_once "gemini_config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Get the user's actual wardrobe
$wardrobe = [];

$stmt = $conn->prepare("
    SELECT 
        clothing_items.name,
        clothing_items.color,
        clothing_items.style,
        clothing_items.occasion,
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

while ($item = $result->fetch_assoc()) {
    $wardrobe[] = $item;
}

$stmt->close();

$response_text = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $prompt = trim($_POST["prompt"] ?? "");

    if ($prompt === "") {
        $error_message = "Please enter a question.";
    } else {

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent";

       $wardrobe_text = "";

foreach ($wardrobe as $item) {

    $wardrobe_text .= "- "
        . $item["name"]
        . " | Category: " . $item["category_name"]
        . " | Color: " . ($item["color"] ?: "Not specified")
        . " | Style: " . ($item["style"] ?: "Not specified")
        . " | Occasion: " . ($item["occasion"] ?: "Not specified")
        . "\n";
}

if ($wardrobe_text === "") {
    $wardrobe_text = "The user has no clothing items in their wardrobe yet.";
}

$final_prompt = "
You are the AI Style Assistant inside a wardrobe management application called StyleMate.

The user owns the following clothing items:

$wardrobe_text

The user asks:
$prompt

IMPORTANT RULES:
1. Recommend outfits using ONLY clothing items listed in the user's wardrobe.
2. Do not invent clothes that are not in the wardrobe.
3. If the wardrobe does not contain suitable items, clearly say so.
4. Keep the recommendation practical and easy to understand.
5. Mention the actual clothing item names from the wardrobe.
6. Give one best outfit first, followed by a short explanation.
7. Do not recommend shoes, bags, accessories or other items unless they are actually present in the wardrobe.
";

$data = [
    "contents" => [
        [
            "parts" => [
                [
                    "text" => $final_prompt
                ]
            ]
        ]
    ]
];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "x-goog-api-key: " . GEMINI_API_KEY
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {

            $error_message = "Connection error: " . curl_error($ch);

        } else {

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $result = json_decode($response, true);

            if ($http_code >= 200 && $http_code < 300) {

                $response_text =
                    $result["candidates"][0]["content"]["parts"][0]["text"]
                    ?? "No response received.";

            } else {

                $error_message =
                    "Gemini API Error (" . $http_code . "): " .
                    ($result["error"]["message"] ?? "Unknown error.");

            }
        }

        curl_close($ch);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AI Style Assistant Test - StyleMate</title>

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

    <div class="text-center mb-5">

        <h1 class="fw-bold">
            🤖 AI Style Assistant
        </h1>

        <p class="text-muted">
            Test your Gemini AI connection.
        </p>

    </div>


    <div class="card shadow-sm border-0 p-4 mx-auto" style="max-width: 800px;">

        <form method="POST">

            <label class="form-label fw-bold">
                Ask the AI
            </label>

            <textarea
                name="prompt"
                class="form-control mb-3"
                rows="4"
                placeholder="Example: Suggest a casual outfit for college."
            ><?php echo htmlspecialchars($_POST["prompt"] ?? ""); ?></textarea>

            <button type="submit" class="btn btn-dark">
                Ask Gemini AI
            </button>

        </form>


        <?php if ($response_text !== ""): ?>

            <div class="alert alert-success mt-4">

                <h5 class="fw-bold">
                    AI Response
                </h5>

                <div style="white-space: pre-line;">
                    <?php echo htmlspecialchars($response_text); ?>
                </div>

            </div>

        <?php endif; ?>


        <?php if ($error_message !== ""): ?>

            <div class="alert alert-danger mt-4">

                <strong>Error:</strong>

                <?php echo htmlspecialchars($error_message); ?>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>

=======
<?php

session_start();

require_once "db.php";
require_once "gemini_config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Get the user's actual wardrobe
$wardrobe = [];

$stmt = $conn->prepare("
    SELECT 
        clothing_items.name,
        clothing_items.color,
        clothing_items.style,
        clothing_items.occasion,
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

while ($item = $result->fetch_assoc()) {
    $wardrobe[] = $item;
}

$stmt->close();

$response_text = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $prompt = trim($_POST["prompt"] ?? "");

    if ($prompt === "") {
        $error_message = "Please enter a question.";
    } else {

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent";

       $wardrobe_text = "";

foreach ($wardrobe as $item) {

    $wardrobe_text .= "- "
        . $item["name"]
        . " | Category: " . $item["category_name"]
        . " | Color: " . ($item["color"] ?: "Not specified")
        . " | Style: " . ($item["style"] ?: "Not specified")
        . " | Occasion: " . ($item["occasion"] ?: "Not specified")
        . "\n";
}

if ($wardrobe_text === "") {
    $wardrobe_text = "The user has no clothing items in their wardrobe yet.";
}

$final_prompt = "
You are the AI Style Assistant inside a wardrobe management application called StyleMate.

The user owns the following clothing items:

$wardrobe_text

The user asks:
$prompt

IMPORTANT RULES:
1. Recommend outfits using ONLY clothing items listed in the user's wardrobe.
2. Do not invent clothes that are not in the wardrobe.
3. If the wardrobe does not contain suitable items, clearly say so.
4. Keep the recommendation practical and easy to understand.
5. Mention the actual clothing item names from the wardrobe.
6. Give one best outfit first, followed by a short explanation.
7. Do not recommend shoes, bags, accessories or other items unless they are actually present in the wardrobe.
";

$data = [
    "contents" => [
        [
            "parts" => [
                [
                    "text" => $final_prompt
                ]
            ]
        ]
    ]
];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "x-goog-api-key: " . GEMINI_API_KEY
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {

            $error_message = "Connection error: " . curl_error($ch);

        } else {

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $result = json_decode($response, true);

            if ($http_code >= 200 && $http_code < 300) {

                $response_text =
                    $result["candidates"][0]["content"]["parts"][0]["text"]
                    ?? "No response received.";

            } else {

                $error_message =
                    "Gemini API Error (" . $http_code . "): " .
                    ($result["error"]["message"] ?? "Unknown error.");

            }
        }

        curl_close($ch);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AI Style Assistant Test - StyleMate</title>

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

    <div class="text-center mb-5">

        <h1 class="fw-bold">
            🤖 AI Style Assistant
        </h1>

        <p class="text-muted">
            Test your Gemini AI connection.
        </p>

    </div>


    <div class="card shadow-sm border-0 p-4 mx-auto" style="max-width: 800px;">

        <form method="POST">

            <label class="form-label fw-bold">
                Ask the AI
            </label>

            <textarea
                name="prompt"
                class="form-control mb-3"
                rows="4"
                placeholder="Example: Suggest a casual outfit for college."
            ><?php echo htmlspecialchars($_POST["prompt"] ?? ""); ?></textarea>

            <button type="submit" class="btn btn-dark">
                Ask Gemini AI
            </button>

        </form>


        <?php if ($response_text !== ""): ?>

            <div class="alert alert-success mt-4">

                <h5 class="fw-bold">
                    AI Response
                </h5>

                <div style="white-space: pre-line;">
                    <?php echo htmlspecialchars($response_text); ?>
                </div>

            </div>

        <?php endif; ?>


        <?php if ($error_message !== ""): ?>

            <div class="alert alert-danger mt-4">

                <strong>Error:</strong>

                <?php echo htmlspecialchars($error_message); ?>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>

>>>>>>> 44c54392920554fc489fa0b5fa2377643cfac17d
</html>