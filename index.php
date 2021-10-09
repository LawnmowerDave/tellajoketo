<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="laughing.ico" type="image/x-icon" />
    <script src="lib/jquery-3.6.0.min.js"></script>
    <script src="lib/js-confetti.browser.js"></script>
    <script src="select.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tell a Joke</title>
</head>

<body>
    <div id="main">
        <div class="container">
            <h1 class="glow">Tell a Joke</h1>

            <?php
            // render appropriate contents based on if it's a user's page
            if (isset($_GET["u"])) {
                $u = $_GET["u"];
                echo("<h2>to $u</h2>");
                // emit a hidden span in order to pass the data to JS
                // it's messy but I'm not sure how else to really do it
                echo("<span id='u' class='hidden'>$u</span>");
                echo (file_get_contents("submit.html"));
            } else {
                echo (file_get_contents("recipient.html"));
            }
            ?>



            <!-- recipient -->
            <!-- <div id="first" class="input_container">
                    <input id="recipient" class="left_box" type="text"
                        placeholder="email or phone #">
                    <input id="submit" class="right_box" type="submit" onclick="submit(this)" value="Send 😂"></input>
                </div> -->

        </div>

        <div class="container">


            <!-- random/custom -->
            <div class="input_container">
                <input id="randomized" type="button" class="left_box selector_button selected" onclick="toggleSelect(this)" value="Random 🎲">
                <input id="custom_joke" type="button" class="right_box selector_button" onclick="toggleSelect(this)" value="Custom 🎨">
            </div>

            <!-- clean/dirty -->
            <div class="input_container">
                <input id="clean" name="maturity" type="button" class="left_box selector_button selected" onclick="toggleSelect(this)" value="Clean 👼">

                <input id="dirty" name="maturity" type="button" class="right_box selector_button" onclick="toggleSelect(this)" value="Dirty 😈">
            </div>

            <!-- custom joke text -->
            <div class="input_container hidden">
                <input type="text" name="custom_joke_text" id="custom_joke_text" placeholder="Something funny 🤣">
            </div>

            <!-- anonymous/identified -->
            <div class="input_container">
                <input id="anonymous" type="button" class="left_box selector_button selected" onclick="toggleSelect(this)" value="Anonymous 🕵️‍♂️">
                <input id="identified" type="button" class="right_box selector_button" onclick="toggleSelect(this)" value="Identified 📛">
            </div>

            <!-- identity -->
            <div class="input_container hidden">
                <input type="text" name="identity" id="identity" placeholder="Your identity">
            </div>
        </div>


    </div>


    <img id="loader" class="post_send hidden" src="assets/loadingcat.gif" alt="Sending...">

    <footer id="footer">
        <!-- <a href="http://tellajoke.to/terms-and-conditions.html">Terms and Conditions</a> | -->
        <a href="https://github.com/LawnmowerDave/">Developed by Matt Loucks</a> |
        <a href="https://www.buymeacoffee.com/LawnmowerDave">Buy Me a Coffee</a>
        <!-- <img id="bmc" src="assets/blue-button.png" alt="Buy me a Coffee"> -->
    </footer>
</body>
<canvas id="confetti">

</html>