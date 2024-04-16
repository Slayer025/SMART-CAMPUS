<?php
require_once "database.php";

// Fetch data from the database
$sql = "SELECT * FROM 2second";
$result = mysqli_query($conn, $sql);

// Check for errors
if (!$result) {
    die("Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORT - 3.1.3</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="reports.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
</head>

<body>
    <div class="graph-container">
        <div class="rep-container">
            <button class="btn btn-primary print-btn" onclick="printReport()">Print Report</button>
            <button class="btn btn-success print-btn" onclick="exportToExcel()">Export to Excel</button>
            <hr>
            <div class="bar-container">

                <h2>Bar/Pie Chart</h2>

                <div>
                    <canvas id="pieChart"></canvas>
                </div>

                <div>
                    <!-- Add a canvas element to render the bar chart -->
                    <canvas id="barChart"></canvas>
                </div>
                <!-- Add buttons for printing and exporting to Excel -->
                <button class="btn btn-primary chart-btn" onclick="showBarChart()">Bar Graph</button>
                <button class="btn btn-primary chart-btn" onclick="showPieChart()">Pie Chart</button>
            </div>
            <br><br>
            <div class="filter-container">
                <h2>Filter Data</h2>
                <select class="filter-button" id="yearFilter">
                    <option class="button" value="">All Years</option>
                    <?php
                    // Generate year options
                    $currentYear = date("Y");
                    for ($i = $currentYear; $i > $currentYear - 5; $i--) {
                        echo "<option value='$i'>{$i}</option>";
                    }
                    ?>
                </select>
                <select class="filter-button" id="deptFilter">
                    <option class="button" value="">All Departments</option>
                    <?php
                    // Generate department options
                    $deptResult = mysqli_query($conn, "SELECT DISTINCT department FROM 2second");
                    while ($deptRow = mysqli_fetch_assoc($deptResult)) {
                        echo "<option value='{$deptRow['department']}'>" . htmlspecialchars($deptRow['department']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <hr>

            <h2>Data Table</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Principal Investigator</th>
                        <th>Project Start</th>
                        <th>Project End</th>
                        <th>Research Project</th>
                        <th>Amount Received</th>
                        <th>Funding Agency</th>
                        <th>Year of Sanction</th>
                        <th>Department</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display data in tabular format
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['principalInvestigator']}</td>";
                        echo "<td>{$row['projectStart']}</td>";
                        echo "<td>{$row['projectEnd']}</td>";
                        echo "<td>{$row['researchProject']}</td>";
                        echo "<td>{$row['amountReceived']}</td>";
                        echo "<td>{$row['fundingAgency']}</td>";
                        echo "<td>{$row['yearOfSanction']}</td>";
                        echo "<td>{$row['department']}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        // Assuming $result is still available with fetched data
        var yearLabels = [];
        var dataValues = [];

        <?php
        // Extract data for the last 5 years
        $currentYear = date("Y");
        for ($i = $currentYear; $i > $currentYear - 5; $i--) {
            echo "yearLabels.push('$i');";

            $sql = "SELECT COUNT(*) as count FROM 2second WHERE yearOfSanction = '$i'";
            $resultCount = mysqli_query($conn, $sql);
            $count = mysqli_fetch_assoc($resultCount)['count'];
            echo "dataValues.push($count);";
        }
        ?>

        // Create a bar chart
        var ctx = document.getElementById('barChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: yearLabels,
                datasets: [{
                    label: 'Number of Entries',
                    data: dataValues,
                    backgroundColor: [
                        'rgba(255, 0, 0, 0.2)', // Red
                        'rgba(0, 0, 255, 0.2)', // Blue
                        'rgba(0, 255, 0, 0.2)'  // Green
                    ],
                    borderColor: [
                        'rgba(255, 0, 0, 1)', // Red
                        'rgba(0, 0, 255, 1)', // Blue
                        'rgba(0, 255, 0, 1)'  // Green
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true,
                        stepSize: 5, // Customize the step size on the y-axis
                        max: 25 // Set the maximum value on the y-axis
                    }
                }
            }
        });


        // Create a pie chart
        var ctx = document.getElementById('pieChart').getContext('2d');
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: yearLabels,
                datasets: [{
                    label: 'Number of Entries',
                    data: dataValues,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Function to export data to Excel
        function exportToExcel() {
            /* Create workbook & add worksheet */
            var wb = XLSX.utils.book_new();

            // Add table worksheet
            var wsTable = XLSX.utils.table_to_sheet(document.querySelector('table'));
            XLSX.utils.book_append_sheet(wb, wsTable, 'Table Data');

            // Add chart worksheet
            var wsChart = XLSX.utils.json_to_sheet([["Year", "Number of Entries"]].concat(yearLabels.map((year, index) => [year, dataValues[index]])));
            XLSX.utils.book_append_sheet(wb, wsChart, 'Chart Data');

            /* Save to file */
            XLSX.writeFile(wb, '3.1.3.report.xlsx');
        }

        function showBarChart() {
            // Show the bar chart and hide the pie chart
            document.getElementById('barChart').style.display = 'block';
            document.getElementById('pieChart').style.display = 'none';
        }

        function showPieChart() {
            // Show the pie chart and hide the bar chart
            document.getElementById('barChart').style.display = 'none';
            document.getElementById('pieChart').style.display = 'block';
        }

        // Hide the pie chart initially
        document.getElementById('pieChart').style.display = 'none';

        // Function to print the report
        function printReport() {
            window.print();
        }
        document.getElementById('yearFilter').addEventListener('change', filterData);
        document.getElementById('deptFilter').addEventListener('change', filterData);

        function filterData() {
            var yearFilter = document.getElementById('yearFilter').value;
            var deptFilter = document.getElementById('deptFilter').value;
            var tableRows = document.querySelectorAll('table tbody tr');

            tableRows.forEach(function (row) {
                var year = row.cells[6].innerText;
                var dept = row.cells[7].innerText;

                if ((yearFilter === '' || year === yearFilter) && (deptFilter === '' || dept === deptFilter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Call filterData initially to display all data
        filterData();
    </script>
</body>

</html>