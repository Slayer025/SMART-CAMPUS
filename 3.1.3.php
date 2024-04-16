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

        // Sanitize and retrieve other form data
        $principalInvestigator = mysqli_real_escape_string($conn, $_POST["principalInvestigator"]);
        $projectStart = mysqli_real_escape_string($conn, $_POST["projectStart"]);
        $projectEnd = mysqli_real_escape_string($conn, $_POST["projectEnd"]);
        $researchProject = mysqli_real_escape_string($conn, $_POST["researchProject"]);
        $amountReceived = mysqli_real_escape_string($conn, $_POST["amountReceived"]);
        $fundingAgency = mysqli_real_escape_string($conn, $_POST["fundingAgency"]);
        $yearOfSanction = mysqli_real_escape_string($conn, $_POST["yearOfSanction"]);
        $department = mysqli_real_escape_string($conn, $_POST["department"]);

        // File upload handling
        $file_name = $_FILES['file']['name'];

        // Get the current year
        $current_year = date('Y');

        // Append current year to the file name
        $file_name_with_year = $current_year . "_" . $file_name;

        // Destination path with appended year
        $file_destination = "C:/xampp/htdocs/login-register-main/UPLOAD/" . $researchProject . "_" . $file_name_with_year;

        if (empty($principalInvestigator) || empty($projectStart) || empty($projectEnd) || empty($researchProject) || empty($amountReceived) || empty($fundingAgency) || empty($yearOfSanction) || empty($department) || empty($file_name)) {
            echo "<div class='alert alert-danger'>All fields are required.</div>";
        } else {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $file_destination)) {
                $sql = "INSERT INTO 2second (principalInvestigator, projectStart, projectEnd, researchProject, amountReceived, fundingAgency, yearOfSanction, department , file_name, username) VALUES ('$principalInvestigator', '$projectStart', '$projectEnd', '$researchProject', '$amountReceived', '$fundingAgency', '$yearOfSanction', '$department', '$file_name', '$username')";

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
    <title>Resource Mobilization for Research</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="forms.css/3.css">
</head>

<body>
    <div class="container">
        <form action="3.1.3.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="principalInvestigator">Name of the Principal Investigator:</label>
                <input type="text" class="form-control" name="principalInvestigator" required>
            </div>
            <div class="form-group">
                <label for="projectStart">Project Start Date:</label>
                <input type="date" class="form-control" name="projectStart" required>
            </div>
            <div class="form-group">
                <label for="projectEnd">Project End Date:</label>
                <input type="date" class="form-control" name="projectEnd" required>
            </div>
            <div class="form-group">
                <label for="researchProject">Name of the Research Project:</label>
                <input type="text" class="form-control" name="researchProject" required>
            </div>
            <div class="form-group">
                <label for="amountReceived">Amount/Fund Received:</label>
                <input type="number" class="form-control" name="amountReceived" required>
            </div>
            <div class="form-group">
                <label for="fundingAgency">Name of Funding Agency:</label>
                <input type="text" class="form-control" name="fundingAgency" required>
            </div>
            <div class="form-group">
                <label for="yearOfSanction">Year of Sanction:</label>
                <input type="number" class="form-control" name="yearOfSanction" placeholder="YYYY" required>
            </div>
            <div class="form-group">
                <label for="department">Department of Recipient:</label><br>
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
                    <input class="form-check-input" type="radio" name="department" id="mechRadio" value="MECH" required>
                    <label class="form-check-label" for="mechRadio">MECH</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="department" id="extcRadio" value="EXTC" required>
                    <label class="form-check-label" for="extcRadio">EXTC</label>
                </div>
            </div>
            <div class="form-group">
                <label for="file">Upload File:</label>
                <input type="file" class="form-control" name="file" required>
            </div>
            <div class="form-btn">
                <input type="submit" class="btnn" value="Submit" name="submit">
            </div>
        </form>
    </div>
</body>

</html>