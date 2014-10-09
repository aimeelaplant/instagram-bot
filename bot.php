<?

/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/

include 'aimee.instagram.class.php';

$tags = array('englishbulldog', 'bulldog', 'igbulldogs_worldwide');


foreach ($tags as $tag) {
    $ig = new Instagram('access_token', true);
    $media_id = $ig->getIDByTag($tag);
    $ig->likeMedia($media_id);

    unset($ig);
    sleep(5);
}


?>
