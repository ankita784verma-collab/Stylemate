<<<<<<< HEAD
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>StyleMate - Your Personal Wardrobe</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="css/style.css">

    <style>

        /* =========================================
           GLOBAL
        ========================================= */

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #050505;
            color: #ffffff;
        }

        a {
            text-decoration: none;
        }

        
<!-- =========================================
     NAVBAR
========================================= -->

<header class="style-header">

    <div class="style-header-inner">

        <!-- LOGO -->
        <a href="index.php" class="style-logo">
            StyleMate
        </a>


        <!-- NAVIGATION -->
        <div class="style-nav-actions">

            <?php if (isset($_SESSION['user_id'])): ?>

                <a
                    href="dashboard.php"
                    class="style-nav-btn style-nav-outline"
                >
                    Dashboard
                </a>

                <a
                    href="logout.php"
                    class="style-nav-btn style-nav-filled"
                >
                    Logout
                </a>

            <?php else: ?>

                <a
                    href="login.php"
                    class="style-nav-btn style-nav-outline"
                >
                    Login
                </a>

                <a
                    href="register.php"
                    class="style-nav-btn style-nav-filled"
                >
                    Get Started
                </a>

            <?php endif; ?>

        </div>

    </div>

</header>


        /* =========================================
           HERO SLIDER
        ========================================= */

        .fashion-slider {
            width: 100%;
            background: #050505;
        }

        #styleMateCarousel {
            width: 100%;
            height: 570px;
            overflow: hidden;
            position: relative;
        }

        .carousel-inner,
        .carousel-item {
            height: 100%;
        }

        .slider-image {
            width: 100%;
            height: 570px;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .slider-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(
                    90deg,
                    rgba(0,0,0,0.72) 0%,
                    rgba(0,0,0,0.35) 45%,
                    rgba(0,0,0,0.50) 100%
                );
            z-index: 1;
        }


        /* =========================================
           HERO TEXT
        ========================================= */

        .custom-caption {
            position: absolute;
            z-index: 2;

            left: 50%;
            top: 50%;

            transform: translate(-50%, -50%);

            width: min(850px, 90%);
            right: auto;
            bottom: auto;

            text-align: center;
        }

        .slider-tag {
            display: inline-block;
            margin-bottom: 18px;

            padding: 8px 15px;

            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 50px;

            color: #ffffff;

            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;

            background: rgba(0,0,0,0.25);
            backdrop-filter: blur(5px);
        }

        .custom-caption h1 {
            margin: 0;

            color: #ffffff;

            font-size: clamp(42px, 5vw, 70px);
            line-height: 1.02;

            font-weight: 800;
            letter-spacing: -2px;

            text-shadow: 0 4px 20px rgba(0,0,0,0.4);
        }

        .custom-caption h1 span {
            color: #d8b47a;
        }

        .custom-caption p {
            max-width: 650px;

            margin: 24px auto 30px;

            color: #f1f1f1;

            font-size: 18px;
            line-height: 1.6;

            text-shadow: 0 2px 8px rgba(0,0,0,0.5);
        }


        /* =========================================
           HERO BUTTON
        ========================================= */

        .hero-btn {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            min-width: 210px;
            min-height: 52px;

            padding: 0 28px;

            background: #ffffff;
            color: #000000 !important;

            border: 2px solid #ffffff;
            border-radius: 7px;

            font-size: 16px;
            font-weight: 800;

            box-shadow: 0 10px 30px rgba(0,0,0,0.30);

            transition: all 0.25s ease;
        }

        .hero-btn:hover {
            background: #d8b47a;
            border-color: #d8b47a;

            color: #000000 !important;

            transform: translateY(-3px);

            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }


        /* =========================================
           SLIDER ARROWS
        ========================================= */

        .carousel-control-prev,
        .carousel-control-next {
            width: 70px;
            z-index: 5;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 48px;
            height: 48px;

            border-radius: 50%;

            background-color: rgba(0,0,0,0.45);
            background-size: 45%;

            border: 1px solid rgba(255,255,255,0.35);

            transition: 0.25s ease;
        }

        .carousel-control-prev-icon:hover,
        .carousel-control-next-icon:hover {
            background-color: rgba(255,255,255,0.2);
        }


        /* =========================================
           SLIDER DOTS
        ========================================= */

        .carousel-indicators {
            z-index: 6;
            bottom: 22px;
        }

        .carousel-indicators button {
            width: 35px;
            height: 3px;

            margin: 0 4px;

            border: none;
            border-radius: 5px;

            background-color: rgba(255,255,255,0.5);

            opacity: 1;
        }

        .carousel-indicators .active {
            background-color: #ffffff;
        }


        /* =========================================
           ABOUT SECTION
        ========================================= */

        .intro-section {
            padding: 100px 0;
            background: #050505;
            border-top: 1px solid #1e1e1e;
        }

        .feature-number {
            display: inline-block;

            margin-bottom: 15px;

            color: #d8b47a;

            font-size: 12px;
            font-weight: 800;

            letter-spacing: 2px;
        }

        .intro-section h2 {
            margin: 0;

            color: #ffffff;

            font-size: clamp(38px, 5vw, 58px);
            line-height: 1.05;

            font-weight: 700;
            letter-spacing: -2px;
        }

        .intro-section > .container > .row > .col-lg-6:first-child p {
            max-width: 560px;

            color: #999;

            font-size: 17px;
            line-height: 1.8;
        }


        /* =========================================
           FEATURE CARDS
        ========================================= */

        .feature-card {
            height: 100%;

            padding: 30px;

            background: #101010;

            border: 1px solid #242424;
            border-radius: 12px;

            transition: all 0.3s ease;
        }

        .feature-card:hover {
            border-color: #555;
            transform: translateY(-5px);

            background: #151515;
        }

        .feature-card h4 {
            margin: 0 0 12px;

            color: #ffffff;

            font-size: 22px;
            font-weight: 700;
        }

        .feature-card p {
            margin: 0;

            color: #8f8f8f;

            font-size: 15px;
            line-height: 1.7;
        }


        /* =========================================
           FOOTER
        ========================================= */

        .style-footer {
            padding: 45px 0;

            background: #000000;

            border-top: 1px solid #202020;

            text-align: center;
        }

        .footer-logo {
            margin-bottom: 8px;

            color: #ffffff;

            font-size: 22px;
            font-weight: 800;
        }

        .style-footer p {
            margin-bottom: 12px;

            color: #777;

            font-size: 14px;
        }

        .style-footer small {
            color: #555;
            font-size: 12px;
        }


        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 992px) {

            .style-navbar {
                height: 72px;
            }

            #styleMateCarousel,
            .slider-image {
                height: 520px;
            }

            .custom-caption {
                width: 82%;
            }

            .custom-caption h1 {
                font-size: 48px;
            }

        }


        @media (max-width: 768px) {

            .style-navbar {
                height: auto;
                min-height: 70px;
                padding: 12px 0;
            }

            .style-logo {
                font-size: 24px;
            }

            .nav-btn {
                min-width: auto;
                height: 38px;
                padding: 0 13px;
                font-size: 13px;
            }

            #styleMateCarousel,
            .slider-image {
                height: 500px;
            }

            .custom-caption {
                width: 82%;
            }

            .slider-tag {
                font-size: 10px;
                padding: 7px 11px;
                letter-spacing: 1.5px;
            }

            .custom-caption h1 {
                font-size: 42px;
                letter-spacing: -1px;
            }

            .custom-caption p {
                font-size: 15px;
                line-height: 1.5;
                margin: 18px auto 24px;
            }

            .hero-btn {
                min-width: 190px;
                min-height: 48px;
                font-size: 14px;
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 50px;
            }

            .carousel-control-prev-icon,
            .carousel-control-next-icon {
                width: 38px;
                height: 38px;
            }

            .intro-section {
                padding: 70px 0;
            }

            .intro-section h2 {
                font-size: 40px;
            }

        }


        @media (max-width: 480px) {

            .style-logo {
                font-size: 22px;
            }

            .nav-btn {
                padding: 0 10px;
                font-size: 12px;
            }

            .nav-btn.me-2 {
                margin-right: 5px !important;
            }

            #styleMateCarousel,
            .slider-image {
                height: 480px;
            }

            .custom-caption {
                width: 78%;
            }

            .custom-caption h1 {
                font-size: 36px;
            }

            .custom-caption p {
                font-size: 14px;
            }

            .hero-btn {
                min-width: 175px;
                min-height: 46px;
            }

            .feature-card {
                padding: 24px;
            }

        }

    </style>

