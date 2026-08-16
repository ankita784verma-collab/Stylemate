<?php

session_start();

require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id, name, password FROM users WHERE email = ?"
    );

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];

            header("Location: dashboard.php");
            exit();

        } else {

            $message = "Incorrect password.";

        }

    } else {

        $message = "Account not found.";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - StyleMate</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="container">

    <div class="row justify-content-center align-items-center min-vh-100">

        <div class="col-md-6 col-lg-5">

            <div class="bg-white p-5 rounded-4 shadow-sm">

                <h2 class="fw-bold mb-2">
                    Welcome back
                </h2>

                <p class="text-muted mb-4">
                    Login to your StyleMate wardrobe.
                </p>


                <?php if ($message != ""): ?>

                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($message); ?>
                    </div>

                <?php endif; ?>


                <form method="POST">

                    <div class="mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="mb-4">

                        <label class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        class="btn btn-dark w-100 py-2"
                    >
                        Login
                    </button>

                </form>


                <p class="text-center mt-4 mb-0">

                    Don't have an account?

                    <a href="register.php">
                        Create Account
                    </a>

                </p>

            </div>

        </div>

    </div>

</div>

</body>
</html>