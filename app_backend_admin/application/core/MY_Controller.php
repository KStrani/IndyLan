<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
/*
| [:START:]
| ADMIN: VOOLSY
| AUTHOR: VINIT
| -------------------------------------------------------------------
| CHECKING FOR CONTROLLER
| -------------------------------------------------------------------
| This file specifies which controller should be loaded by default.
*/
    var $get    = '';
    var $post   = '';
    var $put    = '';
	var $del	= '';
	var $lock	= '';
	var $unlock = '';

/*
| [:START:]
| -------------------------------------------------------------------
| FIREBASE CLOUDE MESSAGING API AND KEY TO SEND PUSH NOTIFICATION ON ANDROID
| -------------------------------------------------------------------
*/	
	private $fcm_api = "https://fcm.googleapis.com/fcm/send";
	private $fcm_key = "AIzaSyCbI_gqYHrt1sQn4OksMQmzyEctLmBXbz0";
/*
| [:START:]
| -------------------------------------------------------------------
| CONSTRUCTOR OF MY_Controller Class
| -------------------------------------------------------------------
*/	
	public function __construct() {
         parent::__construct();
		 date_default_timezone_set("UTC"); 
 		   header('Content-Type: text/html;charset=utf-8');
		   header('Content-Type: application/x-www-form-urlencoded;charset=utf-8	');
		   header("Content-Type: application/json;charset=utf-8");
		   header("Content-Type: application/javascript");
		   header("access-control-allow-origin: *");
		
		// if(error_get_last()) {
  //       	$errlogs = fopen("tmp/logs/err_logs.txt", "w+") or die("Unable to open file!");
		// 	$txt = gmdate('d/m/Y H:i:s')." --- ";
		// 	fwrite($errlogs, $txt);
		// 	$txt = implode(', ', error_get_last()) . " \n";
		// 	fwrite($errlogs, $txt);
		// 	fclose($errlogs);
  //       }


		 switch ($this->getMethod()) {
			 case 'GET':
				$this->get = $this->input->get();
				break;
			 case 'POST':
				$this->post = $this->input->post();
				break;
			 case 'PUT':
				parse_str(file_get_contents("php://input"),$this->put);
				break;
			 case 'DELETE':
				parse_str(file_get_contents("php://input"),$this->del);
				break;
			 case 'LOCK':
				parse_str(file_get_contents("php://input"),$this->lock);
				break;
			 case 'UNLOCK':
				$this->unlock = $this->input->get();
				break;
    	}
		 
		/*if( ! $this->users->is_registered_user($_REQUEST['token'])) {
			die(http_response(401, 0));
		}*/
	}	
/*
| [:START:]
| -------------------------------------------------------------------
| IT RETURNS THE TYPE OF METHOD I.E. GET, POST, PUT, DELETE, LOCK. UNLOCK
| -------------------------------------------------------------------
*/	
	public function getMethod() {												
			return $this->input->server('REQUEST_METHOD');
	}
/*
| [:START:]
| -------------------------------------------------------------------
| CHECKING FOR REGISTERERED USER
| -------------------------------------------------------------------
| it returns true if user is registered with us
| -------------------------------------------------------------------|
*/	
	public function is_registered_user() {
		if ($this->getMethod() === 'GET') {
			if ($response = $this->users->is_registered_user($this->get)) {
				http_response(200, 1, $response);
				return TRUE;
			}else{ 
				http_response(401, 0);
				return FALSE;
			}
		}else{
			http_response(405, 0);
			return FALSE;
		}
	}
/*
| [:START:]
| -------------------------------------------------------------------
| CREATE IMAGE AND SAVE ON A SERVER
| -------------------------------------------------------------------
| this function create image from binary data and save the image at specified location on a server
| -------------------------------------------------------------------|
*/	
	public function create_image_from_binary($args = array()) {
		if ( ! $args) {
			http_response(400, 0);
			return FALSE;
		}
		if ($args['data']) {
			$data = @json_decode($args['data'], TRUE);
			if (@is_array($data)) {
				if ($binary_data = @base64_decode($data['profile_pic'])) {
					$file_extention = new finfo(FILEINFO_MIME);
					$mime_str = strtok($file_extention->buffer($binary_data), ';');
					$mime_arr = explode('/', $mime_str);
					$FILE_NAME = "assets/images/profile/".str_ireplace(' ', '', $data['user_name']).'.'.$mime_arr[1];
					$image = @imagecreatefromstring($binary_data);
					if ($image !== FALSE) {
						if(@file_put_contents($FILE_NAME, $binary_data)) {
							@imagedestroy($image);
							unset($data['profile_pic']);
							$data['user_image'] = $FILE_NAME;
							return array("data" => json_encode($data));
						} else {
						    http_response(500, 0);
							return FALSE;
						}
					} else {
					    http_response(500, 0);
						return FALSE;
					}	
				} else {
					unset($data['profile_pic']);
					return array("data" => json_encode($data));
				}	
			} else {
				http_response(400, 0);
				return FALSE;
			}
		} else {
			http_response(400, 0);
			return FALSE;
		}
	}
