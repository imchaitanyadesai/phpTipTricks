<?php
/* -------DATABASE RELATED FUNCTION [START] ------- */

// GET SINGLE FIELD FROM TABLE USING WHERE CONDITION
function dbQueryField($field, $table, $whereArray = array(), $notWhereArray = array()) {

    $CI = & get_instance();
    parsesArrayIntoWhere($whereArray);
    parsesArrayIntoNotWhere($notWhereArray);
    $res = $CI->db->select($field)->get($table)->row();
    return $res->$field;
}

// GET SINGLE ROW DATA USING WHERE CONDITION
function dbQueryRow($table, $whereArray = array(), $notWhereArray = array()) {
    $CI = & get_instance();
    parsesArrayIntoWhere($whereArray);
    parsesArrayIntoNotWhere($notWhereArray);
    $res = $CI->db->get($table)->row_array();
    return $res;
}

// GET SINGLE LAST ROW DATA USING WHERE CONDITION
function dbQueryLastRow($table, $whereArray = array(), $notWhereArray = array(),$id) {
    $CI = & get_instance();
    parsesArrayIntoWhere($whereArray);
    parsesArrayIntoNotWhere($notWhereArray);
    $CI->db->order_by($id, 'DESC');
    $CI->db->limit('1'); 
    $res = $CI->db->get($table)->row_array();
    return $res;
}

// GET ALL TABLE DATA USING WHERE CONDITION
function dbQueryRows($table, $whereArray = array(), $notWhereArray = array()) {
    $CI = & get_instance();
    parsesArrayIntoWhere($whereArray);
    parsesArrayIntoNotWhere($notWhereArray);
    $res = $CI->db->get($table)->result_array();
    return $res;
}

// GET COUNT INTO TABLE USING WHERE CONDITION
function dbQueryCount($table, $whereArray = array(), $notWhereArray = array()) {
    $CI = & get_instance();
    $CI->db->select("*");
    parsesArrayIntoWhere($whereArray);
    parsesArrayIntoNotWhere($notWhereArray);
    $res = $CI->db->get($table)->num_rows();
    return $res;
}

// FETCH MYSQL QUERY RESULT 
function dbQuery($sql) {

    $CI = & get_instance();
    $res = $CI->db->query($sql);
    $result = $res->result_array();

    return $result;
}

// print last query
function lq() {
    $CI = & get_instance();
    echo $CI->db->last_query();
}

/* -------DATABASE RELATED FUNCTION [ END ] ------- */

// SET MESSAGE INTO SESSION
function setMessage($msg, $color) {
    $CI = & get_instance();
    if ($color == '')
        $color->session->set_userdata('flash_msg', $msg);
    else
        $CI->session->set_userdata('flash_msg', "<font style='color:" . $color . ";' >$msg</font>");
}

// GET SESSION MESSAGE
function getMessage() {

    $CI = & get_instance();
    if ($CI->session->userdata('flash_msg')) {
        $msg = $CI->session->userdata('flash_msg');
        $CI->session->unset_userdata('flash_msg');
    }
    return @$msg;
}

// LOAD VIEWS
function loadTemplate($dm_array) {

    $CI = & get_instance();
    foreach ($dm_array as $dm_row) {
        if (is_array($dm_row)) {
            foreach ($dm_row as $key => $value) {
                $CI->load->view($key, $value);
            }
        } else
            $CI->load->view($dm_row);
    }
}

// encryption function  
function encryptCookie($value) {

    if (!$value) {
        return false;
    }

    $key = 'SecCuREdkEy';
    $text = $value;
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
    return trim(base64_encode($crypttext)); //encode for cookie
}

// DECRYPTION FUNCTION 
function decryptCookie($value) {

    if (!$value) {
        return false;
    }

    $key = 'SecCuREdkEy';
    $crypttext = base64_decode($value); //decode cookie
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
    return trim($decrypttext);
}

