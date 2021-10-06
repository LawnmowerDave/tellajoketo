<?php

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
    $log_dir = "/var/log/tellajoke.to";
    if (!file_exists($log_dir)) 
    {
        // create directory/folder uploads.
        mkdir($log_dir, 0777, true);
    }
    $log_file_data = $log_dir . '/' . date('Y-m-d') . '.log';
    // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
    file_put_contents($log_file_data, $log_msg . ' - ' . date('H:i:s') . "\n", FILE_APPEND);
} 