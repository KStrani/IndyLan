<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
| [:START:]
| ADMIN: VOOLSY
| AUTHOR: VINIT
|
| -------------------------------------------------------------------
| CUSTOM HELPER FOR SELF DEFINE FUNCTION
| -------------------------------------------------------------------
| This file will contain the settings needed to access your custom function.
|
| For complete instructions please consult the 'helper documentation'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
*/
/*
| [:START:]
| -------------------------------------------------------------------
| SELF-DECLARATING FUNCTION FOR PRINT ARRAY AND STOP SCRIPT
| -------------------------------------------------------------------
| function is used to print an array or string
| -------------------------------------------------------------------|
*/
 function _dx($args = array()) {
     echo '<pre>';
	 	print_r($args);
	 echo '</pre>';
	 exit;
 }
/*
| [:START:]
| -------------------------------------------------------------------
| SELF-DECLARATING FUNCTION FOR PRINT ARRAY AND CONTINUE
| -------------------------------------------------------------------
| function is used to print an array or string
| -------------------------------------------------------------------|
*/
 function _d($args = array()) {
	echo '<pre>';
	 	print_r($args);
	echo '</pre>';
 } 
/*
| [:START:]
| -------------------------------------------------------------------
| HTTP-STATUS-CODE
| -------------------------------------------------------------------
| this function returns the status of service
| -------------------------------------------------------------------|
*/
 function http_status_code($code = '') {
 	$status_code = array(
 						'200' => 'OK',
 						'201' => 'CREATED',
 						'202' => 'ACCEPTED',		
						'400' => 'BAD REQUEST',
						'401' => 'UNAUTHORIZED',
						'402' => 'TOO MANY REQUEST',
						'403' => 'FORBIDDEN',
						'404' => 'NOT FOUND',
						'405' => 'METHOD NOT ALLOWED',
						'406' => 'NOT ACCEPTABLE',
						'408' => 'REQUEST TIMEOUT',
						'409' => 'CONFLICT',
						'410' => 'GONE',
						'498' => 'INVALID TOKEN',
						'440' => 'LOGIN TIMEOUT',
						'444' => 'NO RESPONSE',
						'500' => 'INTERNAL SERVER ERROR',
						'502' => 'BAD GATEWAY',
						'503' => 'SERVICE UNAVAILABLE',
						'508' => 'LOOP DETECTED',
						'511' => 'NETWORK AUTHENTICATION REQUIRED', 
						'521' => 'WEB SERVER DOWN',
						'522' => 'CONNECTION TIMEOUT'	
				   );
	 return $status_code[$code];
 }

/*
| [:START:]
| -------------------------------------------------------------------
| SEND-MAIL
| -------------------------------------------------------------------
| this function is used to send the mail
| -------------------------------------------------------------------|
*/
function send_mail($from, $to, $subject, $message) {
	$headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion();
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$result = @mail($to, $subject, $message, $headers);
	return $result;
}
/*
| [:START:]
| -------------------------------------------------------------------
| WEB-SERVICE RESPONSE IN JSON FORMAT
| -------------------------------------------------------------------
| this function returns the response of webservice in json format
| -------------------------------------------------------------------|
*/
function http_response($http_code = '', $status = '', $display_msg = '', $response = array(), $message_en = '') {
	header('Content-Type: application/json, utf-8');
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Pragma: no-cache");	
	
	// if( ! $display_msg) 
	// 	$display_msg = http_status_code($http_code);
	// else 
	// 	$display_msg = display_message($display_msg);
	
	$result = array(
				  	"status" 	=> $status,
				  	"message"	=> $display_msg,
				  	"message_en" => $message_en, 
				 // 	"current_page" => $current_page_number,
				  //	"total_pages" => $total_number_of_pages,
				  	"result"	=> $response,
				  	"status_message" => http_status_code($http_code)			
				);
	//echo $result['status']; die();
	if($result['status'] == "0"){

    	log_message('error',json_encode($result));

    }

	$json=json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);			
    echo $json;
    
    
}

function error_log_api($status,$data = array()){
	$filelog = $status .'</br>'. $data;
	file_put_contents('/logs/logfile.txt', $filelog, FILE_APPEND);
}
/*
| [:START:]
| ------------------------------------------------------------------------------------------------------------
| GET TIMEZONE DIFFERENCE FROM UTC/GMT TO REMOTE TIMEZONE 
| -------------------------------------------------------------------------------------------------------------
| this function returns the difference between timezone from gmt to any in seconds
| -------------------------------------------------------------------|
*/
function get_timezone_offset_from_gmt($remote_tz, $origin_tz = 'UTC') {
	if(!$origin_tz || $origin_tz === null) {
		if(!is_string($origin_tz = date_default_timezone_get())) {
            return false; // A UTC timestamp was returned -- bail out!
        }
    }

    $origin_dtz = new DateTimeZone($origin_tz);
    $remote_dtz = new DateTimeZone($remote_tz);
    $origin_dt = new DateTime("now", $origin_dtz);
    $remote_dt = new DateTime("now", $remote_dtz);
    $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
    return $offset;
}
/*[:END:]*/

/*
| [:START:]
| ------------------------------------------------------------------------------------------------------------
| GET USER LOCATION INFO i.e. IP, COUNTRY-CODE, COUNTRY-NAME, REGION-CODE, REGION-NAME, TIME-ZONE, LAT, LONG
| -------------------------------------------------------------------------------------------------------------
| this function returns the user location information
| -------------------------------------------------------------------|
*/
function get_user_geoloc() {
	$location = file_get_contents('http://freegeoip.net/json/'.$_SERVER['REMOTE_ADDR']);
    return json_decode($location);
}
/*[:END:]*/

function getsupportLanguage()
{
	$ci =& get_instance();
    $query = $ci->db->query('SELECT *FROM tbl_master_language WHERE status = "1"');
    $d = $query->result_array();
    return $d;
}