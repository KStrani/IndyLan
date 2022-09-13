<?php
class Admin_model extends CI_Model {
 
	
	public function __construct() {
       parent::__construct();
        $date = new DateTime();
        $this->getTimestamp = $date->getTimestamp();
  
	}
/* 

/*
| [:START:]
| -------------------------------------------------------------------
| 
| -------------------------------------------------------------------
*/	

	function check_login($data){
		 		
		$result=$this->db->get_Where('tbl_admin', $data)->num_rows();
	    return $result;

	}
	public function get_master_lang($admin_lang = null) {
		$admin_data = $this->session->userdata('logged_in');
		
		$type = $admin_data[0]['type'];
		$ret = array();
		if($type == '0')
		{
			$ret = $this->db->query("select * from tbl_master_language WHERE status='1'")->result_array();
			
		}else{

			
			$lang = explode(',', $admin_lang);
			
			foreach ($lang as $value) {
				$result = $this->db->query("select * from `tbl_master_language` WHERE `support_lang_id`=".$value." AND `status`= '1'")->result_array();
				
				$ret[] = $result[0];
				
			}
			
			
		}
		
		return  $ret;
		
	}

	public function get_support_lang_new(){
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
				
				$dt = array('source_language_id'=>$value['support_lang_id'],
							'language_name'=>$value['lang_name'],
							'lang_code'=>$value['lang_code'],
							'field_name'=>$value['field_name'],
							'image'=>$temp);
				$ret[] = $dt;
			}
			return  $ret;
			
		} 
		else{

			return  $ret;

		}
	}
	public function get_support_languages($admin_lang = null) {
		$admin_data = $this->session->userdata('logged_in');
		
		$type = $admin_data[0]['type'];
		$ret = array();
		if($type == '0')
		{
			$ret = $this->db->query("select * from tbl_source_language WHERE status='1'")->result_array();
			
		}else{

			
			$lang = explode(',', $admin_lang);
			
			foreach ($lang as $value) {
				$result = $this->db->query("select * from `tbl_source_language` WHERE `source_language_id`=".$value." AND `status`= '1'")->result_array();
				
				$ret[] = $result[0];
				
			}
			
			
		}
		
		return  $ret;
		
	}

	public function get_support_lang($id)
	{
		
		$ret = array();
		if($id!=''){
			$this->db->where('support_lang_id', $id);
			$this->db->select('*');
			$this->db->limit(1);
			$query = $this->db->get('tbl_master_language');
			$ret= $query->result_array();
			if($ret)
			{
				$ret=$ret[0];
			}
		}
		return $ret;
	}
	public function get_current_target_lang($id)
	{
		
		$ret = array();
		if($id!=''){
			$this->db->where('source_language_id', $id);
			$this->db->select('*');
			$this->db->limit(1);
			$query = $this->db->get('tbl_source_language');
			$ret= $query->result_array();
			if($ret)
			{
				$ret=$ret[0];
			}
		}
		return $ret;
	}
	public function get_source_lang() {
		
		$result = $this->db->query("select * from tbl_source_language where isinput = '1'")->result_array();
		return  $result;
	}

	public function get_source_lang_by_target($target_id)
	{
		$langArray = array();
		$row = $this->db->query("select * from tbl_source_language where source_language_id = ".$target_id." ")->row_array();	
		$langArray[0] = $row;
		$result = $this->db->query("select * from tbl_source_language where isinput = '1'")->result_array();
		foreach ($result as $key => $value) {
			$langArray[] = $value;
		}
		return $langArray;
	}

	public function get_exercise_mode() {
		
		$result = $this->db->query("select * from tbl_exercise_mode order by mode_name asc")->result_array();
		return  $result;
	}

	public function get_exercise_type($mid=null) {
		$support_lang_id = $this->session->userdata('support_lang_id');
		$query ="select * from tbl_exercise_type";
		if($mid!=""){

			$query.=" Where exercise_mode_id='$mid'";
		}
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function add_category($data){
		$insert = $this->db->insert('tbl_exercise_mode_categories', $data);
		
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}

	function add_user($data){
		unset($data['confirm_password']);
		unset($data['save']);
		
		$insert = $this->db->insert('tbl_admin', $data);
  		$insert_id = $this->db->insert_id();

		return  $insert_id;
		
	}

	function add_subcategory($data){
		$insert = $this->db->insert('tbl_exercise_mode_subcategories', $data);
		
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}

	function add_lang($data){
		$insert = $this->db->insert('tbl_source_language', $data);
  		$insert_id = $this->db->insert_id();
  		$code = $data['language_code'];
  		$name = strtolower($data['field_name']);
  		
  		$query = "ALTER TABLE tbl_exercise_mode_categories ADD category_name_in_$code VARCHAR( 255 ) NOT NULL after category_name_in_en";
		$this->db->query($query);

		$query = "ALTER TABLE tbl_exercise_mode_subcategories ADD subcategory_name_in_$code VARCHAR( 255 ) NOT NULL after subcategory_name_in_en";
		$this->db->query($query);

		$query = "ALTER TABLE tbl_word ADD $name VARCHAR( 255 ) NOT NULL after word_english";
		$this->db->query($query);
		
		$query = "ALTER TABLE tbl_phrases ADD phrase_$code VARCHAR( 255 ) NOT NULL after phrase_en";
		$this->db->query($query);

		$query = "ALTER TABLE tbl_exercise_type ADD type_$code VARCHAR( 255 ) NOT NULL after type_en";
		$this->db->query($query);

		return  $insert_id;
		
	}


	function add_words($data){
		$insert = $this->db->insert('tbl_word', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}

	function add_phrases($data){

		//print_r($data); die();
		$insert = $this->db->insert('tbl_phrases', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}


	function add_grammer($data){
		$insert = $this->db->insert('tbl_grammer_master', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}


	function add_dialogue_master($data){
		$insert = $this->db->insert('tbl_dialogue_master', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}

	function add_dialogue_list($data){
		$insert = $this->db->insert('tbl_dialogue_list', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}


	function add_culture_master($data){
		$insert = $this->db->insert('tbl_culture_master', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}

	function add_culture_que($data){
		$insert = $this->db->insert('tbl_culture_question', $data);
  		 $insert_id = $this->db->insert_id();
		return  $insert_id;
		
	}

	// function delete_dialogue_list($id){

	// 			$this->db->where('dialogue_id', $id);
	// 		$delete = $this->db->delete('tbl_dialogue_list');
	// 		if($delete){
	// 		return true;
	// 	}else{
	// 		return false;
	// 	}

	// }


	function add_category_exercise($data){
		$insert = $this->db->insert('tbl_exercise_mode_categories_exercise', $data);
		 $insert_id = $this->db->insert_id();
		return  $insert_id;
	}


	function get_category_list($mid=null){
		$support_lang_id = $this->session->userdata('support_lang_id');
		$query="select c.*,m.mode_name from tbl_exercise_mode_categories c LEFT JOIN tbl_exercise_mode m ON m.id=c.exercise_mode_id where c.is_active='1' AND c.support_lang_id='$support_lang_id' AND c.is_delete='0'";
		if($mid!=""){

			$query.=" AND c.exercise_mode_id='$mid' ";
		}

		$query.=" order by c.category_name_in_en DESC";

		$result = $this->db->query($query)->result_array();
		/*echo $query;
		echo "<pre>";
		print_r($result);
		exit;*/
		return  $result;
	}

	function get_portalUser_list()
	{
		$query="select * from tbl_admin where type ='1' AND is_deleted = '0'";
		
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function search_category_by_mode($mid=null){
		$support_lang_id = $this->session->userdata('support_lang_id');
		$query="select c.*,m.mode_name from tbl_exercise_mode_categories c LEFT JOIN tbl_exercise_mode m ON m.id=c.exercise_mode_id where c.is_active='1' AND c.support_lang_id='$support_lang_id' AND c.is_delete='0'";
		if($mid!=""){

			$query.=" AND c.exercise_mode_id='$mid' AND c.support_lang_id='$support_lang_id'";
		}
		//echo $query; die();
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_type_list(){
		$support_lang_id = $this->session->userdata('support_lang_id');
		$result = $this->db->query("select t.*,m.mode_name from tbl_exercise_type t LEFT JOIN tbl_exercise_mode m ON m.id=t.exercise_mode_id where t.support_lang_id='$support_lang_id'")->result_array();
		return  $result;
	}

	function get_words_list($mid=null,$cid=null,$scid=null,$sort=null,$search=null)
	{
		$support_lang_id = $this->session->userdata('support_lang_id');
		$support_lang_field = $this->session->userdata('support_lang_field_name');
		$query="select w.*,c.category_name from tbl_word w  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=w.category_id where w.is_active='1' AND w.support_lang_id='$support_lang_id'";
	
		if($cid!=""){

			$query.=" AND w.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND w.subcategory_id='$scid'";
		}
		if($mid!=""){

			$query.=" AND w.exercise_mode_id='$mid'";
		}

		if($search!=""){

		$query.=" AND (".$support_lang_field." LIKE '%$search%' 
		OR word_english LIKE '%$search%' 
		OR word_finnish LIKE '%$search%' 
		OR word_swedish LIKE '%$search%' 
		OR word_spanish LIKE '%$search%' 
		OR word_norwegian LIKE '%$search%' )";
		}

		if($sort!=""){

			if($sort=="1"){

				$query.=" AND w.support_lang_id='$support_lang_id' order by w.word_english asc ";

			}
			if($sort=="2"){

				$query.=" AND w.support_lang_id='$support_lang_id' order by w.word_english desc ";
			}
			
		}
		
		$query .=" order by w.word_id DESC";

		/*echo $query; die();*/
		$result = $this->db->query($query)->result_array();
		/*echo "<pre>";
		print_r($result);
		exit;*/
		return  $result;
	}

	function get_words_list_pagination($limit,$page,$mid=null,$cid=null,$scid=null,$sort=null,$search=null){
		$support_lang_field = $this->session->userdata('support_lang_field_name');
		$support_lang_id = $this->session->userdata('support_lang_id');
		$query="select w.*,c.category_name from tbl_word w  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=w.category_id where w.is_active='1'";
	
		
		if($mid!=""){

			$query.=" AND w.exercise_mode_id='$mid'";
		}
		if($cid!=""){

			$query.=" AND w.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND w.subcategory_id='$scid'";
		}
		if($search!=""){

			$query.=" AND (".$support_lang_field." = '$search' OR word_english ='$search' OR word_finnish = '$search' OR word_swedish= '$search' OR word_spanish = '$search' OR word_norwegian = '$search' )";

		}

		if($sort!=""){

			if($sort=="1"){

				$query.=" AND w.support_lang_id='$support_lang_id' order by w.word_english asc ";

			}
			if($sort=="2"){

				$query.=" AND w.support_lang_id='$support_lang_id' order by w.word_english desc ";
			}
			
		}else{

			$query .=" AND w.support_lang_id='$support_lang_id' order by w.word_english asc ";
		}
		

	    $query .=" limit $page,$limit ";


		$result = $this->db->query($query)->result_array();
		return  $result;
	}


	function get_grammer_list($lid=null,$cid=null,$scid=null,$sort=null){
	
	$query="select g.*,c.category_name from tbl_grammer_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1'";
	
		if($cid!=""){

			$query.=" AND g.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND g.subcategory_id='$scid'";
		}
		if($lid!=""){

			$query.=" AND g.target_language_id='$lid'";
		}
		// if($sort!=""){

		// 	if($sort=="1"){

		// 		$query.=" order by w.word_english asc ";

		// 	}
		// 	if($sort=="2"){

		// 		$query.=" order by w.word_english desc ";
		// 	}
			
		// }

		//echo $query; die();
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_grammer_list_pagination($limit,$page,$lid=null,$cid=null,$scid=null){
		$support_lang_id = $this->session->userdata('support_lang_id');

		$query="select g.*,c.category_name from tbl_grammer_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1'";
	
		if($cid!=""){

			$query.=" AND g.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND g.subcategory_id='$scid'";
		}
		// if($lid!=""){

		// 	$query.=" AND g.support_lang_id='$lid'";
		// }


	   $query .=" AND g.target_language_id='$support_lang_id' limit $page,$limit "; 
		
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	


	function get_phrases_list($cid=null,$scid=null){
		$support_lang_id = $this->session->userdata('support_lang_id');
		$query="select w.*,c.category_name from tbl_phrases w  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=w.category_id where w.is_active='1' AND w.support_lang_id='$support_lang_id'";
	
		if($cid!=""){

			$query.=" AND w.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND w.subcategory_id='$scid'";
		}
			
		

		//echo $query; die();
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_phrases_list_pagination($limit,$page,$cid=null,$scid=null){
		$support_lang_id = $this->session->userdata('support_lang_id');
		$query="select w.*,c.category_name from tbl_phrases w  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=w.category_id where w.is_active='1' AND w.support_lang_id='$support_lang_id'";
	
		
		
		if($cid!=""){

			$query.=" AND w.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND w.subcategory_id='$scid'";
		}

		

		$query .=" order by phrases_id desc ";
	


	   $query .=" limit $page,$limit ";


		$result = $this->db->query($query)->result_array();
		return  $result;
	}



	function get_dialogue_list($lid=null,$cid=null,$scid=null,$sort=null){
	$support_lang_id = $this->session->userdata('support_lang_id');
	$query="select g.*,c.category_name from tbl_dialogue_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1' AND g.is_delete='0' AND g.support_lang_id='$support_lang_id'";
	
		if($cid!=""){

			$query.=" AND g.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND g.subcategory_id='$scid'";
		}
		if($lid!=""){

			$query.=" AND g.target_language_id='$lid'";
		}
		
		//echo $query; die();
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_dialogue_list_pagination($limit,$page,$lid=null,$cid=null,$scid=null){
		$support_lang_id = $this->session->userdata('support_lang_id');
		$query="select g.*,c.category_name from tbl_dialogue_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1' AND g.is_delete='0' AND g.target_language_id='$support_lang_id'";
	
		if($cid!=""){

			$query.=" AND g.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND g.subcategory_id='$scid'";
		}
		if($lid!=""){

			$query.=" AND g.support_lang_id='$lid'";
		}


	   $query .=" limit $page,$limit "; 


		$result = $this->db->query($query)->result_array();
		return  $result;
	}



	function get_culture_list($lid=null,$cid=null,$scid=null,$sort=null){
	
	$query="select g.*,c.category_name from tbl_culture_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1' AND g.is_delete='0'";
	
		if($cid!=""){

			$query.=" AND g.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND g.subcategory_id='$scid'";
		}
		if($lid!=""){

			$query.=" AND g.target_language_id='$lid'";
		}
		
		//echo $query; die();
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_culture_list_pagination($limit,$page,$lid=null,$cid=null,$scid=null){
		$support_lang_id = $this->session->userdata('support_lang_id');
		$query="select g.*,c.category_name from tbl_culture_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1' AND g.is_delete='0'";
	
		if($cid!=""){

			$query.=" AND g.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND g.subcategory_id='$scid'";
		}
		if($lid!=""){

			$query.=" AND g.support_lang_id='$lid'";
		}


	   $query .=" AND g.target_language_id='$support_lang_id' limit $page,$limit "; 


		$result = $this->db->query($query)->result_array();
		return  $result;
	}





	function get_subcategory_list($cid=null,$mid=null){
	$support_lang_id = $this->session->userdata('support_lang_id');
	$query="select s.*,c.category_name from tbl_exercise_mode_subcategories s LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=s.category_id   where s.is_active='1' AND s.support_lang_id='$support_lang_id' AND s.is_delete='0'";
	if($cid!=""){

			$query.=" AND s.category_id='$cid'";
		}

		if($mid!=""){

			$query.=" AND c.exercise_mode_id='$mid'";
		}
		$query.=" order by s.subcategory_name_in_en asc";
		$result = $this->db->query($query)->result_array();
		return  $result;
	}


	function get_words_from_id($wid){
	
	$query="select w.*,c.category_name from tbl_word w  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=w.category_id where w.is_active='1' AND w.word_id='$wid'";
	$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_phrases_from_id($wid){
	
	$query="select w.*,c.category_name from tbl_phrases w  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=w.category_id where w.is_active='1' AND w.is_delete='0' AND w.phrases_id='$wid'";
	$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_grammar_from_id($wid){
	
		$query="select g.*,c.category_name from tbl_grammer_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1' AND g.grammer_master_id='$wid'";
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function get_dialogue_from_id($wid){
	
	$query="select g.*,c.category_name from tbl_dialogue_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1' AND g.dialogue_master_id='$wid'";
	$result = $this->db->query($query)->result_array();
		return  $result;
	}


	function get_dialogue_list_from_id($wid){
	
	$query="select l.* from tbl_dialogue_list l LEFT JOIN tbl_dialogue_master g ON l.dialogue_master_id=g.dialogue_master_id where l.dialogue_master_id='$wid'";
	$result = $this->db->query($query)->result_array();
		return  $result;
	}


	function get_culture_from_id($wid){
	
	$query="select g.*,c.category_name from tbl_culture_master g  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=g.category_id where g.is_active='1' AND g.culture_master_id='$wid'";
	$result = $this->db->query($query)->result_array();
		return  $result;
	}


	function get_culture_list_from_id($wid){
	
	$query="select l.* from tbl_culture_question l LEFT JOIN tbl_culture_master g ON l.culture_master_id=g.culture_master_id where l.culture_master_id='$wid'";
	$result = $this->db->query($query)->result_array();
		return  $result;
	}



	function delete_category($data,$id){

				$this->db->where('exercise_mode_category_id', $id);
			$update = $this->db->update('tbl_exercise_mode_categories', $data);
			if($update){
			return true;
		}else{
			return false;
		}

	}
	function delete_user($data,$id){

			$this->db->where('admin_id', $id);
			$update = $this->db->update('tbl_admin', $data);
			if($update){
			return true;
		}else{
			return false;
		}

	}

	function delete_category_type($id,$type){

			//	$this->db->where('category_id', $id);
				//$this->db->where('exercise_type_id', $type);
				$this->db->where(array('category_id' => $id, 'exercise_type_id' => $type));
			$update = $this->db->delete('tbl_exercise_mode_categories_exercise');
			if($update){
			return true;
		}else{
			return false;
		}

	}


	function delete_subcategory($data,$id){

				$this->db->where('exercise_mode_subcategory_id', $id);
			$update = $this->db->update('tbl_exercise_mode_subcategories', $data);
			if($update){
			return true;
		}else{
			return false;
		}

	}
	
	function delete_word($data,$id){

				$this->db->where('word_id', $id);
			$update = $this->db->update('tbl_word', $data);
			if($update){
			return true;
		}else{
			return false;
		}

	}



	function delete_row_by_condition($table,$data,$condition){

			$this->db->where($condition);
			$update = $this->db->update($table, $data);
			if($update){
			return true;
		}else{
			return false;
		}

	}





	function update_category($data,$id){
					$this->db->where('exercise_mode_category_id', $id);
					$update = $this->db->update('tbl_exercise_mode_categories', $data);
					if($update){
						return true;
				   }else{
						return false;
					}

	}
	function update_user($data,$id){
		
		$this->db->where('admin_id', $id);
		$update = $this->db->update('tbl_admin', $data);
		if($update){
			return true;
		}else{
			return false;
		}

	}
	function update_type($data,$id){
					$this->db->where('id', $id);
					$update = $this->db->update('tbl_exercise_type', $data);
					if($update){
						return true;
				   }else{
						return false;
					}

	}
	function update_subcategory($data,$id){
					$this->db->where('exercise_mode_subcategory_id', $id);
					$update = $this->db->update('tbl_exercise_mode_subcategories', $data);
					if($update){
						return true;
				   }else{
						return false;
					}

	}
	function update_word($data,$id){
					$this->db->where('word_id', $id);
					$update = $this->db->update('tbl_word', $data);
					if($update){
						return true;
				   }else{
						return false;
					}

	}

	function update_phrase($data,$id){
					$this->db->where('phrases_id', $id);
					$update = $this->db->update('tbl_phrases', $data);
					if($update){
						return true;
				   }else{
						return false;
					}

	}

	function update_grammar($data,$id){
					$this->db->where('grammer_master_id', $id);
					$update = $this->db->update('tbl_grammer_master', $data);
					if($update){
						return true;
				   }else{
						return false;
					}

	}

	function update_dialogue($data,$id){
					$this->db->where('dialogue_master_id', $id);
					$update = $this->db->update('tbl_dialogue_master', $data);
					if($update){
						return true;
				   }else{
						return false;
					}

	}

	function update_culture($data,$id){
		$this->db->where('culture_master_id', $id);
		$update = $this->db->update('tbl_culture_master', $data);
		if($update){
			return true;
		}else{
			return false;
		}
	}

	function update_profile($data,$id){
					$this->db->where('user_id', $id);
					$update = $this->db->update('tbl_users', $data);
					if($update){
						return true;
				   }else{
						return false;
					}

	}

	function update_culture_question($data,$id)
	{
		$this->db->where('culture_master_id', $id);
		$update = $this->db->update('tbl_culture_question', $data);
		if($update){
			return true;
		}else{
			return false;
		}
	}

	function delete_culture_question($id){

			$this->db->where('culture_master_id', $id);
			$delete = $this->db->delete('tbl_culture_question');
			if($delete){
			return true;
		}else{
			return false;
		}
	}

	function delete_dialogue_list($id){

			$this->db->where('dialogue_master_id', $id);
			$delete = $this->db->delete('tbl_dialogue_list');
			if($delete){
			return true;
		}else{
			return false;
		}
	}

	function delete_grammar($data,$id){

			$this->db->where('grammer_master_id', $id);
			$update = $this->db->update('tbl_grammer_master', $data);
			if($update){
			return true;
			}else{
				return false;
			}

	}

	function delete_dialogue($data,$id){

			$this->db->where('dialogue_master_id', $id);
			$update = $this->db->update('tbl_dialogue_master', $data);
			if($update){
			return true;
			}else{
				return false;
			}

	}

	function delete_culture($data,$id){

			$this->db->where('culture_master_id', $id);
			$update = $this->db->update('tbl_culture_master', $data);
			if($update){
			return true;
			}else{
				return false;
			}

	}

	function master_function_get_data_by_condition($table,$condition,$order=null,$orderby=null){

		$this->db->where($condition);
		$this->db->order_by($order, $orderby);
		$query = $this->db->get($table);
		
		return $result =$query->result_array();
		//print_r($result);
	}

	function master_function_for_update_by_conditions($table,$condition,$data){

					$this->db->where($condition);
					$update = $this->db->update($table, $data);
					if($update){
						return true;
				   	}else{
						return false;
					}
	}

	function master_function_for_delete_by_conditions($table,$condition)
	{
		$this->db->where($condition);
		$update = $this->db->delete($table);
		return true;
	}


function reset_password($data,$id){

			$this->db->where('reset_token',$id);
			$update = $this->db->update('tbl_users', $data);
		//echo $this->db->last_query(); die();
			if($update){
				return true;
			}else{
				return false;
			}

	}

function get_user_list(){

		$query="select * from tbl_users where is_active='1' AND type='0'";
		
		$query.=" order by user_id asc";

		$result = $this->db->query($query)->result_array();
		return  $result;
	}

function get_missing_image_audio($cid=null,$subcat=null){

		$query ="select word_english,category_id,subcategory_id,image_file,audio_file,is_image_available,is_audio_available from tbl_word where is_active='1' ";
		
		if($cid !=""){
			$query .=" AND category_id='$cid'";
		}
		if($subcat !=""){
			$query .=" AND subcategory_id='$subcat'";
		}

		$result = $this->db->query($query)->result_array();
		$final_array = array();
		$root_path  = $this->config->item('root_path');
		$ctn = 0;
		foreach ($result as $key) {
			
			
				$imagefile = $root_path.'uploads/words/'.$key['category_id'].'/'.$key['subcategory_id'].'/'.$key['image_file'] ;
				$audiofile = $root_path.'uploads/audio/'.$key['category_id'].'/'.$key['subcategory_id'].'/'.$key['audio_file'].'_en.m4a' ;
				if(!file_exists($imagefile) || !file_exists($audiofile) || $key['image_file']=="" || $key['audio_file']=="" ) {
					
						$final_array[$ctn]['image']= '0';
						$final_array[$ctn]['audio']= '0';
						$final_array[$ctn]['word']=$key['word_english'];
				
					if($key['is_image_available'] == "1"){

					$imagefile = $root_path.'uploads/words/'.$key['category_id'].'/'.$key['subcategory_id'].'/'.$key['image_file'] ;

						if(!file_exists($imagefile)){

							$final_array[$ctn]['image']= '1';
						}

						if($key['image_file'] == ""){
							$final_array[$ctn]['image']= '1';
						}
					}

					if($key['is_audio_available'] == "1"){

						$audiofile = $root_path.'uploads/audio/'.$key['category_id'].'/'.$key['subcategory_id'].'/'.$key['audio_file'].'_en.m4a' ;
						
						if(!file_exists($audiofile)){

							$final_array[$ctn]['audio']= '1';
						}

						if($key['audio_file'] == ""){
							$final_array[$ctn]['audio']= '1';
						}

					}

					if($final_array[$ctn]['audio']=="0" && $final_array[$ctn]['image']== "0"){

						unset($final_array[$ctn]);
					}
				}

		
			$ctn++;
		}
		//print_r($final_array);
		return  $final_array;
	}


	function add_aural($data)
	{
		$insert = $this->db->insert('tbl_aural_composition', $data);
		$insert_id = $this->db->insert_id();
		return  $insert_id;
	}

	function get_aural_from_id($wid)
	{
		$query="select w.*,c.category_name from tbl_aural_composition w  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=w.category_id where w.is_active='1' AND w.aural_id='$wid'";
		$result = $this->db->query($query)->result_array();
		return  $result;
	}

	function delete_aural($data,$id)
	{
		$this->db->where('aural_id', $id);
		$update = $this->db->update('tbl_aural_composition', $data);
		if($update){
			return true;
		}else{
			return false;
		}
	}

	function update_aural($data,$id)
	{
		$this->db->where('aural_id', $id);
		$update = $this->db->update('tbl_aural_composition', $data);
		if($update){
			return true;
		}else{
			return false;
		}
	}

	function get_aural_list($mid=null,$cid=null,$scid=null,$sort=null,$search=null)
	{
		$support_lang_id = $this->session->userdata('support_lang_id');
		$support_lang_field = $this->session->userdata('support_lang_field_name');
		$query="select w.*,c.category_name from tbl_aural_composition w  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=w.category_id where w.is_active='1' AND w.support_lang_id='$support_lang_id'";
	
		if($cid!=""){

			$query.=" AND w.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND w.subcategory_id='$scid'";
		}
		if($mid!=""){

			$query.=" AND w.exercise_mode_id='$mid'";
		}

		if($search!=""){

		$query.=" AND (".$support_lang_field." = '$search' OR word_english ='$search' OR word_finnish = '$search' OR word_swedish= '$search' OR word_spanish = '$search' OR word_norwegian = '$search' )";
		}

		if($sort!=""){

			if($sort=="1"){

				$query.=" AND w.support_lang_id='$support_lang_id' order by w.word_english asc ";

			}
			if($sort=="2"){

				$query.=" AND w.support_lang_id='$support_lang_id' order by w.word_english desc ";
			}
			
		}
		
		$query .=" order by w.aural_id DESC";

		/*echo $query; die();*/
		$result = $this->db->query($query)->result_array();
		/*echo "<pre>";
		print_r($result);
		exit;*/
		return  $result;
	}

	function get_aural_list_pagination($limit,$page,$mid=null,$cid=null,$scid=null,$sort=null,$search=null){
		$support_lang_field = $this->session->userdata('support_lang_field_name');
		$support_lang_id = $this->session->userdata('support_lang_id');
		$query="select w.*,c.category_name from tbl_aural_composition w  LEFT JOIN tbl_exercise_mode_categories c ON c.exercise_mode_category_id=w.category_id where w.is_active='1'";
	
		
		if($mid!=""){

			$query.=" AND w.exercise_mode_id='$mid'";
		}
		if($cid!=""){

			$query.=" AND w.category_id='$cid'";
		}
		if($scid!=""){

			$query.=" AND w.subcategory_id='$scid'";
		}
		if($search!=""){

			$query.=" AND (".$support_lang_field." = '$search' OR word_english ='$search' OR word_finnish = '$search' OR word_swedish= '$search' OR word_spanish = '$search' OR word_norwegian = '$search' )";

		}

		if($sort!=""){

			if($sort=="1"){

				$query.=" AND w.support_lang_id='$support_lang_id' order by w.word_english asc ";

			}
			if($sort=="2"){

				$query.=" AND w.support_lang_id='$support_lang_id' order by w.word_english desc ";
			}
			
		}else{

			$query .=" AND w.support_lang_id='$support_lang_id' order by w.word_english asc ";
		}
		

	    $query .=" limit $page,$limit ";


		$result = $this->db->query($query)->result_array();
		return  $result;
	}


}
