Instagram like bot
===============
This is a simple Instagram class that utilizes the Instagram API (http://instagram.com/developer/). The interface allows us to view our own feed (recent postings from people you're following) or get a random object ID (e.g., a photo or video), given a tag, and like the object.

Run a cronjon on bot.php and wala! You have automated your Instagram life. :)

## Initial setup
1. Register your app client at Instagram here: http://instagram.com/developer/clients/register/
2. Once you have your client ID, client secret, and redirect uri, you need an access token for the account associated with your app. A Google search shows a million ways to get an access token, but I've gotten emails about it, so check out `token.php` as an example.

## Things the class can do
* `searchTag('tag')` returns the JSON data for Instagram's search API.
* `getUserFeed()` returns the JSON data of the user's feed (the user associated with the access token).
* `likeMedia($id)` likes the media object.
* Check out `bot.php` for more info.

## Example
Say you want to like a random photo of the last X photos that show up on your feed:
```
$ig = new Instagram();
// sets access token
$ig->setAccessToken('access_token');
// get json data of user feed for last 5 media objects
$results = ig->getUserFeed(5);
// shuffle results
shuffle($results['data']);
// 'POST' a like to the object id.
$ig->likeMedia($results['data'][0]['id'])

```
