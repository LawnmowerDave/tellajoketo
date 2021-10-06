<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="laughing.ico" type="image/x-icon"/>
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
                    // if this is a custom page, omit the recipient input
                    // and show their custom page!
                    require_once "util.php";

                    // TODO: remove hardcoded vals
                    $u = "matt";
                    $num = "9897980489";

                    // if no user is specified, send out the normal recipient
                    // stuff
                    if (is_null($u)) {
                        echo(file_get_contents("recipient.html"));
                        // otherwise, echo out a custom page for the user!
                    } else {

                        echo("<h2>to $u</h2><br>");
                    }
                    
                ?>

                <!-- recipient -->
                <!-- <div id="first" class="input_container">
                    <input id="recipient" class="left_box" type="text"
                        placeholder="email or phone #">
                    <input id="submit" class="right_box" type="submit" onclick="submit(this)" value="Send 😂"></input>
                </div> -->

                <!-- random/custom -->
                <div class="input_container">
                    <input id="randomized" type="button" class="left_box selector_button selected"
                        onclick="toggleSelect(this)" value="Random">
                    <input id="custom_joke" type="button" class="right_box selector_button" onclick="toggleSelect(this)"
                        value="Custom">
                </div>

                <!-- clean/dirty -->
                <div class="input_container">
                    <input id="clean" name="maturity" type="button" class="left_box selector_button selected"
                        onclick="toggleSelect(this)" value="Clean 👼">

                    <input id="dirty" name="maturity" type="button" class="right_box selector_button" onclick="toggleSelect(this)"
                        value="Dirty 😈">
                </div>

                <!-- custom joke text -->
                <div class="input_container hidden">
                    <input type="text" name="custom_joke_text" id="custom_joke_text" placeholder="Something funny 🤣">
                </div>

                <!-- anonymous/identified -->
                <div class="input_container">
                    <input id="anonymous" type="button" class="left_box selector_button selected"
                        onclick="toggleSelect(this)" value="Anonymous">
                    <input id="identified" type="button" class="right_box selector_button" onclick="toggleSelect(this)"
                        value="Identified">
                </div>

                <!-- identity -->
                <div class="input_container hidden">
                    <input type="text" name="identity" id="identity" placeholder="Your identity 🕵️‍♂️">
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
