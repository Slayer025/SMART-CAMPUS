<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["user"])) {
    // Redirect to the login page
    header("Location: login.php");
    exit(); // Stop further execution
}

require_once "database.php";

// Check if the form is submitted and the delete button is pressed
if (isset($_POST["delete"])) {
    // Retrieve the username from the session
    $username = $_SESSION["user"];
    // Retrieve the record ID to be deleted
    $recordId = $_POST["delete"];

    // Delete query for the specific record
    $sqlDelete = "DELETE FROM 1first WHERE username = ? AND id = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sqlDelete);

    if ($stmt) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "si", $username, $recordId);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='alert alert-success'>Record deleted successfully!</div>";
            // Redirect to prevent deletion on page refresh
            header("Refresh:2; url=".$_SERVER['PHP_SELF']);
            exit(); // Stop further execution
        } else {
            echo "<div class='alert alert-danger'>Error deleting data: " . mysqli_error($conn) . "</div>";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='alert alert-danger'>Error preparing delete statement.</div>";
    }
}

// Retrieve the username from the session
$username = $_SESSION["user"];

// Fetch data for the current user
$sql = "SELECT * FROM 1first WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "s", $username);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        // Fetch all rows of the result as an associative array
        $userData = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        echo "<div class='alert alert-danger'>Error retrieving data: " . mysqli_error($conn) . "</div>";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "<div class='alert alert-danger'>Error preparing select statement.</div>";
}

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify or Delete Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="forms.css/3.css">
</head>
<body>
    <div class="container">
        <h2>Modify or Delete Your Data</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Principal Investigator</th>
                    <th>Department</th>
                    <th>Year of Award</th>
                    <th>Funds Provided</th>
                    <th>Project Start</th>
                    <th>Project End</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userData as $data): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['projectName']); ?></td>
                        <td><?php echo htmlspecialchars($data['principalInvestigator']); ?></td>
                        <td><?php echo htmlspecialchars($data['department']); ?></td>
                        <td><?php echo htmlspecialchars($data['yearOfAward']); ?></td>
                        <td><?php echo htmlspecialchars($data['fundsProvided']); ?></td>
                        <td><?php echo htmlspecialchars($data['projectStart']); ?></td>
                        <td><?php echo htmlspecialchars($data['projectEnd']); ?></td>
                        <td>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <input type="hidden" name="delete" value="<?php echo $data['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
