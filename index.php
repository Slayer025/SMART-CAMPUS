<?php
session_start();
if (!isset ($_SESSION["user"])) {
    header("Location: login.php");
}

// Ensure the directory exists or create it
$uploadDirectory = "C:/xampp/htdocs/login-register-main/UPLOAD/";

if (!file_exists($uploadDirectory)) {
    mkdir($uploadDirectory, 0777, true);
}

// Handle file upload logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset ($_FILES["uploadedFile"])) {
    $fileName = $_FILES["uploadedFile"]["name"];

    // Append the current year to the file name
    $newFileName = "file_" . date("Y") . "_" . $fileName;

    if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $uploadDirectory . $newFileName)) {
        // File upload successful
        echo '<script>alert("File uploaded successfully!");</script>';
    } else {
        // File upload failed
        echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
    }
}

// Count the number of files uploaded this year
$currentYear = date("Y");
$currentYearFiles = count(glob($uploadDirectory . "*_" . $currentYear . "_*"));

// Estimate for the next year (you can customize this estimation logic)
$nextYearEstimate = $currentYearFiles * 1.2; // For example, assuming a 20% growth
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz4fnFO9gybBud7RduPuemT//+jJXB16zg6i8UQD3lV5uDC3Yc7bz1Eeow"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"
        integrity="sha384-IDwe1+LCz02ROU9k972gdyoir+gn5zL4/+8RvzKO+j6DQvPGIzdbd8pN7T7w/D9ccnX"
        crossorigin="anonymous"></script>
    <title>User Dashboard</title>
    <style>
        body {
            background-image: url("images/collegepic.webp.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            height: 100vh;
            filter: drop-shadow(10%);
            size-adjust: 100%;
            margin: 0;
            background-attachment: fixed;
            /* Add this line to make the background image fixed */
        }

        .menu {
            position: fixed;
            left: 5%;
            right: 5%;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: row;
            gap: 10px;
        }

        /* Styling the menu buttons */
        .menu button {
            background-color: #f0f0f0;
            border: none;
            padding: 15px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .menu button:hover {
            background-color: #e0e0e0;
        }

        .sub-options {
            position: center center;
            top: 0;
            background-color: #ffffff93;
            margin-left: 0px;
            display: none;
            grid-row: 5;
            row-gap: 5px;
            border: #000000;
            gap: 5px;
            /* Add this line to create a visible gap between the individual elements */
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Add shadows to options moving upwards and downwards */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3), 0 -5px 15px rgba(0, 0, 0, 0.3);
        }

        .sub-options a {
            display: flex;
            min-width: 500px;
            margin-bottom: 5px;
            padding: 15px 15px;
            text-decoration: none;
            color: #ffffff;
            background-color: #64ad49;
            border-radius: 4px;
        }

        .sub-options a:hover {
            background-color: #ffffff37;
            backdrop-filter: blur(5px);
            transition: 0.2s ease-in-out;
            transform: scale(1.02) translateY(-2%);
            color: #000000;
        }

        .navbar {
            background-color: #f0f0f0;
            padding-left: 10px;
            padding-right: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(to right, #4CAF50, #64ad49);
            color: white;

        }

        .navbar img {
            height: 40px;
        }

        .troggleButton {
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, rgba(0, 0, 0, 0.2) 100%);
            /* Radial gradient background with blur effect */
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 10px; /* Add border-radius for rounded corners */
        }

        /* Adding rounded corners and shadow to buttons */
        .button {
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 2;
        }


        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: min-content;
            height: 300px;
            background-color: #e3e3e3d1;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 3;
            border-radius: 10px;
            padding: 25px;
            transition: 0.3s ease-in-out;
            transition: transform 0.3s ease-in-out;
            /* Add this line for the transition effect */
        }


        /* Add this rule to center the modal horizontally */
        .modal {
            transform: translateX(-50%);
        }

        .modal-buttons button {
            display: flex;
            justify-content: center;
            min-width: 200px;
            margin-bottom: 5px;
            padding: 15px 15px;
            text-decoration: none;
            color: #ffffff;
            background-color: #64ad49;
            border-radius: 4px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3), 0 -5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-buttons button:hover {
            background-color: #ffffff37;
            backdrop-filter: blur(5px);
            transition: 0.2s ease-in-out;
            transform: scale(1.02) translateY(-2%);
            color: #000000;
        }

        /* New styles for the popup window */
        .file-stats-popup {
            position: fixed;
            bottom: 10px;
            right: 10px;
            background-color: #ffffff;
            border: 1px solid #ccc;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        /* New styles for alert messages */
        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-top: 10px;
        }

        /* Styles for success alert */
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        /* New styles for file upload elements */
        label[for="uploadedFile"] {
            display: block;
            margin-top: 20px;
        }

        #uploadedFile {
            margin-top: 10px;
        }

        .btn-upload {
            margin-top: 10px;
        }

        .file-upload-window {
            position: fixed;
            top: 30%;
            left: 40%;
            background-color: #e3e3e388;
            width: max-content;
            height: max-content;
            padding: 10px;
            border-radius: 6px;
            display: none;
            z-index: 3;
            transition: opacity 500ms ease-in-out;
            /* Added transition property */
        }

        .file-upload-window1 {
            position: relative;
            background-color: #e3e3e3d1;
            width: fit-content;
            height: fit-content;
            padding: 10px;
            border-radius: 10px;
            z-index: 3;
            transition: opacity 500ms ease-in-out;
            backdrop-filter: blur(10px);
            /* Added transition property */
        }


        .custom-window {
            position: fixed;
            top: 30%;
            left: 30%;
            background-color: #e3e3e3d1;
            width: 40%;
            height: 40%;
            padding: 15px;
            border-radius: 6px;
            display: none;
            z-index: 3;
            transition: opacity 500ms ease-in-out;
            /* Added transition property */
        }

        /* Add this style for the button links in the custom window */
        .custom-window a.button {
            display: block;
            margin-top: 10px;
            padding: 10px;
            background-color: #64ad49;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease-in-out;
        }

        /* Add a hover effect for the button links */
        .custom-window a.button:hover {
            background-color: #ffffff37;
            backdrop-filter: blur(5px);
            transition: 0.2s ease-in-out;
            transform: scale(1.02) translateY(-2%);
            color: #000000;
        }

        .custom-window .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
            position: relative;
        }

        .custom-window .button-container .button {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 96%;
            height: 60px;
            /* Adjust the height as needed */
            background-color: #64ad49;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px 0;
            transition: background-color 0.3s ease-in-out;
        }

        .custom-window .button-container .button:hover {
            background-color: #ffffff37;
            backdrop-filter: blur(5px);
            transition: 0.2s ease-in-out;
            transform: scale(1.02) translateY(-2%);
            color: #000000;
        }

        .button-container {
            overflow-y: auto;
            /* Add this line to enable vertical scrolling within the container */
            max-height: 90%;
            /* Adjust the maximum height as needed */
        }
    </style>