/*
| [:START:]
| -------------------------------------------------------------------
| UPLOAD IMAGE ON SERVER
| -------------------------------------------------------------------
| FUNCTION is used to upload the image on server
| -------------------------------------------------------------------|
*/	
	public function upload_file() {
		if ( ! $_REQUEST['token']) {
			http_response(400, 0);
			return FALSE;
		}
		$data = array(
					"token" => $_REQUEST['token'],
					"user_name" => $_REQUEST['user_name'],
					"time_difference" => $_REQUEST['time_difference']
				);
		if(array_key_exists('profile_pic', $_FILES) && array_key_exists('name', $_FILES['profile_pic']) && $_FILES['profile_pic']['name']) {	
			$targetDir = "assets/images/profile/";
			$targetFile = $targetDir . time();
			$imageFileType = pathinfo($targetDir . basename($_FILES['profile_pic']['name']),PATHINFO_EXTENSION);
			$check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
			if($check !== false) {
				if(move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
					 $data['user_image'] = $targetFile;	
					 return array("data" => json_encode($data));					
				}
				else {
				    http_response(500, 0);
					return FALSE;
				}
			}		
		}
		else {
			return array("data" => json_encode($data));
		}
	}
/*
| [:START:]
| -------------------------------------------------------------------
| SEND PUSH-NOTIFICATION FOR ANDROID DEVICE
| -------------------------------------------------------------------
| FUNCTION is used to send notification over mobile device
| -------------------------------------------------------------------|
*/	
	public function send_push_notification($args = array()) {
		ob_start();
		if ( ! $args) {
			http_response(400, 0);
			return FALSE;
		}	
		if ($args['device_type'] == 3) {	 								//IF DEVICE IS IOS
				$message = $args['notification'];
				$deviceToken = $args['device_id'];
				$passphrase = '';				
				$ctx = stream_context_create();
				$pushFileDirectory = "certify/Venturistic_dev_29-12-2016.pem";
				stream_context_set_option($ctx, 'ssl', 'local_cert', $pushFileDirectory);
			    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
				
				// Open a connection to the APNS server
				$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $errorno, $errorstr, 2, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
			    /*$fp = stream_socket_client(
			   	 |	// 'ssl://gateway.sandbox.push.apple.com:2195', $err,
			     |  'ssl://gateway.push.apple.com:2195', $err,
			     |   $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
				 */
			    if (!$fp) {
			        $log_file = "err_log.txt";
			        $fh = fopen($log_file, 'a') or die("can't open file");
			        $stringData = "Failed to connect: $err $errstr" . PHP_EOL;
			        fwrite($fh, $stringData);
			        fclose($fh);
				//	die("faile to connect with stream socket");
			    }
				
				// Encode the payload as JSON   
    			$payload = json_encode(['aps' => ['alert' =>$message,'sound' => 'default','badge' => 0]]);
				
			    // Build the binary notification
			    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			   
			    // Send it to the server
			    $result = fwrite($fp, $msg, strlen($msg));
			    if (!$result){
			        //echo 'Message not delivered' . PHP_EOL;
			        $log_file = "err_log.txt";
			        $fh = fopen($log_file, 'a') or die("can't open file");
			        $stringData = "notificatin not sent\n";
			        fwrite($fh, $stringData);
			        fclose($fh);
			 		// die("notification not sent");
			
			    } else {
			        //echo 'Message successfully delivered' . PHP_EOL;
			        /*$myFile = "testFile.txt";
			         | $fh = fopen($myFile, 'a') or die("can't open file");
			         | $stringData = "notificatin sent\n".$payload;
			         | fwrite($fh, $stringData);
			         | fclose($fh);
					*/
			        return true;
			    }
			    fclose($fp);
			    return true;	
		}
		elseif ($args['device_type'] == 2) { 						// IF DEVICE IS ANDROID
				$fields = json_encode(array (
				            'to' => $args['device_id'],					//device id to send notification
				            'priority' => 'normal',
							'notification' => array(
												    "body" => "This week’s memory is now available.",
												    "title" => "MicroJournal",
												    "icon" => "new",
							  				  ),		             
				             'data' => array(
				                    		"Message" => $args['notification']  //notification message.. is going to deliver 
				            		   )
				    			));
		
				$headers = array (
			            'Authorization: key=' . "$this->fcm_key", 				//FCM key for verification
			            'Content-Type: application/json'
			    );
		
				// Open connection
		        $ch = curl_init();
		 
		        // Set the url, number of POST vars, POST data
		        curl_setopt($ch, CURLOPT_URL, $this->fcm_api);
		 
		        curl_setopt($ch, CURLOPT_POST, true);
		        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 
		        // Disabling SSL Certificate support temporarly
		        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		 
		        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		 
		        // Execute post
		        $result = curl_exec($ch);
		        if ($result === FALSE) {
		            //die('Curl failed: ' . curl_error($ch));
		        	return false;
				}
		 
		        // Close connection
		        curl_close($ch);
		        ob_flush();			
			//	return $result;
			return true;	
		}
		return FALSE;
	}

/*
| [:START:]
| -------------------------------------------------------------------
| BACK-UP WHOLE DATABASE
| -------------------------------------------------------------------
| FUNCTION is used to back-up whole database
| -------------------------------------------------------------------|
*/	
	public function dump_db() {
		$this->load->dbutil();
        $prefs = array(     
                'format'      => 'zip',             
                'filename'    => 'micro_journal.sql'
              );

        $backup =& $this->dbutil->backup($prefs); 

        $db_name = 'micro-journal-db-backup-on-'. date("Y-m-d-H-i-s") .'.zip';
        $save = $db_name;

        $this->load->helper('file');
        write_file($save, $backup); 

        $this->load->helper('download');
        force_download($db_name, $backup); 
		http_response(200, 1);
	}
/*[:END:]*/	
}