</head>


<body>


<!-- =========================================
     NAVBAR
========================================= -->

<nav class="navbar style-navbar">

    <div class="container">

        <a
            class="navbar-brand style-logo"
            href="index.php"
        >
            StyleMate
        </a>


        <div class="ms-auto d-flex align-items-center">

            <?php if (isset($_SESSION['user_id'])): ?>

                <a
                    href="dashboard.php"
                    class="nav-btn nav-btn-outline me-2"
                >
                    Dashboard
                </a>

                <a
                    href="logout.php"
                    class="nav-btn nav-btn-white"
                >
                    Logout
                </a>

            <?php else: ?>

                <a
                    href="login.php"
                    class="nav-btn nav-btn-outline me-2"
                >
                    Login
                </a>

                <a
                    href="register.php"
                    class="nav-btn nav-btn-white"
                >
                    Get Started
                </a>

            <?php endif; ?>

        </div>

    </div>

</nav>



<!-- =========================================
     FASHION SLIDER
========================================= -->

<section class="fashion-slider">

    <div
        id="styleMateCarousel"
        class="carousel slide carousel-fade"
        data-bs-ride="carousel"
        data-bs-interval="4500"
    >


        <div class="carousel-inner">


            <!-- SLIDE 1 -->

            <div class="carousel-item active">

                <img
                    src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1800&q=85"
                    class="slider-image"
                    alt="Fashion clothing"
                >

                <div class="slider-overlay"></div>


                <div class="carousel-caption custom-caption">

                    <span class="slider-tag">
                        PERSONAL WARDROBE ASSISTANT
                    </span>

                    <h1>
                        Your wardrobe.
                        <br>
                        <span>Your style.</span>
                    </h1>

                    <p>
                        Organize your clothes and create better outfits
                        from what you already own.
                    </p>


                    <?php if (isset($_SESSION['user_id'])): ?>

                        <a
                            href="dashboard.php"
                            class="hero-btn"
                        >
                            Open My Wardrobe
                        </a>

                    <?php else: ?>

                        <a
                            href="register.php"
                            class="hero-btn"
                        >
                            Start Creating
                        </a>

                    <?php endif; ?>

                </div>

            </div>



            <!-- SLIDE 2 -->

            <div class="carousel-item">

                <img
                    src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=1800&q=85"
                    class="slider-image"
                    alt="Fashion style"
                >

                <div class="slider-overlay"></div>


                <div class="carousel-caption custom-caption">

                    <span class="slider-tag">
                        DIGITAL WARDROBE
                    </span>

                    <h1>
                        Everything
                        <br>
                        <span>in one place.</span>
                    </h1>

                    <p>
                        Upload your clothes, organize your wardrobe
                        and easily find what you want to wear.
                    </p>


                    <?php if (isset($_SESSION['user_id'])): ?>

                        <a
                            href="wardrobe.php"
                            class="hero-btn"
                        >
                            View My Wardrobe
                        </a>

                    <?php else: ?>

                        <a
                            href="register.php"
                            class="hero-btn"
                        >
                            Create My Wardrobe
                        </a>

                    <?php endif; ?>

                </div>

            </div>



            <!-- SLIDE 3 -->

            <div class="carousel-item">

                <img
                    src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=1800&q=85"
                    class="slider-image"
                    alt="Personal fashion style"
                >

                <div class="slider-overlay"></div>


                <div class="carousel-caption custom-caption">

                    <span class="slider-tag">
                        AI OUTFIT ASSISTANT
                    </span>

                    <h1>
                        Dress better.
                        <br>
                        <span>Every day.</span>
                    </h1>

                    <p>
                        Get outfit suggestions based on your own
                        wardrobe, occasion and personal style.
                    </p>


                    <?php if (isset($_SESSION['user_id'])): ?>

                        <a
                            href="outfit.php"
                            class="hero-btn"
                        >
                            Create An Outfit
                        </a>

                    <?php else: ?>

                        <a
                            href="register.php"
                            class="hero-btn"
                        >
                            Get Started
                        </a>

                    <?php endif; ?>

                </div>

            </div>

        </div>



        <!-- PREVIOUS -->

        <button
            class="carousel-control-prev"
            type="button"
            data-bs-target="#styleMateCarousel"
            data-bs-slide="prev"
        >

            <span class="carousel-control-prev-icon"></span>

            <span class="visually-hidden">
                Previous
            </span>

        </button>



        <!-- NEXT -->

        <button
            class="carousel-control-next"
            type="button"
            data-bs-target="#styleMateCarousel"
            data-bs-slide="next"
        >

            <span class="carousel-control-next-icon"></span>

            <span class="visually-hidden">
                Next
            </span>

        </button>



        <!-- DOTS -->

        <div class="carousel-indicators">

            <button
                type="button"
                data-bs-target="#styleMateCarousel"
                data-bs-slide-to="0"
                class="active"
                aria-current="true"
                aria-label="Slide 1"
            ></button>

            <button
                type="button"
                data-bs-target="#styleMateCarousel"
                data-bs-slide-to="1"
                aria-label="Slide 2"
            ></button>

            <button
                type="button"
                data-bs-target="#styleMateCarousel"
                data-bs-slide-to="2"
                aria-label="Slide 3"
            ></button>

        </div>

    </div>

