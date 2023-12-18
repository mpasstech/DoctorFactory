<?php 
class Thinapp extends AppModel
{
    public $actsAs = array('Containable');
    public $name = 'Thinapp';

    public $belongsTo = array('User');
    public $hasOne = array(
        'Leads' => array(
            'className' => 'Leads',
            'foreignKey' => 'app_id'
        )
    );



    var $validate = array(
       /* 'facebook_id' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Facebook ID can not be empty.'),
            'number' => array ('rule' => 'numeric','message' => 'Please enter digit in facebook id.'),
            'valid_fb_id' => array('rule' => array('checkFacebookId'),'message' => 'Please enter valid facebook id.')
        ),
        'facebook_secret' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Facebook secret can not be empty.'),
            'valid_fb_sec' => array('rule' => array('checkFacebookId'),'message' => 'Please enter valid facebook secret.')
        ),
        'facebook_url' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Facebook Url can not be empty.'),
            'valid_fb_url' => array('rule' => array('checkValidfacebookUrl'),'message' => 'Please enter valid facebook Url.')
        ),
        'twitter_url' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Twitter Url can not be empty.'),
            'valid_tw_url' => array('rule' =>array("checkValidTwitterUrl"),'message' => 'Please enter valid twitter Url.')
        ),*/
    );


    function header_req( $url )
    {
        $channel = curl_init();
        curl_setopt($channel, CURLOPT_URL, $url);
        curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($channel, CURLOPT_TIMEOUT, 10);
        curl_setopt($channel, CURLOPT_HEADER, true);
        curl_setopt($channel, CURLOPT_NOBODY, true);
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($channel, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201');
        curl_setopt($channel, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($channel, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($channel, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_exec($channel);
        $httpCode = curl_getinfo( $channel, CURLINFO_HTTP_CODE );
        curl_close($channel);
        return $httpCode;
    }

    function checkValidfacebookUrl($data){


        $url = $data['facebook_url'];
        if ( stripos( $url , "http") !== 0)
            $url = "http://" . $url;
            $host = parse_url( $url, PHP_URL_HOST );
        if ( stripos( $host , "facebook.com" ) )
        {
            $response = $this->header_req($url);
            if ( $response === 200 || $response === 302 )
                return true;
            else
                return false;
        }
        return false;
    }


    function checkValidTwitterUrl($data){
        $twitterUrl = $data['twitter_url'];
        $result =  preg_match("|https?://(www\.)?twitter\.com/(#!/)?@?([^/]*)|", $twitterUrl, $matches);
            if ($result)
                return true;
            else
                return false;
    }



    function getToken($data){

        $app_id = $data['facebook_id'];
        $app_secret = $data['facebook_secret'];
        $token = 'https://graph.facebook.com/oauth/access_token?client_id='.$app_id.'&client_secret='.$app_secret.'&grant_type=client_credentials';
        $token = file_get_contents($token);
        return $token;

    }
    function checkFacebookId($data){
        error_reporting(0);
        $post_data  = $this->data['Thinapp'];
         $token = $this->getToken($post_data);
            if($token){
                $app_id = $post_data['facebook_id'];
                $object = json_decode(file_get_contents('https://graph.facebook.com/'.$app_id."?".$token));
                if ($object && isset($object->link)) {
                    //print "The name of this app is: {$object->name}";
                    return true;
                } else {
                    return false;
                }
            }else{
                return false;
            }



    }
   
}
