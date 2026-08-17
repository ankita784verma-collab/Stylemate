<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - StyleMate</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="css/style.css">

</head>

<body class="dashboard-page">


<!-- =========================================
     HEADER
========================================= -->

<header class="style-header">

    <div class="style-header-inner">

        <a href="dashboard.php" class="style-logo">
            StyleMate
        </a>

        <div class="style-nav-actions">

            <span class="dashboard-user">
                Hi, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
            </span>

            <a
                href="logout.php"
                class="style-nav-btn style-nav-filled"
            >
                Logout
            </a>

        </div>

    </div>

</header>



<!-- =========================================
     DASHBOARD IMAGE SLIDER
========================================= -->

<section class="dashboard-slider">

    <div
        id="dashboardCarousel"
        class="carousel slide carousel-fade"
        data-bs-ride="carousel"
        data-bs-interval="4000"
    >

        <div class="carousel-inner">


            <!-- SLIDE 1 -->

            <div class="carousel-item active">

                <img
                    src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1800&q=85"
                    class="dashboard-slider-image"
                    alt="Fashion wardrobe"
                >

                <div class="dashboard-slider-overlay"></div>

                <div class="dashboard-slider-content">

                    <span>
                        YOUR DIGITAL WARDROBE
                    </span>

                    <h1>
                        Everything you own,
                        <br>
                        <strong>in one place.</strong>
                    </h1>

                    <p>
                        Keep your clothes organized and easily explore
                        everything available in your wardrobe.
                    </p>

                </div>

            </div>



            <!-- SLIDE 2 -->

            <div class="carousel-item">

                <img
                    src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=1800&q=85"
                    class="dashboard-slider-image"
                    alt="Personal fashion style"
                >

                <div class="dashboard-slider-overlay"></div>

                <div class="dashboard-slider-content">

                    <span>
                        PERSONAL STYLE
                    </span>

                    <h1>
                        Your clothes.
                        <br>
                        <strong>Your style.</strong>
                    </h1>

                    <p>
                        Build your personal style profile and let StyleMate
                        understand the way you like to dress.
                    </p>

                </div>

            </div>



            <!-- SLIDE 3 -->

            <div class="carousel-item">

                <img
                    src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=1800&q=85"
                    class="dashboard-slider-image"
                    alt="AI outfit styling"
                >

                <div class="dashboard-slider-overlay"></div>

                <div class="dashboard-slider-content">

                    <span>
                        AI STYLE ASSISTANT
                    </span>

                    <h1>
                        Get inspired.
                        <br>
                        <strong>Dress better.</strong>
                    </h1>

                    <p>
                        Get outfit recommendations using the clothes
                        already available in your wardrobe.
                    </p>

                </div>

            </div>

        </div>


        <!-- ARROWS -->

        <button
            class="carousel-control-prev dashboard-arrow"
            type="button"
            data-bs-target="#dashboardCarousel"
            data-bs-slide="prev"
        >
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button
            class="carousel-control-next dashboard-arrow"
            type="button"
            data-bs-target="#dashboardCarousel"
            data-bs-slide="next"
        >
            <span class="carousel-control-next-icon"></span>
        </button>


        <!-- DOTS -->

        <div class="carousel-indicators dashboard-indicators">

            <button
                type="button"
                data-bs-target="#dashboardCarousel"
                data-bs-slide-to="0"
                class="active"
            ></button>

            <button
                type="button"
                data-bs-target="#dashboardCarousel"
                data-bs-slide-to="1"
            ></button>

            <button
                type="button"
                data-bs-target="#dashboardCarousel"
                data-bs-slide-to="2"
            ></button>

        </div>

    </div>

</section>



<!-- =========================================
     DASHBOARD CONTENT
========================================= -->

<main class="dashboard-content">

    <div class="dashboard-heading">

        <span class="dashboard-label">
            STYLEMATE
        </span>

        <h2>
            Your wardrobe dashboard.
        </h2>

        <p>
            Manage your clothes, discover outfit ideas and personalize
            your style experience.
        </p>

    </div>



    <!-- DASHBOARD CARDS -->

    <div class="dashboard-grid">


        <!-- WARDROBE -->

        <div class="dashboard-card">

            <div class="dashboard-card-number">
                01
            </div>

            <div class="dashboard-card-icon">
                👗
            </div>

            <h3>
                My Wardrobe
            </h3>

            <p>
                Add, organize and manage the clothes you already own.
                Keep your digital wardrobe easy to explore.
            </p>

            <a
                href="wardrobe.php"
                class="dashboard-card-btn"
            >
                Open Wardrobe
                <span>→</span>
            </a>

        </div>



        <!-- AI ASSISTANT -->

        <div class="dashboard-card">

            <div class="dashboard-card-number">
                02
            </div>

            <div class="dashboard-card-icon">
                🤖
            </div>

            <h3>
                AI Style Assistant
            </h3>

            <p>
                Get personalized outfit recommendations based on
                your wardrobe, occasion and preferred style.
            </p>

            <a
                href="outfit.php"
                class="dashboard-card-btn"
            >
                Ask AI
                <span>→</span>
            </a>

        </div>



        <!-- STYLE PROFILE -->

        <div class="dashboard-card">

            <div class="dashboard-card-number">
                03
            </div>

            <div class="dashboard-card-icon">
                ✨
            </div>

            <h3>
                My Style Profile
            </h3>

            <p>
                Set your fashion preferences to make your outfit
                recommendations more personalized.
            </p>

            <a
                href="style_profiles.php"
                class="dashboard-card-btn"
            >
               Style Analytics
                <span>→</span>
            </a>

        </div>


    </div>

</main>



<!-- =========================================
     FOOTER
========================================= -->

<footer class="dashboard-footer">

    <div class="dashboard-footer-logo">
        StyleMate
    </div>

    <p>
        Your personal wardrobe assistant.
    </p>

    <small>
        © 2026 StyleMate. All rights reserved.
    </small>

</footer>



<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>