</section>



<!-- =========================================
     ABOUT STYLEMATE
========================================= -->

<section class="intro-section">

    <div class="container">

        <div class="row align-items-center g-5">


            <!-- LEFT -->

            <div class="col-lg-6">

                <span class="feature-number">
                    ABOUT STYLEMATE
                </span>

                <h2>
                    Your wardrobe,
                    <br>
                    made smarter.
                </h2>

                <p class="mt-4">
                    StyleMate is a personal digital wardrobe assistant
                    designed to make everyday styling simple.
                </p>

                <p>
                    Upload the clothes you already own, organize them
                    in one place and discover outfit combinations
                    based on your occasion and personal style.
                </p>

            </div>



            <!-- RIGHT -->

            <div class="col-lg-6">

                <div class="row g-3">


                    <div class="col-12 col-sm-6">

                        <div class="feature-card">

                            <span class="feature-number">
                                01
                            </span>

                            <h4>
                                Digital Wardrobe
                            </h4>

                            <p>
                                Keep your clothes organized by
                                category, color, style and season.
                            </p>

                        </div>

                    </div>



                    <div class="col-12 col-sm-6">

                        <div class="feature-card">

                            <span class="feature-number">
                                02
                            </span>

                            <h4>
                                Easy Styling
                            </h4>

                            <p>
                                Find suitable combinations from
                                the clothes you already own.
                            </p>

                        </div>

                    </div>



                    <div class="col-12">

                        <div class="feature-card">

                            <span class="feature-number">
                                03
                            </span>

                            <h4>
                                AI Outfit Assistant
                            </h4>

                            <p>
                                Get personalized outfit recommendations
                                based on your wardrobe, occasion and
                                preferred style.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =========================================
     FOOTER
