<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit(); // Stop further execution
}

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    require_once "database.php";
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($user) {
        if (password_verify($password, $user["password"])) {
            // Set session to user's email
            $_SESSION["user"] = $user["email"]; 
            header("Location: index.php");
            exit(); // Stop further execution
        } else {
            echo "<div class='alert alert-danger'>Password does not match</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Email does not match</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="login.css"> <!-- Make sure login.css contains your CSS styles -->
    <style>
        /* Add CSS for overlay and loading animation */
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            text-align: center;
        }

        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50px;
            height: 50px;
            border: 5px solid #ccc;
            border-top-color: #333;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div id="overlay">
        <div class="loading"></div>
    </div>

    <div class="container">
        <form action="login.php" class="login" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter Email:" name="email" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter Password:" name="password" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Login" name="login" class="btn btn-primary" onclick="showOverlay()">
            </div>
        </form>
        <hr>
        <div>
            <p>Not yet registered? <a href="registration.php">Register Here</a></p>
        </div>
    </div>

    <script>
        function showOverlay() {
            // Show the overlay with the loading animation
            document.getElementById("overlay").style.display = "block";
        }
    </script>
</body>

</html>