<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

	private $method_type=''; 
	private $getTimestamp;
	public function __construct() {
         parent::__construct();
		$this->load->model('admin_model');
		$this->load->model('api_model');
		$this->load->model('api_test_exercise_model');
	}
	
	public function index(){
		_dx(__CLASS__ ."/". __function__);	 
	}

	public function get_source_language(){

		$lang = $this->input->get_post('lang');
		$data = array('lang'=>$lang);
		$this->api_model->get_source_lang($data);	
	}
	public function get_support_language(){
		$this->api_model->get_support_lang();
	}	

	public function get_target_language() {
		
		 $this->api_model->get_target_lang();
	}	

	public function get_exercise_mode() {

		 $lang = $this->input->get_post('lang');
		 $data = array('lang'=>$lang);
		 $this->api_model->get_exercise_mode($data);	
	}

	public function get_category_list() {
		
		$lang = $this->input->get_post('lang');
		$exercise_mode_id = $this->input->get_post('exercise_mode_id');
		$support_lang_id = $this->input->get_post('support_lang_id');
		$data = array('lang'=>$lang, 'exercise_mode_id'=>$exercise_mode_id, 'support_lang_id'=>$support_lang_id);

		$this->api_model->get_category_list($data);	
		
	}
	public function get_subcategory_list() {
		
		$lang = $this->input->get_post('lang');
		$category_id = $this->input->get_post('category_id');
		$support_lang_id = $this->input->get_post('support_lang_id');
		$user_id = $this->input->get_post('user_id');
		$data = array('lang'=>$lang,'category_id'=>$category_id,'user_id'=>$user_id,'support_lang_id'=>$support_lang_id);
		$this->api_model->get_subcategory_list($data);	
		
	}
	public function get_exercise_type(){
		
		$lang = $this->input->get_post('lang');
		$category_id = $this->input->get_post('subcategory_id');
		$support_lang_id = $this->input->get_post('support_lang_id');
		$data = array('lang'=>$lang,'subcategory_id'=>$category_id, 'support_lang_id'=>$support_lang_id);
		$this->api_model->get_exercise_type_list($data);	
		
	}

	function vocabulary_exercise(){

			$source_lang = $this->input->get_post('lang');
			$target_lang = $this->input->get_post('target_lang');
			$exercise_mode = $this->input->get_post('exercise_mode_id');
			$category_id = $this->input->get_post('category_id');
			$subcategory_id = $this->input->get_post('subcategory_id');
			$type = $this->input->get_post('type');
			$support_lang_id = $this->input->get_post('support_lang_id');

			$data = array(
						
						'slang'=>$source_lang,
						'tlang'=>$target_lang,
						'exercise_mode_id'=>$exercise_mode,
						'category_id'=>$category_id,
						'subcategory_id'=>$subcategory_id,
						'support_lang_id'=>$support_lang_id
					);

			if($type=="1"){
				$this->api_model->get_sorce_lan_word_type_1($data);	

			}else if($type=="2"){
				$this->api_model->get_sorce_lan_word_type_2($data);	

			}else if($type=="3"){
				$this->api_model->get_sorce_lan_word_type_3($data);	
				
			}else if($type=="4"){
				$this->api_model->get_sorce_lan_word_type_4($data);	
				
			}else if($type=="5"){
				$this->api_model->get_sorce_lan_word_type_5($data);	
				
			}else if($type=="6"){
				$this->api_model->get_sorce_lan_word_type_6($data);	
				
			}else if($type=="7"){
				$this->api_model->get_sorce_lan_word_type_7($data);	
				
			}else if($type=="8"){
				$this->api_model->get_sorce_lan_word_type_8($data);	
				
			}else if($type=="9"){
				$this->api_model->get_sorce_lan_word_type_9($data);	
			}
			else{

					http_response(404, 0, 'Invalid Exercise Type', '','');
			}

	}

	function grammar_exercise(){

			$source_lang = $this->input->get_post('lang');
			$target_lang = $this->input->get_post('target_lang');
			$exercise_mode = $this->input->get_post('exercise_mode_id');
			$category_id = $this->input->get_post('category_id');
			$subcategory_id = $this->input->get_post('subcategory_id');
			$support_lang_id = $this->input->get_post('support_lang_id');
			$type = $this->input->get_post('type');

			$data = array(
						
						'slang'=>$source_lang,
						'tlang'=>$target_lang,
						'exercise_mode_id'=>$exercise_mode,
						'category_id'=>$category_id,
						'subcategory_id'=>$subcategory_id,
						'support_lang_id'=>$support_lang_id

					);
				
				if($type=="10"){

					$this->api_model->get_grammer_type_1($data);

				}else if($type=="11"){

					$this->api_model->get_grammer_type_2($data);

				}else{

					http_response(404, 0, 'Invalid Exercise Type', '','');
				}
				
	}

	function phrases_exercise(){

			$source_lang = $this->input->get_post('lang');
			$target_lang = $this->input->get_post('target_lang');
			$exercise_mode = $this->input->get_post('exercise_mode_id');
			$category_id = $this->input->get_post('category_id');
			$subcategory_id = $this->input->get_post('subcategory_id');
			$support_lang_id = $this->input->get_post('support_lang_id');
			$type = $this->input->get_post('type');
			$data = array(
						'slang'=>$source_lang,
						'tlang'=>$target_lang,
						'exercise_mode_id'=>$exercise_mode,
						'category_id'=>$category_id,
						'subcategory_id'=>$subcategory_id,
						'support_lang_id'=>$support_lang_id
					);
			if($type=="12"){
				$this->api_model->get_phrases_type_1($data);
			}else{
				http_response(404, 0, 'Invalid Exercise Type', '','');
			}			
	}

	function dialogues_exercise(){

		$source_lang = $this->input->get_post('lang');
		$target_lang = $this->input->get_post('target_lang');
		$exercise_mode = $this->input->get_post('exercise_mode_id');
		$category_id = $this->input->get_post('category_id');
		$subcategory_id = $this->input->get_post('subcategory_id');
		$support_lang_id = $this->input->get_post('support_lang_id');
		$type = $this->input->get_post('type');
		$data = array(
					'slang'=>$source_lang,
					'tlang'=>$target_lang,
					'exercise_mode_id'=>$exercise_mode,
					'category_id'=>$category_id,
					'subcategory_id'=>$subcategory_id,
					'support_lang_id'=>$support_lang_id
				);
			if($type=="13"){
				$this->api_model->get_dialogue_type_1($data);
			}else if($type=="14"){
				$this->api_model->get_dialogue_type_1($data);
			}else{
				http_response(404, 0, 'Invalid Exercise Type', '','');
			}
	}

	function culture_exercise(){

			$source_lang = $this->input->get_post('lang');
			$target_lang = $this->input->get_post('target_lang');
			$exercise_mode = $this->input->get_post('exercise_mode_id');
			$category_id = $this->input->get_post('category_id');
			$subcategory_id = $this->input->get_post('subcategory_id');
			$support_lang_id = $this->input->get_post('support_lang_id');
			$type = $this->input->get_post('type');
			$data = array(
						'slang'=>$source_lang,
						'tlang'=>$target_lang,
						'exercise_mode_id'=>$exercise_mode,
						'category_id'=>$category_id,
						'subcategory_id'=>$subcategory_id,
						'support_lang_id'=>$support_lang_id
					);
			if($type=="15"){
				$this->api_model->get_culture_type_1($data);
			}else{
				http_response(404, 0, 'Invalid Exercise Type', '','');
			}
	}

	function aural_exercise(){

		$source_lang = $this->input->get_post('lang');
		$target_lang = $this->input->get_post('target_lang');
		$exercise_mode = $this->input->get_post('exercise_mode_id');
		$category_id = $this->input->get_post('category_id');
		$subcategory_id = $this->input->get_post('subcategory_id');
		$type = $this->input->get_post('type');
		$support_lang_id = $this->input->get_post('support_lang_id');

		$data = array(
					
					'slang'=>$source_lang,
					'tlang'=>$target_lang,
					'exercise_mode_id'=>$exercise_mode,
					'category_id'=>$category_id,
					'subcategory_id'=>$subcategory_id,
					'support_lang_id'=>$support_lang_id
				);

		if($type=="16"){
			$this->api_model->get_sorce_lan_word_type_16($data);	
		}else if($type=="17"){
			$this->api_model->get_sorce_lan_word_type_17($data);	
		}else if($type=="18"){
			$this->api_model->get_sorce_lan_word_type_18($data);	
		}else{

			http_response(404, 0, 'Invalid Exercise Type', '','');
		}

	}

