<?php
class Api_model extends CI_Model {
 
	
	public function __construct() {
	   parent::__construct();
		$date = new DateTime();
		$this->getTimestamp = $date->getTimestamp();
 
	}

	public function get_source_lang($data){
		$ret = array();
		$result = $this->db->query("select * from tbl_source_language WHERE `status` = '1' ")->result_array();
		
		if($result) {
			foreach ($result as $value) {
				if($value['image'])
				{
					$temp = COUNTRY_IMAGE."languge/Flags/".$value['image'];
				}else{
					$temp = COUNTRY_IMAGE.'thumb_image_not_available.png';
				}
				$dt = array('source_language_id'=>$value['source_language_id'],
							'support_lang_id'=>$value['support_lang_id'],
							'language_name'=>$value['language_name'],
							'language_code'=>$value['language_code'],
							'field_name'=>$value['field_name'],
							'image'=>$temp);
				$ret[] = $dt;
			}
			http_response(200, 1, 'Record found', $ret,'');
			
		} 
		else{

			http_response(404, 0, 'Record not found', $result,'');
			//error_log_api('fail',$data);	
			return false;

		}
	}
	public function get_support_lang(){
		$ret = array();
		$result = $this->db->query("select * from tbl_master_language WHERE status = '1'")->result_array();
		
		if($result) {
			foreach ($result as $value) {
				if($value['image'])
				{
					$temp = COUNTRY_IMAGE."languge/Flags/".$value['image'];
				}else{
					$temp = COUNTRY_IMAGE.'thumb_image_not_available.png';
				}
				
				$dt = array('support_lang_id'=>$value['support_lang_id'],
							'lang_name'=>$value['lang_name'],
							'lang_code'=>$value['lang_code'],
							'field_name'=>$value['field_name'],
							'image'=>$temp);
				$ret[] = $dt;
			}
			http_response(200, 1, 'Record found', $ret,'');
			
		} 
		else{

			http_response(404, 0, 'Record not found', $result,'');
			//error_log_api('fail',$data);	
			return false;

		}
	}

	public function get_target_lang(){
		
		$result = $this->db->query("select * from tbl_target_language")->result_array();
		
		if($result){
			http_response(200, 1, 'Record found', $result,'');	
		} 
		else{
			http_response(404, 0, 'Record not found', $result,'');
			return false;
		}
		
	}

	public function get_exercise_mode($data){
		
		// $msg = 'get_exercise_mode->'. json_encode($data);
		// log_message('debug', $msg );

		$result = $this->db->query("select * from tbl_exercise_mode")->result_array();
		if($result) {
			http_response(200, 1, 'Record found', $result,'');	
		} 
		else{
			http_response(404, 0, 'Record not found', $result,'');
			return false;
		}
	}

