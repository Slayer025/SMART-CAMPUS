<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["user"])) {
    // Redirect to the login page
    header("Location: login.php");
    exit(); // Stop further execution
}

require_once "database.php";

if (isset ($_POST["submit"])) {
    // Retrieve the username from the session
    $username = $_SESSION["user"];

    $phdScholarName = mysqli_real_escape_string($conn, $_POST["phdScholarName"]);
    $department = mysqli_real_escape_string($conn, $_POST["department"]);
    $guideName = mysqli_real_escape_string($conn, $_POST["guideName"]);
    $thesisTitle = mysqli_real_escape_string($conn, $_POST["thesisTitle"]);
    $yearOfRegistration = mysqli_real_escape_string($conn, $_POST["yearOfRegistration"]);
    $yearOfPhdAward = mysqli_real_escape_string($conn, $_POST["yearOfPhdAward"]);

    // File upload handling
    $file_name = $_FILES['file']['name'];

    // Get the current year
    $current_year = date('Y');

    // Append current year to the file name
    $file_name_with_year = $current_year . "_" . $file_name;

    // Destination path with appended year
    $file_destination = "C:/xampp/htdocs/login-register-main/UPLOAD/" . $thesisTitle . "_" . $file_name_with_year;

    if (empty ($phdScholarName) || empty ($department) || empty ($guideName) || empty ($thesisTitle) || empty ($yearOfRegistration) || empty ($yearOfPhdAward) || empty ($file_name)) {
        echo "<div class='alert alert-danger'>All fields are required.</div>";
    } else {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file_destination)) {
            $sql = "INSERT INTO 6sixth (phdScholarName, department, guideName, thesisTitle, yearOfRegistration, yearOfPhdAward, file_name, username) 
                    VALUES ('$phdScholarName', '$department', '$guideName', '$thesisTitle', '$yearOfRegistration', '$yearOfPhdAward', '$file_name', '$username')";

            if (mysqli_query($conn, $sql)) {
                echo "<div class='alert alert-success'>Data submitted successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhD Scholar Information Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="forms.css/3.css">

</head>

<body>
    <div class="container">
    <form action="3.3.3.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="phdScholarName">Name of the PhD Scholar:</label>
                <input type="text" class="form-control" name="phdScholarName" required>
            </div>
            <div class="form-group">
                <label>Department:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="department" id="feRadio" value="FE" required>
                    <label class="form-check-label" for="feRadio">FE</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="department" id="compRadio" value="COMP" required>
                    <label class="form-check-label" for="compRadio">COMP</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="department" id="itRadio" value="IT" required>
                    <label class="form-check-label" for="itRadio">IT</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="department" id="compRadio" value="MECH" required>
                    <label class="form-check-label" for="compRadio">MECH</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="department" id="extcRadio" value="EXTC" required>
                    <label class="form-check-label" for="extcRadio">EXTC</label>
                </div>
            </div>
            <div class="form-group">
                <label for="guideName">Name of the Guide/s:</label>
                <input type="text" class="form-control" name="guideName" required>
            </div>
            <div class="form-group">
                <label for="thesisTitle">Title of the Thesis:</label>
                <input type="text" class="form-control" name="thesisTitle" required>
            </div>
            <div class="form-group">
                <label for="yearOfRegistration">Year of Registration:</label>
                <input type="number" class="form-control" name="yearOfRegistration" placeholder="YYYY" required>
            </div>
            <div class="form-group">
                <label for="yearOfPhdAward">Year of Award of PhD:</label>
                <input type="number" class="form-control" name="yearOfPhdAward" placeholder="YYYY" required>
            </div>
            <div class="form-group">
                <label for="file">Upload File:</label>
                <input type="file" class="form-control" name="file" required>
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Submit" name="submit">
            </div>
        </form>
    </div>
</body>

</html>