<?

/*
Example for use of aimee.instagram.class.php
*/

include 'aimee.instagram.class.php';

$tags = array('englishbulldog', 'bulldog', 'igbulldogs_worldwide');

foreach ($tags as $tag) {
    // Set the access token
    $ig = new Instagram('access_token', true);
    // Get the ID of the media object from a tag.
    $media_id = $ig->getIDByTag($tag);
    // Likes media object.
    $ig->likeMedia($media_id);
    unset($ig);
}


?>
