<?php

# Grab some of the values from the slash command, create vars for post back to Slack
$command = $_POST['command'];
$text = $_POST['text'];
$token = $_POST['token'];

# Check the token and make sure the request is from our team
if($token != getenv("SLACK_TOKEN")){ #replace this with the token from your slash command configuration page
  $msg = "The token for the slash command doesn't match. Check your script.";
  die($msg);
  echo $msg;
}


$file = 'quotes.txt';
if (!file_exists($file)) {
        touch($file);
}
$handle = fopen($file, "r+");
$quotes = ["42"];
$quotes = file($file);
error_log(count($quotes));
error_log(implode("\n", $quotes));

$reply = "Unsure what's going on here";

if(substr($text, 0, 3) == "add")
{
    error_log("Adding a quote");
    array_push($quotes, substr($text, 4));
    $handle = fopen($file, "r+");
    if(flock($handle, LOCK_EX)) {
        ftruncate($handle, 0);
        rewind($handle);
        fwrite($handle, implode("\n", $quotes));
        flock($handle, LOCK_UN);
    }
    $reply = "Thanks for sharing, mate!";
} else {
    error_log("Returning a quote");
    $randomInt = rand(0, count($quotes));
    $reply = $quotes[$randomInt];
}

echo $reply;
