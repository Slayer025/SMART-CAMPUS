<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["user"])) {
    // Redirect to the login page
    header("Location: login.php");
    exit(); // Stop further execution
}
require_once "database.php";

if (isset($_POST["submit"])) {
    // Retrieve the username from the session
    $username = $_SESSION["user"];

    $titleOfPaper = mysqli_real_escape_string($conn, $_POST["titleOfPaper"]);
    $authorName = mysqli_real_escape_string($conn, $_POST["authorName"]);
    $teacherDepartment = mysqli_real_escape_string($conn, $_POST["teacherDepartment"]);
    $journalName = mysqli_real_escape_string($conn, $_POST["journalName"]);
    $yearOfPublication = mysqli_real_escape_string($conn, $_POST["yearOfPublication"]);
    $issnNumber = mysqli_real_escape_string($conn, $_POST["issnNumber"]);

    // File upload handling
    $file_name = $_FILES['file']['name'];

    // Get the current year
    $current_year = date('Y');

    // Append current year to the file name
    $file_name_with_year = $current_year . "_" . $file_name;

    // Destination path with appended year
    $file_destination = "C:/xampp/htdocs/login-register-main/UPLOAD/" . $titleOfPaper . "_" . $file_name_with_year;

    if (empty($titleOfPaper) || empty($authorName) || empty($teacherDepartment) || empty($journalName) || empty($yearOfPublication) || empty($issnNumber)) {
        echo "<div class='alert alert-danger'>All fields are required.</div>";
    } else {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file_destination)) {
            $sql = "INSERT INTO 7seventh (titleOfPaper, authorName, teacherDepartment, journalName, yearOfPublication, issnNumber, file_name, username) 
                    VALUES ('$titleOfPaper', '$authorName', '$teacherDepartment', '$journalName', '$yearOfPublication' , '$issnNumber', '$file_name', '$username')";

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
    <title>Research Paper Information Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="forms.css/3.css">

</head>

<body>
    <div class="container">
    <form action="3.3.4.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titleOfPaper">Title of Paper:</label>
                <input type="text" class="form-control" name="titleOfPaper" required>
            </div>
            <div class="form-group">
                <label for="authorName">Name of the Author/s:</label>
                <input type="text" class="form-control" name="authorName" required>
            </div>
            <div class="form-group">
                <label>Department of the Teacher:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="teacherDepartment" id="feRadio" value="FE"
                        required>
                    <label class="form-check-label" for="feRadio">FE</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="teacherDepartment" id="compRadio" value="COMP"
                        required>
                    <label class="form-check-label" for="compRadio">COMP</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="teacherDepartment" id="itRadio" value="IT"
                        required>
                    <label class="form-check-label" for="itRadio">IT</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="teacherDepartment" id="mechRadio" value="MECH"
                        required>
                    <label class="form-check-label" for="mechRadio">MECH</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="teacherDepartment" id="extcRadio" value="EXTC"
                        required>
                    <label class="form-check-label" for="extcRadio">EXTC</label>
                </div>
            </div>
            <div class="form-group">
                <label for="journalName">Name of the Journal:</label>
                <input type="text" class="form-control" name="journalName" required>
            </div>
            <div class="form-group">
                <label for="yearOfPublication">Year of Publication:</label>
                <input type="number" class="form-control" name="yearOfPublication" placeholder="YYYY" required>
            </div>
            <div class="form-group">
                <label for="issnNumber">ISBN/ISSN Number:</label>
                <input type="text" class="form-control" name="issnNumber" required>
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