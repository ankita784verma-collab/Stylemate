<?php

session_start();

require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($name) || empty($email) || empty($password)) {

        $message = "Please fill all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email.";

    } elseif (strlen($password) < 6) {

        $message = "Password must contain at least 6 characters.";

    } else {

        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "Email already registered.";

        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
            );

            $stmt->bind_param(
                "sss",
                $name,
                $email,
                $hashed_password
            );

            if ($stmt->execute()) {

                $_SESSION["user_id"] = $stmt->insert_id;
                $_SESSION["user_name"] = $name;

                header("Location: dashboard.php");
                exit();

            } else {

                $message = "Registration failed. Please try again.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account - StyleMate</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="container">

    <div class="row justify-content-center align-items-center min-vh-100">

        <div class="col-md-6 col-lg-5">

            <div class="bg-white p-5 rounded-4 shadow-sm">

                <h2 class="fw-bold mb-2">
                    Create your wardrobe
                </h2>

                <p class="text-muted mb-4">
                    Start building your personal StyleMate wardrobe.
                </p>


                <?php if ($message != ""): ?>

                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($message); ?>
                    </div>

                <?php endif; ?>


                <form method="POST">

                    <div class="mb-3">

                        <label class="form-label">
                            Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            required
                        >

                    </div>


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
                        Create Account
                    </button>

                </form>


                <p class="text-center mt-4 mb-0">

                    Already have an account?

                    <a href="login.php">
                        Login
                    </a>

                </p>

            </div>

        </div>

    </div>

</div>

</body>
</html>