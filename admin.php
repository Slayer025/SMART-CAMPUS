<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}
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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"></script>
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
            /* Remove default margin */
        }

        .menu {
            position: fixed;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 10px;

        }

        .menu button {
            background-color: #f0f0f0;
            border: none;
            padding: 15px 25px;
            cursor: pointer;
        }

        .sub-options {
            position: absolute;
            left: 100%;
            background-color: #ffffff93;
            margin-left: 60px;
            display: none;
            grid-row: 5;
            row-gap: 5px;
            border: #000000;
            gap: 5px;
            /* Add this line to create a visible gap between the individual elements */
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .sub-options a {
            display: flex;
            min-width: 650px;
            margin-bottom: 5px;
            padding: 15px 15px;
            text-decoration: none;
            color: #ffffff;
            background-color: #28b463;
            border-radius: 4px;
        }

        .sub-options a:hover {
            background-color: #ffffff37;
            backdrop-filter: blur(5px);
            transition: 0.2s ease-in-out;
            color: #000000;
            transform: scale(1.02) translateY(-5%);
        }

        .buttons-container {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .buttons-box {
            display: flex;
            flex-direction: row;
            gap: 5px;
        }

        .option {
            margin-bottom: 5px;
            max-width: fit-content;
            border-radius: 6px;
        }

        .admin-box {
            width: 100%;
            justify-content: space-between;
            margin-right: 10px;

        }

        .background-container {
            position: relative;
            width: 170px;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
            border-radius: 4px;
            padding: 10px;
            transition: all 0.3s ease-in-out;
        }

        .background-container:hover {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .navbar {
            background-color: #f0f0f0;
            padding-left: 10px;
            padding-right: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar img {
            height: 40px;
        }

        .navbar button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
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
            transition-duration:  0.3s;
            transition:background-color 0.3s ease-in-out;
            /* Add this line for the fade transition */
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
    </style>

</head>

<body>

    <div class="navbar">
        <!-- Logo on the left -->
        <a href="#">
            <img src="images/dbit_logo.png" alt="Your Logo">
        </a>

        <!-- Button on the right to toggle the modal -->
        <button id="toggleButton">Toggle Modal</button>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <!-- Add your modal content, such as buttons, here -->
        <div class="modal-buttons">
            <button>Button 1</button>
            <button>Button 2</button>
            <button>Button 3</button>
            <button>Button 4</button>
        </div>
    </div>

    <div class="admin-box">
        <div class="menu">
            <button class="option" data-index="0">3.1 - Resource Mobilization For Research</button>
            <div class="sub-options" data-index="0">
                <a href="3.1.1.php">3.1.1 - Grants for Research Projects Sponsored by the Non-Government Sources</a>
                <a href="3.1.3.php">3.1.3 - Number of Research Projects per Teacher Funded by Government and
                    Non-Government
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
                <a href="3.5.1.php">3.5.1 - Number of Linkages for Faculty Exchange, Students Exchange, Internship,
                    Field
                    Trip,
                    On-The-Job Training, Research, etc during the last Five Years</a>
                <a href="3.5.2.php">3.5.2 - Number of Functional MoUs with Institutions of National, International
                    Importance,
                    other
                    Universities, Industries, Corporate Houses, etc during the past 5 years</a>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.menu button');
            const subOptions = document.querySelectorAll('.menu .sub-options');
            const overlay = document.getElementById('overlay');
            const modal = document.getElementById('myModal');

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
                if (modal.style.display === "none") {
                    modal.style.display = "block";
                    modal.style.transform = "translate(-50%, -50%) scale(1)";
                    overlay.style.display = "block";
                } else {
                    modal.style.transform = "translate(-50%, -50%) scale(0)";
                    setTimeout(() => {
                        modal.style.display = "none";
                        overlay.style.display = "none";
                    }, 500); // Adjust the timing to match the transition duration
                }
            }

            // Close modal and overlay when clicking on the overlay
            overlay.addEventListener('click', toggleModal);


            // Attach the toggleModal function to the button click event
            document.getElementById("toggleButton").addEventListener('click', toggleModal);
        });
    </script>

</body>

</html>