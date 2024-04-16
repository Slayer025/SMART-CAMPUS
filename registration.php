<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <style>
              body {
            background-image: url("images/collegepic.webp.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            filter: drop-shadow(10%);
            size-adjust: 100%;
            margin: 0;
            background-attachment: fixed;
            /* Add this line to make the background image fixed */
        }


        .container {
            width: fit-content;
            height: fit-content;
            margin: 0 auto;
            padding: 20px;
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            /* Add this line to move the container to the left */
            margin-top: 40px;
            margin-left: 30px;
            border-radius: 6px;
            background-color: #e3e3e3cb;
            backdrop-filter: blur(10px);
        }

        .form-control {
            width: 330px;
            height: fit-content;
            margin: 0 auto;
            padding: 10px;
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            /* Add this line to move the container to the left */
            margin-top: 10px;
            border-radius: 6px;
            background-color: #e3e3e3a2;
            backdrop-filter: blur(10px);
        }
    </style>

</head>

<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
            $id = $_POST["id"];
            $first_name = $_POST["first_name"];
            $middle_name = $_POST["middle_name"];
            $surname = $_POST["surname"];
            $idap_name = $_POST["idap_user"];
            $dept = $_POST["dept"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["repeat_password"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();

            if (empty($id) or empty($first_name) or empty($surname) or empty($idap_name) or empty($password) or empty($dept) or empty($email) or empty($password) or empty($passwordRepeat)) {
                array_push($errors, "All fields are required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            if (strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 charactes long");
            }
            if ($password !== $passwordRepeat) {
                array_push($errors, "Password does not match");
            }
            require_once "database.php";
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0) {
                array_push($errors, "Email already exists!");
            }
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {

                $sql = "INSERT INTO users (id, first_name, middle_name, surname, idap_user, dept, email, password) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )";
                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                if ($prepareStmt) {
                    mysqli_stmt_bind_param($stmt, "ssssssss", $id, $first_name, $middle_name, $surname, $idap_name, $dept, $email, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>You are registered successfully.</div>";
                } else {
                    die("Something went wrong");
                }
            }


        }
        ?>
        <form action="registration.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="id" placeholder="Emp ID:">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="first_name" placeholder="First Name:">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="middle_name" placeholder="Middle Name:">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="surname" placeholder=" Sur Name:">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="idap_user" placeholder="User Name:">
            </div>
            <div class="form-group">
                <label>Department of the Teacher:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="dept" id="feRadio" value="FE" required>
                    <label class="form-check-label" for="feRadio">FE</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="dept" id="compRadio" value="COMP" required>
                    <label class="form-check-label" for="compRadio">COMP</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="dept" id="itRadio" value="IT" required>
                    <label class="form-check-label" for="itRadio">IT</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="dept" id="mechRadio" value="MECH" required>
                    <label class="form-check-label" for="mechRadio">MECH</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="dept" id="extcRadio" value="EXTC" required>
                    <label class="form-check-label" for="extcRadio">EXTC</label>
                </div>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:">
            </div>
            <br>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
        <div>
            <hr>
            <div>
                <p>Already Registered? <a href="login.php">Login Here</a></p>
            </div>
        </div>
    </div>
</body>

</html>