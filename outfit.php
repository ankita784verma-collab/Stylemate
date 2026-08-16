<?php

session_start();

require_once "db.php";
require_once "gemini_config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$response_text = "";
$error_message = "";

$recommended_items = [];
$outfit_name = "Your Recommended Outfit";

/* ==========================================
   REAL-TIME WEATHER - OPEN-METEO
========================================== */

$weather = [
    "temperature" => null,
    "feels_like" => null,
    "humidity" => null,
    "wind_speed" => null,
    "condition" => "Weather unavailable"
];

/*
   Ludhiana coordinates
   Latitude: 30.896309
   Longitude: 75.83432
*/

$weather_url =
    "https://api.open-meteo.com/v1/forecast" .
    "?latitude=30.896309" .
    "&longitude=75.83432" .
    "&current=temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,weather_code,wind_speed_10m" .
    "&timezone=Asia%2FKolkata";

$weather_ch = curl_init($weather_url);

curl_setopt($weather_ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($weather_ch, CURLOPT_TIMEOUT, 10);
curl_setopt($weather_ch, CURLOPT_SSL_VERIFYPEER, true);

$weather_response = curl_exec($weather_ch);

$weather_http_code = curl_getinfo(
    $weather_ch,
    CURLINFO_HTTP_CODE
);

if (!curl_errno($weather_ch) && $weather_http_code === 200) {

    $weather_data = json_decode(
        $weather_response,
        true
    );

    if (
        isset($weather_data["current"]) &&
        is_array($weather_data["current"])
    ) {

        $current = $weather_data["current"];

        $weather["temperature"] =
            $current["temperature_2m"] ?? null;

        $weather["feels_like"] =
            $current["apparent_temperature"] ?? null;

        $weather["humidity"] =
            $current["relative_humidity_2m"] ?? null;

        $weather["wind_speed"] =
            $current["wind_speed_10m"] ?? null;

        $weather_code =
            $current["weather_code"] ?? null;


        /* WEATHER CODE DESCRIPTION */

        $weather_conditions = [

            0 => "Clear sky",

            1 => "Mainly clear",
            2 => "Partly cloudy",
            3 => "Overcast",

            45 => "Foggy",
            48 => "Foggy",

            51 => "Light drizzle",
            53 => "Drizzle",
            55 => "Heavy drizzle",

            56 => "Freezing drizzle",
            57 => "Heavy freezing drizzle",

            61 => "Light rain",
            63 => "Rain",
            65 => "Heavy rain",

            66 => "Freezing rain",
            67 => "Heavy freezing rain",

            71 => "Light snow",
            73 => "Snow",
            75 => "Heavy snow",

            77 => "Snow grains",

            80 => "Light rain showers",
            81 => "Rain showers",
            82 => "Heavy rain showers",

            85 => "Light snow showers",
            86 => "Heavy snow showers",

            95 => "Thunderstorm",
            96 => "Thunderstorm with hail",
            99 => "Heavy thunderstorm with hail"
        ];


        $weather["condition"] =
            $weather_conditions[$weather_code]
            ?? "Current weather";
    }
}

curl_close($weather_ch);


/* ==========================================
   GET USER'S REAL WARDROBE
========================================== */

$wardrobe = [];

$stmt = $conn->prepare("
    SELECT
        clothing_items.id,
        clothing_items.name,
        clothing_items.image,
        clothing_items.color,
        clothing_items.secondary_color,
        clothing_items.pattern,
        clothing_items.style,
        clothing_items.season,
        clothing_items.occasion,
        categories.name AS category_name
    FROM clothing_items
    INNER JOIN categories
        ON clothing_items.category_id = categories.id
    WHERE clothing_items.user_id = ?
    ORDER BY clothing_items.created_at DESC
");

if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

while ($item = $result->fetch_assoc()) {
    $wardrobe[] = $item;
}

$stmt->close();


/* ==========================================
   GENERATE OUTFIT
========================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $occasion = trim($_POST["occasion"] ?? "");
    $style = trim($_POST["style"] ?? "");


    if (empty($occasion) || empty($style)) {

        $error_message =
            "Please select an occasion and style.";

    } elseif (count($wardrobe) < 2) {

        $error_message =
            "Please add at least 2 clothing items to your wardrobe.";

    } else {


        /* ==========================================
           CREATE WARDROBE LIST FOR GEMINI
        ========================================== */

        $wardrobe_text = "";

        foreach ($wardrobe as $item) {

            $wardrobe_text .=
                "ID: " . $item["id"] .
                " | Name: " . $item["name"] .
                " | Category: " . $item["category_name"] .
                " | Color: " . ($item["color"] ?: "Not specified") .
                " | Secondary Color: " . ($item["secondary_color"] ?: "None") .
                " | Pattern: " . ($item["pattern"] ?: "Not specified") .
                " | Style: " . ($item["style"] ?: "Not specified") .
                " | Season: " . ($item["season"] ?: "Not specified") .
                " | Occasion: " . ($item["occasion"] ?: "Not specified") .
                "\n";
        }


        /* ==========================================
           WEATHER INFORMATION
        ========================================== */

        if ($weather["temperature"] !== null) {

            $weather_text =
                "Location: Ludhiana, Punjab\n" .
                "Temperature: " . $weather["temperature"] . "°C\n" .
                "Feels Like: " . $weather["feels_like"] . "°C\n" .
                "Humidity: " . $weather["humidity"] . "%\n" .
                "Wind Speed: " . $weather["wind_speed"] . " km/h\n" .
                "Condition: " . $weather["condition"];

        } else {

            $weather_text =
                "Current weather information is unavailable.";
        }


        /* ==========================================
           AI PROMPT
        ========================================== */

        $final_prompt = "
You are the AI Outfit Recommendation Assistant inside StyleMate.

The user wants an outfit for:

Occasion: $occasion
Style: $style

CURRENT REAL-WORLD WEATHER:

$weather_text

USER'S REAL WARDROBE:

$wardrobe_text

IMPORTANT RULES:

1. Use ONLY clothing items from the wardrobe listed above.
2. Never invent or add clothing items that are not listed.
3. Recommend one complete outfit.
4. Select a suitable top and bottom whenever possible.
5. A jacket or layer can be included only if it exists in the wardrobe.
6. Consider the selected occasion and style.
7. Consider colors and clothing compatibility.
8. Consider the current weather when selecting clothing.
9. Avoid recommending clothing that is unsuitable for the current weather.
10. If the weather is hot, prefer lighter and more breathable items when available.
11. If the weather is cold, prefer warmer items or layers when available.
12. If it is raining, prefer suitable clothing from the wardrobe.
13. If a suitable weather-friendly item is not available, do not invent one.
14. The selected item IDs must exactly match IDs from the wardrobe.
15. Return ONLY valid JSON.
16. Do not use markdown.

Return this exact JSON structure:

{
  \"outfit_name\": \"Short name of outfit\",
  \"items\": [
    {
      \"id\": 1,
      \"role\": \"Top\"
    },
    {
      \"id\": 2,
      \"role\": \"Bottom\"
    }
  ],
  \"reason\": \"Short explanation of why these items work together and why they are suitable for the current weather.\"
}
";


        /* ==========================================
           GEMINI API
        ========================================== */

        $url =
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent";


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

        curl_setopt(
            $ch,
            CURLOPT_RETURNTRANSFER,
            true
        );

        curl_setopt(
            $ch,
            CURLOPT_POST,
            true
        );

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [

                "Content-Type: application/json",

                "x-goog-api-key: " .
                GEMINI_API_KEY

            ]
        );

        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            json_encode($data)
        );


        $response = curl_exec($ch);


        if (curl_errno($ch)) {

            $error_message =
                "Connection error: " .
                curl_error($ch);

        } else {

            $http_code =
                curl_getinfo(
                    $ch,
                    CURLINFO_HTTP_CODE
                );


            $result =
                json_decode(
                    $response,
                    true
                );


            if (
                $http_code >= 200 &&
                $http_code < 300
            ) {

                $response_text =
                    $result["candidates"][0]["content"]["parts"][0]["text"]
                    ?? "";


                /* ==========================================
                   CLEAN GEMINI JSON RESPONSE
                ========================================== */

                $clean_json =
                    trim($response_text);


                $clean_json =
                    preg_replace(
                        '/^```json\s*/i',
                        '',
                        $clean_json
                    );


                $clean_json =
                    preg_replace(
                        '/^```\s*/',
                        '',
                        $clean_json
                    );


                $clean_json =
                    preg_replace(
                        '/\s*```$/',
                        '',
                        $clean_json
                    );


                $ai_data =
                    json_decode(
                        trim($clean_json),
                        true
                    );


                if (
                    is_array($ai_data) &&
                    isset($ai_data["items"]) &&
                    is_array($ai_data["items"])
                ) {

                    foreach (
                        $ai_data["items"]
                        as $ai_item
                    ) {

                        if (
                            !isset(
                                $ai_item["id"]
                            )
                        ) {
                            continue;
                        }


                        $ai_id =
                            intval(
                                $ai_item["id"]
                            );


                        foreach (
                            $wardrobe
                            as $wardrobe_item
                        ) {

                            if (
                                intval(
                                    $wardrobe_item["id"]
                                )
                                ===
                                $ai_id
                            ) {

                                $wardrobe_item["role"] =
                                    $ai_item["role"]
                                    ?? "Item";


                                $recommended_items[] =
                                    $wardrobe_item;

                                break;
                            }
                        }
                    }


                    if (
                        count(
                            $recommended_items
                        ) > 0
                    ) {

                        $response_text =
                            $ai_data["reason"]
                            ??
                            "These items work well together.";


                        $outfit_name =
                            $ai_data["outfit_name"]
                            ??
                            "Your Recommended Outfit";

                    } else {

                        $error_message =
                            "AI could not find suitable items from your wardrobe.";
                    }

                } else {

                    $error_message =
                        "AI returned an unexpected response. Please try again.";
                }

            } else {

                $error_message =
                    "Gemini API Error (" .
                    $http_code .
                    "): " .
                    (
                        $result["error"]["message"]
                        ??
                        "Unknown error."
                    );
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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>AI Outfit Builder - StyleMate</title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <link
        rel="stylesheet"
        href="css/style.css"
    >


    <style>

        .weather-card {
            background: #111;
            color: white;
            border-radius: 20px;
            padding: 24px 28px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.10);
        }

        .weather-main {
            font-size: 34px;
            font-weight: 700;
        }

        .weather-condition {
            color: #bdbdbd;
            font-size: 15px;
        }

        .weather-stat {
            border-left: 1px solid #333;
            padding-left: 20px;
        }

        .weather-stat small {
            color: #999;
            display: block;
            margin-bottom: 4px;
        }

        .weather-stat strong {
            font-size: 16px;
        }

        .outfit-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: white;
            box-shadow: 8px 25px rgba(0,0,0,0.08);
            height: 100%;
        }

        .outfit-image {
            width: 100%;
            height: 280px;
            object-fit: cover;
        }

        .role-badge {
            display: inline-block;
            background: #f1f3f5;
            color: #111;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .builder-box {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        }

        .result-box {
            background: #111;
            color: white;
            border-radius: 20px;
            padding: 25px;
        }

        .result-box p {
            color: #d0d0d0;
        }

        @media (max-width: 768px) {

            .weather-stat {
                border-left: none;
                border-top: 1px solid #333;
                padding-left: 0;
                padding-top: 12px;
                margin-top: 12px;
            }

        }

    </style>

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
                href="dashboard.php"
                class="btn btn-outline-dark me-2"
            >
                Dashboard
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


    <!-- PAGE HEADER -->

    <div class="text-center mb-4">

        <h1 class="fw-bold">
            ✨ AI Outfit Builder
        </h1>

        <p class="text-muted">
            Let AI create an outfit using your own wardrobe and today's weather.
        </p>

    </div>


    <!-- REAL WEATHER -->

    <div class="weather-card">

        <div class="row align-items-center">

            <div class="col-lg-4">

                <div class="small text-uppercase text-secondary mb-2">
                    Today's Weather
                </div>

                <?php if ($weather["temperature"] !== null): ?>

                    <div class="weather-main">

                        <?php
                        echo htmlspecialchars(
                            $weather["temperature"]
                        );
                        ?>°C

                    </div>

                    <div class="weather-condition">

                        <?php
                        echo htmlspecialchars(
                            $weather["condition"]
                        );
                        ?>

                    </div>

                    <small class="text-secondary">
                        Ludhiana, Punjab
                    </small>

                <?php else: ?>

                    <div class="weather-main">
                        --
                    </div>

                    <div class="weather-condition">
                        Weather unavailable
                    </div>

                <?php endif; ?>

            </div>


            <?php if ($weather["temperature"] !== null): ?>

                <div class="col-lg-8">

                    <div class="row g-3 mt-3 mt-lg-0">

                        <div class="col-6 col-md-3">

                            <div class="weather-stat">

                                <small>
                                    Feels Like
                                </small>

                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                        $weather["feels_like"]
                                    );
                                    ?>°C
                                </strong>

                            </div>

                        </div>


                        <div class="col-6 col-md-3">

                            <div class="weather-stat">

                                <small>
                                    Humidity
                                </small>

                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                        $weather["humidity"]
                                    );
                                    ?>%
                                </strong>

                            </div>

                        </div>


                        <div class="col-6 col-md-3">

                            <div class="weather-stat">

                                <small>
                                    Wind
                                </small>

                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                        $weather["wind_speed"]
                                    );
                                    ?> km/h
                                </strong>

                            </div>

                        </div>


                        <div class="col-6 col-md-3">


                        </div>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>


    <!-- BUILDER -->

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="builder-box">

                <h4 class="fw-bold mb-4">
                    Create Your Outfit
                </h4>


                <form method="POST">


                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            What are you dressing for?
                        </label>


                        <select
                            name="occasion"
                            class="form-select form-select-lg"
                            required
                        >

                            <option value="">
                                Select occasion
                            </option>


                            <option
                                value="College"
                                <?php
                                if (
                                    ($_POST["occasion"] ?? "")
                                    === "College"
                                ) echo "selected";
                                ?>
                            >
                                College
                            </option>


                            <option
                                value="Work"
                                <?php
                                if (
                                    ($_POST["occasion"] ?? "")
                                    === "Work"
                                ) echo "selected";
                                ?>
                            >
                                Work
                            </option>


                            <option
                                value="Casual"
                                <?php
                                if (
                                    ($_POST["occasion"] ?? "")
                                    === "Casual"
                                ) echo "selected";
                                ?>
                            >
                                Casual Outing
                            </option>


                            <option
                                value="Party"
                                <?php
                                if (
                                    ($_POST["occasion"] ?? "")
                                    === "Party"
                                ) echo "selected";
                                ?>
                            >
                                Party
                            </option>


                            <option
                                value="Date"
                                <?php
                                if (
                                    ($_POST["occasion"] ?? "")
                                    === "Date"
                                ) echo "selected";
                                ?>
                            >
                                Date
                            </option>


                            <option
                                value="Travel"
                                <?php
                                if (
                                    ($_POST["occasion"] ?? "")
                                    === "Travel"
                                ) echo "selected";
                                ?>
                            >
                                Travel
                            </option>


                            <option
                                value="Traditional Event"
                                <?php
                                if (
                                    ($_POST["occasion"] ?? "")
                                    === "Traditional Event"
                                ) echo "selected";
                                ?>
                            >
                                Traditional Event
                            </option>

                        </select>

                    </div>


                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            What style do you want?
                        </label>


                        <select
                            name="style"
                            class="form-select form-select-lg"
                            required
                        >

                            <option value="">
                                Select style
                            </option>


                            <option
                                value="Casual"
                                <?php
                                if (
                                    ($_POST["style"] ?? "")
                                    === "Casual"
                                ) echo "selected";
                                ?>
                            >
                                Casual
                            </option>


                            <option
                                value="Formal"
                                <?php
                                if (
                                    ($_POST["style"] ?? "")
                                    === "Formal"
                                ) echo "selected";
                                ?>
                            >
                                Formal
                            </option>


                            <option
                                value="Party"
                                <?php
                                if (
                                    ($_POST["style"] ?? "")
                                    === "Party"
                                ) echo "selected";
                                ?>
                            >
                                Party
                            </option>


                            <option
                                value="Traditional"
                                <?php
                                if (
                                    ($_POST["style"] ?? "")
                                    === "Traditional"
                                ) echo "selected";
                                ?>
                            >
                                Traditional
                            </option>


                            <option
                                value="Sporty"
                                <?php
                                if (
                                    ($_POST["style"] ?? "")
                                    === "Sporty"
                                ) echo "selected";
                                ?>
                            >
                                Sporty
                            </option>


                            <option
                                value="Streetwear"
                                <?php
                                if (
                                    ($_POST["style"] ?? "")
                                    === "Streetwear"
                                ) echo "selected";
                                ?>
                            >
                                Streetwear
                            </option>

                        </select>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-dark btn-lg w-100"
                    >
                        ✨ Generate Outfit
                    </button>

                </form>

            </div>

        </div>

    </div>


    <?php if ($error_message !== ""): ?>

        <div class="row justify-content-center mt-4">

            <div class="col-lg-8">

                <div class="alert alert-danger">

                    <strong>Error:</strong>

                    <?php
                    echo htmlspecialchars(
                        $error_message
                    );
                    ?>

                </div>

            </div>

        </div>

    <?php endif; ?>


    <?php if (!empty($recommended_items)): ?>


        <!-- RESULT -->

        <div class="mt-5">


            <div class="text-center mb-4">

                <h2 class="fw-bold">

                    <?php
                    echo htmlspecialchars(
                        $outfit_name
                    );
                    ?>

                </h2>


                <p class="text-muted">
                    Selected from your real wardrobe using your preferences and current weather.
                </p>

            </div>


            <div class="row g-4 justify-content-center">


                <?php foreach ($recommended_items as $item): ?>

                    <div class="col-12 col-md-6 col-lg-4">

                        <div class="outfit-card">


                            <img
                                src="<?php
                                echo htmlspecialchars(
                                    $item["image"]
                                );
                                ?>"
                                alt="<?php
                                echo htmlspecialchars(
                                    $item["name"]
                                );
                                ?>"
                                class="outfit-image"
                            >


                            <div class="p-4">


                                <span class="role-badge">

                                    <?php
                                    echo htmlspecialchars(
                                        $item["role"]
                                    );
                                    ?>

                                </span>


                                <h4 class="fw-bold">

                                    <?php
                                    echo htmlspecialchars(
                                        $item["name"]
                                    );
                                    ?>

                                </h4>


                                <?php if (!empty($item["color"])): ?>

                                    <p class="mb-1">

                                        <strong>
                                            Color:
                                        </strong>

                                        <?php
                                        echo htmlspecialchars(
                                            $item["color"]
                                        );
                                        ?>

                                    </p>

                                <?php endif; ?>


                                <?php if (!empty($item["style"])): ?>

                                    <p class="mb-1">

                                        <strong>
                                            Style:
                                        </strong>

                                        <?php
                                        echo htmlspecialchars(
                                            $item["style"]
                                        );
                                        ?>

                                    </p>

                                <?php endif; ?>


                                <?php if (!empty($item["occasion"])): ?>

                                    <p class="mb-0">

                                        <strong>
                                            Suitable for:
                                        </strong>

                                        <?php
                                        echo htmlspecialchars(
                                            $item["occasion"]
                                        );
                                        ?>

                                    </p>

                                <?php endif; ?>


                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>


            </div>


            <div class="result-box mt-4">

                <h5 class="fw-bold">
                    💡 Why this outfit?
                </h5>


                <p class="mb-0">

                    <?php
                    echo nl2br(
                        htmlspecialchars(
                            $response_text
                        )
                    );
                    ?>

                </p>

            </div>

        </div>

    <?php endif; ?>


</div>


</body>

</html>