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

        // Escape user inputs for security
        $slNo = mysqli_real_escape_string($conn, $_POST["slNo"]);
        $firstName = mysqli_real_escape_string($conn, $_POST["firstName"]);
        $lastName = mysqli_real_escape_string($conn, $_POST["lastName"]);
        $bookTitle = mysqli_real_escape_string($conn, $_POST["bookTitle"]);
        $paperTitle = mysqli_real_escape_string($conn, $_POST["paperTitle"]);
        $proceedingsTitle = mysqli_real_escape_string($conn, $_POST["proceedingsTitle"]);
        $conferenceName = mysqli_real_escape_string($conn, $_POST["conferenceName"]);
        $nationalOrInternational = mysqli_real_escape_string($conn, $_POST["nationalOrInternational"]);
        $yearOfPublication = mysqli_real_escape_string($conn, $_POST["yearOfPublication"]);
        $issnNumber = mysqli_real_escape_string($conn, $_POST["issnNumber"]);
        $affiliatingInstitute = mysqli_real_escape_string($conn, $_POST["affiliatingInstitute"]);
        $publisherName = mysqli_real_escape_string($conn, $_POST["publisherName"]);

        // File upload handling
        $file_name = $_FILES['file']['name'];

        // Get the current year
        $current_year = date('Y');

        // Append current year to the file name
        $file_name_with_year = $current_year . "_" . $file_name;

        // Destination path with appended year
        $file_destination = "C:/xampp/htdocs/login-register-main/UPLOAD/" . $paperTitle . "_" . $file_name_with_year;

        // Check for empty fields
        if (empty($slNo) || empty($firstName) || empty($lastName) || empty($bookTitle) || empty($paperTitle) || empty($proceedingsTitle) || empty($conferenceName) || empty($nationalOrInternational) || empty($yearOfPublication) || empty($issnNumber) || empty($affiliatingInstitute) || empty($publisherName) || empty($file_name)) {
            echo "<div class='alert alert-danger'>All fields are required.</div>";
        } else {
            // Move uploaded file to destination
            if (move_uploaded_file($_FILES['file']['tmp_name'], $file_destination)) {
                // SQL query to insert data into database
                $sql = "INSERT INTO 8eight (username, slNo, firstName, lastName, bookTitle, paperTitle, proceedingsTitle, conferenceName, nationalOrInternational, yearOfPublication, issnNumber, affiliatingInstitute, publisherName, file_name) VALUES ('$username', '$slNo', '$firstName', '$lastName', '$bookTitle', '$paperTitle', '$proceedingsTitle', '$conferenceName', '$nationalOrInternational', '$yearOfPublication', '$issnNumber', '$affiliatingInstitute', '$publisherName', '$file_name')";

                // Execute SQL query
                if (mysqli_query($conn, $sql)) {
                    echo "<div class='alert alert-success'>Data submitted successfully!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
                }
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
    <title>Book and Paper Information Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="forms.css/3.css">

</head>

<body>
    <div class="container">
        <form action="3.3.5.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="slNo">Sl. No.:</label>
                <input type="text" class="form-control" name="slNo">
            </div>
            <div class="form-group">
                <label for="firstName">First Name of the Teacher:</label>
                <input type="text" class="form-control" name="firstName">
            </div>
            <div class="form-group">
                <label for="lastName">Last Name of the Teacher:</label>
                <input type="text" class="form-control" name="lastName">
            </div>
            <div class="form-group">
                <label for="bookTitle">Title of the Book/Chapters Published:</label>
                <input type="text" class="form-control" name="bookTitle">
            </div>
            <div class="form-group">
                <label for="paperTitle">Title of the Paper:</label>
                <input type="text" class="form-control" name="paperTitle">
            </div>
            <div class="form-group">
                <label for="proceedingsTitle">Title of the Proceedings of the Conference:</label>
                <input type="text" class="form-control" name="proceedingsTitle">
            </div>
            <div class="form-group">
                <label for="conferenceName">Name of the Conference (National/International):</label>
                <input type="text" class="form-control" name="conferenceName">
            </div>
            <div class="form-group">
                <label>National/International:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="nationalOrInternational" id="nationalRadio"
                        value="National" required>
                    <label class="form-check-label" for="nationalRadio">National</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="nationalOrInternational" id="internationalRadio"
                        value="International" required>
                    <label class="form-check-label" for="internationalRadio">International</label>
                </div>
            </div>
            <div class="form-group">
                <label for="yearOfPublication">Year of Publication:</label>
                <input type="number" class="form-control" name="yearOfPublication" placeholder="YYYY" >
            </div>
            <div class="form-group">
                <label for="issnNumber">ISBN/ISSN Number of the Proceeding:</label>
                <input type="text" class="form-control" name="issnNumber">
            </div>
            <div class="form-group">
                <label for="affiliatingInstitute">Affiliating Institute at the Time of Publication:</label>
                <input type="text" class="form-control" name="affiliatingInstitute">
            </div>
            <div class="form-group">
                <label for="publisherName">Name of the Publisher:</label>
                <input type="text" class="form-control" name="publisherName">
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