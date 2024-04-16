<?php
// Include the database connection file
require_once "database.php";

// Mapping of table IDs to user-friendly names
$tableNamesMap = array(
    "1first" => "3.1.1",
    "2second" => "3.1.3",
    "3third" => "3.2.2",
    "4fourth" => "3.3.1",
    "5fifth" => "3.3.2",
    "6sixth" => "3.3.3",
    "7seventh" => "3.3.4",
    "8eight" => "3.3.5",
    "9nine" => "3.4.2",
    "91ten" => "3.4.3",
    "92eleven" => "3.4.4",
    "93twelve" => "3.5.1",
    "94thirteen" => "3.5.2",
    "users" => "USERS"
);

// Function to fetch column names from a given table
function getColumnNames($tableName)
{
    global $conn;
    $sql = "SHOW COLUMNS FROM $tableName";
    $result = mysqli_query($conn, $sql);
    $columnNames = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $columnNames[] = $row['Field'];
    }
    return $columnNames;
}

// Function to fetch data from a given table
function fetchData($tableName)
{
    global $conn;
    $sql = "SELECT * FROM $tableName";
    $result = mysqli_query($conn, $sql);
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

// Determine which table to fetch data from (default to the first table)
$selectedTable = isset ($_GET['table']) ? $_GET['table'] : key($tableNamesMap);

// Fetch column names and data from the selected table
$columnNames = getColumnNames($selectedTable);
$data = fetchData($selectedTable);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Representation</title>
    <style>
        /* Button and dropdown list styling */
        #tableForm {
            margin-bottom: 20px;
            text-align: center;
        }

        #tableSelect {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }

        #tableSelect.open {
            max-height: 200px;
            /* Expanded height */
        }

        #tableSelect:focus {
            outline: none;
            border-color: green;
            /* Green border color when focused */
        }

        #showTableBtn {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: green;
            /* Green background color */
            color: white;
            /* White text color */
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #showTableBtn:hover {
            background-color: #e3e3e3cb;
            backdrop-filter: blur(10px);
            color: #000000;
        }

        /* Transition animations */
        table {
            border-collapse: collapse;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: green;
            /* Green background for header row */
            color: white;
            /* White text color for header row */
        }

        td:first-child {
            background-color: #ccffcc;
            /* Light green background for the first column */
            font-weight: bold;
            /* Bold text for the first column */
        }

        tbody tr:nth-child(even) {
            background-color: #ccffcc;
            /* Light green background for even rows */
        }

        tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
            /* Light gray background for odd rows */
        }
    </style>
</head>

<body>
    <h1>Data Representation</h1>

    <!-- Dropdown list to select table -->
    <form id="tableForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
        <label for="table">Select Table:</label>
        <select name="table" id="tableSelect">
            <?php foreach ($tableNamesMap as $tableName => $displayName): ?>
                <option value="<?php echo $tableName; ?>" <?php if ($tableName === $selectedTable)
                       echo "selected"; ?>>
                    <?php echo $displayName; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" id="showTableBtn" value="Show Table">
    </form>
    <hr>
    <!-- Display fetched data in a table -->
    <table>
        <thead>
            <tr>
                <?php foreach ($columnNames as $columnName): ?>
                    <th>
                        <?php echo $columnName; ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
                <tr>
                    <?php foreach ($columnNames as $columnName): ?>
                        <td>
                            <?php echo $row[$columnName]; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add any JavaScript code here if needed -->
</body>

</html>