/*
* START :  APP User Register  @ 25-Dec-2017 - By: Nimesh Patel 
*/

	function register(){

			$first_name = $this->input->get_post('first_name');
			$last_name = $this->input->get_post('last_name');
			$email = $this->input->get_post('email');
			$password = $this->input->get_post('password');
			$confirm_password = $this->input->get_post('confirm_password');
			$social_id = $this->input->get_post('social_id');
			$social_type = $this->input->get_post('social_type');
			$user_image = $this->input->get_post('profile_pic');
			$data = array(
				
						'first_name'=>$first_name,
						'last_name'=>$last_name,
						'email'=>$email,
						'password'=>$password,
						'confirm_password'=>$confirm_password,
						'social_id'=>$social_id,
						'social_type'=>$social_type,
						'user_image'=>$user_image,
					);

			if($social_type == "0"){
				//echo "here"; die();

				if(array_key_exists('profile_pic', $_FILES) && array_key_exists('name', $_FILES['profile_pic']) && $_FILES['profile_pic']['name']) {
						
					$targetDir = "uploads/user_profile/";
					
					$imageFileType = pathinfo($targetDir . basename($_FILES['profile_pic']['name']),PATHINFO_EXTENSION);
					$targetFile = $targetDir . time().".".$imageFileType;
					$check = getimagesize($_FILES["profile_pic"]["tmp_name"]);

						if($check !== false) {
							
							if(move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
								 $data['user_image'] = $targetFile;	
								// return array("data" => json_encode($data));					
							}
							else {
							    http_response(500, 0);
								return FALSE;
							}
					}		
				}
			}
		
	    $this->api_model->user_register($data);


	}
		
