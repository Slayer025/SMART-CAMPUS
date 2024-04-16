<?php
session_start();
require_once "database.php";

if (isset($_POST["submit"])) {
    $codeOfEthicsURL = mysqli_real_escape_string($conn, $_POST["codeOfEthicsURL"]);
    $accessToPlagiarismSoftware = mysqli_real_escape_string($conn, $_POST["accessToPlagiarismSoftware"]);
    $mechanismForPlagiarism = mysqli_real_escape_string($conn, $_POST["mechanismForPlagiarism"]);

    if (empty($codeOfEthicsURL) || empty($accessToPlagiarismSoftware) || empty($mechanismForPlagiarism)) {
        echo "<div class='alert alert-danger'>All fields are required.</div>";
    } else {
        $sql = "INSERT INTO 4fourth (codeOfEthicsURL, accessToPlagiarismSoftware, mechanismForPlagiarism) VALUES ('$codeOfEthicsURL', '$accessToPlagiarismSoftware', '$mechanismForPlagiarism')";

        if (mysqli_query($conn, $sql)) {
            echo "<div class='alert alert-success'>Data submitted successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
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
    <title>Data Display</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="reports.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
</head>

<body>
    <?php
    // Display data in tabular format
    $sql = "SELECT * FROM 4fourth";
    $result = mysqli_query($conn, $sql);

    // Check for errors
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }
    ?>

    <div class="graph-container">

        <div class="bar-container">

            <div>
                <canvas id="pieChart"></canvas>
            </div>

            <div>
                <!-- Add a canvas element to render the bar chart -->
                <canvas id="barChart"></canvas>
            </div>
            <h2>Bar/Pie Chart</h2>
            <!-- Add buttons for printing and exporting to Excel -->
            <button class="btn btn-primary chart-btn" onclick="showBarChart()">Bar Graph</button>
            <button class="btn btn-primary chart-btn" onclick="showPieChart()">Pie Chart</button>
        </div>

        <div class="main-container d-flex">

            <div class="rep-container">
                <button class="btn btn-primary print-btn" onclick="printReport()">Print Report</button>
                <button class="btn btn-success print-btn" onclick="exportToExcel()">Export to Excel</button>
                <hr>

                <hr>

                <h2>Data Table</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Code of Ethics URL</th>
                            <th>Access to Plagiarism Software</th>
                            <th>Mechanism for Plagiarism</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>{$row['codeOfEthicsURL']}</td>";
                            echo "<td>{$row['accessToPlagiarismSoftware']}</td>";
                            echo "<td>{$row['mechanismForPlagiarism']}</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php
            // Fetch data for the bar chart
            $sqlCountYes = "SELECT COUNT(*) as countYes FROM 4fourth WHERE accessToPlagiarismSoftware = 'Yes'";
            $resultCountYes = mysqli_query($conn, $sqlCountYes);
            $countYes = mysqli_fetch_assoc($resultCountYes)['countYes'];

            $sqlCountNo = "SELECT COUNT(*) as countNo FROM 4fourth WHERE accessToPlagiarismSoftware = 'No'";
            $resultCountNo = mysqli_query($conn, $sqlCountNo);
            $countNo = mysqli_fetch_assoc($resultCountNo)['countNo'];
            ?>

            <script>
                // Create a bar chart
                var ctx = document.getElementById('barChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Yes', 'No'],
                        datasets: [{
                            label: 'Number of Entries',
                            data: [<?php echo $countYes; ?>, <?php echo $countNo; ?>],
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
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Yes', 'No'],
                        datasets: [{
                            label: 'Number of Entries',
                            data: [<?php echo $countYes; ?>, <?php echo $countNo; ?>],

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
                    var wsChart = XLSX.utils.json_to_sheet([["Access to Plagiarism Software", "Number of Entries"], ["Yes", <?php echo $countYes; ?>], ["No", <?php echo $countNo; ?>]]);
                    XLSX.utils.book_append_sheet(wb, wsChart, 'Chart Data');

                    /* Save to file */
                    XLSX.writeFile(wb, '3.3.1.report.xlsx');
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
                        var year = row.cells[3].innerText;
                        var dept = row.cells[2].innerText;

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