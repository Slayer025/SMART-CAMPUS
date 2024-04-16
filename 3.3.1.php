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
    // Check if the username session variable is set
    if (isset($_SESSION["user"])) {
        // Retrieve the username from the session
        $username = $_SESSION["user"];

        $codeOfEthicsURL = mysqli_real_escape_string($conn, $_POST["codeOfEthicsURL"]);
        $accessToPlagiarismSoftware = mysqli_real_escape_string($conn, $_POST["accessToPlagiarismSoftware"]);
        $mechanismForPlagiarism = mysqli_real_escape_string($conn, $_POST["mechanismForPlagiarism"]);

        // File upload handling
        $file_name = $_FILES['file']['name'];

        // Get the current year
        $current_year = date('Y');

        // Append current year to the file name
        $file_name_with_year = $current_year . "_" . $file_name;

        // Destination path with appended year
        $file_destination = "C:/xampp/htdocs/login-register-main/UPLOAD/" . $mechanismForPlagiarism . "_" . $file_name_with_year;

        if (empty ($codeOfEthicsURL) || empty ($accessToPlagiarismSoftware) || empty ($mechanismForPlagiarism) || empty ($file_name)) {
            echo "<div class='alert alert-danger'>All fields are required.</div>";
        } else {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $file_destination)) {
                $sql = "INSERT INTO 4fourth (codeOfEthicsURL, accessToPlagiarismSoftware, mechanismForPlagiarism, file_name, username) VALUES ('$codeOfEthicsURL', '$accessToPlagiarismSoftware',  '$mechanismForPlagiarism', '$file_name', '$username')";

                if (mysqli_query($conn, $sql)) {
                    echo "<div class='alert alert-success'>Data submitted successfully!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Error uploading file.</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Session user not set. Please log in again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Title</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="forms.css/3.css">

</head>

<body>
    <div class="container">
        <form action="3.3.1.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="codeOfEthicsURL">Provide upload the URL having code of ethics:</label>
                <input type="url" class="form-control" name="codeOfEthicsURL" required>
            </div>
            <div class="form-group">
                <label>Whether Colleges have been provided access to plagiarism detecting software:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="accessToPlagiarismSoftware" id="yesRadio"
                        value="Yes" required>
                    <label class="form-check-label" for="yesRadio">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="accessToPlagiarismSoftware" id="noRadio"
                        value="No" required>
                    <label class="form-check-label" for="noRadio">No</label>
                </div>
            </div>
            <div class="form-group">
                <label for="mechanismForPlagiarism">Mechanism for detecting plagiarism:</label>
                <textarea class="form-control" name="mechanismForPlagiarism" rows="3" required></textarea>
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