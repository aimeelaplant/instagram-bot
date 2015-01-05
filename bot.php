<?php

/*
Example use of aimee.instagram.class.php
*/

include 'aimee.instagram.class.php';

// tags to query
$tags = array('englishbulldog', 'igbulldogs_worldwide');
// initiate class
$ig = new Instagram();
// set your access token
$ig->setAccessToken('access_token');
// loop through tags
foreach($tags as $tag) {
    // searches tag
    $tag = $ig->searchTag($tag);
    // shuffles json data
    shuffle($tag['data']);
    // likes the randomly shuffled tag!
    $ig->likeMedia($tag['data'][0]['id']);
}
// free up some memory
unset($ig);

?>
