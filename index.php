<?php

# Grab some of the values from the slash command, create vars for post back to Slack
$command = $_POST['command'];
$text = $_POST['text'];
$token = $_POST['token'];

# Check the token and make sure the request is from our team
if($token != getenv("SLACK_TOKEN")){ #replace this with the token from your slash command configuration page
  $msg = "The token for the slash command doesn't match. Check your script.";
  print($token);
  print(getenv("SLACK_TOKEN"));
  die($msg);
  echo $msg;
}


$file = 'quotes.txt';
if (!file_exists($file)) {
        touch($file);
}
$handle = fopen($file, "r+");
$quotes = [];
$quotes = file($file);

if(substr($text, 0, 2) == "add")
{
    array_push($quotes, substr($text, 3));
    $handle = fopen($file, "r+");
    if(flock($handle, LOCK_EX)) {
        ftruncate($handle, 0);
        rewind($handle);
        fwrite($handle, $quotes);
        flock($handle, LOCK_UN);
    }
    $reply = "Thanks for sharing, mate!";
} else {
    $randomInt = rand(0, count($quotes)-1);
    $reply = $quotes[$randomInt];
}

echo $reply;