========================================= -->

<footer class="style-footer">

    <div class="container">

        <div class="footer-logo">
            StyleMate
        </div>

        <p>
            Your personal wardrobe assistant.
        </p>

        <small>
            © 2026 StyleMate. All rights reserved.
        </small>

    </div>

</footer>



<!-- BOOTSTRAP JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>
```
=======
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>StyleMate - Your Personal Wardrobe</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="css/style.css">

    <style>

        /* =========================================
           GLOBAL
        ========================================= */

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #050505;
            color: #ffffff;
        }

        a {
            text-decoration: none;
        }

        
<!-- =========================================
     NAVBAR
========================================= -->

<header class="style-header">

    <div class="style-header-inner">

        <!-- LOGO -->
        <a href="index.php" class="style-logo">
            StyleMate
        </a>


        <!-- NAVIGATION -->
        <div class="style-nav-actions">

            <?php if (isset($_SESSION['user_id'])): ?>

                <a
                    href="dashboard.php"
                    class="style-nav-btn style-nav-outline"
                >
                    Dashboard
                </a>

                <a
                    href="logout.php"
                    class="style-nav-btn style-nav-filled"
                >
                    Logout
                </a>

            <?php else: ?>

                <a
                    href="login.php"
                    class="style-nav-btn style-nav-outline"
                >
                    Login
                </a>

                <a
                    href="register.php"
                    class="style-nav-btn style-nav-filled"
                >
                    Get Started
                </a>

            <?php endif; ?>

        </div>

    </div>

</header>


        /* =========================================
           HERO SLIDER
        ========================================= */

        .fashion-slider {
            width: 100%;
            background: #050505;
        }

        #styleMateCarousel {
            width: 100%;
            height: 570px;
            overflow: hidden;
            position: relative;
        }

        .carousel-inner,
        .carousel-item {
            height: 100%;
        }

        .slider-image {
            width: 100%;
            height: 570px;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .slider-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(
                    90deg,
                    rgba(0,0,0,0.72) 0%,
                    rgba(0,0,0,0.35) 45%,
                    rgba(0,0,0,0.50) 100%
                );
            z-index: 1;
        }


        /* =========================================
           HERO TEXT
        ========================================= */

        .custom-caption {
            position: absolute;
            z-index: 2;

            left: 50%;
            top: 50%;

            transform: translate(-50%, -50%);

            width: min(850px, 90%);
            right: auto;
            bottom: auto;

            text-align: center;
        }

        .slider-tag {
            display: inline-block;
            margin-bottom: 18px;

            padding: 8px 15px;

            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 50px;

            color: #ffffff;

            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;

            background: rgba(0,0,0,0.25);
            backdrop-filter: blur(5px);
        }

        .custom-caption h1 {
            margin: 0;

            color: #ffffff;

            font-size: clamp(42px, 5vw, 70px);
            line-height: 1.02;

            font-weight: 800;
            letter-spacing: -2px;

            text-shadow: 0 4px 20px rgba(0,0,0,0.4);
        }

        .custom-caption h1 span {
            color: #d8b47a;
        }

        .custom-caption p {
            max-width: 650px;

            margin: 24px auto 30px;

            color: #f1f1f1;

            font-size: 18px;
            line-height: 1.6;

            text-shadow: 0 2px 8px rgba(0,0,0,0.5);
        }


        /* =========================================
           HERO BUTTON
        ========================================= */

        .hero-btn {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            min-width: 210px;
            min-height: 52px;

            padding: 0 28px;

            background: #ffffff;
            color: #000000 !important;

            border: 2px solid #ffffff;
            border-radius: 7px;

            font-size: 16px;
            font-weight: 800;

            box-shadow: 0 10px 30px rgba(0,0,0,0.30);

            transition: all 0.25s ease;
        }

        .hero-btn:hover {
            background: #d8b47a;
            border-color: #d8b47a;

            color: #000000 !important;

            transform: translateY(-3px);

            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }


        /* =========================================
           SLIDER ARROWS
        ========================================= */

        .carousel-control-prev,
        .carousel-control-next {
            width: 70px;
            z-index: 5;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 48px;
            height: 48px;

            border-radius: 50%;

            background-color: rgba(0,0,0,0.45);
            background-size: 45%;

            border: 1px solid rgba(255,255,255,0.35);

            transition: 0.25s ease;
        }

        .carousel-control-prev-icon:hover,
        .carousel-control-next-icon:hover {
            background-color: rgba(255,255,255,0.2);
        }


        /* =========================================
           SLIDER DOTS
        ========================================= */

        .carousel-indicators {
            z-index: 6;
            bottom: 22px;
        }

        .carousel-indicators button {
            width: 35px;
            height: 3px;

            margin: 0 4px;

            border: none;
            border-radius: 5px;

            background-color: rgba(255,255,255,0.5);

            opacity: 1;
        }

        .carousel-indicators .active {
            background-color: #ffffff;
        }


        /* =========================================
           ABOUT SECTION
        ========================================= */

        .intro-section {
            padding: 100px 0;
            background: #050505;
            border-top: 1px solid #1e1e1e;
        }

        .feature-number {
            display: inline-block;

            margin-bottom: 15px;

            color: #d8b47a;

            font-size: 12px;
            font-weight: 800;

            letter-spacing: 2px;
        }

        .intro-section h2 {
            margin: 0;

            color: #ffffff;

            font-size: clamp(38px, 5vw, 58px);
            line-height: 1.05;

            font-weight: 700;
            letter-spacing: -2px;
        }

        .intro-section > .container > .row > .col-lg-6:first-child p {
            max-width: 560px;

            color: #999;

            font-size: 17px;
            line-height: 1.8;
        }


        /* =========================================
           FEATURE CARDS
        ========================================= */

        .feature-card {
            height: 100%;

            padding: 30px;

            background: #101010;

            border: 1px solid #242424;
            border-radius: 12px;

            transition: all 0.3s ease;
        }

        .feature-card:hover {
            border-color: #555;
            transform: translateY(-5px);

            background: #151515;
        }

        .feature-card h4 {
            margin: 0 0 12px;

            color: #ffffff;

            font-size: 22px;
            font-weight: 700;
        }

        .feature-card p {
            margin: 0;

            color: #8f8f8f;

            font-size: 15px;
            line-height: 1.7;
        }


        /* =========================================
           FOOTER
        ========================================= */

        .style-footer {
            padding: 45px 0;

            background: #000000;

            border-top: 1px solid #202020;

            text-align: center;
        }

        .footer-logo {
            margin-bottom: 8px;

            color: #ffffff;

            font-size: 22px;
            font-weight: 800;
        }

        .style-footer p {
            margin-bottom: 12px;

            color: #777;

            font-size: 14px;
        }

        .style-footer small {
            color: #555;
            font-size: 12px;
        }


        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 992px) {

            .style-navbar {
                height: 72px;
            }

            #styleMateCarousel,
            .slider-image {
                height: 520px;
            }

            .custom-caption {
                width: 82%;
            }

            .custom-caption h1 {
                font-size: 48px;
            }

        }


        @media (max-width: 768px) {

            .style-navbar {
                height: auto;
                min-height: 70px;
                padding: 12px 0;
            }

            .style-logo {
                font-size: 24px;
            }

            .nav-btn {
                min-width: auto;
                height: 38px;
                padding: 0 13px;
                font-size: 13px;
            }

            #styleMateCarousel,
            .slider-image {
                height: 500px;
            }

            .custom-caption {
                width: 82%;
            }

            .slider-tag {
                font-size: 10px;
                padding: 7px 11px;
                letter-spacing: 1.5px;
            }

            .custom-caption h1 {
                font-size: 42px;
                letter-spacing: -1px;
            }

            .custom-caption p {
                font-size: 15px;
                line-height: 1.5;
                margin: 18px auto 24px;
            }

            .hero-btn {
                min-width: 190px;
                min-height: 48px;
                font-size: 14px;
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 50px;
            }

            .carousel-control-prev-icon,
            .carousel-control-next-icon {
                width: 38px;
                height: 38px;
            }

            .intro-section {
                padding: 70px 0;
            }

            .intro-section h2 {
                font-size: 40px;
            }

        }


        @media (max-width: 480px) {

            .style-logo {
                font-size: 22px;
            }

            .nav-btn {
                padding: 0 10px;
                font-size: 12px;
            }

            .nav-btn.me-2 {
                margin-right: 5px !important;
            }

            #styleMateCarousel,
            .slider-image {
                height: 480px;
            }

            .custom-caption {
                width: 78%;
            }

            .custom-caption h1 {
                font-size: 36px;
            }

            .custom-caption p {
                font-size: 14px;
            }

            .hero-btn {
                min-width: 175px;
                min-height: 46px;
            }

            .feature-card {
                padding: 24px;
            }

        }

    </style>

</head>


<body>


<!-- =========================================
     NAVBAR
========================================= -->

<nav class="navbar style-navbar">

    <div class="container">

        <a
            class="navbar-brand style-logo"
            href="index.php"
        >
            StyleMate
        </a>


        <div class="ms-auto d-flex align-items-center">

            <?php if (isset($_SESSION['user_id'])): ?>

                <a
                    href="dashboard.php"
                    class="nav-btn nav-btn-outline me-2"
                >
                    Dashboard
                </a>

                <a
                    href="logout.php"
                    class="nav-btn nav-btn-white"
                >
                    Logout
                </a>

            <?php else: ?>

                <a
                    href="login.php"
                    class="nav-btn nav-btn-outline me-2"
                >
                    Login
                </a>

                <a
                    href="register.php"
                    class="nav-btn nav-btn-white"
                >
                    Get Started
                </a>

            <?php endif; ?>

        </div>

    </div>

</nav>



<!-- =========================================
     FASHION SLIDER
========================================= -->

<section class="fashion-slider">

    <div
        id="styleMateCarousel"
        class="carousel slide carousel-fade"
        data-bs-ride="carousel"
        data-bs-interval="4500"
    >


        <div class="carousel-inner">


            <!-- SLIDE 1 -->

            <div class="carousel-item active">

                <img
                    src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1800&q=85"
                    class="slider-image"
                    alt="Fashion clothing"
                >

                <div class="slider-overlay"></div>


                <div class="carousel-caption custom-caption">

                    <span class="slider-tag">
                        PERSONAL WARDROBE ASSISTANT
                    </span>

                    <h1>
                        Your wardrobe.
                        <br>
                        <span>Your style.</span>
                    </h1>

                    <p>
                        Organize your clothes and create better outfits
                        from what you already own.
                    </p>


                    <?php if (isset($_SESSION['user_id'])): ?>

                        <a
                            href="dashboard.php"
                            class="hero-btn"
                        >
                            Open My Wardrobe
                        </a>

                    <?php else: ?>

                        <a
                            href="register.php"
                            class="hero-btn"
                        >
                            Start Creating
                        </a>

                    <?php endif; ?>

                </div>

            </div>



            <!-- SLIDE 2 -->

            <div class="carousel-item">

                <img
                    src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=1800&q=85"
                    class="slider-image"
                    alt="Fashion style"
                >

                <div class="slider-overlay"></div>


                <div class="carousel-caption custom-caption">

                    <span class="slider-tag">
                        DIGITAL WARDROBE
                    </span>

                    <h1>
                        Everything
                        <br>
                        <span>in one place.</span>
                    </h1>

                    <p>
                        Upload your clothes, organize your wardrobe
                        and easily find what you want to wear.
                    </p>


                    <?php if (isset($_SESSION['user_id'])): ?>

                        <a
                            href="wardrobe.php"
                            class="hero-btn"
                        >
                            View My Wardrobe
                        </a>

                    <?php else: ?>

                        <a
                            href="register.php"
                            class="hero-btn"
                        >
                            Create My Wardrobe
                        </a>

                    <?php endif; ?>

                </div>

            </div>



            <!-- SLIDE 3 -->

            <div class="carousel-item">

                <img
                    src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=1800&q=85"
                    class="slider-image"
                    alt="Personal fashion style"
                >

                <div class="slider-overlay"></div>


                <div class="carousel-caption custom-caption">

                    <span class="slider-tag">
                        AI OUTFIT ASSISTANT
                    </span>

                    <h1>
                        Dress better.
                        <br>
                        <span>Every day.</span>
                    </h1>

                    <p>
                        Get outfit suggestions based on your own
                        wardrobe, occasion and personal style.
                    </p>


                    <?php if (isset($_SESSION['user_id'])): ?>

                        <a
                            href="outfit.php"
                            class="hero-btn"
                        >
                            Create An Outfit
                        </a>

                    <?php else: ?>

                        <a
                            href="register.php"
                            class="hero-btn"
                        >
                            Get Started
                        </a>

                    <?php endif; ?>

                </div>

            </div>

        </div>



        <!-- PREVIOUS -->

        <button
            class="carousel-control-prev"
            type="button"
            data-bs-target="#styleMateCarousel"
            data-bs-slide="prev"
        >

            <span class="carousel-control-prev-icon"></span>

            <span class="visually-hidden">
                Previous
            </span>

        </button>



        <!-- NEXT -->

        <button
            class="carousel-control-next"
            type="button"
            data-bs-target="#styleMateCarousel"
            data-bs-slide="next"
        >

            <span class="carousel-control-next-icon"></span>

            <span class="visually-hidden">
                Next
            </span>

        </button>



        <!-- DOTS -->

        <div class="carousel-indicators">

            <button
                type="button"
                data-bs-target="#styleMateCarousel"
                data-bs-slide-to="0"
                class="active"
                aria-current="true"
                aria-label="Slide 1"
            ></button>

            <button
                type="button"
                data-bs-target="#styleMateCarousel"
                data-bs-slide-to="1"
                aria-label="Slide 2"
            ></button>

            <button
                type="button"
                data-bs-target="#styleMateCarousel"
                data-bs-slide-to="2"
                aria-label="Slide 3"
            ></button>

        </div>

    </div>

</section>



<!-- =========================================
     ABOUT STYLEMATE
========================================= -->

<section class="intro-section">

    <div class="container">

        <div class="row align-items-center g-5">


            <!-- LEFT -->

            <div class="col-lg-6">

                <span class="feature-number">
                    ABOUT STYLEMATE
                </span>

                <h2>
                    Your wardrobe,
                    <br>
                    made smarter.
                </h2>

                <p class="mt-4">
                    StyleMate is a personal digital wardrobe assistant
                    designed to make everyday styling simple.
                </p>

                <p>
                    Upload the clothes you already own, organize them
                    in one place and discover outfit combinations
                    based on your occasion and personal style.
                </p>

            </div>



            <!-- RIGHT -->

            <div class="col-lg-6">

                <div class="row g-3">


                    <div class="col-12 col-sm-6">

                        <div class="feature-card">

                            <span class="feature-number">
                                01
                            </span>

                            <h4>
                                Digital Wardrobe
                            </h4>

                            <p>
                                Keep your clothes organized by
                                category, color, style and season.
                            </p>

                        </div>

                    </div>



                    <div class="col-12 col-sm-6">

                        <div class="feature-card">

                            <span class="feature-number">
                                02
                            </span>

                            <h4>
                                Easy Styling
                            </h4>

                            <p>
                                Find suitable combinations from
                                the clothes you already own.
                            </p>

                        </div>

                    </div>



                    <div class="col-12">

                        <div class="feature-card">

                            <span class="feature-number">
                                03
                            </span>

                            <h4>
                                AI Outfit Assistant
                            </h4>

                            <p>
                                Get personalized outfit recommendations
                                based on your wardrobe, occasion and
                                preferred style.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =========================================
     FOOTER
========================================= -->

<footer class="style-footer">

    <div class="container">

        <div class="footer-logo">
            StyleMate
        </div>

        <p>
            Your personal wardrobe assistant.
        </p>

        <small>
            © 2026 StyleMate. All rights reserved.
        </small>

    </div>

</footer>



<!-- BOOTSTRAP JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>
```
>>>>>>> 44c54392920554fc489fa0b5fa2377643cfac17d
