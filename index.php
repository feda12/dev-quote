<?php

/*

REQUIREMENTS

* A custom slash command on a Slack team
* A web server running PHP5 with cURL enabled

USAGE

* Place this script on a server running PHP5 with cURL.
* Set up a new custom slash command on your Slack team:
  http://my.slack.com/services/new/slash-commands
* Under "Choose a command", enter whatever you want for
  the command. /isitup is easy to remember.
* Under "URL", enter the URL for the script on your server.
* Leave "Method" set to "Post".
* Decide whether you want this command to show in the
  autocomplete list for slash commands.
* If you do, enter a short description and usage hint.

*/


abstract class SnackStatus
{
    const In = 10;
    const Out = 0;
    const Low = 1;
}

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

$snacks = getenv("SNACKS");

switch ($text) {
    case "status":
        if($snacks == SnackStatus::In)
        {
            $reply = "Yay! No need to panic, snacks are overflowing!";
        } else if ($snacks == SnackStatus::Low) {
            $reply = "We are running low... let Karie know before it's too late..";
        } else if ($snacks == SnackStatus::Out) {
            $reply = "We're out... Ship is sinking..";
        } else {
            $reply = "404: Snacks not found";
        }
        break;
    case "in":
        $status = $SnackStatus::In;
        putenv("SNACKS=$status");
        $reply = "Fresh snacks delivery!";
        break;
    case "low":
        $status = $SnackStatus::Low;
        putenv("SNACKS=$status");
        $reply = "Holy cow! Someone shoud call Karie!!";
        break;
    case "out":
        $status = $SnackStatus::Out;
        putenv("SNACKS=$status");
        $reply = "Argh. No more snacks";
        break;
    default:
        $reply = "404: Snacks not found";
        break;
}

# Send the reply back to the user.
echo $reply;