</head>

<body>
    <div class="navbar">
        <!-- Logo on the left -->
        <a href="#">
            <img src="images/dbit_logo.png" alt="Your Logo">
        </a>

        <!-- Button on the right to toggle the modal -->
        <button id="toggleButton">Menu</button>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <!-- Add your modal content, such as buttons, here -->
        <div class="modal-buttons">
            <button>Button 1</button>
            <button id="toggleCustomWindowButton">Reports</button>
            <!-- New button for toggling custom window -->
            <button id="toggleFileUploadWindowButton">Upload</button>
            <button id="logoutButton"><a href="logout.php">Logout</a></button>
        </div>
    </div>
    <div class="menu">
        <button class="option" data-index="0">3.1 - Resource Mobilization For Research</button>
        <div class="sub-options" data-index="0">
            <a href="3.1.1.php">3.1.1 - Grants for Research Projects Sponsored by the Non-Government Sources</a>
            <a href="3.1.3.php">3.1.3 - Number of Research Projects per Teacher Funded by Government and Non-Government
                Agencies</a>
        </div>

        <!-- Added more options with increased data-index values -->
        <button class="option" data-index="1">3.2 - Innovation Ecosystem</button>
        <div class="sub-options" data-index="1">
            <a href="3.2.2.php">3.2.2 - Number of Workshops/Seminars conducted on Intellectual Property
                Rights(IPR) and Industry</a>
        </div>

        <button class="option" data-index="2">3.3 - Research Publications And Awards</button>
        <div class="sub-options" data-index="2">
            <a href="3.3.1.php">3.3.1 - Code Of Ethics stated by Institution to check Malpractices and Plagirism
                in Research</a>
            <a href="3.3.2.php">3.3.2 - Incentives provided to Teachers who receive State, National and
                International Recognition/Awards</a>
            <a href="3.3.3.php">3.3.3 - Number of Ph.D.s Awarded per Teacher during the last 5 Years (Not
                Applicable For UG Colleges)</a>
            <a href="3.3.4.php">3.3.4 - Number Of Research Papers per Teacher in the Journals notifies on UGC
                website during the last five years</a>
            <a href="3.3.5.php">3.3.5 - Number of Books and Chapters in edited Volumes/Books Published,
                and Papers in the National/International Conference-Proceedings per Teacher during the last Five
                Years</a>
        </div>

        <button class="option" data-index="3">3.4 - Extension Activities</button>
        <div class="sub-options" data-index="3">
            <a href="3.4.2.php">3.4.2 - Number of Awards and Recognition received for Extension Activities from
                Government/recognised bodies during the last 5 Years</a>
            <a href="3.4.3.php">3.4.3 - Number of Extension and Outreach Programs conducted in collaboration with
                Industry,
                Community and Non-Government Organizations through NSS/NCC/Red Cross/YRC etc during the past 5
                years</a>
            <a href="3.4.4.php">3.4.4 - Average percentage of Students participating in Extension activities
                with<br>Government
                Oragnizations, Non-Government Organizaations and programs such as Swachh Bharat, Aids Awareness,
                Gender
                Issue, etc during the past 5 years</a>
        </div>

        <button class="option" data-index="4">3.5 - Collaboration</button>
        <div class="sub-options" data-index="4">
            <a href="3.5.1.php">3.5.1 - Number of Linkages for Faculty Exchange, Students Exchange, Internship, Field
                Trip,
                On-The-Job Training, Research, etc during the last Five Years</a>
            <a href="3.5.2.php">3.5.2 - Number of Functional MoUs with Institutions of National, International
                Importance,
                other
                Universities, Industries, Corporate Houses, etc during the past 5 years</a>
        </div>
    </div>
    <div class="file-upload-window">
        <!-- Display naming convention to users -->
        <div style="margin-top: 20px;">
            <h4>Naming Convention:</h4>
            <p>Follow this convention when naming your file:</p>
            <p><strong>file_TIMESTAMP_originalFileName</strong></p>
        </div>
        <div class="file-upload-window1">
            <!-- Upload button and form -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                enctype="multipart/form-data">
                <div style="margin-top: 20px;">
                    <label for="uploadedFile">Choose a file to upload:</label>
                    <input type="file" name="uploadedFile" id="uploadedFile" required>
                </div>
                <div style="margin-top: 5px;">
                    <button type="submit" class="btn btn-primary">Upload File</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Display the popup window with file stats -->
    <div class="file-stats-popup">
        <p>Number of files uploaded this year:
            <?php echo $currentYearFiles; ?>
        </p>
        <p>Estimate of files that will be uploaded next year:
            <?php echo round($nextYearEstimate); ?>
        </p>
    </div>

    <!-- Add this HTML section for the new window with buttons -->
    <div class="custom-window" id="customWindow">

        <h3>Reports</h3>
        <div class="button-container">
            <a href="report3.1.1.php" class="button">Report for 3.1.1</a>
            <a href="report3.1.3.php" class="button">Report for 3.1.3</a>
            <a href="report3.2.2.php" class="button">Report for 3.2.2</a>
            <a href="report3.3.1.php" class="button">Report for 3.3.1</a>
            <a href="report3.3.2.php" class="button">Report for 3.3.2</a>
            <a href="report3.3.3.php" class="button">Report for 3.3.3</a>
            <a href="report3.3.4.php" class="button">Report for 3.3.4</a>
            <a href="report3.3.5.php" class="button">Report for 3.3.5</a>
            <a href="report3.4.2.php" class="button">Report for 3.4.2</a>
            <a href="report3.4.3.php" class="button">Report for 3.4.3</a>
            <a href="report3.4.4.php" class="button">Report for 3.4.4</a>
            <a href="report3.5.1.php" class="button">Report for 3.5.1</a>
            <a href="report3.5.2.php" class="button">Report for 3.5.2</a>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.menu button');
            const subOptions = document.querySelectorAll('.menu .sub-options');
            const overlay = document.getElementById('overlay');
            const modal = document.getElementById('myModal');
            const fileUploadWindow = document.querySelector('.file-upload-window');
            const customWindow = document.getElementById('customWindow');

            buttons.forEach(button => {
                button.addEventListener('mouseover', () => {
                    const index = button.getAttribute('data-index');
                    subOptions.forEach(subOp => subOp.style.display = 'none');
                    subOptions[index].style.display = 'block';
                });

                button.addEventListener('click', () => {
                    const index = button.getAttribute('data-index');
                    subOptions[index].style.display = 'block';
                });
            });

            subOptions.forEach(subOp => {
                subOp.addEventListener('mouseleave', () => {
                    subOp.style.display = 'none';
                });
            });

            // Toggle modal visibility on button click
            function toggleModal() {
                if (modal.style.display === "none" || modal.style.display === "") {
                    modal.style.display = "block";
                    setTimeout(() => {
                        modal.style.transform = "translate(-50%, -50%) scale(1)";
                        overlay.style.display = "block";
                    }, 50); // Adding a slight delay for smoother animation
                } else {
                    modal.style.transform = "translate(-50%, -50%) scale(0)";
                    setTimeout(() => {
                        modal.style.display = "none";
                        overlay.style.display = "none";
                    }, 500); // Adjust the timing to match the transition duration
                }

                // If file-upload-window is active, close it
                if (fileUploadWindow.style.display === "block") {
                    fileUploadWindow.style.display = "none";
                }

                // If custom window is active, close it
                if (customWindow.style.display === "block") {
                    customWindow.style.display = "none";
                }
            }

            // Close modal, file upload window, and custom window on overlay click
            overlay.addEventListener('click', toggleModal);

            // Attach the toggleModal function to the button click event
            document.getElementById("toggleButton").addEventListener('click', toggleModal);

            // Add this block to handle the file-upload-window toggle
            document.getElementById('toggleFileUploadWindowButton').addEventListener('click', function () {
                // Toggle the file-upload-window visibility
                fileUploadWindow.style.display = (fileUploadWindow.style.display === "none" || fileUploadWindow.style.display === "") ? "block" : "none";

                // Hide the modal and overlay if the file-upload-window is active
                if (fileUploadWindow.style.display === "block") {
                    modal.style.transform = "translate(-50%, -50%) scale(0)";
                    setTimeout(() => {
                        modal.style.display = "none";
                    }, 200); // Adjust the timing to match the transition duration
                }

                // Show the overlay when the file-upload-window is active
                overlay.style.display = "block";
            });

            // Add this block to handle the custom window toggle
            document.getElementById('toggleCustomWindowButton').addEventListener('click', function () {
                // Toggle the custom window visibility
                customWindow.style.display = (customWindow.style.display === "none" || customWindow.style.display === "") ? "block" : "none";

                // Hide the modal and overlay if the custom window is active
                if (customWindow.style.display === "block") {
                    modal.style.transform = "translate(-50%, -50%) scale(0)";
                    setTimeout(() => {
                        modal.style.display = "none";
                    }, 200); // Adjust the timing to match the transition duration
                }

                // Show the overlay when the custom window is active
                overlay.style.display = "block";

                // Add this event listener for the scrolling effect
                customWindow.addEventListener('scroll', function () {
                    const scrollAmount = customWindow.scrollTop;
                    const buttonContainer = customWindow.querySelector('.button-container');

                    // Apply a translateY transformation based on the scroll amount
                    buttonContainer.style.transform = `translateY(-${scrollAmount}px)`;
                });
            });
        });

    </script>
</body>

</html>