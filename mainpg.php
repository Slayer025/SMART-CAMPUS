<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main-styles.css">
    <title>Button Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            overflow: hidden;
            background-image: url("images/collegepic.webp.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            height: 100vh;
            filter: drop-shadow(10%);
            size-adjust: 100%;
            transition: background-color 0.3s;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-left: 40%;
            height: 40%;
            width: 20%;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0);
            /* Semi-transparent white background */
            backdrop-filter: blur(5px);
            /* Add a blur effect */
            transition: background-color 0.3s;
            /* Add transition for smoother color change */
        }

        .button {
            margin: 5px;
            padding: 10px;
            font-size: 20px;
            width: 100%;
            cursor: pointer;
            border: none;
            outline: none;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }

        .button:hover {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .button-container:hover {
            position: relative;
            background-color: rgba(255, 255, 255, 0.403);
            /* Change background opacity on hover */
            transition: 1s ease-in-out;
        }

        .button::after {
            content: '';
            position: absolute;
            /* Change position to absolute */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0);
            /* Transparent background */
            pointer-events: none;
            z-index: -1;
            transform: scale(-1) ease-in-out;
            transition: background-color 0.3s;
        }

        .button:hover::after {
            background: rgba(0, 0, 0, 0.2);
            /* Add a blur overlay on button hover */
        }
    </style>
</head>

<body>
    <div class="button-container">
        <button class="button" id="button1">Button 1</button>
        <button class="button" id="button2">Button 2</button>
        <button class="button" id="button3">Button 3</button>
        <button class="button" id="button4">Button 4</button>
        <button class="button" id="button5">Button 5</button>
        <button class="button" id="button6">Button 6</button>
    </div>
</body>

</html>