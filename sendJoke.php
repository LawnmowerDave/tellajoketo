<?php
header('Content-Type: application/json');
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';
$mail = new PHPMailer();


/**
 * Validate the given associative array of user input
 * 
 * @param AssociativeArray user input
 */
function validateInput($userInput)
{
    $status = false;

    // throw an error if there's no ampersand or number
    if (
        strpos($userInput["recipient"], "@") === false &&
        !is_numeric($userInput["recipient"])
    ) {
        $status =  array(
            "status" => 422,
            "message" => "phone number or email address was entered incorrectly!"
        );
    }

    if ($status !== false) {
        // echo (json_encode($status, true));
        die();
    }
}

/**
 * Take the given html file and inject an associative
 * array of variables into any fields demarcated with {variable}
 * 
 * given ex.
 * 
 * email.html
 * array ("joke" => "chicken cross road") 
 * {joke} -- turns to --> chicken cross road
 * 
 * @param String name of file
 * @param AssociativeArray key value array 
 */
function getInjectedHtml($filename, $vars)
{
    $html = file_get_contents($filename);

    foreach ($vars as $key => $value) {
        $html = preg_replace("/{$key}/", $value, $html);
    }

    $html = preg_replace("/{/", "", $html);
    $html = preg_replace("/}/", "", $html);

    return $html;
}

function sendMessage($message, $recipient, $identity, $isPhoneNumber)
{

    $mail = new PHPMailer();

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->SMTPDebug = 2;  // debugging: 1 = errors and messages, 2 = messages only
        $mail->Host       = 'smtp.gmail.com';                         //Set the SMTP server to send through
        // $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->SMTPAutoTLS = false;
        $mail->Username   = 'tellajoketo@gmail.com';                     //SMTP username
        $mail->Password   = 'ludicrous8Canadian53';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
        $mail->Port = 465; // or 587

        //Recipients
        $mail->setFrom('tellajoketo@gmail.com', 'tellajoke.to');
        $mail->addReplyTo('tellajoketo@gmail.com', 'tellajokeTellAJoke.to');
        $mail->addAddress($recipient, 'Recipient');     //Add a recipient

        $html = getInjectedHtml("email.html", array(
            "given_joke" => $message,
            "identity" => $identity
        ));

        if ($isPhoneNumber) {
            $mail->isHTML(false);
            $mail->Subject = '';
            $mail->Body    = "$message\n ~ $identity \n\n Sent using tellajoke.to";
            $mail->AltBody = "$message\n ~ $identity \n\n Sent using tellajoke.to";

        } else {
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Somebody sent you a joke!';
            $mail->Body    = $html;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        }


        if ($mail->send()) {
            return true;
        } else {
            return json_encode($mail->ErrorInfo);
        }
    } catch (Exception $e) {
        return json_encode($e);
    }
}

/**
 * get a joke for the appropriate maturity level
 * 
 * @param String maturity, 'clean' or 'dirty'
 * @return String a joke
 */
function getJoke($maturity)
{

    if($maturity == "clean") {
        $jokes = readCSV("jokes/clean.csv");
    } else if($maturity == "dirty") {
        $jokes = readCSV("jokes/dirty.csv");
    } else {
        throw new Exception("maturity was not specified");
    }

    $badWords = require_once("badWords.php");


    // try finding a joke that's clean/dirty for a finite number of iterations
    // a while true might lead to bad things
    for ($i = 0; $i < 2000; $i++) {

        // select a random index, and find the joke for it
        $jokeIndex = rand(1, count($jokes));
        
        // return it if it's what we want
        if ($maturity == "clean") {
            return $jokes[$jokeIndex][1];
            // make sure we got ourselves a real dirty one :)
        } else if ($maturity == "dirty") {
            $dirtyJoke = $jokes[$jokeIndex][1] . " " . $jokes[$jokeIndex][2];

            if(contains($dirtyJoke, $badWords)) {
                return $dirtyJoke;
            }
        }
    }

    // default in case something breaks
    return "why did the chicken cross the road? To get to the other side! :D";
}

/**
 * return a csv in array form, shamelessly stolen from SO
 */
function readCSV($csvFile)
{
    $file_handle = fopen($csvFile, 'r');
    while (!feof($file_handle)) {
        $line_of_text[] = fgetcsv($file_handle, 1024);
    }
    fclose($file_handle);
    return $line_of_text;
}

/**
 * does a string contain any words in an array?
 * 
 * shamelessly stolen from SO
 */
function contains($str, array $arr)
{
    foreach ($arr as $a) {
        if (stripos($str, " $a ") !== false) return true;
    }
    return false;
}

function log_msg($log_msg)
{
    $log_filename = "log";
    if (!file_exists($log_filename)) 
    {
        // create directory/folder uploads.
        mkdir($log_filename, 0777, true);
    }
    $log_file_data = $log_filename.'/log_' . date('Y-m-d') . '.log';
    // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
    file_put_contents($log_file_data, $log_msg . ' - ' . date('H:i:s') . "\n", FILE_APPEND);
} 


try {
    // validate input and return an error if anything doesn't work
    validateInput($_POST);

    if ($_POST["joke"] == "") {
        $joke = getJoke($_POST["maturity"]);
    } else {
        $joke = $_POST["joke"];
    }

    $carriers = array(
        "@txt.att.net",
        "@vtext.com",
        // spring
        "@messaging.sprintpcs.com",
        "@mymetropcs",
        "@myboostmobile",
        "@sms.mycricket.com",
        // T-Mobile
        "@tmomail.net",
        // Virgin Mobile
        "@vmobl.com",
        "@page.nextel.com"
    );

    // the recipient is a phone number, loop through all major carriers and send it everywhere.
    if(is_numeric($_POST["recipient"])) {
        foreach($carriers as $carrier) {
            $isSent = sendMessage($joke, $_POST["recipient"] . $carrier, $_POST["identity"], true);
            
            // return an ok after the first iteration, because if it doesn't 
            // fail first run, it probably won't fail after
            echo(json_encode(array(
                "status" => 200
            )));
        }
    } else {
        $isSent = sendMessage($joke, $_POST["recipient"], $_POST["identity"], false);
    }

    echo(json_encode(array(
        "status" => 200
    )));
    
    log_msg("sent joke $joke to " . $_POST["recipient"]);

} catch (Exception $e) {
    log_msg("Exception " . print_r(e) . " POST:  " . print_r($_POST));

    echo (json_encode(array(
        "status" => 500,
        "exception" => $e
    )));

    die();
}