	public function get_category_list($data){
		
		// $msg = 'get_category_list->'. json_encode($data);
		// log_message('debug', $msg ); 

		 $menu_lang = $data['lang'];
		 $support_lang_id = $data['support_lang_id'];
		 $exercise_mode = $data['exercise_mode_id'];


		 if($support_lang_id=="" || $exercise_mode=="" ){

				http_response(404, 0, 'Parameters not passed', array(),'');
				return false;
				
		 }else{
				
				$get_field_name = $this->db->query("SELECT * FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array();

				$language_code = $get_field_name[0]['lang_code'];


				$result = $this->db->query("select exercise_mode_category_id,image,category_name_in_$language_code as category_name from tbl_exercise_mode_categories where support_lang_id='$menu_lang' AND exercise_mode_id='$exercise_mode' AND is_active='1' AND is_delete='0' order by category_name_in_$language_code asc")->result_array();
				/*echo "<pre>";
				print_r($result);
				exit;*/
				foreach ($result as $key => $value){

					if($value['image']==""){

						$result[$key]['image_path'] = COUNTRY_IMAGE.'thumb_image_not_available.png';
					
					}else{
						$path = $this->config->item('root_path');
						if(file_exists($path.'/uploads/'.$value['image']))
						{
							$result[$key]['image_path'] = MEDIA_URL.'uploads/'.$value['image'];
						}else{

							$result[$key]['image_path'] = COUNTRY_IMAGE.'thumb_image_not_available.png';
						}
						
					}
					
				}
				if($result){

					http_response(200, 1, 'Record found', $result,'');

				} 
				else{
					
					http_response(404, 0, 'Record not found', $result,'');
					return false;
				}
		}
	}


	public function get_subcategory_list($data) {
	
		// $msg = 'get_subcategory_list->'. json_encode($data);
		// log_message('debug', $msg );

		 $lang = $data['lang'];
		 $category_id = $data['category_id'];
		 $support_lang_id = $data['support_lang_id'];
		 $user_id = $data['user_id'];
		 //print_r($data); die();
		 if($support_lang_id=="" || $category_id==""){
				http_response(404, 0, 'Parameters not passed', array(),'');
				return false;
		 }else{
		 	
				$get_field_name = $this->db->query("SELECT * FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array();

				$language_code = $get_field_name[0]['lang_code'];

				
				$result = $this->db->query("select exercise_mode_subcategory_id,category_id,difficulty_level_id,image,subcategory_name_in_$language_code as subcategory_name from tbl_exercise_mode_subcategories where category_id='$category_id' AND is_active='1' AND is_delete='0' order by subcategory_name_in_$language_code asc")->result_array();
				/*echo "<pre>";
				print_r($result);
				exit;*/
				foreach ($result as $key => $value){
							
							if($value['image']==""){

								$result[$key]['image_path'] = COUNTRY_IMAGE.'thumb_image_not_available.png';

							}else{
								$path = $this->config->item('root_path');
								if(file_exists($path.'/uploads/'.$value['image']))
								{
									$result[$key]['image_path'] = MEDIA_URL.'uploads/'.$value['image'];
								}else{
									$result[$key]['image_path'] = COUNTRY_IMAGE.'thumb_image_not_available.png';
								}
						
							}
					/* ==============================================START RATING CODE ==============================================================================*/
						$subcategory_id = $value['exercise_mode_subcategory_id'];

						$result_1 = $this->db->query("select * from tbl_exercise_mode_categories_exercise WHERE category_id='$subcategory_id'")->result_array();
						$total_exercise_type = count($result_1); 
					    $fifty_per = round(($total_exercise_type * 50)/100);
					    $seventyfive_per = round(($total_exercise_type * 75)/100);
					    
					    $user_con="";
					    if($user_id!=""){
							$user_con =" AND user_id='$user_id'";
						}
					    $result_2 = $this->db->query("select type_id,count(type_id) total,category_id,subcategory_id from tbl_user_score WHERE category_id='$category_id' AND subcategory_id='$subcategory_id' $user_con  group by type_id")->result_array();
					//   print_r($result_2); die();
					    $unique_count_1 = 0;
					    $unique_count_2 = 0;
					    $unique_count_3=0;
					    if(count($result_2) > 0){

					    	foreach ($result_2 as $k => $val) {
					    					//echo $val['total'];
					 				if($val['total'] >= 1 && $val['total'] < 5){
					 					 $unique_count_1++;
					 				}
					 				if($val['total'] >= 5 && $val['total'] < 10){
					 					 $unique_count_2++;
					 				}
					 				if($val['total'] >= 10){
					 					 $unique_count_3++;
					 				}

					  		}
					    }

					      	  if($fifty_per <= $unique_count_1){
							  		$result[$key]['ratting'] = 1;
							  }else if($fifty_per <= $unique_count_2){
							  		$result[$key]['ratting'] = 2;
							  }else if($seventyfive_per <= $unique_count_3){
							  		$result[$key]['ratting'] = 3;
							  }else{
							  		$result[$key]['ratting'] = 0;
							  }

					    

					/* ==============================================END RATING CODE ==============================================================================*/

				}


					//die();
				
				if($result){
					
					http_response(200, 1, 'Record found', $result,'');

				}else{
					
					http_response(404, 1, 'Record not found', $result,'');
					return false;
				}
		}
	}

	public function get_exercise_type_list($data){
		

		// $msg = 'get_exercise_type_list->'. json_encode($data);
		// log_message('debug', $msg );

			$lang = $data['lang'];
			$support_lang_id = $data['support_lang_id'];
			$category_id = $data['subcategory_id'];
			if($support_lang_id=="" || $category_id==""){

					http_response(404, 0, 'Parameters not passed', array(),'');
					return false;
			
			}else{

				$get_field_name = $this->db->query("SELECT * FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array();

				$language_code = $get_field_name[0]['lang_code'];
				
					$result = $this->db->query("select type.id, type.type_$language_code as type_name,type.image  from tbl_exercise_type type LEFT JOIN tbl_exercise_mode_categories_exercise ex ON type.id=ex.exercise_type_id where ex.category_id='$category_id' AND ex.is_active='1' order by type.sequence asc")->result_array();

					foreach ($result as $key => $value){
						
						if($value['image']==""){

							$result[$key]['image_path'] = base_url().'assets/thumb_image_not_available.png';

						}else{

							$result[$key]['image_path'] = UPLOAD_URL.'uploads/'.$value['image'];
						}

					}
					if($result){

						http_response(200, 1, 'Record found',$result,'');	
					} 
					else{

						http_response(404, 1, 'Record not found', $result,'');
						return false;
					}
			}
	}
	
	public function get_sorce_lan_word_type_1($data){
		

		// $msg = 'get_sorce_lan_word_type_1->'. json_encode($data);
		// log_message('debug', $msg );


			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$support_lang_id = $data['support_lang_id'];
			$limit = $this->config->item('api_record_limit');
			
			if($support_lang_id=="" ||$slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

					http_response(404, 0, 'Parameters not passed', array(),'');
					return false;
			
			}else{
				//================================================== For GETTING Quetions==================================================
					//$get_field_name = $this->db->query("SELECT field_name FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
					$get_field_name = $this->db->query("SELECT field_name, lang_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$result = $this->db->query("SELECT $field_name as word,word_id,is_image_available,image_file
								FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1' ")->result_array();
					
					$lang_code = $get_field_name[0]['lang_code'];
					foreach ($result as $key => $value) {
					  
					if($value['is_image_available'] == '1')
					{
						$result[$key]['image_path']=MEDIA_URL.'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value['image_file'];			
					}
				//================================================== FOR GETTING 3 WRONG OPTIONS==================================================
					$get_field_name = $this->db->query("SELECT field_name FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
					
					$field_name = $get_field_name[0]['field_name'];
					$queid = $value['word_id'];
					$option = $this->db->query("SELECT $field_name as word
								FROM tbl_word where subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND word_id!=$queid AND is_active='1'")->result_array();

					shuffle($option);
					$option = array_slice($option, 0, 3);
					$result[$key]['option'] = array();
					if(!empty($option) && count($option) > 0)
					{
						$result[$key]['option'] = $option;
						foreach ($option as $key1 => $value1) {
							$result[$key]['option'][$key1]['is_correct'] = 0;
						}
					} 
									//==================================================For GETTING  currect option================================================================
							$wid = $result[$key]['word_id'];

							$option1 = $this->db->query("SELECT $field_name as word
								FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND word_id=$wid AND is_active='1'")->result_array();
							$option1[0]['is_correct'] = 1;
							$arr  = array_push($result[$key]['option'], $option1[0]);
							// foreach ($option1 as $key2 => $value2) {
							// 	$result[$key]['option'][3]['is_correct'] = 1;
							// }
								// for random index of options
							shuffle($result[$key]['option']);
					} // END FOR LOOP OF RESULT ARRAY
										//================================================== For Random RESULTS==================================================
						shuffle($result);
						$result = array_slice($result, 0, $limit); 
						if($result)
						{
							http_response(200, 1, 'Record found',$result,'');
						}else{

							http_response(400, 0, 'Record not found',$result,'');
						}
							
		}
	}



	public function get_sorce_lan_word_type_2($data)
	{
		// $msg = 'get_sorce_lan_word_type_2->'. json_encode($data);
		// log_message('debug', $msg );


		$slang = $data['slang'];
		$tlang = $data['tlang'];
		$category_id = $data['category_id'];
		$subcategory_id = $data['subcategory_id'];
		$exercise_mode_id = $data['exercise_mode_id'];
		$support_lang_id =  $data['support_lang_id'];
		$limit = $this->config->item('api_record_limit');

		
		if($support_lang_id == "" || $slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				http_response(404, 0, 'Parameters not passed', array(),'');
				return false;
		
		}else{

				//================================================== For GETTING Quetions==================================================
				
				// $get_field_name = $this->db->query("SELECT field_name,lang_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array(); //New Target change
				$get_field_name = $this->db->query("SELECT field_name,language_code as lang_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
				$field_name = $get_field_name[0]['field_name'];
				
				$result = $this->db->query("SELECT $field_name as word,word_id,image_file,word_english,audio_file
							FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1' AND is_image_available='1' AND is_audio_available='1'")->result_array();

				foreach ($result as $key => $value) {
				
				$result[$key]['image_path']=MEDIA_URL.'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value['image_file'];
				
				$language_code = $get_field_name[0]['lang_code']; 
				 $aname = str_replace(" ","_",$value['audio_file']); 
				
				$root_path  = $this->config->item('root_path');


				// $aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id.'/'.$aname.'_'.$language_code.'.m4a';
				$aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id.'/'.$aname;

				if(file_exists($aufile) && !empty($aname)){

					// $result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					$result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
					$result[$key]['is_audio_available'] = 1;

				}else{

					$result[$key]['audio_file']="";
					$result[$key]['is_audio_available']=0;

				}
				 
					
				//================================================== FOR GETTING 3 WRONG OPTIONS==================================================

				$queid = $value['word_id'];
				$get_field_name = $this->db->query("SELECT field_name,lang_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array();
				$field_name = $get_field_name[0]['field_name'];

				$option = $this->db->query("SELECT $field_name as word
							FROM tbl_word where subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND word_id!=$queid AND is_active='1'")->result_array();

				shuffle($option);
				$option = array_slice($option, 0, 3); 
				$result[$key]['option'] = array();
				if(!empty($option) && count($option) > 0)
				{
					$result[$key]['option'] = $option;
					foreach ($option as $key1 => $value1) {
						$result[$key]['option'][$key1]['is_correct'] = 0;
					 }
				}
					//==================================================For GETTING  currect option================================================================
						$wid = $result[$key]['word_id'];
						$option1 = $this->db->query("SELECT $field_name as word
							FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND word_id=$wid AND is_active='1'")->result_array();
						$option1[0]['is_correct'] = 1;
						$arr  = array_push($result[$key]['option'], $option1[0]);
						
						 // foreach ($option1 as $key2 => $value2) {

							// $result[$key]['option'][3]['is_correct'] = 1;
						 // }
							
							// for random index of options
							shuffle($result[$key]['option']);
						
				} // END FOR LOOP OF RESULT ARRAY
						//================================================== For Random RESULTS==================================================
						shuffle($result);
						$result = array_slice($result, 0, $limit); 
					  

						if($result)
						{
							http_response(200, 1, 'Record found',$result,'');
						}else{

							http_response(400, 0, 'Record not found',$result,'');
						}	
			
		}
	}


	public function get_sorce_lan_word_type_3($data)
	{
		// $msg = 'get_sorce_lan_word_type_3->'. json_encode($data);
		// log_message('debug', $msg );

		$slang = $data['slang'];
		$tlang = $data['tlang'];
		$category_id = $data['category_id'];
		$subcategory_id = $data['subcategory_id'];
		$exercise_mode_id = $data['exercise_mode_id'];
		$support_lang_id = $data['support_lang_id'];
		$limit = $this->config->item('api_record_limit');

		if($support_lang_id == "" || $slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){
				http_response(404, 0, 'Parameters not passed', array(),'');
				return false;
		}else
		{
				//================================================== For GETTING Quetions==================================================
				$get_field_name = $this->db->query("SELECT field_name,lang_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array();
				$field_name = $get_field_name[0]['field_name'];

				$language_code = $get_field_name[0]['lang_code']; 
				 

				$result = $this->db->query("SELECT word_id,image_file,$field_name as word,audio_file
							FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1'")->result_array();

				foreach ($result as $key => $value)
				{
				  

				//================================================== FOR GETTING 3 WRONG OPTIONS==================================================
				$aname = str_replace(" ","_",$value['audio_file']);
				$queid = $value['word_id'];
				$get_field_name = $this->db->query("SELECT field_name FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
				$field_name = $get_field_name[0]['field_name'];

				$option = $this->db->query("SELECT $field_name as word
							FROM tbl_word where subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND word_id!=$queid AND is_active='1'")->result_array();

				shuffle($option);
				$option = array_slice($option, 0, 3); 


				 $aname = str_replace(" ","_",$value['audio_file']);
				$root_path  = $this->config->item('root_path');
				// $aufile=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
				$aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;

				if(file_exists($aufile) && !empty($aname)){

					// $result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					$result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
					$result[$key]['is_audio_available'] = 1;

				}else{

					$result[$key]['audio_file']="";
					$result[$key]['is_audio_available']=0;

				}		

				$result[$key]['option'] = array();
				if(!empty($option) && count($option) > 0)
				{
					$result[$key]['option'] = $option;
					foreach ($option as $key1 => $value1) {
						$result[$key]['option'][$key1]['is_correct'] = 0;
					}
				}
					//==================================================For GETTING  currect option================================================================
						$wid = $result[$key]['word_id'];
						$option1 = $this->db->query("SELECT $field_name as word
							FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND word_id=$wid AND is_active='1'")->result_array();

						$option1[0]['is_correct'] = 1;
						$arr  = array_push($result[$key]['option'], $option1[0]);
						// foreach ($option1 as $key2 => $value2) {
							// $result[$key]['option'][3]['is_correct'] = 1;
						// }
							
							// for random index of options
							shuffle($result[$key]['option']);
				} // END FOR LOOP OF RESULT ARRAY
					//================================================== For Random RESULTS ==================================================
			   shuffle($result);
				  $result = array_slice($result, 0, $limit); 
				   
				if($result)
				{
					http_response(200, 1, 'Record found',$result,'');
				}else{

					http_response(400, 0, 'Record not found',$result,'');
				}
		}
	}

	public function get_sorce_lan_word_type_4($data)
	{
		// $msg = 'get_sorce_lan_word_type_4->'. json_encode($data);
		// log_message('debug', $msg );

		$slang = $data['slang'];
		$tlang = $data['tlang'];
		$category_id = $data['category_id'];
		$subcategory_id = $data['subcategory_id'];
		$exercise_mode_id = $data['exercise_mode_id'];
		$limit = $this->config->item('api_record_limit');
		$support_lang_id = $data['support_lang_id'];
		
		if($support_lang_id == "" || $slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				http_response(404, 0, 'Parameters not passed', array(),'');
				return false;
		
		}else{

			//================================================== For GETTING Quetions==================================================
			// $get_field_name = $this->db->query("SELECT field_name,lang_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array(); //New Target change
			$get_field_name = $this->db->query("SELECT field_name,language_code as lang_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
			$field_name = $get_field_name[0]['field_name'];

			$result = $this->db->query("SELECT word_id,is_image_available,image_file,$field_name as word,word_english,audio_file
						FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1' ")->result_array();
			$language_code = $get_field_name[0]['lang_code'];
			foreach ($result as $key => $value) {
				if($value['is_image_available'] == '1')
				{
					$result[$key]['image_path']=MEDIA_URL.'/uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value['image_file'];
				}
			
			$aname = str_replace(" ","_",$value['audio_file']); 
			$root_path  = $this->config->item('root_path');
			// $aufile=$root_path.$language_code.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
			$aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
				
				if(file_exists($aufile) && !empty($aname)){
					// $result[$key]['audio_file']=MEDIA_URL.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					$result[$key]['audio_file']=MEDIA_URL.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
					$result[$key]['is_audio_available'] = 1;
				}else{
					$result[$key]['audio_file']="";
					$result[$key]['is_audio_available']=0;
				}	

			$result[$key]['option']=$value['word'];
				
			} // END FOR LOOP OF RESULT ARRAY
					//================================================== For Random RESULTS ==================================================
				shuffle($result);
				$result = array_slice($result, 0, $limit); 
				if($result)
				{
					http_response(200, 1, 'Record found',$result,'');
				}else{

					http_response(400, 0, 'Record not found',$result,'');
				}	
			
		}
	}

	public function get_sorce_lan_word_type_5($data)
	{
		// $msg = 'get_sorce_lan_word_type_5->'. json_encode($data);
		// log_message('debug', $msg );

		$slang = $data['slang'];
		$tlang = $data['tlang'];
		$category_id = $data['category_id'];
		$subcategory_id = $data['subcategory_id'];
		$exercise_mode_id = $data['exercise_mode_id'];
		$limit = $this->config->item('api_record_limit');
		$support_lang_id = $data['support_lang_id'];
		if($support_lang_id == "" || $slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				http_response(404, 0, 'Parameters not passed', array(),'');
				return false;
		
		}else{
				//================================================== For GETTING Quetions==================================================
				// $get_field_name = $this->db->query("SELECT field_name,lang_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array(); //New Target change
				$get_field_name = $this->db->query("SELECT field_name,language_code as lang_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
				$field_name = $get_field_name[0]['field_name'];

				$result = $this->db->query("SELECT word_id,image_file,$field_name as word,word_english,audio_file
							FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1'")->result_array();
				
				$language_code = $get_field_name[0]['lang_code'];
				foreach ($result as $key => $value) {
				  
				$result[$key]['image_path']=MEDIA_URL.'/uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value['image_file'];
				
				$aname = str_replace(" ","_",$value['audio_file']); 
				$root_path  = $this->config->item('root_path');
				// $aufile=$root_path.$language_code.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
				$aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
				if(file_exists($aufile) && !empty($aname)){

					// $result[$key]['audio_file']=MEDIA_URL.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					$result[$key]['audio_file']=MEDIA_URL.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
					$result[$key]['is_audio_available'] = 1;

				}else{

					$result[$key]['audio_file']="";
					$result[$key]['is_audio_available']=0;

				}
				} // END FOR LOOP OF RESULT ARRAY
						//================================================== For Random RESULTS==================================================
			  	shuffle($result);
			  	$result = array_slice($result, 0, $limit); 
				if($result)
				{
					http_response(200, 1, 'Record found',$result,'');
				}else{

					http_response(400, 0, 'Record not found',$result,'');
				}	
			
		}
	}

	public function get_sorce_lan_word_type_6($data)
	{

		// $msg = 'get_sorce_lan_word_type_6->'. json_encode($data);
		// log_message('debug', $msg );

		$slang = $data['slang'];
		$tlang = $data['tlang'];
		$category_id = $data['category_id'];
		$subcategory_id = $data['subcategory_id'];
		$exercise_mode_id = $data['exercise_mode_id'];
		$limit = $this->config->item('api_record_limit');
		$support_lang_id = $data['support_lang_id'];
		if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				http_response(404, 0, 'Parameters not passed', array(),'');
				return false;
		
		}else{

				//================================================== For GETTING Quetions==================================================
				
				// $get_field_name = $this->db->query("SELECT field_name,lang_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array(); //New Target change
				$get_field_name = $this->db->query("SELECT field_name,language_code as lang_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
				$field_name = $get_field_name[0]['field_name'];

				$result = $this->db->query("SELECT word_id,image_file,$field_name as word,word_english,audio_file
							FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1' AND is_image_available='1' AND is_audio_available='1'")->result_array();
				$language_code = $get_field_name[0]['lang_code'];
				foreach ($result as $key => $value)
				{
				
				 
					 // $aname = str_replace(" ","_",$value['audio_file']); 
					 // $result[$key]['audio_file']=MEDIA_URL.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
							
					//================================================== FOR GETTING 3 WRONG OPTIONS==================================================

					 $language_code = $get_field_name[0]['lang_code'];
					 $aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					// $aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					$aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
					if(file_exists($aufile) && !empty($aname)){

						// $result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
						$result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
						$result[$key]['is_audio_available'] = 1;

					}else{

						$result[$key]['audio_file']="";
						$result[$key]['is_audio_available']=0;

					}
					$queid = $value['word_id'];
					$option = $this->db->query("SELECT image_file,subcategory_id
								FROM tbl_word where subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND word_id!=$queid AND is_active='1' AND is_image_available='1'")->result_array();

					shuffle($option);
					$option = array_slice($option, 0, 1); 
					$result[$key]['option'] = $option;
					$get_cate_name = $this->db->query("SELECT category_name FROM tbl_exercise_mode_categories WHERE exercise_mode_category_id='$category_id' AND is_active='1'")->result_array();
					$category_folder = $get_cate_name[0]['category_name'];
					foreach ($option as $key1 => $value1) {
						$result[$key]['option'][$key1]['image_path']=MEDIA_URL.'uploads/words/'.$category_id.'/'.$value1['subcategory_id'] .'/'.$value1['image_file'];		
						$result[$key]['option'][$key1]['is_correct'] = 0;
					}
					//==================================================For GETTING  currect option================================================================
						$wid = $result[$key]['word_id'];
						$option1 = $this->db->query("SELECT image_file
							FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND word_id=$wid AND is_active='1' AND is_image_available='1' AND is_audio_available='1'")->result_array();

						$arr  = array_push($result[$key]['option'], $option1[0]);
						
						foreach ($option1 as $key2 => $value2) {

							$result[$key]['option'][1]['image_path']=MEDIA_URL.'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value2['image_file'];	
							$result[$key]['option'][1]['is_correct'] = 1;
						}

						// for random index of options
						shuffle($result[$key]['option']);

				}
				 // END FOR LOOP OF RESULT ARRAY
				//================================================== For Random RESULTS==================================================
				shuffle($result);
				$result = array_slice($result, 0, $limit); 
				if($result)
				{
					http_response(200, 1, 'Record found',$result,'');
				}else{
					http_response(400, 0, 'Record not found',$result,'');
				}	
				
		}
	}

	public function get_sorce_lan_word_type_7($data)
	{
			
		// $msg = 'get_sorce_lan_word_type_7->'. json_encode($data);
		// log_message('debug', $msg );

		$slang = $data['slang'];
		$tlang = $data['tlang'];
		$category_id = $data['category_id'];
		$subcategory_id = $data['subcategory_id'];
		$exercise_mode_id = $data['exercise_mode_id'];
		$limit = $this->config->item('api_record_limit');
		$support_lang_id = $data['support_lang_id'];
		//	print_r($data); die();
		
		if($support_lang_id==""||$slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				http_response(404, 0, 'Parameters not passed', array(),'');
				return false;
		
		}else{

			//================================================== For GETTING Quetions==================================================

			$result = $this->db->query("SELECT word_id
						FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1'")->result_array();

			foreach ($result as $key => $value)
			{
			  
				//================================================== FOR GETTING 3 WRONG OPTIONS==================================================

				$get_field_name = $this->db->query("SELECT field_name,lang_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array();
				$field_name = $get_field_name[0]['field_name'];

				$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();

				$field_name1 = $get_field_name[0]['field_name'];

				$option = $this->db->query("SELECT word_id,$field_name as word_s,$field_name1 as word_t
							FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id'  AND is_active='1'")->result_array();

				shuffle($option);
				$option = array_slice($option, 0, 6); 

				$result[$key]['option'] = $option;
				$result[$key]['option1'] = $option;
				foreach ($option as $key1 => $value1) {

					$result[$key]['option'][$key1]['word']=$value1['word_s'];
					$result[$key]['option1'][$key1]['word']=$value1['word_t'];
				}
					
				// for random index of options
				shuffle($result[$key]['option']);
				shuffle($result[$key]['option1']);
			} // END FOR LOOP OF RESULT ARRAY
					//================================================== For Random RESULTS==================================================
			shuffle($result);
			$result = array_slice($result, 0, $limit); 
			http_response(200, 1, 'Record not found',$result,'');
		}
	}

	public function get_sorce_lan_word_type_8($data)
	{
		
		// $msg = 'get_sorce_lan_word_type_8->'. json_encode($data);
		// log_message('debug', $msg );


		$slang = $data['slang'];
		$tlang = $data['tlang'];
		$category_id = $data['category_id'];
		$subcategory_id = $data['subcategory_id'];
		$exercise_mode_id = $data['exercise_mode_id'];
		$limit = $this->config->item('api_record_limit');
		$support_lang_id = $data['support_lang_id'];
		//	print_r($data); die();
		
		if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

				http_response(404, 0, 'Parameters not passed' , array(),'');
				return false;
		
		}else{

				//================================================== For GETTING Quetions==================================================

				$get_field_name = $this->db->query("SELECT field_name,language_code as lang_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
				$field_name = $get_field_name[0]['field_name'];

				$result = $this->db->query("SELECT word_id, image_file, $field_name as word, word_english, audio_file
							FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_active='1'")->result_array();
				//echo $this->db->last_Query();
				//print_r($result); die();
				$get_cate_name = $this->db->query("SELECT category_name FROM tbl_exercise_mode_categories WHERE exercise_mode_category_id='$category_id'")->result_array();
				$category_folder = $get_cate_name[0]['category_name'];
				$language_code = $get_field_name[0]['lang_code'];
				foreach($result as $key => $value)
				{
				  

					$result[$key]['image_path']=MEDIA_URL.'/uploads/words/'.$category_id.'/'.$subcategory_id.'/'.$value['image_file'];
					
					
					$aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					// $aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					$aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
					if(file_exists($aufile) && !empty($aname)){

						// $result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
						$result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
						$result[$key]['is_audio_available'] = 1;

					}else{

						$result[$key]['audio_file']="";
						$result[$key]['is_audio_available']=0;

					}					 
					$result[$key]['word']=$value['word'];
					

					//==================================================For GETTING  currect option================================================================
					$wid = $result[$key]['word_id'];
					$get_field_name1 = $this->db->query("SELECT field_name,lang_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array();
					$field_name = $get_field_name1[0]['field_name'];

					$option1 = $this->db->query("SELECT $field_name as word
						FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND word_id=$wid AND is_active='1'" )->result_array();

					 foreach ($option1 as $key2 => $value2) {
								$result[$key]['option']=$value2['word'];		
					
					 }
						
							
				} // END FOR LOOP OF RESULT ARRAY
					//================================================== For Random RESULTS==================================================
				shuffle($result);
				$result = array_slice($result, 0, $limit);
				if($result){

					http_response(200, 1, 'Record found',$result,'');	
				} 
				else{

					http_response(404, 1, 'Record not found', $result,'');
					return false;
				}
				//http_response(200, 1, 'Record not found',$result,'');		
		}
	}

	public function get_sorce_lan_word_type_9($data)
	{
		// $msg = 'get_sorce_lan_word_type_9->'. json_encode($data);
		// log_message('debug', $msg );

		$slang = $data['slang'];
		$tlang = $data['tlang'];
		$category_id = $data['category_id'];
		$subcategory_id = $data['subcategory_id'];
		$exercise_mode_id = $data['exercise_mode_id'];
		$limit = $this->config->item('api_record_limit');
		$support_lang_id = $data['support_lang_id'];

		if($support_lang_id=="" || $slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id=="")
		{
				http_response(404, 0, 'Parameters not passed', array(),'');
				return false;
		}else{
				//================================================== For GETTING Quetions==================================================
				$get_field_name = $this->db->query("SELECT field_name,lang_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array();
				$field_name = $get_field_name[0]['field_name'];

				$result = $this->db->query("SELECT word_id,image_file,$field_name as word,word_english,audio_file
							FROM tbl_word where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_audio_available='1' AND is_active='1'")->result_array();
				
				$language_code = $get_field_name[0]['lang_code'];
				foreach ($result as $key => $value) {
				
					
					$aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					// $aufile=$root_path.$language_code.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					$aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
					if(file_exists($aufile) && !empty($aname)){
						// $result[$key]['audio_file']=MEDIA_URL.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
						$result[$key]['audio_file']=MEDIA_URL.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
						$result[$key]['is_audio_available'] = 1;
					}else{
						$result[$key]['audio_file']="";
						$result[$key]['is_audio_available']=0;
					}

					$result[$key]['word'] = trim($value['word']);
					$result[$key]['word_english'] = trim($value['word_english']);

				} // END FOR LOOP OF RESULT ARRAY
				//================================================== For Random RESULTS==================================================
				shuffle($result);
				$result = array_slice($result, 0, $limit); 
			  	if($result)
				{
					http_response(200, 1, 'Record found',$result,'');
				}else{

					http_response(400, 0, 'Record not found',$result,'');
				}
		}
	}

	public function get_sorce_lan_word_type_16($data)
	{
		// $msg = 'get_sorce_lan_word_type_16->'. json_encode($data);
		// log_message('debug', $msg );

		$slang = $data['slang'];
		$tlang = $data['tlang'];
		$category_id = $data['category_id'];
		$subcategory_id = $data['subcategory_id'];
		$exercise_mode_id = $data['exercise_mode_id'];
		$limit = $this->config->item('api_record_limit');
		$support_lang_id = $data['support_lang_id'];

		if($support_lang_id=="" || $slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id=="")
		{
				http_response(404, 0, 'Parameters not passed', array(),'');
				return false;
		}else{
				//================================================== For GETTING Quetions==================================================
				$get_field_name = $this->db->query("SELECT field_name,lang_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array();
				$field_name = $get_field_name[0]['field_name'];

				$result = $this->db->query("SELECT aural_id,image_file,$field_name as word,word_english,audio_file
							FROM tbl_aural_composition where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND is_audio_available='1' AND is_active='1'")->result_array();
				
				$language_code = $get_field_name[0]['lang_code'];
				foreach ($result as $key => $value) {
				
					
					$aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					// $aufile=$root_path.$language_code.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					$aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
					
					if(file_exists($aufile) && !empty($aname)){
						// $result[$key]['audio_file']=MEDIA_URL.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
						$result[$key]['audio_file']=MEDIA_URL.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
						$result[$key]['is_audio_available'] = 1;
					}else{
						$result[$key]['audio_file']="";
						$result[$key]['is_audio_available']=0;
					}

				} // END FOR LOOP OF RESULT ARRAY
				//================================================== For Random RESULTS==================================================
				shuffle($result);
				$result = array_slice($result, 0, $limit); 
			  	if($result)
				{
					http_response(200, 1, 'Record found',$result,'');
				}else{

					http_response(400, 0, 'Record not found',$result,'');
				}
		}
	}

	public function get_sorce_lan_word_type_17($data)
	{
		// $msg = 'get_sorce_lan_word_type_17->'. json_encode($data);
		// log_message('debug', $msg );

		$slang = $data['slang'];
		$tlang = $data['tlang'];
		$category_id = $data['category_id'];
		$subcategory_id = $data['subcategory_id'];
		$exercise_mode_id = $data['exercise_mode_id'];
		$support_lang_id =  $data['support_lang_id'];
		$limit = $this->config->item('api_record_limit');

		
		if($support_lang_id == "" || $slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){
				http_response(404, 0, 'Parameters not passed', array(),'');
				return false;
		}else{
				//================================================== For GETTING Quetions==================================================
				
				// $get_field_name = $this->db->query("SELECT field_name,lang_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array();
				// $field_name = $get_field_name[0]['field_name'];
				$get_field_name = $this->db->query("SELECT field_name FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
				$field_name = $get_field_name[0]['field_name'];
				
				$result = $this->db->query("SELECT $field_name as word,aural_id,image_file,word_english,audio_file FROM tbl_aural_composition where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id'   AND is_audio_available='1' AND is_active='1' LIMIT $limit ")->result_array();

				foreach ($result as $key => $value)
				{
					$result[$key]['image_path']=MEDIA_URL.'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value['image_file'];
					$language_code = $get_field_name[0]['lang_code']; 
					$aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					// $aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id.'/'.$aname.'_'.$language_code.'.m4a';
					$aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id.'/'.$aname;
					if(file_exists($aufile) && !empty($aname)){
						// $result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
						$result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
						$result[$key]['is_audio_available'] = 1;
					}else{
						$result[$key]['audio_file']="";
						$result[$key]['is_audio_available']=0;
					}
					
					//================================================== FOR GETTING 3 WRONG OPTIONS==================================================

					$queid = $value['aural_id'];
					$get_field_name = $this->db->query("SELECT field_name,lang_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$option = $this->db->query("SELECT $field_name as word
								FROM tbl_aural_composition where subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND aural_id != $queid AND is_active='1'")->result_array();

					shuffle($option);
					$option = array_slice($option, 0, 3); 
					$result[$key]['option'] = array();
					if(!empty($option) && count($option) > 0)
					{
						$result[$key]['option'] = $option;
						foreach ($option as $key1 => $value1) {
							$result[$key]['option'][$key1]['is_correct'] = 0;
						 }
					}
					//==================================================For GETTING  currect option================================================================
					$wid = $result[$key]['aural_id'];
					$option1 = $this->db->query("SELECT $field_name as word FROM tbl_aural_composition where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND aural_id=$wid AND is_active='1'")->result_array();
					// $option1 = array();
					// $option1[0] = $value;
					$option1[0]['is_correct'] = 1;
					$arr  = array_push($result[$key]['option'], $option1[0]);

					// foreach ($option1 as $key2 => $value2) {

					// $result[$key]['option'][3]['is_correct'] = 1;
					// }

					// for random index of options
					shuffle($result[$key]['option']);
						
				} // END FOR LOOP OF RESULT ARRAY
			//================================================== For Random RESULTS==================================================
			shuffle($result);
			$result = array_slice($result, 0, $limit); 

			if($result)
			{
				http_response(200, 1, 'Record found',$result,'');
			}else{

				http_response(400, 0, 'Record not found',$result,'');
			}
		}
	}

	public function get_sorce_lan_word_type_18($data)
	{
		// $msg = 'get_sorce_lan_word_type_18->'. json_encode($data);
		// log_message('debug', $msg );

		$slang = $data['slang'];
		$tlang = $data['tlang'];
		$category_id = $data['category_id'];
		$subcategory_id = $data['subcategory_id'];
		$exercise_mode_id = $data['exercise_mode_id'];
		$support_lang_id =  $data['support_lang_id'];
		$limit = $this->config->item('api_record_limit');

		
		if($support_lang_id == "" || $slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){
				http_response(404, 0, 'Parameters not passed', array(),'');
				return false;
		}else{
				//================================================== For GETTING Quetions==================================================

				$get_field_name = $this->db->query("SELECT field_name FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
				$field_name = $get_field_name[0]['field_name'];
				
				$result = $this->db->query("SELECT $field_name as word,aural_id,image_file,word_english,audio_file FROM tbl_aural_composition where category_id='$category_id' AND subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id'  AND is_audio_available='1' AND is_active='1' LIMIT $limit ")->result_array();

				foreach ($result as $key => $value)
				{
					$result[$key]['image_path']=MEDIA_URL.'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value['image_file'];
					$language_code = $get_field_name[0]['lang_code']; 
					$aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					// $aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id.'/'.$aname.'_'.$language_code.'.m4a';
					$aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id.'/'.$aname;

					if(file_exists($aufile) && !empty($aname)){
						// $result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
						$result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
						$result[$key]['is_audio_available'] = 1;
					}else{
						$result[$key]['audio_file']="";
						$result[$key]['is_audio_available']=0;
					}
					
					//================================================== FOR GETTING 3 WRONG OPTIONS==================================================

					$queid = $value['aural_id'];
					$get_field_name = $this->db->query("SELECT field_name,lang_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array();
					$field_name = $get_field_name[0]['field_name'];
					// $get_field_name = $this->db->query("SELECT field_name FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
					// $field_name = $get_field_name[0]['field_name'];

					$option = $this->db->query("SELECT $field_name as word
								FROM tbl_aural_composition where subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND aural_id != $queid AND is_active='1'")->result_array();

					shuffle($option);
					$option = array_slice($option, 0, 3); 
					$result[$key]['option'] = array();
					if(!empty($option) && count($option) > 0)
					{
						$result[$key]['option'] = $option;
						foreach ($option as $key1 => $value1) {
							$result[$key]['option'][$key1]['is_correct'] = 0;
						 }
					}
					//==================================================For GETTING  currect option================================================================
					$wid = $result[$key]['aural_id'];
					$option1 = $this->db->query("SELECT $field_name as word FROM tbl_aural_composition where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND aural_id=$wid AND is_active='1'")->result_array();
					// $option1 = array();
					// $option1[0] = $value;
					$option1[0]['is_correct'] = 1;
					$arr  = array_push($result[$key]['option'], $option1[0]);

					// foreach ($option1 as $key2 => $value2) {

					// $result[$key]['option'][3]['is_correct'] = 1;
					// }

					// for random index of options
					shuffle($result[$key]['option']);
						
				} // END FOR LOOP OF RESULT ARRAY
			//================================================== For Random RESULTS==================================================
			shuffle($result);
			$result = array_slice($result, 0, $limit); 

			if($result)
			{
				http_response(200, 1, 'Record found',$result,'');
			}else{

				http_response(400, 0, 'Record not found',$result,'');
			}
		}
	}

// --------------------------------------------------------  GRAMMER MODE------------------------------------------------------------

	public function get_grammer_type_1($data){
		
		// $msg = 'get_grammer_type_1->'. json_encode($data);
		// log_message('debug', $msg );


			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $this->config->item('api_record_limit');
			$support_lang_id = $data['support_lang_id'];
			if($support_lang_id=="" || $slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

					http_response(404, 0, 'Parameters not passed', array(),'');
					return false;
			
			}else{

					$result = $this->db->query("SELECT question As word,options,notes,is_audio_available,audio_file
								FROM tbl_grammer_master where category_id='$category_id' AND subcategory_id='$subcategory_id' AND target_language_id='$tlang' AND question_type='1' AND is_active='1' AND is_delete='0'")->result_array();
					$optionArr = [];
					foreach ($result as $key => $value) {

							if($value['is_audio_available'] == "1")
							{
								$root_path  = $this->config->item('root_path');
								$aname = str_replace(" ","_",$value['audio_file']); 
								// $aufile = $root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$value['audio_file'].'.m4a';
								$aufile = $root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
								
								if(file_exists($aufile) && !empty($aname)){

									// $result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$value['audio_file'].'.m4a';
									$result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$value['audio_file'];
									$result[$key]['is_audio_available'] = 1;

								}else{

									$result[$key]['audio_file']= "";
									$result[$key]['is_audio_available'] = 0;

								}
							}

							$option = explode("#",trim($value['options']));
							$objOpt = [];
							$ocount=1;
							foreach  ($option as $k => $v){
								$objOpt['word'] = $v;
								if($ocount=="1"){
										$objOpt['is_correct'] = 1;
								}else{
									$objOpt['is_correct'] = 0;
								}
								
								$optionArr[$k] = $objOpt;
								$ocount++;
							}
							$result[$key]['option'] = $optionArr;
							shuffle($result[$key]['option']);
								
					} // END FOR LOOP OF RESULT ARRAY
					  
					shuffle($result);
					$result = array_slice($result, 0, $limit); 
					if($result)
					{
						http_response(200, 1, 'Record found',$result,'');
					}else{

						http_response(400, 0, 'Record not found',$result,'');
					}	
		}
	}

	public function get_grammer_type_2($data){
			
		// $msg = 'get_grammer_type_2->'. json_encode($data);
		// log_message('debug', $msg );


			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$support_lang_id = $data['support_lang_id'];
			$limit = $this->config->item('api_record_limit');

			if($support_lang_id=="" || $slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){

					http_response(404, 0, 'Parameters not passed', array(),'');
					return false;
			
			}else{


						// $str = "Robert på ####### fabriken idag.";
						// $count = substr_count($str, '#');
					// 	$str1 = str_repeat("#",$count);
					 //  	echo $final_string = str_replace($str1,"###",$str);

				  //       die();

				$result = $this->db->query("SELECT question,options,notes,is_audio_available,audio_file
								FROM tbl_grammer_master where category_id='$category_id' AND subcategory_id='$subcategory_id' AND target_language_id='$tlang' AND question_type='2' AND is_active='1' AND is_delete='0'")->result_array();
					 
				$ctn=0;
				foreach ($result as $key => $value) {
					if($value['is_audio_available'] == "1")
					{
								$root_path  = $this->config->item('root_path');
								// $aufile = $root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$value['audio_file'].'.m4a';
								$aufile = $root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$value['audio_file'];								

								if(file_exists($aufile) && !empty($aname)){

									// $result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$value['audio_file'].'.m4a';
									$result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$value['audio_file'];
									$result[$key]['is_audio_available'] = 1;

								}else{

									$result[$key]['audio_file']= "";
									$result[$key]['is_audio_available'] = 0;

								}
					}
					
					$count = substr_count($value['question'], '#');
					$str1 = str_repeat("#",$count);
					$final_string = str_replace($str1,"...",$value['question']);
					$result[$ctn]['question']= $final_string;
					$ctn++;
				}
						
					shuffle($result);
					$result = array_slice($result, 0, $limit); 
					if($result)
					{
						http_response(200, 1, 'Record found',$result,'');
					}else{

						http_response(400, 0, 'Record not found',$result,'');
					}	
			}
	}
//========================================================= Grammer Mode END ===========================================================

//========================================================= Phrases Mode Start ===========================================================

	public function get_phrases_type_1($data)
	{

		// $msg = 'get_phrases_type_1->'. json_encode($data);
		// log_message('debug', $msg );

		$slang = $data['slang'];
		$tlang = $data['tlang'];
		$category_id = $data['category_id'];
		$support_lang_id =  $data['support_lang_id'];
		$subcategory_id = $data['subcategory_id'];
		$limit = $this->config->item('api_record_limit');

		if($support_lang_id=="" || $slang=="" || $tlang=="" || $category_id=="" || $subcategory_id==""){
				http_response(404, 0, 'Parameters not passed' , array(),'');
				return false;
		}else{
			//================================================== For GETTING Quetions==================================================
			// $get_field_name = $this->db->query("SELECT field_name,lang_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array(); //New Target change
			$get_field_name = $this->db->query("SELECT field_name,language_code as lang_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
			$field_name = $get_field_name[0]['field_name'];
			$language_code = $get_field_name[0]['lang_code'];
			//print_r($get_field_name); die();

			$result = $this->db->query("SELECT phrases_id,phrase_$language_code as word,phrase_en,audio_file FROM tbl_phrases where category_id='$category_id' AND subcategory_id='$subcategory_id' AND is_active='1'")->result_array();

			$get_cate_name = $this->db->query("SELECT category_name FROM tbl_exercise_mode_categories WHERE exercise_mode_category_id='$category_id'")->result_array();
			$category_folder = $get_cate_name[0]['category_name'];

			foreach($result as $key => $value)
			{
			 
				$aname = str_replace(" ","_",$value['audio_file']); 
				$language_code = $get_field_name[0]['lang_code'];
				$root_path  = $this->config->item('root_path');
				// $aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
				$aufile=$root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
		
				if(file_exists($aufile) && !empty($aname)){

					// $result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					$result[$key]['audio_file']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname;
					$result[$key]['is_audio_available'] = 1;

				}else{

					$result[$key]['audio_file']="";
					$result[$key]['is_audio_available']=0;

				}
				$result[$key]['word']=$value['word'];
				

				//==================================================For GETTING  currect option================================================================
				$wid = $result[$key]['phrases_id'];
				$get_field_name1 = $this->db->query("SELECT field_name,lang_code as language_code FROM tbl_master_language WHERE support_lang_id='$support_lang_id'")->result_array();
				$field_name = $get_field_name1[0]['field_name'];
				$language_code = $get_field_name1[0]['language_code'];

				$option1 = $this->db->query("SELECT phrase_$language_code as word
					FROM tbl_phrases where category_id='$category_id'  AND phrases_id=$wid AND is_active='1'" )->result_array();
				foreach ($option1 as $key2 => $value2) {
							$result[$key]['option']=$value2['word'];		
				}
						
			} // END FOR LOOP OF RESULT ARRAY

 			//================================================== For Random RESULTS==================================================				
			$rand = 0;
			if(count($result) >= $limit){
				$count = count($result)-$limit ;
				$rand = rand(0,$count);
			}else{
				$result;
			}

			$result = array_slice($result, $rand , $limit); 
			if($result)
			{
				http_response(200, 1, 'Record found',$result,'');
			}else{
				http_response(400, 0, 'Record not found',$result,'');
			}	
		}
	}
//========================================================= Phrases Mode END ===========================================================
//========================================================= Dialogue Mode START ===========================================================

	public function get_dialogue_type_1($data){

		// $msg = 'get_dialogue_type_1->'. json_encode($data);
		// log_message('debug', $msg );

				$slang = $data['slang'];
				$tlang = $data['tlang'];
				$category_id = $data['category_id'];
				$subcategory_id = $data['subcategory_id'];
				$limit = $this->config->item('api_record_limit');
				$support_lang_id = $data['support_lang_id'];
				if($support_lang_id=="" || $slang=="" || $tlang=="" || $category_id=="" || $subcategory_id==""){
						http_response(404, 0, 'Parameters not passed' , array(),'');
						return false;
				}else{
						$result = $this->db->query("SELECT dialogue_master_id,title,full_audio
									FROM tbl_dialogue_master  where category_id='$category_id' AND subcategory_id='$subcategory_id' AND target_language_id='$tlang' AND is_active='1' AND is_delete='0'")->result_array();
						
						foreach($result as $key => $value)
						{
							
							$root_path  = $this->config->item('root_path');
							$aname = str_replace(" ", "_", $value['full_audio']);
							$aufile = $root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.strtolower($aname);
							if(file_exists($aufile) && !empty($aname)){
								$result[$key]['full_audio']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.strtolower($aname);
								$result[$key]['is_audio_available'] = 1;
							}else{
								$result[$key]['full_audio']="";
								$result[$key]['is_audio_available'] = 0;
							}
							$mid = $value['dialogue_master_id'];
							$result1 = $this->db->query("SELECT phrase,audio_name,speaker,sequence_no
									FROM  tbl_dialogue_list WHERE dialogue_master_id='$mid' order by sequence_no asc")->result_array();
							 
								foreach ($result1 as $k=> $v) {
									$root_path  = $this->config->item('root_path');
									$aufile = $root_path.'/uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.strtolower($v['audio_name']);
									if(file_exists($aufile) && !empty($v['audio_name'])){
										$result1[$k]['audio_name']=MEDIA_URL.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.strtolower($v['audio_name']);
										$result1[$k]['is_audio_available'] = 1;
									}else{
										$result1[$k]['audio_name']="";
										$result1[$k]['is_audio_available'] = 0;
									}
								}
							$result[$key]['list']=$result1;
						}
						  shuffle($result);
						  $result = array_slice($result, 0, $limit); 
						  	if($result)
							{
								http_response(200, 1, 'Record found',$result,'');
							}else{

								http_response(400, 0, 'Record not found',$result,'');
							}	
				}
	}

	public function get_dialogue_type_2($data){

		// $msg = 'get_dialogue_type_2->'. json_encode($data);
		// log_message('debug', $msg );

				$slang = $data['slang'];
				$tlang = $data['tlang'];
				$category_id = $data['category_id'];
				$subcategory_id = $data['subcategory_id'];
				$limit = $this->config->item('api_record_limit');
				if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id==""){
						http_response(404, 0, 'Parameters not passed' , array(),'');
						return false;
				}else{
					$result = $this->db->query("SELECT dialogue_master_id,title,full_audio
									FROM tbl_dialogue_master  where category_id='$category_id' AND subcategory_id='$subcategory_id' AND target_language_id='$tlang' AND is_active='1' AND is_delete='0'")->result_array();
						  
						foreach($result as $key => $value) {
								$mid = $value['dialogue_master_id'];
								$result1 = $this->db->query("SELECT phrase,audio_name,speaker
									FROM  tbl_dialogue_list WHERE dialogue_master_id='$mid' order by sequence_no asc limit 2")->result_array();
								
								$result2 = $this->db->query("SELECT phrase,sequence_no,speaker
									FROM  tbl_dialogue_list WHERE dialogue_master_id='$mid' limit 2,10")->result_array();
									$result[$key]['list']=$result1;
									$optionArr = [];
								foreach($result2 as $k => $v){
									$objOpt = [];
									$objOpt['word'] = $v['phrase'];
									$objOpt['sequence'] = $v['sequence_no'];
									$objOpt['speaker'] = $v['speaker'];
									$optionArr[$k] = $objOpt;
								}
								shuffle($optionArr);
								$result[$key]['option']=$optionArr;
						}
						  
					  shuffle($result);
					  $result = array_slice($result, 0, $limit); 
					  	if($result)
						{
							http_response(200, 1, 'Record found',$result,'');
						}else{

							http_response(400, 0, 'Record not found',$result,'');
						}	

				}
	}
//========================================================= Dialogue Mode END ===========================================================

//=========================================================Culture Mode Start ==========================================================

	public function get_culture_type_1($data){

		// $msg = 'get_culture_type_1->'. json_encode($data);
		// log_message('debug', $msg );


			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$category_id = $data['category_id'];
			$subcategory_id = $data['subcategory_id'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$support_lang_id = $data['support_lang_id'];
			$limit = $this->config->item('api_record_limit');
				if($slang=="" || $tlang=="" || $category_id=="" || $subcategory_id=="" || $exercise_mode_id==""){
						http_response(404, 0, 'Parameters not passed', array(),'');
						return false;
				}else{

					$result1 = $this->db->query("SELECT culture_master_id,title_text,external_link,paragraph,image_name
								FROM tbl_culture_master where category_id='$category_id' AND subcategory_id='$subcategory_id' AND target_language_id='$tlang' AND is_active='1' AND is_delete='0'")->result_array();
			
					$questionarray =[];
					foreach ($result1 as $key1 => $value1) {
						$result1[$key1]['image_path']=MEDIA_URL.'uploads/words/'.$category_id.'/'.$subcategory_id.'/'.$value1['image_name'];
						$mid= $value1['culture_master_id'];
						$result = $this->db->query("SELECT question As word,options,notes
									FROM tbl_culture_question where  culture_master_id='$mid'")->result_array();
						$optionArr = [];
						foreach ($result as $key => $value) {
							$option = explode("#",$value['options']);
							$objOpt = [];
							$ocount=1;
							foreach  ($option as $k => $v){
								$objOpt['word'] = $v;
								if($ocount=="1"){
										$objOpt['is_correct'] = 1;
								}else{
									$objOpt['is_correct'] = 0;
								}
								$optionArr[$k] = $objOpt;
								$ocount++;
							}
							$result[$key]['option'] = $optionArr;
							shuffle($result[$key]['option']);
						} // END FOR LOOP OF RESULT ARRAY
						$result1[$key1]['questions']=$result;
					}
					shuffle($result1);
					$result = array_slice($result1, 0, $limit); 
					if($result)
					{
						http_response(200, 1, 'Record found',$result,'');
					}else{

						http_response(400, 0, 'Record not found',$result,'');
					}	
			}
	}
//==========================================================Culture Mode End=============================================================
/*
*  User Register Start 
*/
	public function user_register($data){

		// $msg = 'user_register->'. json_encode($data);
		// log_message('debug', $msg );

		
			$first_name = $data['first_name'];
			$last_name = $data['last_name'];
			$email = $data['email'];
			$password = $data['password'];
			$confirm_password = $data['confirm_password'];
			$social_id = $data['social_id'];
			$social_type = $data['social_type'];

			if($first_name == ""){
					http_response(404, 0, 'Parameters not passed', array(),'');
					return false;
			}else{
 					
	 			//print_r($data); die();		
	 				if($social_type == "0"){
	 						
	 						$check = $this->check_email_exist($email);
		 					if($check){
			 					http_response(404, 0, 'Email Already Exist', array(),'');
								return false;
		 					}

		 					if($password == $confirm_password){

		 							$insert_data = array(
 									'first_name'=>$first_name ? $first_name : "",
 									'last_name'=>$last_name ? $last_name : "",
 									'email'=>$email ? $email : "",
 									'password'=>$password ? md5($password): "",
 									//'password'=>$password,
 									'type'=>0,
 									//'social_id'=>$social_id,
 									'social_type'=>$social_type ? $social_type : "",
 								);

			 					if($data['user_image'] != ""){
	 								$insert_data['profile_pic'] = $data['user_image'];
	 							}

	 						$insert = $this->db->insert('tbl_users', $insert_data);
							$insert_id = $this->db->insert_id();
								if($insert){

									$result = $this->db->query("select * from tbl_users where user_id=$insert_id")->result_array();
									$response = array();
									foreach ($result as $key => $value) {
										if(is_null($value['user_id']))
										{
											$response['user_id'] = "";
										}else{
											$response['user_id'] = $value['user_id'];
										}

										if(is_null($value['first_name']))
										{
											$response['first_name'] = "";
										}else{
											$response['first_name'] = $value['first_name'];
										}

										if(is_null($value['last_name']))
										{
											$response['last_name'] = "";
										}else{
											$response['last_name'] = $value['last_name'];
										}

										if(is_null($value['email']))
										{
											$response['email'] = "";
										}else{
											$response['email'] = $value['email'];
										}

										if(is_null($value['password']))
										{
											$response['password'] = "";
										}else{
											$response['password'] = $value['password'];
										}

										if(is_null($value['type']))
										{
											$response['type'] = "";
										}else{
											$response['type'] = $value['type'];
										}

										if(is_null($value['profile_pic']))
										{
											$response['profile_pic'] = "";
										}else{
											
											$response['profile_pic'] = API_URL.$value['profile_pic'];
											
										}

										if(is_null($value['social_pic']))
										{
											$response['social_pic'] = "";
										}else{
											$response['social_pic'] = $value['social_pic'];
										}

										if(is_null($value['social_id']))
										{
											$response['social_id'] = "";
										}else{
											$response['social_id'] = $value['social_id'];
										}

										if(is_null($value['social_type']))
										{
											$response['social_type'] = "";
										}else{
											$response['social_type'] = $value['social_type'];
										}

										if(is_null($value['is_active']))
										{
											$response['is_active'] = "";
										}else{
											$response['is_active'] = $value['is_active'];
										}

										if(is_null($value['reset_token']))
										{
											$response['reset_token'] = "";
										}else{
											$response['reset_token'] = $value['reset_token'];
										}

										if(is_null($value['support_lang_id']))
										{
											$response['support_lang_id'] = "";
										}else{
											$response['support_lang_id'] = $value['support_lang_id'];
										}

										if(is_null($value['menu_lang_id']))
										{
											$response['menu_lang_id'] = "";
										}else{
											$response['menu_lang_id'] = $value['menu_lang_id'];
										}

										if(is_null($value['target_lang_id']))
										{
											$response['target_lang_id'] = "";
										}else{
											$response['target_lang_id'] = $value['target_lang_id'];
										}

										
										$response['score'] = 0;
										
									}
									if($result){
										http_response(200, 1, 'User Successfully Registered!!', $response,'');
									}else{
										http_response(404, 0, 'Record not found', $result,'');
										return false;
									}

								}

		 					}else{
	 							http_response(404, 0, 'Confirm Password not matched', array(),'');
								return false;
	 						}

	 					}else{

	 							$res = $this->db->query("select * from tbl_users where social_id=$social_id")->row();
								if(!empty($res)) {
									http_response(200, 1, 'User Already Registered!!', $res,'');
									return false;
								}

								$insert_data = array(
 									'first_name'=>$first_name,
 									'email'=>$email,
 									'password'=>md5($password),
 									//'password'=>$password,
 									'type'=>0,
 									'social_id'=>$social_id,
 									'social_type'=>$social_type,
 								);
 								if(!empty($last_name))
 								{
 									$insert_data['last_name'] = $last_name;
 								}
 								if($data['user_image'] != ""){
 									$insert_data['social_pic'] = $data['user_image'];
 								}
 							//print_r($insert_data); die();
 							$insert = $this->db->insert('tbl_users', $insert_data);
							$insert_id = $this->db->insert_id();
								if($insert){

									$result = $this->db->query("select * from tbl_users where user_id=$insert_id")->result_array();
									$response = array();
									foreach ($result as $key => $value) {

										if(is_null($value['user_id']))
										{
											$response['user_id'] = "";
										}else{
											$response['user_id'] = $value['user_id'];
										}

										if(is_null($value['first_name']))
										{
											$response['first_name'] = "";
										}else{
											$response['first_name'] = $value['first_name'];
										}

										if(is_null($value['last_name']))
										{
											$response['last_name'] = "";
										}else{
											$response['last_name'] = $value['last_name'];
										}

										if(is_null($value['email']))
										{
											$response['email'] = "";
										}else{
											$response['email'] = $value['email'];
										}

										if(is_null($value['password']))
										{
											$response['password'] = "";
										}else{
											$response['password'] = $value['password'];
										}

										if(is_null($value['type']))
										{
											$response['type'] = "";
										}else{
											$response['type'] = $value['type'];
										}

										if(is_null($value['profile_pic']))
										{
											$response['profile_pic'] = "";
										}else{
											
											$response['profile_pic'] = API_URL.$value['profile_pic'];
										}

										if(is_null($value['social_pic']))
										{
											$response['social_pic'] = "";
										}else{
											$response['social_pic'] = $value['social_pic'];
										}

										if(is_null($value['social_id']))
										{
											$response['social_id'] = "";
										}else{
											$response['social_id'] = $value['social_id'];
										}

										if(is_null($value['social_type']))
										{
											$response['social_type'] = "";
										}else{
											$response['social_type'] = $value['social_type'];
										}

										if(is_null($value['is_active']))
										{
											$response['is_active'] = "";
										}else{
											$response['is_active'] = $value['is_active'];
										}

										if(is_null($value['reset_token']))
										{
											$response['reset_token'] = "";
										}else{
											$response['reset_token'] = $value['reset_token'];
										}

										if(is_null($value['support_lang_id']))
										{
											$response['support_lang_id'] = "";
										}else{
											$response['support_lang_id'] = $value['support_lang_id'];
										}

										if(is_null($value['menu_lang_id']))
										{
											$response['menu_lang_id'] = "";
										}else{
											$response['menu_lang_id'] = $value['menu_lang_id'];
										}

										if(is_null($value['target_lang_id']))
										{
											$response['target_lang_id'] = "";
										}else{
											$response['target_lang_id'] = $value['target_lang_id'];
										}

										$response['score'] = 0;
									}

									if($result){
										http_response(200, 1, 'User Successfully Registered!!', $response,'');
									}else{
										http_response(404, 0, 'Record not found', $result,'');
										return false;
									}

								}


	 					}

			}	
	}
/*
* User Regsiter End
*/
	function check_email_exist($email,$id=null){
		$sql = "select * from tbl_users WHERE email='$email' AND type='0' AND is_active='1'";
		 if($id != ""){
		 	$sql .=" AND user_id != '$id' ";
		 }
		$result = $this->db->query($sql)->result_array();
		if(count($result) >= 1 ) {
			return true;
		}else{
			return false;
		} 
	}
	function check_email_exist_with_social($email,$social_id){
		$sql = "select * from tbl_users WHERE email='$email' AND social_id='$social_id' AND is_active='1'";
		$result = $this->db->query($sql)->result_array();
		if(count($result) >= 1 ) {
			return true;
		}else{
			return false;
		} 
	}

	public function user_login($data){

		// $msg = 'user_login->'. json_encode($data);
		// log_message('debug', $msg );


		$email =$data['email'];
		$pass = $data['password'];
		$result = $this->db->query("select * from tbl_users where email='$email' AND password='$pass' AND is_active='1' AND type='0'")->result_array();
		// $result = $this->db->query($query);
		//print_r($result);die();
		if(count($result) == 1){
			$user_id = $result[0]['user_id'];
			$result_score = $this->db->query("select sum(correct_score) as score from tbl_user_score where user_id='$user_id'")->row();
			$response = array();
			foreach ($result as $key => $value) {
										
				if(is_null($value['user_id']))
				{
					$response['user_id'] = "";
				}else{
					$response['user_id'] = $value['user_id'];
				}

				if(is_null($value['first_name']))
				{
					$response['first_name'] = "";
				}else{
					$response['first_name'] = $value['first_name'];
				}

				if(is_null($value['last_name']))
				{
					$response['last_name'] = "";
				}else{
					$response['last_name'] = $value['last_name'];
				}

				if(is_null($value['email']))
				{
					$response['email'] = "";
				}else{
					$response['email'] = $value['email'];
				}

				if(is_null($value['password']))
				{
					$response['password'] = "";
				}else{
					$response['password'] = $value['password'];
				}

				if(is_null($value['type']))
				{
					$response['type'] = "";
				}else{
					$response['type'] = $value['type'];
				}

				if(is_null($value['profile_pic']))
				{
					$response['profile_pic'] = "";
				}else{
					$response['profile_pic'] = API_URL.$value['profile_pic'];
				}

				if(is_null($value['social_pic']))
				{
					$response['social_pic'] = "";
				}else{
					$response['social_pic'] = $value['social_pic'];
				}

				if(is_null($value['social_id']))
				{
					$response['social_id'] = "";
				}else{
					$response['social_id'] = $value['social_id'];
				}

				if(is_null($value['social_type']))
				{
					$response['social_type'] = "";
				}else{
					$response['social_type'] = $value['social_type'];
				}

				if(is_null($value['is_active']))
				{
					$response['is_active'] = "";
				}else{
					$response['is_active'] = $value['is_active'];
				}

				if(is_null($value['reset_token']))
				{
					$response['reset_token'] = "";
				}else{
					$response['reset_token'] = $value['reset_token'];
				}

				if(is_null($value['support_lang_id']))
				{
					$response['support_lang_id'] = "";
				}else{
					$response['support_lang_id'] = $value['support_lang_id'];
				}

				if(is_null($value['menu_lang_id']))
				{
					$response['menu_lang_id'] = "";
				}else{
					$response['menu_lang_id'] = $value['menu_lang_id'];
				}

				if(is_null($value['target_lang_id']))
				{
					$response['target_lang_id'] = "";
				}else{
					$response['target_lang_id'] = $value['target_lang_id'];
				}

				if($result_score->score == NULL || $result_score->score == "0" || $result_score->score == ""){
					$response['score'] = "0";
				}else{
					$response['score'] = $result_score->score;
				}	
			}
			$this->session->set_userdata('user_id',$result[0]['user_id']);
			http_response(200, 1, 'Login Successfully',$response,'');
		}else{
			http_response(200, 0, 'Username or Password Wrong!!',array(),'');
			return false;				
		} 
	}

	public function user_edit_profile($data){

		// $msg = 'user_edit_profile->'. json_encode($data);
		// log_message('debug', $msg );


			$first_name = $data['first_name'];
			$last_name = $data['last_name'];
			$email = $data['email'];
			$user_id = $data['user_id'];
			$is_remove_pic = $data['is_remove_pic'];
			$current_password = $data['current_password'];
			$new_password = $data['new_password'];
			$con_new_password = $data['con_new_password'];
			
			if($first_name=="" || $user_id=="" || $email==""){
					http_response(404, 0, 'Parameters not passed', array(),'');
					return false;
			}else{

					$result_1 = $this->db->query("select * from tbl_users where user_id='$user_id'")->row();
					$existing_email = $result_1->email;
					$check = false;
					if($existing_email!=$email){
						$check = $this->check_email_exist($email,$user_id);
					}
 					
 					if($check){
	 					http_response(404, 0, 'Email Already Exist', array(),'');
						return false;
 					}else{
 							$insert_data = array(
	 								'first_name'=>$first_name,
 									'last_name'=>$last_name,
 									'email'=>$email,
 								);

 							if($data['user_image'] != ""){
 								$insert_data['profile_pic'] = $data['user_image'];
 								$insert_data['social_pic'] = "";
 							}
 							if($is_remove_pic == 1){
 								$insert_data['profile_pic'] = "";
 								$insert_data['social_pic'] = "";
 							}
 							if($current_password!=""){
	 							$crpass = md5($current_password);
	 							// $crpass = $current_password;
	 							$result_1 = $this->db->query("select * from tbl_users where user_id='$user_id' AND  password='$crpass'")->row();
	 							if(count($result_1) > 0){

	 								if($new_password == $con_new_password){
	 									$insert_data['password'] = md5($new_password);
	 									// $insert_data['password'] = $new_password;
	 								}else{
	 									http_response(404, 0, 'Confirm New Password  not matched', array(),'');
										return false;
	 								}

	 							}else{

	 								http_response(404, 0, 'Current Password not matched', array(),'');
									return false;
	 							}
 							}
 							$this->db->where('user_id', $user_id);
							$update = $this->db->update('tbl_users', $insert_data);
							$result = $this->db->query("select * from tbl_users where user_id=$user_id")->result_array();
							$response = array();
							$result_score = $this->db->query("select sum(correct_score) as score from tbl_user_score where user_id='$user_id'")->row();
							foreach ($result as $key => $value) {
										
								if(is_null($value['user_id']))
								{
									$response['user_id'] = "";
								}else{
									$response['user_id'] = $value['user_id'];
								}

								if(is_null($value['first_name']))
								{
									$response['first_name'] = "";
								}else{
									$response['first_name'] = $value['first_name'];
								}

								if(is_null($value['last_name']))
								{
									$response['last_name'] = "";
								}else{
									$response['last_name'] = $value['last_name'];
								}

								if(is_null($value['email']))
								{
									$response['email'] = "";
								}else{
									$response['email'] = $value['email'];
								}

								if(is_null($value['password']))
								{
									$response['password'] = "";
								}else{
									$response['password'] = $value['password'];
								}

								if(is_null($value['type']))
								{
									$response['type'] = "";
								}else{
									$response['type'] = $value['type'];
								}

								if(is_null($value['profile_pic']))
								{
									$response['profile_pic'] = "";
								}else{
									$response['profile_pic'] = API_URL.$value['profile_pic'];
								}

								if(is_null($value['social_pic']))
								{
									$response['social_pic'] = "";
								}else{
									$response['social_pic'] = $value['social_pic'];
								}

								if(is_null($value['social_id']))
								{
									$response['social_id'] = "";
								}else{
									$response['social_id'] = $value['social_id'];
								}

								if(is_null($value['social_type']))
								{
									$response['social_type'] = "";
								}else{
									$response['social_type'] = $value['social_type'];
								}

								if(is_null($value['is_active']))
								{
									$response['is_active'] = "";
								}else{
									$response['is_active'] = $value['is_active'];
								}

								if(is_null($value['reset_token']))
								{
									$response['reset_token'] = "";
								}else{
									$response['reset_token'] = $value['reset_token'];
								}

								if(is_null($value['support_lang_id']))
								{
									$response['support_lang_id'] = "";
								}else{
									$response['support_lang_id'] = $value['support_lang_id'];
								}

								if(is_null($value['menu_lang_id']))
								{
									$response['menu_lang_id'] = "";
								}else{
									$response['menu_lang_id'] = $value['menu_lang_id'];
								}

								if(is_null($value['target_lang_id']))
								{
									$response['target_lang_id'] = "";
								}else{
									$response['target_lang_id'] = $value['target_lang_id'];
								}

								if($result_score->score == NULL || $result_score->score == "0" || $result_score->score == ""){
									$response['score'] = "0";
								}else{
									$response['score'] = $result_score->score;
								}	
							}

							if($result) {
								http_response(200, 1, 'User Profile Successfully Updated!!', $response,'');	
							} 
							else {
								http_response(404, 0, 'Record not found', $result,'');
								return false;
							}
 					}	
			}
		}

	public function get_user_info($data){
		
		$user_id =$data['user_id'];
		if($user_id != ""){
			$result = $this->db->query("select * from tbl_users where user_id='$user_id'")->result_array();
			$result_score = $this->db->query("select sum(correct_score) as score from tbl_user_score where user_id='$user_id'")->row();
			$response = array();
			foreach ($result as $key => $value) {
										
				if(is_null($value['user_id']))
				{
					$response['user_id'] = "";
				}else{
					$response['user_id'] = $value['user_id'];
				}

				if(is_null($value['first_name']))
				{
					$response['first_name'] = "";
				}else{
					$response['first_name'] = $value['first_name'];
				}

				if(is_null($value['last_name']))
				{
					$response['last_name'] = "";
				}else{
					$response['last_name'] = $value['last_name'];
				}

				if(is_null($value['email']))
				{
					$response['email'] = "";
				}else{
					$response['email'] = $value['email'];
				}

				if(is_null($value['password']))
				{
					$response['password'] = "";
				}else{
					$response['password'] = $value['password'];
				}

				if(is_null($value['type']))
				{
					$response['type'] = "";
				}else{
					$response['type'] = $value['type'];
				}

				if(is_null($value['profile_pic']))
				{
					$response['profile_pic'] = "";
				}else{
					$response['profile_pic'] = API_URL.$value['profile_pic'];
				}

				if(is_null($value['social_pic']))
				{
					$response['social_pic'] = "";
				}else{
					$response['social_pic'] = $value['social_pic'];
				}

				if(is_null($value['social_id']))
				{
					$response['social_id'] = "";
				}else{
					$response['social_id'] = $value['social_id'];
				}

				if(is_null($value['social_type']))
				{
					$response['social_type'] = "";
				}else{
					$response['social_type'] = $value['social_type'];
				}

				if(is_null($value['is_active']))
				{
					$response['is_active'] = "";
				}else{
					$response['is_active'] = $value['is_active'];
				}

				if(is_null($value['reset_token']))
				{
					$response['reset_token'] = "";
				}else{
					$response['reset_token'] = $value['reset_token'];
				}

				if(is_null($value['support_lang_id']))
				{
					$response['support_lang_id'] = "";
				}else{
					$response['support_lang_id'] = $value['support_lang_id'];
				}

				if(is_null($value['menu_lang_id']))
				{
					$response['menu_lang_id'] = "";
				}else{
					$response['menu_lang_id'] = $value['menu_lang_id'];
				}

				if(is_null($value['target_lang_id']))
				{
					$response['target_lang_id'] = "";
				}else{
					$response['target_lang_id'] = $value['target_lang_id'];
				}

				if($result_score->score == NULL || $result_score->score == "0" || $result_score->score == ""){
					$response['score'] = "0";
				}else{
					$response['score'] = $result_score->score;
				}	
			}
					
			if(!empty($response)){
				http_response(200, 1, '',$response,'');
			}else{
				http_response(200, 0, 'Record not found!!',array(),'');
				return false;				
			} 
		}else{
				http_response(404, 0, 'Parameters not passed', array(),'');
				return false;
		}
	}

	public function forgot_password($data){
		$email =$data['email'];
		if($email != ""){
			$result = $this->db->query("select * from tbl_users where email='$email'")->result_array();
			if($result[0]['social_type'] != "0"){

				http_response(404, 0, 'Email address not registered !!', array(),'');
				return false;
			}

			$rest_token = rand(0,999999);
			$this->db->where('email',$email);
			$update = $this->db->update('tbl_users',array('reset_token'=>$rest_token));
			if(count($result) == 1){
				$user_id = $result[0]['user_id'];
				$first_name = $result[0]['first_name'];
				$last_name = $result[0]['last_name'];
				$url = base_url().'admin_master/reset_password/'.$rest_token;
				$message = "Hello $first_name,  \r\n\n";
				$message .= "Please click on below link to reset your password  \r\n\n";
				$message .= "$url \r\n\n\n";
				$message .= "Thank you \r\n";
				$message .= "Regards,\r\n";
				$message .= "Indylan Team \r\n";

				// $config['use_ci_email'] = TRUE;
				$config['protocol'] = 'smtp'; // mail
				$config['smtp_host'] = 'ssl://smtp.gmail.com';
				$config['smtp_port'] = 465;
				$config['smtp_user'] = 'indylansprt@gmail.com';
				$config['smtp_pass'] = '2rqwWzAEj73g?8lw';
				// $config['mailpath'] = '/user/sbin/sendmail';
				$config['charset'] = 'iso-8859-1';
				$config['wordwrap'] = TRUE;
				$config['mailtype'] = 'html';
				$this->load->library('email',$config);
				$this->email->set_newline("\r\n");
				$this->email->initialize($config);
				$this->email->from('indylansprt@gmail.com', 'Indylan');   // info@svenskaifinland.com sfi@learnmera.com
				$this->email->to($email);
				$this->email->subject('Indylan : Reset Your Password');
				$this->email->message($message);
				$isSend = $this->email->send();
				// echo $this->email->print_debugger();
				if($isSend){
					http_response(200, 1, 'Reset Password link sent to your email address',array(),'');
				}else{
					http_response(200,0, 'Email Not Sent due to technical problems.',array(),'');
				}
			}else{
				http_response(200, 0, 'Record not found!!',array(),'');
				return false;				
			} 
		}else{
				http_response(404, 0, 'Parameters not passed', array(),'');
				return false;
		}
	}

	public function submit_user_score($data){
		$user_id =$data['user_id'];
		if($user_id==""){
			 	http_response(404, 0, 'Parameters not passed', array(),'');
			 	return false;
		 }else{
				$insert = $this->db->insert('tbl_user_score', $data);
				$insert_id = $this->db->insert_id();
				http_response(200, 1, 'Score Successfully submited',array(),'');
				return false;					
		 }
	}
	function update_user($field=array(),$wh=array()){
			
		$ret = 0;
		if(is_array($field)){
			$this->db->where($wh);
			$this->db->set($field);
			$ret= $this->db->update('tbl_users');
			
			$ret = $this->db->affected_rows();
		}
		return $ret;
	}

}
