<?php

include 'aimee.instagram.class.php'; 
$url = $ig->getLoginURL();

if (isset($_GET['code']) {
    $code = $_GET['code'];
    // posts the code to Instagram and returns the access token from IG
    $token = $ig->getOAuthToken($code);
	  // Copy and paste or store this token in a database so you can do setAccessToken() in bot.php
    echo 'Access token: . ' $token . '(copy and paste or store this in a database).';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Access Token Example</title>

    </head>
    <body>
      <p><a href="<?php echo $url; ?>">Authentication link.</a></p>
    </body>
</html>