/*
* END :  APP User Register  @ 25-Dec-2017 - By: Nimesh Patel 
*/

		function login(){

					$email = $this->input->get_post('email');
					$password =$this->input->get_post('password');
					
					$data = array(
						'email'=>$email,
						'password'=>md5($password),
					);
					$sessiondata = $this->admin_model->master_function_get_data_by_condition('tbl_users',$data);
					//print_r($data); die();
					$this->api_model->user_login($data);

		}

		function edit_profile(){

			$first_name = $this->input->get_post('first_name');
			$last_name = $this->input->get_post('last_name');
			$email = $this->input->get_post('email');
			$user_id = $this->input->get_post('user_id');
			$is_remove_pic = $this->input->get_post('is_remove_pic');
			$current_password = $this->input->get_post('current_password');
			$new_password = $this->input->get_post('new_password');
			$con_new_password = $this->input->get_post('con_new_password');
			$social_type = $this->input->get_post('social_type');


			$data = array(

						'first_name'=>$first_name,
						'last_name'=>$last_name,
						'email'=>$email,
						'user_id'=>$user_id,
						'is_remove_pic'=>$is_remove_pic,
						'current_password'=>$current_password,
						'new_password'=>$new_password,
						'con_new_password'=>$con_new_password,
						'social_type'=>$social_type,
						'user_image'=>''
					);

			if(array_key_exists('profile_pic', $_FILES) && array_key_exists('name', $_FILES['profile_pic']) && $_FILES['profile_pic']['name']) {	
			$targetDir = "uploads/user_profile/";
			
			$imageFileType = pathinfo($targetDir . basename($_FILES['profile_pic']['name']),PATHINFO_EXTENSION);
			$targetFile = $targetDir . time().".".$imageFileType;
			$check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
				if($check !== false) {
					if(move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
						 $data['user_image'] = $targetFile;	
						// return array("data" => json_encode($data));					
					}else{+
						
					    http_response(500, 0);
						return FALSE;
					}
				}		
			}
	  
	  	 	$this->api_model->user_edit_profile($data);
		}

		function submit_user_score(){

					$user_id = $this->input->get_post('user_id');
					$total_score =$this->input->get_post('total_score');
					$correct_score =$this->input->get_post('correct_score');
					$category_id =$this->input->get_post('category_id');
					$subcategory_id =$this->input->get_post('subcategory_id');
					$type_id =$this->input->get_post('type_id');
					$data = array(
						'user_id'=>$user_id,
						'total_score'=>$total_score,
						'correct_score'=>$correct_score,
						'category_id'=>$category_id,
						'subcategory_id'=>$subcategory_id,
						'type_id'=>$type_id,
					);

					$this->api_model->submit_user_score($data);

		}

		function get_user_info(){

					$user_id = $this->input->get_post('user_id');
					
					$data = array(
						'user_id'=>$user_id,
					);

					//print_r($data); die();
					$this->api_model->get_user_info($data);

		}

		function forgot_password(){
			//phpinfo();
					$email = $this->input->get_post('email');
					
					$data = array(
						'email'=>$email,
					);

					//print_r($data); die();
					$this->api_model->forgot_password($data);

		}


		function test_exercise_section(){

			$source_lang = $this->input->get_post('lang');
			$target_lang = $this->input->get_post('target_lang');
			$exercise_mode = $this->input->get_post('exercise_mode_id');
			$type = $this->input->get_post('type');
			$question = $this->input->get_post('question');
			$support_lang_id = $this->input->get_post('support_lang_id');
			$data = array(
						'slang'=>$source_lang,
						'tlang'=>$target_lang,
						'exercise_mode_id'=>$exercise_mode,
						'type'=>$type,
						'question'=>$question,
						'support_lang_id'=>$support_lang_id
					);
				
				$this->api_test_exercise_model->test_exercise_section($data);		
		}
		public function test_exercise_type(){
		
			$lang = $this->input->get_post('lang');
			$exercise_mode_id = $this->input->get_post('exercise_mode_id');
			$target_lang = $this->input->get_post('target_lang');

			$data = array('lang'=>$lang,'exercise_mode_id'=>$exercise_mode_id,'tlang'=>$target_lang,);
			$this->api_test_exercise_model->test_exercise_type_list($data);	
		
		}



	
/*[:END:]*/	
}