// PRINT ARRAY WITH FORMATTING
function pr($arr) {
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

// LOAD IMAGE FOR PROFILE PICTURE
function loadImage($path) {
    if (file_exists('./' . $path) && $path != '')
        echo base_url() . $path;
    else
        echo base_url() . 'images/user.png';
}

// manage the aspect rasio of image 
function apsectRasioManagement($imageFullPath, $staticWidth, $minHeight) {

    if ($_SERVER['SERVER_NAME'] == 'localhost')
        return $minHeight;

    list($ImageOriginalWidth, $ImageOriginalHeight, $type, $attr) = getimagesize($imageFullPath);
    $newHeight = (int) (( $ImageOriginalHeight / $ImageOriginalWidth) * $staticWidth );

    if ($newHeight < $minHeight) {
        $newHeight = $minHeight;
    }
    return $newHeight;
}

// Generate random key
function generateRandom($length = 32) {
    $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $len = strlen($salt);
    $makepass = '';
    mt_srand(10000000 * (double) microtime());

    for ($i = 0; $i < $length; $i ++) {
        $makepass .= $salt[mt_rand(0, $len - 1)];
    }
    return $makepass;
}
function generateRandomNumeric($length = 32) {
    $salt = "0123456789";
    $len = strlen($salt);
    $makepass = '';
    mt_srand(10000000 * (double) microtime());

    for ($i = 0; $i < $length; $i ++) {
        $makepass .= $salt[mt_rand(0, $len - 1)];
    }
    return $makepass;
}

function oldhumanTiming($time) {
 
    $time = time() - $time; // to get the time since that moment    
    //echo '1. '.date('Y-m-d H:i:s', time() ) .'---';
    //echo '2. ' .date('Y-m-d H:i:s', $time)  .'---';
    $time = ($time < 1) ? 1 : $time;
    $tokens = array(
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit)
            continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . ' ago';
    }
}
function newhumanTiming($time) {
    $CI = & get_instance();
    $tt = $CI->session->userdata('currentTime');
     //echo '1. '.date('Y-m-d H:i:s', $time ) .'---';
    //echo '2. ' .date('Y-m-d H:i:s', $tt)  .'---';
    //die;
    if($tt == ''){
        $tt = time();
    }
    $time = $tt - $time; // to get the time since that moment    
    //echo '1. '.date('Y-m-d H:i:s', time() ) .'---';
    //echo '2. ' .date('Y-m-d H:i:s', $time)  .'---';
    $time = ($time < 1) ? 1 : $time;
    $tokens = array(
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit)
            continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . ' ago';
    }
}
function humanTiming($time) {
$yourDate = strtotime(date('Y-m-d',$time));
$todayDate = strtotime(date('Y-m-d'));
    if($yourDate == $todayDate){
        return 'Today';
    }elseif(strtotime("-1 day", $todayDate) == $yourDate) {
        return 'Yesterday';
    }else{
        return date('M d',$yourDate);
    }
}

/* -------HELPER FUNCTIONS [START] ------- */

function parsesArrayIntoWhere($data) {

    if (!is_array($data))
        die('parsesArrayIntoWhere - You have not enter valid array');

    $CI = & get_instance();
    foreach ($data as $key => $value) {
        $CI->db->where($key, $value);
    }
}

function parsesArrayIntoNotWhere($data) {

    if (!is_array($data))
        die('parsesArrayIntoWhere - You have not enter valid array');

    $CI = & get_instance();
    foreach ($data as $key => $value) {
        $CI->db->where($key . ' !=', $value);
    }
}

function putOnS3($sourceFile, $newFileName, $folder) {

    $CI = & get_instance();
    $destination = $folder . '/' . $newFileName;
    $CI->load->library('s3');
    if ($folder == 'videos') {
        $metaHeaders = array();
        $contentType = "video/mp4";
    }

    $result = $CI->s3->putObjectFile($sourceFile, S3_BACKET, $destination, S3::ACL_PUBLIC_READ, $metaHeaders, $contentType);
    return ($result);
}

function removeOnS3($fileName) {

    $fileName = str_replace('http://' . S3_BACKET . '.s3.amazonaws.com/', '', $fileName);

    $CI = & get_instance();
    $CI->load->library('s3');
    $result = $CI->s3->deleteObject(S3_BACKET, $fileName);
}
function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}
  
function ratingStar($ratingStar){
    $cfg_min_stars = 1;
    $cfg_max_stars = 5;
    $temp_stars = $ratingStar;
    for($i=$cfg_min_stars; $i<=$cfg_max_stars; $i++) {
    //echo $temp_stars;
        if ($temp_stars >= 1) {
            echo '<img src="'.base_url().'images/dashboard/Star (Full).png"/>';
            $temp_stars--;
        }else {
            if ($temp_stars >= 0.5) {
                echo '<img src="'.base_url().'images/dashboard/Star (Half Full).png"/>';
                $temp_stars -= 0.5;
            }else {
                echo '<img src="'.base_url().'images/dashboard/Star (Empty).png"/>';
            }
        }
    }
}

?>
