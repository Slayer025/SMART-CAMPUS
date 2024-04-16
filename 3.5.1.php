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
    // Check if the username session variable is set
    if (isset($_SESSION["user"])) {
        // Retrieve the username from the session
        $username = $_SESSION["user"];

        $slNo = mysqli_real_escape_string($conn, $_POST["slNo"]);
        $linkageTitle = mysqli_real_escape_string($conn, $_POST["linkageTitle"]);
        $partneringInstitution = mysqli_real_escape_string($conn, $_POST["partneringInstitution"]);
        $yearOfCommencement = mysqli_real_escape_string($conn, $_POST["yearOfCommencement"]);
        $durationFrom = mysqli_real_escape_string($conn, $_POST["durationFrom"]);
        $durationTo = mysqli_real_escape_string($conn, $_POST["durationTo"]);
        $natureOfLinkage = mysqli_real_escape_string($conn, $_POST["natureOfLinkage"]);

        // File upload handling
        $file_name = $_FILES['file']['name'];

        // Sanitize the filename to remove any special characters
        $file_name = preg_replace("/[^a-zA-Z0-9.]/", "_", $file_name);

        // Remove newline characters from the partnering institution name
        $partneringInstitution = str_replace(array("\r", "\n"), '', $partneringInstitution);

        // Get the current year
        $current_year = date('Y');

        // Append current year to the file name
        $file_name_with_year = $current_year . "_" . $file_name;

        // Destination path with appended year
        $file_destination = "C:/xampp/htdocs/login-register-main/UPLOAD/" . $partneringInstitution . "_" . $file_name_with_year;

        if (empty($slNo) || empty($linkageTitle) || empty($partneringInstitution) || empty($yearOfCommencement) || empty($durationFrom) || empty($durationTo) || empty($natureOfLinkage) || empty($file_name)) {
            echo "<div class='alert alert-danger'>All fields are required.</div>";
        } else {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $file_destination)) {
                $sql = "INSERT INTO 93twelve (username, slNo, linkageTitle, partneringInstitution, yearOfCommencement, durationFrom, durationTo, natureOfLinkage, file_name) 
                    VALUES ('$username', '$slNo', '$linkageTitle', '$partneringInstitution', '$yearOfCommencement', '$durationFrom', '$durationTo', '$natureOfLinkage', '$file_name')";

                if (mysqli_query($conn, $sql)) {
                    echo "<div class='alert alert-success'>Data submitted successfully!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Failed to move uploaded file.</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>User session not set.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linkage Information Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="forms.css/3.css">
</head>

<body>
    <div class="container">
        <form action="3.5.1.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="slNo">Sl. No.:</label>
                <input type="text" class="form-control" name="slNo" required>
            </div>
            <div class="form-group">
                <label for="linkageTitle">Title of the Linkage:</label>
                <input type="text" class="form-control" name="linkageTitle" required>
            </div>
            <div class="form-group">
                <label for="partneringInstitution">Name of the Partnering Institution/Industry/Research Lab with Contact
                    Details:</label>
                <textarea class="form-control" name="partneringInstitution" required></textarea>
            </div>
            <div class="form-group">
                <label for="yearOfCommencement">Year of Commencement:</label>
                <input type="number" class="form-control" name="yearOfCommencement" placeholder="YYYY" required>
            </div>
            <div class="form-group">
                <label for="durationFrom">Duration (From):</label>
                <input type="date" class="form-control" name="durationFrom" required>
            </div>
            <div class="form-group">
                <label for="durationTo">Duration (To):</label>
                <input type="date" class="form-control" name="durationTo" required>
            </div>
            <div class="form-group">
                <label for="natureOfLinkage">Nature of Linkage:</label>
                <input type="text" class="form-control" name="natureOfLinkage" required>
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