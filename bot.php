<?

/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/

include 'aimee.instagram.class.php';

$tags = array('englishbulldog', 'bulldog', 'igbulldogs_worldwide');

$ig = new Instagram('access_token', true);

foreach ($tags as $tag) {
    $media_id = $ig->getIDByTag($tag);
    $ig->likeMedia($media_id);
}

unset($ig);

?>
