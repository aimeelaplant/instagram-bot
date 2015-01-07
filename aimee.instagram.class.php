<?php

class Instagram {
    
    private $_access_token;
    private $_endpoints = array(
            'tag_search' => 'https://api.instagram.com/v1/tags/%s/media/recent?access_token=%s',
            'media_like' => 'https://api.instagram.com/v1/media/%s/likes',
            'user_feed'  => 'https://api.instagram.com/v1/users/self/feed?access_token=%s&count=%s',
            'login' => 'https://api.instagram.com/oauth/authorize/?client_id=%s&redirect_uri=%s&response_type=code&scope=likes',
            'oauth_token' => 'https://api.instagram.com/oauth/access_token',

    );

    public function __construct($client_id=null, $client_secret=null, $redirect_uri=null) {
        $this->_client_id = $client_id;
        $this->_client_secret = $client_secret;
        $this->_redirect_uri = $redirect_uri;
    }

    // login url for user to grant permission to client.
    public function getLoginURL() {
        return sprintf($this->_endpoints['login'], $this->_client_id, $this->_redirect_uri);
    }

    public function setAccessToken($access_token) {
        $this->_access_token = $access_token;
    }

    public function getAccessToken() {
        return $this->_access_token;
    }

    // function to request url and return json data.
    protected function curl($url, $post_params = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        // parameters for POST data
        if ($post_params) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_params));
        }
        $results = curl_exec($ch);
        curl_close($ch);
        return json_decode($results, true);
    }

    public function getOAuthToken($code) {
        $params = array(
        'client_id' => $this->_client_id, 
        'client_secret' => $this->_client_secret,
        'grant_type' => 'authorization_code',
        'redirect_uri' => $this->_redirect_uri,
        'code' => $code
        );
        $result = $this->curl($this->_endpoints['oauth_token'], $post_params=$params);
        return $result['access_token'];
  }

    public function log($log) {
        $date = new DateTime();
        $file = "log.txt";
        $content = file_get_contents($file);
        // timestamp and log message
        $content .= $date->format('U = Y-m-d H:i:s') . ": " . $log . "\n";
        file_put_contents($file, $content);
    }
    
    // Searches a tag from Instagram's public tag feed.
    public function searchTag($tag) {
        $url = sprintf($this->_endpoints['tag_search'], $tag, $this->getAccessToken());
        $data = $this->curl($url);
        return $data;
    }

    // Gets the feed of the user (your own account) with X number of results
    public function getUserFeed($num_results) {
        $url = sprintf($this->_endpoints['user_feed'], $this->getAccessToken(), $num_results);
        return $this->curl($url);
    }

    // Likes a media object given a media ID (e.g. a photo or video).
    public function likeMedia($id, $log=false)  {
        $url = sprintf($this->_endpoints['media_like'], $id);
        $params = array('access_token' => $this->getAccessToken());
        $results = $this->curl($url, $post_params=$params);

        // log response
        if ($log) {
            if ($results['meta']['code'] == 200)   {
                $this->log($this->getAccessToken() . 'liked object ID' . $id);
            } else {
                $this->log($results['meta']);
            }
        }
    }

}
