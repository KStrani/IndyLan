<?php
class Api_test_exercise_model extends CI_Model {
 
	
	public function __construct() {
       parent::__construct();
        $date = new DateTime();
        $this->getTimestamp = $date->getTimestamp();
 
	}
	/*
	*  START @ ============================================================== @@ 28-12-2017 Developed By: Nimesh Patel  @@ TEST EXERCISE SECTION ======================================================================
	*/

		public function test_exercise_type_list($data){
		//print_r($data); die();
			$lang = $data['lang'];
			$tlang = $data['tlang'];
			
			$exercise_mode_id = $data['exercise_mode_id'];
			if($lang=="" || $exercise_mode_id==""){

					http_response(404, 0, 'Parameters not passed', array(),'');
					return false;
			
			}else{

				$get_field_name = $this->db->query("SELECT * FROM tbl_source_language WHERE source_language_id='$lang'")->result_array();
				$language_code = $get_field_name[0]['language_code'];
				
				$result = $this->db->query("select id,type_$language_code as type_name  from tbl_exercise_type where exercise_mode_id='$exercise_mode_id' order by sequence asc")->result_array();

				foreach ($result as $key => $value) {
					$type_id = $value['id'];

					if($type_id=="1"){

						$result_all = $this->db->query("SELECT count(*) as total
								FROM tbl_word where is_active='1' AND is_image_available='1'")->result_array();

						$result[$key]['total']=$result_all[0]['total'];
					}else if($type_id=="2"){

						$result_all = $this->db->query("SELECT count(*) as total
								FROM tbl_word where is_active='1' AND is_image_available='1' AND is_audio_available='1' ")->result_array();

						$result[$key]['total']=$result_all[0]['total'];
					}else if($type_id=="3"){

						$result_all = $this->db->query("SELECT count(*) as total
								FROM tbl_word where is_active='1'")->result_array();

						$result[$key]['total']=$result_all[0]['total'];

					}else if($type_id=="4"){

						$result_all = $this->db->query("SELECT count(*) as total
								FROM tbl_word where is_active='1' AND is_image_available='1'")->result_array();

						$result[$key]['total']=$result_all[0]['total'];
					}else if($type_id=="5"){

						$result_all = $this->db->query("SELECT count(*) as total
								FROM tbl_word where is_active='1' ")->result_array();

						$result[$key]['total']=$result_all[0]['total'];
					}else if($type_id=="6"){

						$result_all = $this->db->query("SELECT count(*) as total
								FROM tbl_word where is_image_available='1' AND is_audio_available='1'  AND is_active='1' ")->result_array();

						$result[$key]['total']=$result_all[0]['total'];
					}else if($type_id=="7"){

						$result_all = $this->db->query("SELECT count(*) as total
								FROM tbl_word where is_active='1' ")->result_array();

						$result[$key]['total']=$result_all[0]['total'];
					}else if($type_id=="8"){

						$result_all = $this->db->query("SELECT count(*) as total
								FROM tbl_word where is_active='1' AND is_image_available='1' ")->result_array();

						$result[$key]['total']=$result_all[0]['total'];
					}else if($type_id=="9"){

						$result_all = $this->db->query("SELECT count(*) as total
								FROM tbl_word where is_active='1'")->result_array();

						$result[$key]['total']=$result_all[0]['total'];
					}else if($type_id=="10"){

						$result_all = $this->db->query("SELECT count(*) as total
								FROM tbl_grammer_master where target_language_id='$tlang' AND question_type='1' AND is_active='1' AND is_delete='0'")->result_array();

						$result[$key]['total']=$result_all[0]['total'];
					}else if($type_id=="11"){

						$result_all = $this->db->query("SELECT count(*) as total
								FROM tbl_grammer_master where target_language_id='$tlang' AND question_type='2' AND is_active='1' AND is_delete='0'")->result_array();

						$result[$key]['total']=$result_all[0]['total'];
					}else if($type_id=="12"){

						$result_all = $this->db->query("SELECT count(*) total
								FROM tbl_phrases where is_active='1'")->result_array();

						$result[$key]['total']=$result_all[0]['total'];
					}else if($type_id=="13"){

						$result_all = $this->db->query("SELECT count(*) as total
									FROM tbl_dialogue_master  where target_language_id='$tlang' AND is_active='1' AND is_delete='0'")->result_array();


						$result[$key]['total']=$result_all[0]['total'];
					}else if($type_id=="14"){
						
						$result_all = $this->db->query("SELECT count(*) as total
									FROM tbl_dialogue_master  where target_language_id='$tlang' AND is_active='1' AND is_delete='0'")->result_array();

						$result[$key]['total']=$result_all[0]['total'];
					}else if($type_id=="15"){

						$result_all = $this->db->query("SELECT count(*) total
								FROM tbl_culture_master where target_language_id='$tlang' AND is_active='1' AND is_delete='0'")->result_array();

						$result[$key]['total']=$result_all[0]['total'];
					}

				}
				
					if($result){

						http_response(200, 1, 'Record not found',$result,'');	
					} 
					else{

						http_response(404, 1, 'Record not found', $result,'');
						return false;
					}
			}
	}


		public function test_exercise_section($data){

			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$question = $data['question'];
			$type = $data['type'];
			
			if($slang=="" || $tlang=="" || $type=="" || $question==""){

					http_response(404, 0, 'Parameters not passed', array(),'');
					return false;
			
			}else{

					if($type=="1"){
						$this->test_type_1($data);
					}else if($type=="2"){
						$this->test_type_2($data);
					}else if($type=="3"){
						$this->test_type_3($data);
					}else if($type=="4"){
						$this->test_type_4($data);
					}else if($type=="5"){
						$this->test_type_5($data);
					}else if($type=="6"){
						$this->test_type_6($data);
					}else if($type=="7"){
						$this->test_type_7($data);
					}else if($type=="8"){
						$this->test_type_8($data);
					}else if($type=="9"){
						$this->test_type_9($data);
					}else if($type=="10"){
						$this->test_type_10($data);
					}else if($type=="11"){
						$this->test_type_11($data);
					}else if($type=="12"){
						$this->test_type_12($data);
					}else if($type=="13"){
						$this->test_type_13($data);
					}else if($type=="14"){
						$this->test_type_13($data);
					}else if($type=="15"){
						$this->test_type_15($data);
					}


			}
		}

		public function test_type_1($data){

			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $data['question'];

			//================================================== For GETTING Quetions==================================================
					
					$get_field_name = $this->db->query("SELECT field_name FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];


					// $total = $this->db->query("SELECT count(*) as total
					// 			FROM tbl_word where is_active='1' AND is_image_available='1'")->result_array();
				 //    $total = $total[0]['total'];

				 //    $tempcount = $total - $limit;

				 //    $rand = rand($limit,$tempcount);  


					$result = $this->db->query("SELECT $field_name as word,word_id,image_file,category_id,subcategory_id
								FROM tbl_word where is_active='1' AND is_image_available='1'  ORDER BY RAND()
LIMIT $limit")->result_array();
				//	print_r($result); 
			//die();
					foreach ($result as $key => $value) {
					  $category_id = $value['category_id'];
					  $subcategory_id = $value['subcategory_id'];

					$result[$key]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value['image_file'];
						
			//================================================== FOR GETTING 3 WRONG OPTIONS==================================================

					$get_field_name = $this->db->query("SELECT field_name FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$queid = $value['word_id'];
					$option = $this->db->query("SELECT $field_name as word
								FROM tbl_word  where subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND word_id!=$queid AND is_active='1'")->result_array();

					shuffle($option);
					$option = array_slice($option, 0, 3); 

					$result[$key]['option'] = $option;
					foreach ($option as $key1 => $value1) {

										$result[$key]['option'][$key1]['is_correct'] = 0;
					}
			//==================================================For GETTING  currect option================================================================
							$wid = $result[$key]['word_id'];

							$option1 = $this->db->query("SELECT $field_name as word
								FROM tbl_word where  word_id=$wid AND is_active='1'")->result_array();

							$arr  = array_push($result[$key]['option'], $option1[0]);
							
							foreach ($option1 as $key2 => $value2) {
								$result[$key]['option'][3]['is_correct'] = 1;
							}
								// for random index of options
							shuffle($result[$key]['option']);
					} // END FOR LOOP OF RESULT ARRAY
            //================================================== For Random RESULTS==================================================
						shuffle($result);
						$result = array_slice($result, 0, $limit); 
						 http_response(200, 1, 'Record not found',$result,'');	

		}

			public function test_type_2($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $data['question'];

			//================================================== For GETTING Quetions==================================================
					
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					//print_r($get_field_name); 
					$field_name = $get_field_name[0]['field_name'];

					// $total = $this->db->query("SELECT count(*) as total
					// 			FROM tbl_word where is_active='1' AND is_image_available='1' AND is_audio_available='1'")->result_array();
				 //    $total = $total[0]['total'];

				 //    $tempcount = $total - $limit;

				 //    $rand = rand($limit,$tempcount); 


					$result = $this->db->query("SELECT $field_name as word,word_id,image_file,word_english,audio_file,category_id,subcategory_id
								FROM tbl_word where  is_active='1' AND is_image_available='1' AND is_audio_available='1'  ORDER BY RAND()
LIMIT $limit ")->result_array();

					foreach ($result as $key => $value) {

					$category_id = $value['category_id'];
					$subcategory_id = $value['subcategory_id'];
					
					$result[$key]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value['image_file'];
					
					$language_code = $get_field_name[0]['language_code']; 
					 $aname = str_replace(" ","_",$value['audio_file']); 
					
					$root_path  = $this->config->item('root_path');


					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id.'/'.$aname.'_'.$language_code.'.m4a';

					if(file_exists($aufile)){

						$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

						$result[$key]['audio_file']="";

					}
					 
						
			//================================================== FOR GETTING 3 WRONG OPTIONS==================================================

					$queid = $value['word_id'];
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$option = $this->db->query("SELECT $field_name as word
								FROM tbl_word where subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND word_id!=$queid AND is_active='1'")->result_array();

					shuffle($option);
					$option = array_slice($option, 0, 3); 

					$result[$key]['option'] = $option;
					foreach ($option as $key1 => $value1) {

										$result[$key]['option'][$key1]['is_correct'] = 0;
							 }
			//==================================================For GETTING  currect option================================================================
							$wid = $result[$key]['word_id'];
							$option1 = $this->db->query("SELECT $field_name as word
								FROM tbl_word where  word_id=$wid AND is_active='1'")->result_array();

							 $arr  = array_push($result[$key]['option'], $option1[0]);
							
							 foreach ($option1 as $key2 => $value2) {

								$result[$key]['option'][3]['is_correct'] = 1;
							 }
								
								// for random index of options
								shuffle($result[$key]['option']);
							
					} // END FOR LOOP OF RESULT ARRAY
 			//================================================== For Random RESULTS==================================================
							shuffle($result);
							$result = array_slice($result, 0, $limit); 
							http_response(200, 1, 'Record not found',$result,'');	
				
	}

	public function test_type_3($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $data['question'];

//================================================== For GETTING Quetions==================================================
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];
					$language_code = $get_field_name[0]['language_code']; 
					

					// $total = $this->db->query("SELECT count(*) as total
					// 			FROM tbl_word where is_active='1' ")->result_array();
				 //    $total = $total[0]['total'];

				 //    $tempcount = $total - $limit;

				 //    $rand = rand($limit,$tempcount); 


					$result = $this->db->query("SELECT word_id,image_file,$field_name as word,audio_file,category_id,subcategory_id
								FROM tbl_word where is_active='1'  ORDER BY RAND()
LIMIT $limit")->result_array();

					foreach ($result as $key => $value) {
					  
					$category_id = $value['category_id'];
					$subcategory_id = $value['subcategory_id'];
					$aname = str_replace(" ","_",$value['audio_file']); 
//================================================== FOR GETTING 3 WRONG OPTIONS==================================================

					$queid = $value['word_id'];
					$get_field_name = $this->db->query("SELECT field_name FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$option = $this->db->query("SELECT $field_name as word
								FROM tbl_word where  subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND word_id!=$queid AND is_active='1'")->result_array();

					shuffle($option);
					$option = array_slice($option, 0, 3); 


					$aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					if(file_exists($aufile)){

						$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

						$result[$key]['audio_file']="";

					}		

					
					$result[$key]['option'] = $option;
					foreach ($option as $key1 => $value1) {
										$result[$key]['option'][$key1]['is_correct'] = 0;
							 }
//==================================================For GETTING  currect option================================================================
							$wid = $result[$key]['word_id'];
							$option1 = $this->db->query("SELECT $field_name as word
								FROM tbl_word where word_id=$wid AND is_active='1'")->result_array();

							 $arr  = array_push($result[$key]['option'], $option1[0]);
							
							 foreach ($option1 as $key2 => $value2) {
								$result[$key]['option'][3]['is_correct'] = 1;
							 }
								
								// for random index of options
								shuffle($result[$key]['option']);
					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS ==================================================
					   shuffle($result);
						  $result = array_slice($result, 0, $limit); 
						   
						   http_response(200, 1, 'Record not found',$result,'');	
				
	}

	public function test_type_4($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $data['question'];

//================================================== For GETTING Quetions==================================================
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					// $total = $this->db->query("SELECT count(*) as total
					// 			FROM tbl_word where is_active='1' AND is_image_available='1'")->result_array();
				 //    $total = $total[0]['total'];

				 //    $tempcount = $total - $limit;

				 //    $rand = rand($limit,$tempcount);


					$result = $this->db->query("SELECT word_id,image_file,$field_name as word,word_english,audio_file,category_id,subcategory_id
								FROM tbl_word where  is_active='1' AND is_image_available='1'  ORDER BY RAND()
LIMIT $limit")->result_array();

					foreach ($result as $key => $value) {

					$category_id = $value['category_id'];
					$subcategory_id = $value['subcategory_id'];

					$result[$key]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value['image_file'];

					 $language_code = $get_field_name[0]['language_code'];
					 $aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					if(file_exists($aufile)){

						$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

						$result[$key]['audio_file']="";

					}	
					 $result[$key]['option']=$value['word'];
						
					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS==================================================
						   shuffle($result);
						   $result = array_slice($result, 0, $limit); 
						   http_response(200, 1, 'Record not found',$result,'');	

	}

	public function test_type_5($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $data['question'];
			
//================================================== For GETTING Quetions==================================================
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					// $total = $this->db->query("SELECT count(*) as total
					// 			FROM tbl_word where is_active='1'")->result_array();
				 //    $total = $total[0]['total'];

				 //    $tempcount = $total - $limit;

				 //    $rand = rand($limit,$tempcount);

					$result = $this->db->query("SELECT word_id,image_file,$field_name as word,word_english,audio_file,category_id,subcategory_id
								FROM tbl_word where is_active='1' AND is_image_available='1' AND is_audio_available='1'   ORDER BY RAND()
LIMIT $limit")->result_array();
					
					foreach ($result as $key => $value) {

					$category_id = $value['category_id'];
					$subcategory_id = $value['subcategory_id'];
					  
					$result[$key]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value['image_file'];
					$language_code = $get_field_name[0]['language_code'];
					$aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					
					if(file_exists($aufile)){

						$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

						$result[$key]['audio_file']="";

					}
					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS==================================================
						  shuffle($result);
						  $result = array_slice($result, 0, $limit); 
						  
						http_response(200, 1, 'Record not found',$result,'');	
				
	}

	public function test_type_6($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $data['question'];
//================================================== For GETTING Quetions==================================================
					
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					// $total = $this->db->query("SELECT count(*) as total
					// 			FROM tbl_word where is_active='1' AND is_image_available='1' AND is_audio_available='1'")->result_array();
				 //    $total = $total[0]['total'];

				 //    $tempcount = $total - $limit;

				 //    $rand = rand($limit,$tempcount);


					$result = $this->db->query("SELECT word_id,image_file,$field_name as word,word_english,audio_file,category_id,subcategory_id
								FROM tbl_word where  is_image_available='1' AND is_audio_available='1'  AND is_active='1' ORDER BY RAND()
LIMIT $limit")->result_array();

					foreach ($result as $key => $value) {

					$category_id = $value['category_id'];
					$subcategory_id = $value['subcategory_id'];
					
					 $language_code = $get_field_name[0]['language_code'];
					 $aname = str_replace(" ","_",$value['audio_file']); 
					 $result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
							
//================================================== FOR GETTING 3 WRONG OPTIONS==================================================

					 $language_code = $get_field_name[0]['language_code'];
					 $aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					
					if(file_exists($aufile)){

						$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

						$result[$key]['audio_file']="";
					}
					$queid = $value['word_id'];
					$option = $this->db->query("SELECT image_file,subcategory_id
								FROM tbl_word where subcategory_id='$subcategory_id' AND exercise_mode_id='$exercise_mode_id' AND word_id!=$queid AND is_active='1' AND is_image_available='1'")->result_array();

					shuffle($option);
					$option = array_slice($option, 0, 1); 
					$result[$key]['option'] = $option;
				
					foreach ($option as $key1 => $value1) {
						
						$result[$key]['option'][$key1]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$value1['subcategory_id'] .'/'.$value1['image_file'];

						$result[$key]['option'][$key1]['is_correct'] = 0;
					}
//==================================================For GETTING  currect option================================================================
							$wid = $result[$key]['word_id'];
							$option1 = $this->db->query("SELECT image_file
								FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id' AND word_id=$wid AND is_active='1' AND is_image_available='1' AND is_audio_available='1'")->result_array();

							$arr  = array_push($result[$key]['option'], $option1[0]);
							
							foreach ($option1 as $key2 => $value2) {

								$result[$key]['option'][1]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$subcategory_id .'/'.$value2['image_file'];	
								$result[$key]['option'][1]['is_correct'] = 1;
							}
								
								// for random index of options
							shuffle($result[$key]['option']);
							
						

					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS==================================================
						 shuffle($result);
						 $result = array_slice($result, 0, $limit); 
						   
						 http_response(200, 1, 'Record not found',$result,'');	
	}

	public function test_type_7($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $data['question'];
//==============================================

//================================================== For GETTING Quetions==================================================


					// $total = $this->db->query("SELECT count(*) as total
					// 			FROM tbl_word where is_active='1'")->result_array();
				 //    $total = $total[0]['total'];

				 //    $tempcount = $total - $limit;

				 //    $rand = rand($limit,$tempcount);


					$result = $this->db->query("SELECT word_id,category_id,subcategory_id
								FROM tbl_word where  is_active='1'  ORDER BY RAND()
LIMIT $limit")->result_array();

					foreach ($result as $key => $value) {
					  
//================================================== FOR GETTING 3 WRONG OPTIONS==================================================
					$category_id = $value['category_id'];
					$subcategory_id = $value['subcategory_id'];


					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name1 = $get_field_name[0]['field_name'];

					$option = $this->db->query("SELECT word_id,$field_name as word_s,$field_name1 as word_t
								FROM tbl_word where category_id='$category_id' AND exercise_mode_id='$exercise_mode_id'")->result_array();

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

	public function test_type_8($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $data['question'];
//================================================== For GETTING Quetions==================================================
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];
					//print_r($get_field_name); die();

					// $total = $this->db->query("SELECT count(*) as total
					// 			FROM tbl_word where is_active='1' AND is_image_available='1'")->result_array();
				 //    $total = $total[0]['total'];

				 //    $tempcount = $total - $limit;

				 //    $rand = rand($limit,$tempcount);


					$result = $this->db->query("SELECT word_id,image_file,$field_name as word,word_english,audio_file,category_id,subcategory_id
								FROM tbl_word where  is_active='1' AND is_image_available='1'  ORDER BY RAND()
LIMIT $limit")->result_array();

					foreach($result as $key => $value) {
					  
					 $category_id = $value['category_id'];
					$subcategory_id = $value['subcategory_id'];

					$get_cate_name = $this->db->query("SELECT category_name FROM tbl_exercise_mode_categories WHERE exercise_mode_category_id='$category_id'")->result_array();
					$category_folder = $get_cate_name[0]['category_name'];

					$result[$key]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$subcategory_id.'/'.$value['image_file'];
					
					$language_code = $get_field_name[0]['language_code'];
					$aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					
					if(file_exists($aufile)){

						$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

						$result[$key]['audio_file']="";

					}					 
					 $result[$key]['word']=$value['word'];
						

//==================================================For GETTING  currect option================================================================
							$wid = $result[$key]['word_id'];
							$get_field_name1 = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
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
						 
							http_response(200, 1, 'Record not found',$result,'');	
				
	
	}


	public function test_type_9($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $data['question'];
//================================================== For GETTING Quetions==================================================
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];

					// $total = $this->db->query("SELECT count(*) as total
					// 			FROM tbl_word where is_active='1'")->result_array();
				 //    $total = $total[0]['total'];

				 //    $tempcount = $total - $limit;

				 //    $rand = rand($limit,$tempcount);


					$result = $this->db->query("SELECT word_id,image_file,$field_name as word,word_english,audio_file, ,subcategory_id
								FROM tbl_word where  is_active='1' AND is_audio_available='1'  ORDER BY RAND()
LIMIT $limit")->result_array();
					
					
					foreach ($result as $key => $value) {

					$category_id = $value['category_id'];
					$subcategory_id = $value['subcategory_id'];
					
					$language_code = $get_field_name[0]['language_code'];
					$aname = str_replace(" ","_",$value['audio_file']); 
					$root_path  = $this->config->item('root_path');
					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
					
					if(file_exists($aufile)){
 
						$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

						$result[$key]['audio_file']="";

					}
					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS==================================================
						  shuffle($result);
						  $result = array_slice($result, 0, $limit); 
	  					  http_response(200, 1, 'Record not found',$result,'');	
				
	}


	/* GRAMMER TYPES  START  */

	public function test_type_10($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $data['question'];

					$result = $this->db->query("SELECT question As word,options
								FROM tbl_grammer_master where target_language_id='$tlang' AND question_type='1' AND is_active='1' AND is_delete='0'")->result_array();
					$optionArr = [];
					foreach ($result as $key => $value) {

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
					http_response(200, 1, 'Record not found',$result,'');	
		
	}

	public function test_type_11($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $data['question'];
				
				$result = $this->db->query("SELECT question,options
								FROM tbl_grammer_master where target_language_id='$tlang' AND question_type='2' AND is_active='1' AND is_delete='0'")->result_array();
					 
				$ctn=0;
				foreach ($result as $key => $value) {
					
					$count = substr_count($value['question'], '#');
					$str1 = str_repeat("#",$count);
					$final_string = str_replace($str1,"...",$value['question']);
					$result[$ctn]['question']= $final_string;
					$ctn++;
				}
						
					shuffle($result);
					$result = array_slice($result, 0, $limit); 
					http_response(200, 1, 'Record not found',$result,'');	
	}

	/* PHRASES TYPE START */

	public function test_type_12($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $data['question'];
//================================================== For GETTING Quetions==================================================
					$get_field_name = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$tlang'")->result_array();
					$field_name = $get_field_name[0]['field_name'];
					$language_code = $get_field_name[0]['language_code'];
					//print_r($get_field_name); die();

					$result = $this->db->query("SELECT phrases_id,phrase_$language_code as word,phrase_en,audio_file,category_id,subcategory_id
								FROM tbl_phrases where is_active='1'")->result_array();


					foreach($result as $key => $value) {

					$category_id = $value['category_id'];
					$subcategory_id = $value['subcategory_id'];
					 
					 $aname = str_replace(" ","_",$value['audio_file']); 
					 $language_code = $get_field_name[0]['language_code'];
					$root_path  = $this->config->item('root_path');
					$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';
				
					if(file_exists($aufile) && $aname!=""){

						$result[$key]['audio_file']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$aname.'_'.$language_code.'.m4a';

					}else{

						$result[$key]['audio_file']="";

					}
						$result[$key]['word']=$value['word'];
						

//==================================================For GETTING  currect option================================================================
							$wid = $result[$key]['phrases_id'];
							$get_field_name1 = $this->db->query("SELECT field_name,language_code FROM tbl_source_language WHERE source_language_id='$slang'")->result_array();
							$field_name = $get_field_name1[0]['field_name'];
							$language_code = $get_field_name1[0]['language_code'];

							$option1 = $this->db->query("SELECT phrase_$language_code as word
								FROM tbl_phrases where category_id='$category_id'  AND phrases_id=$wid AND is_active='1'" )->result_array();
							
							 foreach ($option1 as $key2 => $value2) {
										$result[$key]['option']=$value2['word'];		
							 }
								
					} // END FOR LOOP OF RESULT ARRAY
 //================================================== For Random RESULTS==================================================
						   

						  // shuffle($result);
							
							$rand = 0;
							if(count($result) >= $limit){
								$count = count($result)-$limit ;
								 $rand = rand(0,$count);
							}else{

							   $result;
							}
							

						   $result = array_slice($result, $rand , $limit); 
						   http_response(200, 1, 'Record not found',$result,'');	
				
	}


	/* DIALOUGE TYPE START */

	public function test_type_13($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $data['question'];

						$result = $this->db->query("SELECT dialogue_master_id,title,full_audio,category_id,subcategory_id
									FROM tbl_dialogue_master  where target_language_id='$tlang' AND is_active='1' AND is_delete='0'")->result_array();
						  

					foreach($result as $key => $value) {
						
						$category_id = $value['category_id'];
						$subcategory_id = $value['subcategory_id'];

						$root_path  = $this->config->item('root_path');
						$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$value['full_audio'];
						
						if(file_exists($aufile) && $value['full_audio']!=""){

							$result[$key]['full_audio']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$value['full_audio'];

						}else{

							$result[$key]['full_audio']="";

						}

							$mid = $value['dialogue_master_id'];
							$result1 = $this->db->query("SELECT phrase,audio_name,speaker,sequence_no
									FROM  tbl_dialogue_list WHERE dialogue_master_id='$mid' order by sequence_no asc")->result_array();
						 

							foreach ($result1 as $k=> $v) {
								
								$root_path  = $this->config->item('root_path');
								$aufile=$root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$v['audio_name'];
							
								if(file_exists($aufile) && $v['audio_name']!=""){

									$result1[$k]['audio_name']=base_url().'uploads/audio/'.$category_id.'/'.$subcategory_id .'/'.$v['audio_name'];

								}else{

									$result1[$k]['audio_name']="";

								}
							}

							$result[$key]['list']=$result1;

					}
						  
						  shuffle($result);
						  $result = array_slice($result, 0, $limit); 
						  http_response(200, 1, 'Record not found',$result,'');

	}

	/* CULTURE TYPE START */

		public function test_type_15($data){
		
			$slang = $data['slang'];
			$tlang = $data['tlang'];
			$exercise_mode_id = $data['exercise_mode_id'];
			$limit = $data['question'];

					$result1 = $this->db->query("SELECT culture_master_id,title_text,external_link,paragraph,image_name,category_id,subcategory_id
								FROM tbl_culture_master where target_language_id='$tlang' AND is_active='1' AND is_delete='0'")->result_array();
			
					$questionarray =[];
					foreach ($result1 as $key1 => $value1) {
						
						$category_id = $value1['category_id'];
						$subcategory_id = $value1['subcategory_id'];

						$result1[$key1]['image_path']=base_url().'uploads/words/'.$category_id.'/'.$subcategory_id.'/'.$value1['image_name'];
						$mid= $value1['culture_master_id'];
						$result = $this->db->query("SELECT question As word,options
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
					http_response(200, 1, 'Record not found',$result,'');	
	}
	/*
	*  END @  ============================================================== TEST EXERCISE SECTION ======================================================================
	*/
	

}
