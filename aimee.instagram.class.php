<?php

class Instagram {

    public function __construct($access_token, $testing) {
        $this->access_token = $access_token;
        $this->testing = $testing;
        $this->endpoint_urls = array(
            'search_tag'    => 'https://api.instagram.com/v1/tags/%s/media/recent?access_token=%s',
            'like_media'    => 'https://api.instagram.com/v1/media/%s/likes',
            'comment_media' => 'https://api.instagram.com/v1/media/%s/comments',
        );
    }

    public function logData($data) {
        $file = "log.txt";
        $handle = fopen($file, 'w');
        fwrite($handle, $data);
        fclose($handle);
    }

    public function curl($url, $data_type = null, $post_params = null) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

        if ($data_type == "post") {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_params));
        }

        $results = curl_exec($ch);
        curl_close($ch);
        return $results;
    }

//1 comment per run. 
    public function getIDByTag($tag) {
        // Returns ONE random ID
        // For now, we'll set num_results to 30 (max return results for Instagram API);
        $num_results = 30;
        $url = sprintf($this->endpoint_urls['search_tag'], $tag, $this->access_token);
        $data = $this->curl($url);
        $data = json_decode($data, true);

        if ($data['meta']['code'] == 200) {
            // Puts results into an array.
            $media_ids = array();
            for ($i=0; $i < $num_results; $i++) {
                $media_ids[] = $data['data'][$i]['id'];
            }
            // Shuffle results
            shuffle($media_ids);
            // Return ONE media id.
            return $media_ids[0];
        } else {
            print_r($data);
            $this->logData($data);
            exit;
        }
    }

    public function likeMedia($media_id) {
        // Likes a media object given the media ID.
        $url = sprintf($this->endpoint_urls['like_media'], $media_id);
        $params = array('access_token' => $this->access_token);
        $data = $this->curl($url, $data_type='post', $post_params=$params);
        $data = json_decode($data, true);

        if ($data['meta']['code'] == 200) {
            $log = "Success! Liked post ID " . $media_id . ". ";
            $this->logData($log);
        } else {
            $this->logData($data);
            exit;
        }
    }


    public function commentMedia($media_id, $comment_text) {
        // Comments on media object.
        $url = sprintf($this->endpoint_urls['comment_media'], $media_id);
        $params = array(
            'access_token' => $this->access_token, 
            'text'         => $comment_text
        );
        $data = $this->curl($url, $data_type='post', $post_params=$params);
        if ($data['meta']['code'] == 200) {
            $log = "Success! You posted comment " .$comment_text." to object ID " . $media_id . ". ";
            $this->logData($log);
        }
        if ($data['meta']['code'] == 429) {
            $this->logData($data);
            exit;
        }
    }

}

?>