<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin_master extends CI_Controller {
		public function __construct() {
	        
	        parent::__construct();
			$this->data = $this->session->userdata('logged_in');
	   			
		}
		public function index() {
			_dx(__CLASS__ ."/". __function__);	 
		}

	/**************************************
	 @@ Developer: Nimesh Patel
	 @@ Project Start Date :  22-march-17
	 *************************************
	*/
	function test(){
		 phpinfo();
		//echo FCPATH;
	}

	function test_email()
    {
		$config = Array(
		    'protocol' => 'smtp',
		    'smtp_host' => 'veronica',
		    'smtp_port' => 465,
		    'smtp_user' => 'sfi@learnmera.com',
		    'smtp_pass' => 'Cuddlebear1',
		    'mailtype'  => 'html', 
		    'charset'   => 'iso-8859-1'
		);
		$this->load->library('email', $config);
		$this->email->from('sfi@learnmera.com', 'nimesh');
		$this->email->to('nimu811@gmail.com'); 
		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');  
		$this->email->send();
	//	echo $this->email->print_debugger();
 		die();
    }
   
    /*
    @@ Developer : Nimesh Patel 
    Login in to Admin Panel
    */	
	function login(){

				$this->form_validation->set_rules('email','Email','required');
				$this->form_validation->set_rules('password','Password','required');
				if($this->form_validation->run()==true){
					$email = $this->input->post('email');
					$pass = $this->input->post('password');
					$data = array('email'=>$email,'password'=>md5($pass));
					$check_login = $this->admin_model->check_login($data);						
					if($check_login=="1"){
							$sessiondata = $this->admin_model->master_function_get_data_by_condition('tbl_admin',$data);

							$this->session->set_userdata('logged_in',$sessiondata);
							$admin_language = $sessiondata[0]['support_lang_ids'];
							
							$support_language = $this->admin_model->get_support_languages($admin_language);

							if(!empty($support_language))
							{
								$support_lang_name = $support_language[0]['language_name'];

								$support_lang_code = $support_language[0]['language_code'];
								$support_lang_id = $support_language[0]['source_language_id'];
								$support_lang_field_name = $support_language[0]['field_name'];
								$this->session->set_userdata('support_lang_name',$support_lang_name);
								$this->session->set_userdata('support_lang_code',$support_lang_code);
								$this->session->set_userdata('support_lang_id',$support_lang_id);
								$this->session->set_userdata('support_lang_field_name',$support_lang_field_name);
							}

							redirect('admin_master/category_list','refresh');

					}else{
								$this->session->set_flashdata('error','Authentication Error');
								redirect('admin_master/login', 'refresh');
				  	}
				 }else{
					
				  		$data['error'] = $this->session->flashdata('error');
				  		$this->load->view('admin/login',$data);
				 }
	}
 	/*
    @@ Developer : Nimesh Patel 
   	Logout From Admin
    */	
	function logout(){
			$this->session->unset_userdata('logged_in');
			$this->session->unset_userdata('support_lang_name');
			$this->session->unset_userdata('support_lang_code');
			$this->session->unset_userdata('support_lang_id');
			$this->session->unset_userdata('support_lang_field_name');
			$this->load->view('admin/login');
	}

	/* 
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Excercise Types List 
    *************************************
    */
	public function type_list(){
			if($this->session->userdata('logged_in'))
			{
				$sessiondata = $this->session->userdata('logged_in');
				$data['useremail']=$sessiondata[0]['email'];
				$data['userefirst_name']=$sessiondata[0]['first_name'];
				$data['userelast_name']=$sessiondata[0]['last_name'];
				$data['type_list'] = $this->admin_model->get_type_list();
				$data['success_msg']=$this->session->flashdata('sucess_msg');
				$data['source_lang']=$this->admin_model->get_source_lang();
				$data['active_class']="type";
				$admin_data = $this->session->userdata('logged_in');
				$admin_language = $admin_data[0]['support_lang_ids'];
				$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
				$this->load->view('admin/header',$data);
				$this->load->view('admin/type_list',$data);
				$this->load->view('admin/side_menu',$data);
				$this->load->view('admin/footer');
			}else{
				  redirect('admin_master/login', 'refresh');
			}
	}
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Excercise Types List  Edit
   	*************************************
    */
	public function edit_type($id){

		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			$source_lang=$this->admin_model->get_source_lang();
			foreach($source_lang as $key){
				$this->form_validation->set_rules('type_name_'.$key['language_code'],'Type Name in '.$key['language_name'],'required');
			}
			if($this->form_validation->run() == FALSE)
	       	{
			    $data['edit_data'] = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_type',array('id'=>$id));
				$data['source_lang']=$this->admin_model->get_source_lang();
				$data['active_class']="type";
				$this->load->view('admin/header',$data);
				$this->load->view('admin/edit_type',$data);
				$this->load->view('admin/side_menu',$data);
				$this->load->view('admin/footer');	
	     	}else{

				if(empty($_FILES['userfile']['name'])){
					$data = array();
					foreach($source_lang as $langkey){
						$name = $this->input->post('type_name_'.$langkey['language_code']);
						$data["type_".$langkey['language_code']] = $name;
						$data["type_name"] = $name = $this->input->post('type_name_en');

					}
					$data['support_lang_id'] = $this->session->userdata('support_lang_id');				
					$insert = $this->admin_model->update_type($data,$id);
					if($insert){
						$this->session->set_flashdata('sucess_msg','Type Updated Successfully');
						redirect('admin_master/type_list', 'refresh');
					}
				}else{

					$path = FCPATH.'uploads/'; 
					$config['upload_path'] = $path; 
					$config['allowed_types'] = "gif|jpg|png|jpeg";
			        $this->load->library('upload', $config);
					$this->upload->initialize($config);
	                if ( ! $this->upload->do_upload('userfile'))
	                {
                        $error = array('error' => $this->upload->display_errors());
						print_r($error); die();
                        $this->session->set_flashdata('error_upload', $error['error']);
                        redirect('admin_master/type_list', 'refresh');

	 				 }else{
	                	 $upload_data = $this->upload->data();
	       				 $data = array(	
								"image"=>$upload_data['file_name']
							);
	       				foreach($source_lang as $langkey){
							$name = $this->input->post('type_name_'.$langkey['language_code']);
							$data["type_".$langkey['language_code']] = $name;
							$data["type_name"] = $name = $this->input->post('type_name_en');

						}
						$data['support_lang_id'] = $this->session->userdata('support_lang_id');
						$insert = $this->admin_model->update_type($data,$id);
						if($insert){
							$this->session->set_flashdata('sucess_msg','Type Updated Successfully');
							redirect('admin_master/type_list', 'refresh');
						}
					}
				}
	
			}
		}else{

				redirect('admin_master/login', 'refresh');
			}
	}
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for add category
   	*************************************
    */
	public function add_category() {

		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			$admin_data = $this->session->userdata('logged_in');
			$admin_language = $admin_data[0]['support_lang_ids'];
			$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
			$this->form_validation->set_rules('mode', 'Mode', 'required');
			// $source_lang=$this->admin_model->get_source_lang();		// New Target change
			$target_lang_id = $this->session->userdata('support_lang_id');
			$source_lang= $this->admin_model->get_source_lang_by_target($target_lang_id);
			foreach($source_lang as $key){
				$this->form_validation->set_rules('cate_name_'.$key['language_code'],'Category Name in '.$key['language_name'],'required');
			}

			if($this->form_validation->run() == FALSE)
	       	{
		        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
		        $data['exercise_type']=$this->admin_model->get_exercise_type();
		        // $data['source_lang']=$this->admin_model->get_source_lang();		// New Target change
		        $data['source_lang']=$source_lang;
		        $data['success_msg']=$this->session->flashdata('insert_cat');
		        $data['error_msg']=$this->session->flashdata('error_upload');
		        $data['active_class']="category";				
				$this->load->view('admin/header',$data);
				$this->load->view('admin/add_category',$data);
				$this->load->view('admin/side_menu',$data);
				$this->load->view('admin/footer');
				
	     	}else{

				$mode = $this->input->post('mode');
				$config =  array(
                  'upload_path'     => "./uploads/",
                  'allowed_types'   => "gif|jpg|png|jpeg",
                );

                 $this->load->library('upload', $config);
				 $this->upload->initialize($config);
				 	// echo "<pre>";
					// 	print_r($_FILES);
					// 	exit;
						$imagename = "";
				 		/*if(!empty($_FILES['userfile']['name'])){
				 					$this->upload->do_upload('userfile');
									$upload_data = $this->upload->data();
									$imagename = $upload_data['file_name'];
				 		}	*/

								if(($_FILES["userfile"]['error']==0))
								{
									  $upload_file=$_FILES["userfile"]["name"];
									 
									  @move_uploaded_file($_FILES["userfile"]["tmp_name"],FILE_UPLOAD.$upload_file);
									  $imagename=$upload_file;
								}			
						$data = array(
								"exercise_mode_id"=>$mode,
								"image"=>$imagename
						);
					
					foreach($source_lang as $langkey){
						$name = $this->input->post('cate_name_'.$langkey['language_code']);
						$data["category_name_in_".$langkey['language_code']] = $name;
					}	

						$data['support_lang_id'] = $this->session->userdata('support_lang_id');		

						$insert = $this->admin_model->add_category($data);
						
						//-------- Create Folder For images-----------
						$name = $this->input->post('cate_name_en');
						if (!file_exists('./uploads/words/'.$insert)) {
   							 mkdir('./uploads/words/'.$insert, 0777, true);
						}
						if (!file_exists('./uploads/audio/'.$insert)) {
   							 mkdir('./uploads/audio/'.$insert, 0777, true);
						}

						if($insert){
							$this->session->set_flashdata('sucess_msg','Category Inserted Successfully');
							redirect('admin_master/category_list', 'refresh');
						}

			}

		}else{

			redirect('admin_master/login', 'refresh');
		}
	
	}
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for List category
   	*************************************
    */
	public function category_list(){

		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			$mid = $this->uri->segment(3);
			$data['mid']=$mid;
			$data['category_list'] = $this->admin_model->get_category_list($mid);
			$data['success_msg']=$this->session->flashdata('sucess_msg');
			$data['error_msg']=$this->session->flashdata('error_msg');
			$data['exercise_mode']=$this->admin_model->get_exercise_mode();
			// $data['source_lang']= $this->admin_model->get_source_lang();		// New Target change
			$target_lang_id = $this->session->userdata('support_lang_id');
			$data['source_lang']= $this->admin_model->get_source_lang_by_target($target_lang_id);
			$admin_data = $this->session->userdata('logged_in');
			$admin_language = $admin_data[0]['support_lang_ids'];
			$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
			
			
			$slang_name = $this->session->userdata('support_lang_name');
			/*echo $slang_name;
			exit;*/
			$data['current_support_lang'] = $slang_name;
			$data['active_class']="category";
			$this->load->view('admin/header',$data);
			$this->load->view('admin/category_list',$data);
			$this->load->view('admin/side_menu',$data);
			$this->load->view('admin/footer');

		}else{

			redirect('admin_master/login', 'refresh');

		}
	}

	function get_mode_category(){
		$modeid = $this->input->post('mode_id');
		$result = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_categories',array('exercise_mode_id'=>$modeid));

		$result = $this->admin_model->search_category_by_mode($modeid);
		echo json_encode($result);
	}
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Delete category
   	*************************************
    */
	public function delete_category(){

				$id = $this->uri->segment('3');
				$data = array('is_active'=>'0','is_delete'=>'1');
				$delete = $this->admin_model->delete_category($data,$id);
				$data1 = array('is_active'=>'0');
				$delete = $this->admin_model->delete_row_by_condition('tbl_word',$data1,array('category_id'=>$id));
				$delete = $this->admin_model->delete_row_by_condition('tbl_grammer_master',$data,array('category_id'=>$id));
				$delete = $this->admin_model->delete_row_by_condition('tbl_culture_master',$data,array('category_id'=>$id));
				$delete = $this->admin_model->delete_row_by_condition('tbl_phrases',$data,array('category_id'=>$id));
				$delete = $this->admin_model->delete_row_by_condition('tbl_dialogue_master',$data,array('category_id'=>$id));
				$delete = $this->admin_model->delete_row_by_condition('tbl_aural_composition',$data1,array('category_id'=>$id));
				if($delete){
					$this->session->set_flashdata('sucess_msg','Category Deleted Successfully');
					redirect('admin_master/category_list', 'refresh');	
				}
	}
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Edit category
   	*************************************
    */
	public function edit_category($id){

		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			// $source_lang=$this->admin_model->get_source_lang();		// New Target change
			$target_lang_id = $this->session->userdata('support_lang_id');
			$source_lang= $this->admin_model->get_source_lang_by_target($target_lang_id);
					$this->form_validation->set_rules('mode', 'Mode', 'required');
					foreach($source_lang as $key){
						$this->form_validation->set_rules('cate_name_'.$key['language_code'],'Category Name in '.$key['language_name'],'required');
					}
					if($this->form_validation->run() == FALSE)
			       	{
				       	$data['edit_data'] = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_categories',array('exercise_mode_category_id'=>$id));
						$data['exercise_mode']=$this->admin_model->get_exercise_mode();
						$data['exercise_type']=$this->admin_model->get_exercise_type();
						// $data['source_lang']=$this->admin_model->get_source_lang();		// New Target change
						$data['source_lang'] = $source_lang;
						$admin_data = $this->session->userdata('logged_in');
						$admin_language = $admin_data[0]['support_lang_ids'];
						$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
						$slang_name = $this->session->userdata('support_lang_name');
						$data['current_support_lang'] = $slang_name;
						$data['get_selected_type'] = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_categories_exercise',array('category_id'=>$id));
						$data['active_class']="category";
						$this->load->view('admin/header',$data);
						$this->load->view('admin/edit_category',$data);
						$this->load->view('admin/side_menu',$data);
						$this->load->view('admin/footer');	
			     	}else{

						$mode = $this->input->post('mode');
						$types = $this->input->post('type');
						$selected_type = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_categories_exercise',array('category_id'=>$id));

						
			

						if(empty($_FILES['userfile']['name'])){
								$data = array(
										"exercise_mode_id"=>$mode,
									);
								foreach($source_lang as $langkey){
									$name = $this->input->post('cate_name_'.$langkey['language_code']);
									$data["category_name_in_".$langkey['language_code']] = $name;
								}
								if($this->input->post('is_image_delete') =="0"){
										 		$data['image']="";
								}
								$data['support_lang_id'] = $this->session->userdata('support_lang_id');
								$insert = $this->admin_model->update_category($data,$id);
								
								if($insert){
									if($this->input->post('is_image_delete') == "0"){

										unlink($this->input->post('old_image_name'));
									}
									$this->session->set_flashdata('sucess_msg','Category Updated Successfully');
									redirect('admin_master/category_list', 'refresh');
								}

						}else{

							/*$config =  array(
			                  'upload_path'     => "./uploads/",
			                  'allowed_types'   => "gif|jpg|png|jpeg",                
			                );*/
			                /*$config = array(
					        'upload_path' => "./uploads/",
					        'allowed_types' => "gif|jpg|png|jpeg",
					        'overwrite' => TRUE,
					        'max_size' => "2048000"
					        );*/
					        $cat_data = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_categories',array('exercise_mode_category_id'=>$id));
					        $imagename = '';
					        if(($_FILES["userfile"]['error']==0))
							{
								if(isset($cat_data[0]['image']) && !empty($cat_data[0]['image']) && file_exists(FILE_UPLOAD.$path.$cat_data[0]['image']))
								{
									unlink(FILE_UPLOAD.$cat_data[0]['image']);
								}
								$upload_file=$_FILES["userfile"]["name"];
								@move_uploaded_file($_FILES["userfile"]["tmp_name"],FILE_UPLOAD.$upload_file);
								$imagename=$upload_file;
							}	
			               
			                	 $data = array(
										"exercise_mode_id"=>$mode,
										//"category_name"=>$name,
										"image"=>$imagename
									);
			                	foreach($source_lang as $langkey){
								$name = $this->input->post('cate_name_'.$langkey['language_code']);
								$data["category_name_in_".$langkey['language_code']] = $name;

								}
								$data['support_lang_id'] = $this->session->userdata('support_lang_id');
								$insert = $this->admin_model->update_category($data,$id);

								if($insert){
									$this->session->set_flashdata('sucess_msg','Category Updated Successfully');
									redirect('admin_master/category_list', 'refresh');
								}
							 
			                
		                }
			
					}
		}else{

			redirect('admin_master/login', 'refresh');
		}

	}
	/*
	*************************************
    @@ Developer : Navdeepsinh Jethwa 
   	@@ Description : Function for List portaluser
   	*************************************
    */
	public function portal_users(){

		if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			
			
			$data['portal_users'] = $this->admin_model->get_portalUser_list();

			$data['success_msg']=$this->session->flashdata('sucess_msg');
			$data['error_msg']=$this->session->flashdata('error_msg');
			$admin_data = $this->session->userdata('logged_in');

			$admin_language = $admin_data[0]['support_lang_ids'];
			$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
			
			$slang_name = $this->session->userdata('support_lang_name');
			
			$data['current_support_lang'] = $slang_name;
			$data['active_class']="portal_user";

			$this->load->view('admin/header',$data);

			$this->load->view('admin/portaluser_list',$data);

			$this->load->view('admin/side_menu',$data);
			$this->load->view('admin/footer');

		}else{

			redirect('admin_master/login', 'refresh');

		}
	}
	/*
	*************************************
    @@ Developer : Navdeepsinh Jethwa 
   	@@ Description : Function for add portal user
   	*************************************
    */
	public function add_user() {

		
		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			$admin_data = $this->session->userdata('logged_in');
			$admin_language = $admin_data[0]['support_lang_ids'];
			$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
			$this->form_validation->set_rules('first_name', 'FirstName', 'required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
			
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
			
			

			if($this->form_validation->run() == FALSE)
	       	{

		        $data['success_msg']=$this->session->flashdata('insert_user');
		        $data['error_msg']=$this->session->flashdata('error_upload');
		        $data['active_class']="portaluser";				
				$this->load->view('admin/header',$data);
				$this->load->view('admin/add_users',$data);
				$this->load->view('admin/side_menu',$data);
				$this->load->view('admin/footer');
				
	     	}else{

						$data = $this->input->post();
						$ret = array('first_name'=>$data['first_name'],
										  'last_name'=>$data['last_name'],
										  'email'=>$data['email'],
										  'type'=>'1');
						if($this->input->post('phone'))
						{
							$ret['phone'] = $this->input->post('phone');
						}
						$pass = $this->input->post('password');
						if(!empty($this->input->post('support_lang_ids')))
			            {
			                $ret['support_lang_ids'] = implode(',', $this->input->post('support_lang_ids'));
			            }
		                $ret['password'] = md5($pass);
		                
						
						$insert = $this->admin_model->add_user($ret);

						if($insert){
							$this->session->set_flashdata('sucess_msg','User Inserted Successfully');
							redirect('admin_master/portal_users', 'refresh');
						}

			}

		}else{

			redirect('admin_master/login', 'refresh');
		}
	
	}
	/*
	*************************************
    @@ Developer : Navdeepsinh Jethwa 
   	@@ Description : Function for edit portal user
   	*************************************
    */
	public function edit_portaluser($id) {

		/**/
		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			$this->form_validation->set_rules('first_name', 'FirstName', 'required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
			
			$data = $this->input->post();
			if(in_array('change_password', $data) && $data['change_password'] == 1)
			{
				$this->form_validation->set_rules('password', 'Password', 'required');
				$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
			}
			
					if($this->form_validation->run() == FALSE)
			       	{
			       		//$id = $this->uri->segment('3');
				       	$user = $this->admin_model->master_function_get_data_by_condition('tbl_admin',array('admin_id'=>$id));
						$data['edit_data'] = $user[0];
						$admin_data = $this->session->userdata('logged_in');
						$admin_language = $admin_data[0]['support_lang_ids'];
						$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
						$slang_name = $this->session->userdata('support_lang_name');
						$data['current_support_lang'] = $slang_name;
						
						$data['active_class']="category";
						$this->load->view('admin/header',$data);
						$this->load->view('admin/edit_users',$data);
						$this->load->view('admin/side_menu',$data);
						$this->load->view('admin/footer');	
			     	}else{
			               
			               
							
							$ret = array('first_name'=>$data['first_name'],
										  'last_name'=>$data['last_name'],
										  'email'=>$data['email']);
							if($this->input->post('phone'))
							{
								$ret['phone'] = $this->input->post('phone');
							}
							
							if(!empty($this->input->post('support_lang_ids')))
			                {
			                  $ret['support_lang_ids'] = implode(',', $this->input->post('support_lang_ids'));
			                }
							if($this->input->post('password'))
							{
								$pass = $this->input->post('password');
								$ret['password'] = md5($pass);
							}

							
							$insert = $this->admin_model->update_user($ret,$id);

							if($insert){
								$this->session->set_flashdata('sucess_msg','User Updated Successfully');
								redirect('admin_master/portal_users', 'refresh');
							}
					}
		}else{

			redirect('admin_master/login', 'refresh');
		}
	
	}
	/*
	*************************************
    @@ Developer : Navdeepsinh Jethwa 
   	@@ Description : Function for Delete User
   	*************************************
    */
	public function delete_portaluser(){

				$id = $this->uri->segment('3');
				$data = array('is_deleted'=>'1');
				$delete = $this->admin_model->delete_user($data,$id);
				if($delete){
					$this->session->set_flashdata('sucess_msg','User Deleted Successfully');
					redirect('admin_master/portal_users', 'refresh');	
				}
	}
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Add Sub-category
   	*************************************
    */
	public function add_subcategory(){

			if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			$this->form_validation->set_rules('category', 'Category', 'required');
			$this->form_validation->set_rules('level','Level','required');
			$this->form_validation->set_rules('type[]','Type','required');
			// $source_lang=$this->admin_model->get_source_lang();		// New Target change
			$target_lang_id = $this->session->userdata('support_lang_id');
			$source_lang= $this->admin_model->get_source_lang_by_target($target_lang_id);
			foreach($source_lang as $key){
				$this->form_validation->set_rules('subcate_name_'.$key['language_code'],'SubCategory Name in '.$key['language_name'],'required');
			}
				if($this->form_validation->run() == FALSE)
		       	{
					$data['category']=$this->admin_model->get_category_list();
			        // $data['exercise_type']=$this->admin_model->get_exercise_type();
			        // $data['source_lang']=$this->admin_model->get_source_lang();		// New Target change
			        $data['source_lang'] = $source_lang;
			        $data['success_msg']=$this->session->flashdata('insert_cat');
 					$data['exercise_type']=$this->admin_model->get_exercise_type();
			        $data['error_msg']=$this->session->flashdata('error_upload');
			        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
			        $admin_data = $this->session->userdata('logged_in');
					$admin_language = $admin_data[0]['support_lang_ids'];
					$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
			        $slang_name = $this->session->userdata('support_lang_name');
					$data['current_support_lang'] = $slang_name;
			        $data['active_class']="subcategory";			
					$this->load->view('admin/header',$data);
					$this->load->view('admin/add_subcategory',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');
					
		     	}else{
					$category = $this->input->post('category');
					$level = $this->input->post('level');
					/*$config =  array(
	                  'upload_path'     => "./uploads/",
	                  'allowed_types'   => "gif|jpg|png|jpeg",
	                );
	                $this->load->library('upload', $config);
					$this->upload->initialize($config);*/
					$imagename = "";
				 		/*if(!empty($_FILES['userfile']['name'])){
				 					$this->upload->do_upload('userfile');

									$upload_data = $this->upload->data();
									$imagename = $upload_data['file_name'];
				 		}*/
				 		if(($_FILES["userfile"]['error']==0))
						{
									  $upload_file=$_FILES["userfile"]["name"];
									 
									  @move_uploaded_file($_FILES["userfile"]["tmp_name"],FILE_UPLOAD.$upload_file);
									  $imagename=$upload_file;
						}
						$upload_data = $this->upload->data();
							$data = array(
									"category_id"=>$category,
									//"subcategory_name"=>$name,
									"difficulty_level_id"=>$level,
									"image"=>$imagename
							);
						foreach($source_lang as $langkey){
							$name = $this->input->post('subcate_name_'.$langkey['language_code']);
							$data["subcategory_name_in_".$langkey['language_code']] = $name;
						}
						$data['support_lang_id'] = $this->session->userdata('support_lang_id');	
							
							$insert = $this->admin_model->add_subcategory($data);
							//------ create subcategory folder in category folder ------ 
							if (!file_exists('./uploads/words/'.$category.'/'.$insert)) {
	   							 mkdir('./uploads/words/'.$category.'/'.$insert, 0777, true);
							}
							if (!file_exists('./uploads/audio/'.$category.'/'.$insert)) {
	   							 mkdir('./uploads/audio/'.$category.'/'.$insert, 0777, true);
							}
							$path = getcwd().'/uploads/words/'.$category.'/'.$insert.'/index.php';
							$this->makePathNotAccessible($path);
							$path1 = getcwd().'/uploads/audio/'.$category.'/'.$insert.'/index.php';
							$this->makePathNotAccessible($path1);

							$types = $this->input->post('type');
							foreach($types as $key){
							 		$data = array(
										"exercise_type_id"=>$key,
										"category_id"=>$insert,
									);
									$data['support_lang_id'] = $this->session->userdata('support_lang_id');										
							 		$insert_exercise = $this->admin_model->add_category_exercise($data);
							}

							if($insert){
								$this->session->set_flashdata('sucess_msg','SubCategory Inserted Successfully');
								redirect('admin_master/subcategory_list', 'refresh');
							}
				}

			}else{

						redirect('admin_master/login', 'refresh');
			}
		
	}
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for List Sub-category
   	*************************************
    */
	public function subcategory_list(){

			if ($this->session->userdata('logged_in')){

				$sessiondata = $this->session->userdata('logged_in');
				$data['useremail']=$sessiondata[0]['email'];
				$data['userefirst_name']=$sessiondata[0]['first_name'];
				$data['userelast_name']=$sessiondata[0]['last_name'];
				$category = $this->uri->segment(4);
				$mode = $this->uri->segment(3);
				$data['category']=$category;
				$data['mode']=$mode;
				$data['subcategory_list'] = $this->admin_model->get_subcategory_list($category,$mode);
				$data['success_msg']=$this->session->flashdata('sucess_msg');
				$data['error_msg']=$this->session->flashdata('error_msg');
				$data['category_list']=$this->admin_model->get_category_list($mode);
				// $data['source_lang']=$this->admin_model->get_source_lang();		// New Target change
				$target_lang_id = $this->session->userdata('support_lang_id');
				$data['source_lang']= $this->admin_model->get_source_lang_by_target($target_lang_id);
				$data['active_class']="subcategory";
				$data['exercise_mode']=$this->admin_model->get_exercise_mode();
				$admin_data = $this->session->userdata('logged_in');
				$admin_language = $admin_data[0]['support_lang_ids'];
				$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
				$slang_name = $this->session->userdata('support_lang_name');
				$data['current_support_lang'] = $slang_name;
				$this->load->view('admin/header',$data);
				$this->load->view('admin/subcategory_list',$data);
				$this->load->view('admin/side_menu',$data);
				$this->load->view('admin/footer');
			}else{
				redirect('admin_master/login', 'refresh');
			}
	}
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Delete sub-category
   	*************************************
    */

	public function delete_subcategory(){

				$id = $this->uri->segment('3');
				$data = array('is_active'=>'0','is_delete'=>'1');
				$delete = $this->admin_model->delete_subcategory($data,$id);
				$data1 = array('is_active'=>'0');
				$delete = $this->admin_model->delete_row_by_condition('tbl_word',$data1,array('subcategory_id'=>$id));
				$delete = $this->admin_model->delete_row_by_condition('tbl_grammer_master',$data,array('subcategory_id'=>$id));
				$delete = $this->admin_model->delete_row_by_condition('tbl_culture_master',$data,array('subcategory_id'=>$id));
				$delete = $this->admin_model->delete_row_by_condition('tbl_phrases',$data,array('subcategory_id'=>$id));
				$delete = $this->admin_model->delete_row_by_condition('tbl_dialogue_master',$data,array('subcategory_id'=>$id));
				$delete = $this->admin_model->delete_row_by_condition('tbl_aural_composition',$data1,array('subcategory_id'=>$id));
				if($delete){
					$this->session->set_flashdata('sucess_msg','SubCategory Deleted Successfully');
					header('Location: '.$_SERVER['HTTP_REFERER']);
					//redirect('admin_master/subcategory_list', 'refresh');	
				}
	}
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for edit Sub-category
   	*************************************
    */
	public function edit_subcategory($id){
		
		if($this->session->userdata('logged_in'))
		{
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			$this->form_validation->set_rules('category', 'Category', 'required');
			$this->form_validation->set_rules('level','Level','required');
			// $source_lang=$this->admin_model->get_source_lang();		// New Target change
			$target_lang_id = $this->session->userdata('support_lang_id');
			$source_lang= $this->admin_model->get_source_lang_by_target($target_lang_id);
			foreach($source_lang as $key){
				$this->form_validation->set_rules('subcate_name_'.$key['language_code'],'SubCategory Name in '.$key['language_name'],'required');
			}
			if($this->form_validation->run() == FALSE)
	       	{
				// echo "<pre>"; print_r($_REQUEST); die;
				if(isset($_POST) && !empty($_POST))
				{		
					$selected_type = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_categories_exercise',array('category_id'=>$id));
					$category = $this->input->post('category');
					$name = $this->input->post('subcate_name');
					$level = $this->input->post('level');
					$types = $this->input->post('type');
					if(empty($_FILES['userfile']['name'])){
							$data = array(
									"category_id"=>$category,
									"difficulty_level_id"=>$level
							);
							foreach($source_lang as $langkey){
								$name = $this->input->post('subcate_name_'.$langkey['language_code']);
								$data["subcategory_name_in_".$langkey['language_code']] = $name;
							}
							$data['support_lang_id'] = $this->session->userdata('support_lang_id');
							$insert = $this->admin_model->update_subcategory($data,$id);
							$delete = $this->admin_model->master_function_for_delete_by_conditions('tbl_exercise_mode_categories_exercise',array('support_lang_id'=>$data['support_lang_id'],'category_id'=>$id));
								// foreach($selected_type as $typekey){
								// 	$delete_exercise = $this->admin_model->delete_category_type($id,$typekey['exercise_type_id']);
								// }
								if($this->input->post('is_image_delete') == "0"){
										$data['image']="";
								}
								foreach($types as $key){										
										$data = array(
											"exercise_type_id"=>$key,
											"category_id"=>$id,
										);
										$data['support_lang_id'] = $this->session->userdata('support_lang_id');	
								 		$insert_exercise = $this->admin_model->add_category_exercise($data);
								}

							if($insert){
								if($this->input->post('is_image_delete') == "0"){
											unlink($this->input->post('old_image_name'));
								}
								$this->session->set_flashdata('sucess_msg','SubCategory Updated Successfully');
								redirect('admin_master/subcategory_list', 'refresh');
							}

					}else{
						/*$config =  array(
		                  'upload_path'     => "./uploads/",
		                  'allowed_types'   => "gif|jpg|png|jpeg",
		                );
		                 $this->load->library('upload', $config);
						 $this->upload->initialize($config);*/
						$subcat_data = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_subcategories',array('exercise_mode_subcategory_id'=>$id));
						$imagename= "";
						if(($_FILES["userfile"]['error']==0))
						{
							if(isset($subcat_data[0]['image']) && !empty($subcat_data[0]['image']) && file_exists(FILE_UPLOAD.$path.$subcat_data[0]['image']))
							{
								unlink(FILE_UPLOAD.$subcat_data[0]['image']);
							}
							$upload_file=$_FILES["userfile"]["name"];				 
							@move_uploaded_file($_FILES["userfile"]["tmp_name"],FILE_UPLOAD.$upload_file);
							$imagename=$upload_file;
						}
						$data = array(
							"category_id"=>$category,
							"difficulty_level_id"=>$level,
							"image"=>$imagename
						);
		               	foreach($source_lang as $langkey){
							$name = $this->input->post('subcate_name_'.$langkey['language_code']);
							$data["subcategory_name_in_".$langkey['language_code']] = $name;
						}
						$data['support_lang_id'] = $this->session->userdata('support_lang_id');
						$insert = $this->admin_model->update_subcategory($data,$id);
						$delete = $this->admin_model->master_function_for_delete_by_conditions('tbl_exercise_mode_categories_exercise',array('support_lang_id'=>$data['support_lang_id'],'category_id'=>$id));
						foreach($types as $key)
						{
					 		$data = array(
								"exercise_type_id"=>$key,
								"category_id"=>$id,
							);
							$data['support_lang_id'] = $this->session->userdata('support_lang_id');	
					 		$insert_exercise = $this->admin_model->add_category_exercise($data);
						}
						if($insert){
							$this->session->set_flashdata('sucess_msg','SubCategory Updated Successfully');
							redirect('admin_master/subcategory_list', 'refresh');
						}
	                }
				}else{
			       	$data['edit_data'] = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_subcategories',array('exercise_mode_subcategory_id'=>$id));
					$mode = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_categories',array('exercise_mode_category_id'=>$data['edit_data'][0]['category_id']));
					$data['selected_mode']=$mode[0]['exercise_mode_id'];
					$data['category']=$this->admin_model->get_category_list($data['selected_mode']);
					// $data['source_lang']=$this->admin_model->get_source_lang();		// New Target change
					$target_lang_id = $this->session->userdata('support_lang_id');
					$data['source_lang']= $this->admin_model->get_source_lang_by_target($target_lang_id);
					$data['get_selected_type'] = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_categories_exercise',array('category_id'=>$id));
					$data['exercise_type']=$this->admin_model->get_exercise_type();
					$data['exercise_mode']=$this->admin_model->get_exercise_mode();
					$admin_data = $this->session->userdata('logged_in');
					$admin_language = $admin_data[0]['support_lang_ids'];
					$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
					$slang_name = $this->session->userdata('support_lang_name');
					$data['current_support_lang'] = $slang_name;
					$data['active_class']="subcategory";
					// echo "<pre>"; print_r($data); die;
					$this->load->view('admin/header',$data);
					$this->load->view('admin/edit_subcategory',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');
				}
	     	}else{
				redirect('refresh');
			}
		}else{

			redirect('admin_master/login', 'refresh');
		}
	}
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Vocabulary words List
   	*************************************
    */
	public function words_list(){

		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
					 if($this->input->post()){
 								$this->session->set_userdata('modeid',$this->input->post('mode_id'));
 								$this->session->set_userdata('cateid',$this->input->post('cate_id'));
 								$this->session->set_userdata('subcateid',$this->input->post('subcate_id'));
 								$this->session->set_userdata('sort',$this->input->post('sort'));
 								$this->session->set_userdata('per_page',$this->input->post('per_page'));
 								$this->session->set_userdata('search',$this->input->post('search_text'));
					 }

					$mode =  $this->session->userdata('modeid');
					$data['mode']=$mode; 
					$category = $this->session->userdata('cateid');
					$data['category_select']=$category;
					$subcategory = $this->session->userdata('subcateid');
					$data['subcategory_select']=$subcategory;
					$sort = $this->session->userdata('sort');
					$data['sort_select']=$sort;
					$per_page = $this->session->userdata('per_page');
					$data['per_page_select']=$per_page;
					$search = $this->session->userdata('search');
					$data['search']=$search; 
					if(!isset($per_page) || $per_page == ""){
						$per_page=100;
					}
					$config = array();			       
			        $res = $this->admin_model->get_words_list($mode,$category,$subcategory,$sort,$search);

			        $config["total_rows"] = count($res);
			        $config["per_page"] = $per_page;			     
        			$config["uri_segment"] = 3;
        			$config["base_url"] = base_url() . "admin_master/words_list";
        			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		        	$config['full_tag_open'] = "<ul class='pagination pagination-small pagination-centered'>";
					$config['full_tag_close'] ="</ul>";
					$config['num_tag_open'] = '<li>';
					$config['num_tag_close'] = '</li>';
					$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
					$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
					$config['next_tag_open'] = "<li>";
					$config['next_tagl_close'] = "</li>";
					$config['prev_tag_open'] = "<li>";
					$config['prev_tagl_close'] = "</li>";
					$config['first_tag_open'] = "<li>";
					$config['first_tagl_close'] = "</li>";
					$config['last_tag_open'] = "<li>";
					$config['last_tagl_close'] = "</li>";
			        $this->pagination->initialize($config);
			        $data['words_list'] = $this->admin_model->get_words_list_pagination($config["per_page"], $page,$mode,$category,$subcategory,$sort,$search);
			        $data["links"] = $this->pagination->create_links();		
                    $data["page_info"] =  "Showing ".($config["per_page"])." of ".$config["total_rows"]." total results";
					//end pagination
					$data['success_msg']=$this->session->flashdata('sucess_msg');
					$data['error_msg']=$this->session->flashdata('error_msg');
					$data['category']=$this->admin_model->get_category_list(1);
			        $data['subcategory'] = $this->admin_model->get_subcategory_list($category);
			        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
					// $data['source_lang']=$this->admin_model->get_source_lang();		// New Target change
					$target_lang_id = $this->session->userdata('support_lang_id');
					$data['source_lang']= $this->admin_model->get_source_lang_by_target($target_lang_id);
			        $admin_data = $this->session->userdata('logged_in');
					$admin_language = $admin_data[0]['support_lang_ids'];
					$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
			        $slang_name = $this->session->userdata('support_lang_name');
					$data['current_support_lang'] = $slang_name;
					$data['active_class']="word";
					$this->load->view('admin/header',$data);
					$this->load->view('admin/words_list',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');
					$this->session->unset_userdata('search');
		}else{

			redirect('admin_master/login', 'refresh');

		}
	}
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for add Vocabulary words
   	*************************************
    */
	public function add_words(){

		if($this->session->userdata('logged_in')){
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					// $source_lang=$this->admin_model->get_source_lang();		// New Target change
					$target_lang_id = $this->session->userdata('support_lang_id');
					$source_lang= $this->admin_model->get_source_lang_by_target($target_lang_id);
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
					$this->form_validation->set_rules('audio_name','Audio Name','required');
					foreach($source_lang as $key){
						$this->form_validation->set_rules('word_'.$key['language_code'],'Word Name in '.$key['language_name'],'required');
					}
					$this->form_validation->set_rules('mode','Mode','required');
					if($this->form_validation->run() == FALSE){
						        $data['category']=$this->admin_model->get_category_list();
						        $data['subcategory'] = $this->admin_model->get_subcategory_list();
						        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
						        $data['success_msg']=$this->session->flashdata('insert_cat');
						        $data['error_msg']=$this->session->flashdata('error_upload');
						        $data['active_class']="word";
								// $data['source_lang'] = $this->admin_model->get_source_lang();		// New Target change
								$data['source_lang'] = $source_lang;
								$admin_data = $this->session->userdata('logged_in');
								$admin_language = $admin_data[0]['support_lang_ids'];
								$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
								$slang_name = $this->session->userdata('support_lang_name');
								$data['current_support_lang'] = $slang_name;
								$this->load->view('admin/header',$data);
								$this->load->view('admin/add_words',$data);
								$this->load->view('admin/side_menu',$data);
								$this->load->view('admin/footer');
							
				     }else{
								$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								$mode = $this->input->post('mode');
								$is_audio = $this->input->post('is_audio');
								$is_image = $this->input->post('is_image');
								$audio_name = $this->input->post('audio_name');
								$image = "";
								if(!isset($is_audio)){
										$is_audio=0;
								}else{
									$is_audio=1;
								}
								if(!isset($is_image)){
										$is_image=0;
								}else{
									$is_image=1;
								}
								/*$config =  array(
				                  'upload_path'     => "./uploads/words/$category/$subcategoty/",
				                  'allowed_types'   => "gif|jpg|png|jpeg",
				            
				                );
								$this->load->library('upload', $config);
								$this->upload->initialize($config);*/
								$path = 'words'.DIRECTORY_SEPARATOR.$category.DIRECTORY_SEPARATOR.$subcategoty.DIRECTORY_SEPARATOR.'';
								if(($_FILES["userfile"]['error']==0))
								{
									  $upload_file=$_FILES["userfile"]["name"];
									 
									  @move_uploaded_file($_FILES["userfile"]["tmp_name"],FILE_UPLOAD.$path.$upload_file);
									  $image=$upload_file;
								}
								/*if($this->upload->do_upload('userfile')){
								 	$upload_data = $this->upload->data();
								 	$image= $upload_data['file_name'];
								}*/
										$data = array(
											"category_id"=>$category,
											"subcategory_id"=>$subcategoty,
											"exercise_mode_id"=>$mode,
											"image_file"=>$image,
											"audio_file"=>$audio_name,
											"is_image_available"=>$is_audio,
											"is_audio_available"=>$is_image
										);
									foreach($source_lang as $langkey){
										$name = $this->input->post('word_'.$langkey['language_code']);
										$data[$langkey['field_name']] = $name;
									}
								$data['support_lang_id'] = $this->session->userdata('support_lang_id');
									
								$insert = $this->admin_model->add_words($data);
								if($insert){
									$this->session->set_flashdata('sucess_msg','Word Inserted Successfully');
									redirect('admin_master/words_list', 'refresh');
								}
						}
		}else{

				redirect('admin_master/login', 'refresh');
		}
		
	}
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for delete Vocabulary words
   	*************************************
    */
	public function delete_word(){
				$id = $this->uri->segment('3');
				$data = array('is_active'=>'0');
				if(!empty($id) && $id > 0)
				{
					$delete = $this->admin_model->delete_word($data,$id);
					if($delete){
						$this->session->set_flashdata('sucess_msg','Word Deleted Successfully');
						//redirect('admin_master/words_list', 'refresh');	
						header('Location: '.$_SERVER['HTTP_REFERER']);
					}
				}else{
					$this->session->set_flashdata('error_msg','Word not deleted, Invalid word ID');
					//redirect('admin_master/words_list', 'refresh');	
					header('Location: '.$_SERVER['HTTP_REFERER']);
				}
	}

	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for edit Vocabulary words
   	*************************************
    */

	public function edit_words($id){

			if($this->session->userdata('logged_in'))
			{
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					// $source_lang=$this->admin_model->get_source_lang();		// New Target change
					$target_lang_id = $this->session->userdata('support_lang_id');
					$source_lang= $this->admin_model->get_source_lang_by_target($target_lang_id);
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
						foreach($source_lang as $key){
							$this->form_validation->set_rules('word_'.$key['language_code'],'Word Name in '.$key['language_name'],'required');
						}
					$this->form_validation->set_rules('mode','Mode','required');
					if($this->form_validation->run() == FALSE)
					{
						    $data['edit_data'] = $this->admin_model->get_words_from_id($id);
							$data['category']=$this->admin_model->get_category_list();
					       	$data['subcategory'] = $this->admin_model->get_subcategory_list();
					        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
	  						// $data['source_lang']=$this->admin_model->get_source_lang();		// New Target change
							$target_lang_id = $this->session->userdata('support_lang_id');
							$data['source_lang']= $this->admin_model->get_source_lang_by_target($target_lang_id);
	  						$admin_data = $this->session->userdata('logged_in');
							$admin_language = $admin_data[0]['support_lang_ids'];
							$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
	  						$slang_name = $this->session->userdata('support_lang_name');
							$data['current_support_lang'] = $slang_name;
							$data['active_class']="word";
							$this->load->view('admin/header',$data);
							$this->load->view('admin/edit_words',$data);
							$this->load->view('admin/side_menu',$data);
							$this->load->view('admin/footer');
						
					}else{
				    		$category = $this->input->post('category');
							$subcategoty = $this->input->post('subcategory');
							$mode = $this->input->post('mode');
							$arabic = $this->input->post('arabic');
							$is_audio = $this->input->post('is_audio');
							$is_image = $this->input->post('is_image');								
							if(!isset($is_audio)){
									$is_audio=0;
							}else{
								$is_audio=1;
							}
							if(!isset($is_image)){
									$is_image=0;
							}else{
								$is_image=1;
							}
							if(empty($_FILES['userfile']['name'])){
								
								$data = array(
											"category_id"=>$category,
											"subcategory_id"=>$subcategoty,
											"exercise_mode_id"=>$mode,
											"is_image_available"=>$is_image,
											"is_audio_available"=>$is_audio,
											"audio_file"=>$this->input->post('audio_name')
										);

								if($this->input->post('is_image_delete') =="0"){

								 		$data['image_file']="";
								} 	
								foreach($source_lang as $langkey){

										$name = $this->input->post('word_'.$langkey['language_code']);
										$data[$langkey['field_name']] = $name;
								}
								$data['support_lang_id'] = $this->session->userdata('support_lang_id');
								$insert = $this->admin_model->update_word($data,$id);
								if($insert){
									$this->session->set_flashdata('sucess_msg','Word Updated Successfully');
									redirect('admin_master/words_list', 'refresh');
								}

							}else{
									/*$config =  array(
					                  'upload_path'     => "./uploads/words/".$category.'/'.$subcategoty.'/',
					                  'allowed_types'   => "gif|jpg|png|jpeg", 
					                );
						            $this->load->library('upload', $config);
									$this->upload->initialize($config);
									if (!$this->upload->do_upload('userfile'))
					                {
				                        $error = array('error' => $this->upload->display_errors());
				                        $this->session->set_flashdata('error_upload', $error['error']);
				                        redirect('admin_master/words_list', 'refresh');
					                }else{*/
					                	$path = 'words'.DIRECTORY_SEPARATOR.$category.DIRECTORY_SEPARATOR.$subcategoty.DIRECTORY_SEPARATOR.'';
					                	$image = "";
					                	$word_data = $this->admin_model->get_words_from_id($id);
										if(($_FILES["userfile"]['error']==0))
										{
											if(isset($word_data[0]['image_file']) && !empty($word_data[0]['image_file']) && file_exists(FILE_UPLOAD.$path.$word_data[0]['image_file']))
											{
												unlink(FILE_UPLOAD.$path.$word_data[0]['image_file']);
											}
											$upload_file=$_FILES["userfile"]["name"];
											@move_uploaded_file($_FILES["userfile"]["tmp_name"],FILE_UPLOAD.$path.$upload_file);
											$image=$upload_file;
										}
					                	$upload_data = $this->upload->data();
					                	$data = array(
					                	 		"category_id"=>$category,
												"subcategory_id"=>$subcategoty,
												"exercise_mode_id"=>$mode,
												"image_file"=>$image,
												"is_image_available"=>$is_image,
												"is_audio_available"=>$is_audio,
												"audio_file"=>$this->input->post('audio_name')
											);
								        foreach($source_lang as $langkey){
											$name = $this->input->post('word_'.$langkey['language_code']);
											$data[$langkey['field_name']] = $name;
										}
										$data['support_lang_id'] = $this->session->userdata('support_lang_id');
										$insert = $this->admin_model->update_word($data,$id);
										if($insert){
											$this->session->set_flashdata('sucess_msg','Word Updated Successfully');
											redirect('admin_master/words_list', 'refresh');
										}

					               /* }*/
							}	
					}

			}else{

					redirect('admin_master/login', 'refresh');
			}

	}
	



/* AURAL START */

	/*
	*************************************
    @@ Developer : Harsh Nebhwani 
   	@@ Description : Function for Vocabulary aural List
   	*************************************
    */
	public function aural_list(){

		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
					 if($this->input->post()){
 								$this->session->set_userdata('modeid',$this->input->post('mode_id'));
 								$this->session->set_userdata('cateid',$this->input->post('cate_id'));
 								$this->session->set_userdata('subcateid',$this->input->post('subcate_id'));
 								$this->session->set_userdata('sort',$this->input->post('sort'));
 								$this->session->set_userdata('per_page',$this->input->post('per_page'));
 								$this->session->set_userdata('search',$this->input->post('search_text'));
					 }

					$mode =  $this->session->userdata('modeid');
					$data['mode']=$mode; 
					$category = $this->session->userdata('cateid');
					$data['category_select']=$category;
					$subcategory = $this->session->userdata('subcateid');
					$data['subcategory_select']=$subcategory;
					$sort = $this->session->userdata('sort');
					$data['sort_select']=$sort;
					$per_page = $this->session->userdata('per_page');
					$data['per_page_select']=$per_page;
					$search = $this->session->userdata('search');
					$data['search']=$search; 
					if(!isset($per_page) || $per_page == ""){
						$per_page=100;
					}
					$config = array();			       
			        $res = $this->admin_model->get_aural_list($mode,$category,$subcategory,$sort,$search);

			        $config["total_rows"] = count($res);
			        $config["per_page"] = $per_page;			     
        			$config["uri_segment"] = 3;
        			$config["base_url"] = base_url() . "admin_master/aural_list";
        			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		        	$config['full_tag_open'] = "<ul class='pagination pagination-small pagination-centered'>";
					$config['full_tag_close'] ="</ul>";
					$config['num_tag_open'] = '<li>';
					$config['num_tag_close'] = '</li>';
					$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
					$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
					$config['next_tag_open'] = "<li>";
					$config['next_tagl_close'] = "</li>";
					$config['prev_tag_open'] = "<li>";
					$config['prev_tagl_close'] = "</li>";
					$config['first_tag_open'] = "<li>";
					$config['first_tagl_close'] = "</li>";
					$config['last_tag_open'] = "<li>";
					$config['last_tagl_close'] = "</li>";
			        $this->pagination->initialize($config);
			        $data['aural_list'] = $this->admin_model->get_aural_list_pagination($config["per_page"], $page,$mode,$category,$subcategory,$sort,$search);
			        $data["links"] = $this->pagination->create_links();		
                    $data["page_info"] =  "Showing ".($config["per_page"])." of ".$config["total_rows"]." total results";
					//end pagination
					$data['success_msg']=$this->session->flashdata('sucess_msg');
					$data['error_msg']=$this->session->flashdata('error_msg');
					$data['category']=$this->admin_model->get_category_list(6);
			        $data['subcategory'] = $this->admin_model->get_subcategory_list($category);
			        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
					// $data['source_lang']=$this->admin_model->get_source_lang();		// New Target change
					$target_lang_id = $this->session->userdata('support_lang_id');
					$data['source_lang']= $this->admin_model->get_source_lang_by_target($target_lang_id);
			        $admin_data = $this->session->userdata('logged_in');
					$admin_language = $admin_data[0]['support_lang_ids'];
					$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
			        $slang_name = $this->session->userdata('support_lang_name');
					$data['current_support_lang'] = $slang_name;
					$data['active_class']="aural";
					$this->load->view('admin/header',$data);
					$this->load->view('admin/aural_list',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');
					$this->session->unset_userdata('search');
		}else{

			redirect('admin_master/login', 'refresh');

		}
	}
	/*
	*************************************
    @@ Developer : Harsh Nebhwani 
   	@@ Description : Function for add Vocabulary aural
   	*************************************
    */
	public function add_aural(){

		if($this->session->userdata('logged_in')){
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					// $source_lang=$this->admin_model->get_source_lang();		// New Target change
					$target_lang_id = $this->session->userdata('support_lang_id');
					$source_lang= $this->admin_model->get_source_lang_by_target($target_lang_id);
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
					$this->form_validation->set_rules('audio_name','Audio Name','required');
					foreach($source_lang as $key){
						$this->form_validation->set_rules('word_'.$key['language_code'],'Aural Name in '.$key['language_name'],'required');
					}
					$this->form_validation->set_rules('mode','Mode','required');
					if($this->form_validation->run() == FALSE){
						        $data['category']=$this->admin_model->get_category_list();
						        $data['subcategory'] = $this->admin_model->get_subcategory_list();
						        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
						        $data['success_msg']=$this->session->flashdata('insert_cat');
						        $data['error_msg']=$this->session->flashdata('error_upload');
						        $data['active_class']="aural";
								// $data['source_lang'] = $this->admin_model->get_source_lang();		// New Target change
								$data['source_lang'] = $source_lang;
								$admin_data = $this->session->userdata('logged_in');
								$admin_language = $admin_data[0]['support_lang_ids'];
								$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
								$slang_name = $this->session->userdata('support_lang_name');
								$data['current_support_lang'] = $slang_name;
								$this->load->view('admin/header',$data);
								$this->load->view('admin/add_aural',$data);
								$this->load->view('admin/side_menu',$data);
								$this->load->view('admin/footer');
							
				     }else{
								$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								$mode = $this->input->post('mode');
								$is_audio = $this->input->post('is_audio');
								// $is_image = $this->input->post('is_image');
								$audio_name = $this->input->post('audio_name');
								$image = "";
								if(!isset($is_audio)){
										$is_audio=0;
								}else{
									$is_audio=1;
								}
								// if(!isset($is_image)){
								// 		$is_image=0;
								// }else{
								// 	$is_image=1;
								// }
								/*$config =  array(
				                  'upload_path'     => "./uploads/aural/$category/$subcategoty/",
				                  'allowed_types'   => "gif|jpg|png|jpeg",
				            
				                );
								$this->load->library('upload', $config);
								$this->upload->initialize($config);*/
								// $path = 'words'.DIRECTORY_SEPARATOR.$category.DIRECTORY_SEPARATOR.$subcategoty.DIRECTORY_SEPARATOR.'';
								// if(($_FILES["userfile"]['error']==0))
								// {
								// 	  $upload_file=$_FILES["userfile"]["name"];
									 
								// 	  @move_uploaded_file($_FILES["userfile"]["tmp_name"],FILE_UPLOAD.$path.$upload_file);
								// 	  $image=$upload_file;
								// }
								/*if($this->upload->do_upload('userfile')){
								 	$upload_data = $this->upload->data();
								 	$image= $upload_data['file_name'];
								}*/
										$data = array(
											"category_id"=>$category,
											"subcategory_id"=>$subcategoty,
											"exercise_mode_id"=>$mode,
											// "image_file"=>$image,
											"audio_file"=>$audio_name,
											// "is_image_available"=>$is_audio,
											"is_audio_available"=>$is_image
										);
									foreach($source_lang as $langkey){
										$name = $this->input->post('word_'.$langkey['language_code']);
										$data[$langkey['field_name']] = $name;
									}
								$data['support_lang_id'] = $this->session->userdata('support_lang_id');
									
								$insert = $this->admin_model->add_aural($data);
								if($insert){
									$this->session->set_flashdata('sucess_msg','Aural Inserted Successfully');
									redirect('admin_master/aural_list', 'refresh');
								}
						}
		}else{

				redirect('admin_master/login', 'refresh');
		}
		
	}

	/*
	*************************************
    @@ Developer : Harsh Nebhwani 
   	@@ Description : Function for edit Vocabulary aural
   	*************************************
    */

	public function edit_aural($id){

			if($this->session->userdata('logged_in'))
			{
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					// $source_lang=$this->admin_model->get_source_lang();		// New Target change
					$target_lang_id = $this->session->userdata('support_lang_id');
					$source_lang= $this->admin_model->get_source_lang_by_target($target_lang_id);
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
						foreach($source_lang as $key){
							$this->form_validation->set_rules('word_'.$key['language_code'],'Aural Name in '.$key['language_name'],'required');
						}
					$this->form_validation->set_rules('mode','Mode','required');
					if($this->form_validation->run() == FALSE)
					{
						    $data['edit_data'] = $this->admin_model->get_aural_from_id($id);
							$data['category']=$this->admin_model->get_category_list();
					       	$data['subcategory'] = $this->admin_model->get_subcategory_list();
					        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
	  						// $data['source_lang']=$this->admin_model->get_source_lang();		// New Target change
							$target_lang_id = $this->session->userdata('support_lang_id');
							$data['source_lang']= $this->admin_model->get_source_lang_by_target($target_lang_id);
	  						$admin_data = $this->session->userdata('logged_in');
							$admin_language = $admin_data[0]['support_lang_ids'];
							$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
	  						$slang_name = $this->session->userdata('support_lang_name');
							$data['current_support_lang'] = $slang_name;
							$data['active_class']="aural";
							$this->load->view('admin/header',$data);
							$this->load->view('admin/edit_aural',$data);
							$this->load->view('admin/side_menu',$data);
							$this->load->view('admin/footer');
						
					}else{
				    		$category = $this->input->post('category');
							$subcategoty = $this->input->post('subcategory');
							$mode = $this->input->post('mode');
							$arabic = $this->input->post('arabic');
							$is_audio = $this->input->post('is_audio');
							// $is_image = $this->input->post('is_image');								
							if(!isset($is_audio)){
									$is_audio=0;
							}else{
								$is_audio=1;
							}
							// if(!isset($is_image)){
							// 		$is_image=0;
							// }else{
							// 	$is_image=1;
							// }
							if(empty($_FILES['userfile']['name'])){
								
								$data = array(
											"category_id"=>$category,
											"subcategory_id"=>$subcategoty,
											"exercise_mode_id"=>$mode,
											// "is_image_available"=>$is_image,
											"is_audio_available"=>$is_audio,
											"audio_file"=>$this->input->post('audio_name')
										);

								// if($this->input->post('is_image_delete') =="0"){

								//  		$data['image_file']="";
								// } 	
								foreach($source_lang as $langkey){
									$name = $this->input->post('word_'.$langkey['language_code']);
									$data[$langkey['field_name']] = $name;
								}
								$data['support_lang_id'] = $this->session->userdata('support_lang_id');
								$insert = $this->admin_model->update_aural($data,$id);
								if($insert){
									$this->session->set_flashdata('sucess_msg','Aural Updated Successfully');
									redirect('admin_master/aural_list', 'refresh');
								}

							}else{
									/*$config =  array(
					                  'upload_path'     => "./uploads/aural/".$category.'/'.$subcategoty.'/',
					                  'allowed_types'   => "gif|jpg|png|jpeg", 
					                );
						            $this->load->library('upload', $config);
									$this->upload->initialize($config);
									if (!$this->upload->do_upload('userfile'))
					                {
				                        $error = array('error' => $this->upload->display_errors());
				                        $this->session->set_flashdata('error_upload', $error['error']);
				                        redirect('admin_master/aural_list', 'refresh');
					                }else{*/
					     //            	$path = 'words'.DIRECTORY_SEPARATOR.$category.DIRECTORY_SEPARATOR.$subcategoty.DIRECTORY_SEPARATOR.'';
					     //            	$image = "";
										// if(($_FILES["userfile"]['error']==0))
										// {
										// 	  $upload_file=$_FILES["userfile"]["name"];
											 
										// 	  @move_uploaded_file($_FILES["userfile"]["tmp_name"],FILE_UPLOAD.$path.$upload_file);
										// 	  $image=$upload_file;
										// }
					     //            	$upload_data = $this->upload->data();
					     //            	$data = array(
					     //            	 		"category_id"=>$category,
										// 		"subcategory_id"=>$subcategoty,
										// 		"exercise_mode_id"=>$mode,
										// 		"image_file"=>$image,
										// 		"is_image_available"=>$is_image,
										// 		"is_audio_available"=>$is_audio,
										// 		"audio_file"=>$this->input->post('audio_name')
										// 	);
								  //       foreach($source_lang as $langkey){
										// 	$name = $this->input->post('word_'.$langkey['language_code']);
										// 	$data[$langkey['field_name']] = $name;
										// }
										// $data['support_lang_id'] = $this->session->userdata('support_lang_id');
										// $insert = $this->admin_model->update_word($data,$id);
										// if($insert){
										// 	$this->session->set_flashdata('sucess_msg','Aural Updated Successfully');
										// 	redirect('admin_master/aural_list', 'refresh');
										// }

					               /* }*/
							}	
					}

			}else{

					redirect('admin_master/login', 'refresh');
			}

	}

	/*
	*************************************
    @@ Developer : Harsh Nebhwani 
   	@@ Description : Function for delete Vocabulary aural
   	*************************************
    */
	public function delete_aural(){
		$id = $this->uri->segment('3');
		$data = array('is_active'=>'0');
		if(!empty($id) && $id > 0)
		{
			$delete = $this->admin_model->delete_aural($data,$id);
			if($delete){
				$this->session->set_flashdata('sucess_msg','Aural Deleted Successfully');
				header('Location: '.$_SERVER['HTTP_REFERER']);
			}
		}else{
				$this->session->set_flashdata('error_msg','Aural not deleted, Invalid Aural ID');
				//redirect('admin_master/words_list', 'refresh');	
				header('Location: '.$_SERVER['HTTP_REFERER']);
			}
	}

	/*
	*************************************
    @@ Developer : Harsh Nebhwani 
   	@@ Description : Function for Delete all aural Words
   	*************************************
    */
	function delete_all_aurals()
	{
		$ids = $this->input->post('delete');
		$submit = $this->input->post('submit');
		if(empty($ids)){
			$this->session->set_flashdata('error_msg','Please select at least one');
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}

		if($submit=="Delete Selected")
		{
			if(empty($ids)){
				$this->session->set_flashdata('error_msg','Please select at least one');
				redirect('admin_master/aural_list', 'refresh');	
			}else{
				foreach ($ids as $key) {
					if(!empty($key) && $key > 0)
					{
						$data = array('is_active'=>'0');
						$delete = $this->admin_model->delete_aural($data,$key);		
					}
				}
				if($delete){
					$this->session->set_flashdata('sucess_msg','Aural Composition Deleted Successfully');
					header('Location: '.$_SERVER['HTTP_REFERER']);
				}
			}
		}

		if($submit=="Delete Images")
		{
			foreach ($ids as $key)
			{
				$res = $this->admin_model->master_function_get_data_by_condition('tbl_aural_composition',array('aural_id'=>$key));
				if(count($res)>0){
					$image=$res[0]['image_file'];
					$category_id=$res[0]['category_id'];
					$subcategory_id=$res[0]['subcategory_id'];

					$root_path  = $this->config->item('root_path');   
					$file = $root_path.'uploads/words/'.$category_id.'/'.$subcategory_id.'/'.$image;
					if($image != "")
						unlink($file);
				}
				$update = $this->admin_model->master_function_for_update_by_conditions('tbl_aural_composition',array('aural_id'=>$key),array("image_file"=>""));
			}
			$this->session->set_flashdata('sucess_msg','Aural Composition Audio Deleted Successfully');
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}

		if($submit=="Delete Audios")
		{
			foreach ($ids as $key)
			{
				$res = $this->admin_model->master_function_get_data_by_condition('tbl_aural_composition',array('aural_id'=>$key));
				if(count($res)>0){
					$image=$res[0]['audio_file'];
					$category_id=$res[0]['category_id'];
					$subcategory_id=$res[0]['subcategory_id'];

					$root_path  = $this->config->item('root_path');   
					$file = $root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id.'/'.$image;
					if($image != "")
						unlink($file);
				}
				$update = $this->admin_model->master_function_for_update_by_conditions('tbl_aural_composition',array('aural_id'=>$key),array("audio_file"=>"","is_audio_available"=>'0'));
			}
			$this->session->set_flashdata('sucess_msg','Aural Composition Audio Deleted Successfully');
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}
	}

    /*
    *************************************
    @@ Developer : Harsh Nebhwani 
   	@@ Description : Function for Vocabulary words Import
   	*************************************
    */
    public function aural_import()
    {
		$path = FCPATH.'uploads/excel/';
		$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;
		$this->load->library('excel');//load PHPExcel library 
        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
        $configUpload['allowed_types'] = 'xls|xlsx|csv';
        $configUpload['max_size'] = '5000';
        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
        $file_name = $_FILES["file"]["name"]; //uploded file name
		$extension=$upload_data['file_ext'];    // uploded file extension
		//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
		$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
          //Set to read only
        $objReader->setReadDataOnly(true); 		  
        //Load excel file
		PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
		$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);  
        // if(($objWorksheet->getCellByColumnAndRow(0,1))!="image_name" && ($objWorksheet->getCellByColumnAndRow(0,2))!=" "){
	        	// $this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference ');	 
	            // redirect('admin_master/aural_list', 'refresh');
		// }      
          //loop from first data untill last data
        for($i=2;$i<=$totalrows;$i++)
        {
        	$trgt_lang_col = $objWorksheet->getCellByColumnAndRow(1,$i);
        	if(isset($trgt_lang_col) && !empty(trim($trgt_lang_col)) && strlen($trgt_lang_col) > 0)
        	{
        		$target_lang_id = $this->session->userdata('support_lang_id');
				$getLang = $this->admin_model->master_function_get_data_by_condition("tbl_source_language",array("source_language_id"=>$target_lang_id,"status"=>"1"));
				$category = $this->input->post('category');
				$subcategoty = $this->input->post('subcategory');
				$mode = $this->input->post('mode');
				$audio_name = strtolower(str_replace(" ", "_", $objWorksheet->getCellByColumnAndRow(0,$i)));
				$is_audio_available = !empty($audio_name)?1:0;
				$data = array(
								"category_id"=>$category,
								"subcategory_id"=>$subcategoty,
								"exercise_mode_id"=>$mode,
								// "image_file"=>strtolower(str_replace(" ", "_", $objWorksheet->getCellByColumnAndRow(0,$i))),
								"audio_file"=>$audio_name,
								// "is_image_available"=>$objWorksheet->getCellByColumnAndRow(3,$i),
								"is_audio_available"=> $is_audio_available
						);
				$source_lang=$this->admin_model->get_source_lang_by_target($target_lang_id);
				$ctn=0;
				foreach($source_lang as $langkey)
				{
					if($ctn==0){
						$j=1;

					}else{

						$j=$j+1;
					}
					$name=$objWorksheet->getCellByColumnAndRow($j,$i);
					$data[$langkey['field_name']] = $name;
					$ctn++;
				}
				$res = $this->admin_model->master_function_get_data_by_condition("tbl_aural_composition",array($getLang[0]['field_name'] => $objWorksheet->getCellByColumnAndRow(1,$i), "category_id"=>$category, "subcategory_id"=>$subcategoty, "is_active"=>"1"));
				if(count($res) > 0){
					$data['support_lang_id'] = $this->session->userdata('support_lang_id');
					$update = $this->admin_model->master_function_for_update_by_conditions("tbl_aural_composition",array($getLang[0]['field_name'] => $objWorksheet->getCellByColumnAndRow(1,$i)),$data);
				}else{
					$data['is_active'] = 1;
					$data['support_lang_id'] = $this->session->userdata('support_lang_id');	
					$insert = $this->admin_model->add_aural($data);
				}
			}
        }
        unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
        $this->session->set_flashdata('sucess_msg','Aural imported Successfully');	 
        redirect('admin_master/aural_list', 'refresh');
    }

    /*
	************************************
    @@ Developer : Harsh Nebhwani 
   	@@ Description : This Function is for export aural words
   	*************************************
    */
    public function excel_export_aural()
    {			

    			$newarray_code = array();
                $newarray_name = array();
                $res = $this->db->query("select language_code,language_name,field_name from tbl_source_language where isinput='1' OR status ='1' ")->result_array();
		               foreach ($res as $key) {
		              		$newarray[]=$key['field_name'];
		              		$newarray_name[] = $key['language_name'];
		               }
				$this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('vocabulary');
                //set cell A1 content with some text 
                // $this->excel->getActiveSheet()->setCellValue('A1', 'Image name');
                $this->excel->getActiveSheet()->setCellValue('A1', 'Audio name');
                $this->excel->getActiveSheet()->setCellValue('B1', 'Is audio available');
                // $this->excel->getActiveSheet()->setCellValue('D1', 'Is image avalible');
                $this->excel->getActiveSheet()->setCellValue('C1', 'English');
                $this->excel->getActiveSheet()->setCellValue('D1', 'Finnish');
                $this->excel->getActiveSheet()->setCellValue('E1', 'Swedish');
                $this->excel->getActiveSheet()->setCellValue('F1', 'Spanish');
                $this->excel->getActiveSheet()->setCellValue('G1', 'Norwegian');
                $this->excel->getActiveSheet()->setCellValue('H1', 'Scots');
                $this->excel->getActiveSheet()->setCellValue('I1', 'Galiec');
                $this->excel->getActiveSheet()->setCellValue('J1', 'Northern Saami');
                $this->excel->getActiveSheet()->setCellValue('K1', 'Cornish');
                $this->excel->getActiveSheet()->setCellValue('L1', 'Galician');
                $this->excel->getActiveSheet()->setCellValue('M1', 'Basque');
                //merge cell A1 until C1
                $this->excel->getActiveSheet()->getStyle('A1:AD1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1:AD1')->getFont()->setBold(true);
          
		       for($col = ord('A'); $col <= ord('C'); $col++){
		                $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
		                $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		        }
		        $cid = $this->uri->segment('3');
		        $scid = $this->uri->segment('4');
		        $all_fields = implode(',', $newarray); 
		        $support_lang_id = $this->session->userdata('support_lang_id');

		        $query="SELECT audio_file,is_audio_available,$all_fields from tbl_aural_composition WHERE is_active='1' AND support_lang_id=".$support_lang_id."";
             
               $filename='Aural_Composition'; 
               if($cid!=""){

		               	$query .= " AND category_id='$cid'";
						$sql = "SELECT category_name_in_en FROM tbl_exercise_mode_categories WHERE exercise_mode_category_id='$cid'";
		           		$rs1 = $this->db->query($sql)->row();
		           		$cat =$rs1->category_name_in_en;
		           		$cat = str_replace(" ","-",$cat);
		           		$filename .= "_$cat";
               } 
               if($scid!=""){

               		 $query .= " AND subcategory_id='$scid'";
               		 $sql = "SELECT subcategory_name_in_en FROM tbl_exercise_mode_subcategories WHERE exercise_mode_subcategory_id='$scid'";
               		  $rs1 = $this->db->query($sql)->row();
               		  $subcat =$rs1->subcategory_name_in_en;
               		  $subcat = str_replace(" ","-",$subcat);
               		  $filename .= "_$subcat";

               } 
                $rs = $this->db->query($query);
                $exceldata = array();
		        foreach ($rs->result_array() as $row){
		                $exceldata[] = $row;
		        }
                //Fill data                 
                $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');
                $filename .='.xls'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                $objWriter->save('php://output');
                 
    }

	 /*
	*************************************
    @@ Developer : Harsh Nebhwani 
   	@@ Description : Function for Download vocabulary words sample file
   	*************************************
    */
	public function download_aural_sample() {

			$this->load->helper('download');
			$target_lang_id = $this->session->userdata('support_lang_id');
			$data = file_get_contents("./assets/sample_xls_file/".$target_lang_id."/IL_Aural_content_sample.xlsx");
			$name = 'IL_Aural_content_sample.xlsx';
			force_download($name, $data);
	}


/* AURAL END */	

	/*
	*************************************
    @@ Developer : Nimesh Patel
   	@@ Description : Function for cataegory Import
   	*************************************
    */
	public function category_import()
	{
		$path = FCPATH.'uploads/excel/';
		$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;
		$this->load->library('excel');//load PHPExcel library 
        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
        $configUpload['allowed_types'] = 'xls|xlsx|csv';
        $configUpload['max_size'] = '5000';
        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
        $file_name = $_FILES["file"]["name"]; //uploded file name
		$extension=$upload_data['file_ext'];    // uploded file extension
		$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
          //Set to read only
        $objReader->setReadDataOnly(true); 		  
        //Load excel file
		PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
		$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);      
	    // if(($objWorksheet->getCellByColumnAndRow(0,1)) != "image_name"){
			// $this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference ');	 
			// redirect('admin_master/category_list', 'refresh');
	    // }	          
		//loop from first data untill last data
        for($i=2;$i<=$totalrows;$i++)
        {
        	$image_name_col = str_replace(" ", "_", $objWorksheet->getCellByColumnAndRow(0,$i));
        	$trgt_lang_col = $objWorksheet->getCellByColumnAndRow(1,$i);
        	if(isset($trgt_lang_col) && !empty(trim($trgt_lang_col)) && strlen($trgt_lang_col) > 0)
        	{
        		$target_lang_id = $this->session->userdata('support_lang_id');
				$getLang = $this->admin_model->master_function_get_data_by_condition("tbl_source_language",array("source_language_id"=>$target_lang_id,"status"=>"1"));
				$data = array(
							"exercise_mode_id"=>$this->input->post('exercise_mode'),
							"image"=>strtolower($image_name_col)
						);
				$source_lang=$this->admin_model->get_source_lang_by_target($target_lang_id);
				$ctn=0;
				foreach($source_lang as $langkey){
						if($ctn==0){
							$j=1;
						}else{
							$j=$j+1;
						}
						$name=$objWorksheet->getCellByColumnAndRow($j,$i);
						$data["category_name_in_".$langkey['language_code']] = $name;
						$ctn++;
				}
				$res = $this->admin_model->master_function_get_data_by_condition("tbl_exercise_mode_categories",array("category_name_in_".$getLang[0]['language_code']=>$objWorksheet->getCellByColumnAndRow(1,$i),"is_active"=>"1","is_delete"=>"0","exercise_mode_id"=>$this->input->post('exercise_mode')));
				if(count($res) >= 1){
					$data['support_lang_id'] = $this->session->userdata('support_lang_id');
					$update = $this->admin_model->master_function_for_update_by_conditions("tbl_exercise_mode_categories",array("category_name_in_".$getLang[0]['language_code']=>$objWorksheet->getCellByColumnAndRow(1,$i),"exercise_mode_id"=>$this->input->post('exercise_mode')),$data);
				}else{
					$data['support_lang_id'] = $this->session->userdata('support_lang_id');
					$insert = $this->admin_model->add_category($data);
					if (!file_exists('./uploads/words/'.$insert)) {
						mkdir('./uploads/words/'.$insert, 0777, true);
						$old = umask(0);
						chmod("./uploads/words/".$insert, 0777);
						umask($old);
					}
					if (!file_exists('./uploads/audio/'.$insert)) {
						mkdir('./uploads/audio/'.$insert, 0777, true);
						$old = umask(0);
						chmod("./uploads/audio/".$insert, 0777);
						umask($old);
					}
				}
        	}
        }
        unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
        $this->session->set_flashdata('sucess_msg','Category imported Successfully');	 
        redirect('admin_master/category_list/'.$this->input->post('exercise_mode'), 'refresh');
    }
    /*
    *************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for sub-cataegory Import
   	*************************************
    */
    public function subcategory_import()
    {
		$path = FCPATH.'uploads/excel/';
		$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;
		$this->load->library('excel');//load PHPExcel library 
        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
        $configUpload['allowed_types'] = 'xls|xlsx|csv';
        $configUpload['max_size'] = '5000';
        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
        $file_name = $_FILES["file"]["name"]; //uploded file name
		$extension=$upload_data['file_ext'];    // uploded file extension
		$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
          //Set to read only
        $objReader->setReadDataOnly(true); 		  
        //Load excel file
		PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
		$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0); 
	    // if(($objWorksheet->getCellByColumnAndRow(0,1))!="image_name"){
        	// $this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference ');	 
            // redirect('admin_master/subcategory_list', 'refresh');
    	// }
		//loop from first data untill last data
        for($i=2;$i<=$totalrows;$i++)
        {
        	$trgt_lang_col = $objWorksheet->getCellByColumnAndRow(3,$i);
        	if(isset($trgt_lang_col) && !empty(trim($trgt_lang_col)) && strlen($trgt_lang_col) > 0)
        	{
        		$target_lang_id = $this->session->userdata('support_lang_id');
				$getLang = $this->admin_model->master_function_get_data_by_condition("tbl_source_language",array("source_language_id"=>$target_lang_id,"status"=>"1"));
        		$data = array(
	        				"category_id"=>$this->input->post('category'),
							"image"=>strtolower(str_replace(" ", "_", $objWorksheet->getCellByColumnAndRow(0,$i))),
	        				"difficulty_level_id"=>$objWorksheet->getCellByColumnAndRow(2,$i)
        				);
				$source_lang=$this->admin_model->get_source_lang_by_target($target_lang_id);
				$ctn=0;
				foreach($source_lang as $langkey)
				{
					if($ctn==0){
						$j=3;
					}else{

						$j=$j+1;
					}
					$name=$objWorksheet->getCellByColumnAndRow($j,$i);
					$data["subcategory_name_in_".$langkey['language_code']] = $name;
					$ctn++;
				}

		        $res = $this->admin_model->master_function_get_data_by_condition("tbl_exercise_mode_subcategories",array("subcategory_name_in_".$getLang[0]['language_code']=>$objWorksheet->getCellByColumnAndRow(3,$i),"is_active"=>"1","is_delete"=>"0","category_id"=>$this->input->post('category')));
				if(count($res) > 0)
				{
					$data['support_lang_id'] = $this->session->userdata('support_lang_id');
					$subcateid = $res[0]['exercise_mode_subcategory_id'];
					$update = $this->admin_model->master_function_for_update_by_conditions("tbl_exercise_mode_subcategories",array("subcategory_name_in_".$getLang[0]['language_code']=>$objWorksheet->getCellByColumnAndRow(3,$i),"category_id"=>$this->input->post('category')),$data);
					
					// Delete Existing Data 
					$this->db->where('category_id', $subcateid);
					$delete = $this->db->delete('tbl_exercise_mode_categories_exercise');

					$types = $objWorksheet->getCellByColumnAndRow(1,$i);
						if($types=="" || $types==null || $types==" "){
							if($this->input->post('mode')=="4"){
										$types="10,11";
							}else if($this->input->post('mode')=="1"){
								$types="1,2,3,4,5,6,7,8,9,16,17,18";
							}
							else if($this->input->post('mode')=="2"){
								$types="13,14";
							}else if($this->input->post('mode')=="3"){
								$types="12";
							}else if($this->input->post('mode')=="5"){
								$types="15";
							}
							$types = explode(',', $types);
							foreach($types as $key){
						 		$data = array(
									"exercise_type_id"=>$key,
									"category_id"=>$subcateid,
								);
								$data['support_lang_id'] = $this->session->userdata('support_lang_id');	
					 			$insert_exercise = $this->admin_model->add_category_exercise($data);
							}
							
						}else{
							
							if($this->input->post('mode')=="4"){
										if($types=="1"){
											$types="10";
										}else{
											$types="11";
										}
										
							}else if($this->input->post('mode')=="2"){
								if($types=="1"){
											$types="13";
								}else{
											$types="14";
								}

							}else if($this->input->post('mode')=="3"){
								$types="12";
							}else if($this->input->post('mode')=="5"){
								$types="15";
							}
								$types = explode(',', $types);
								foreach($types as $key){
								 		$data = array(
											"exercise_type_id"=>$key,
											"category_id"=>$subcateid,
										);
									$data['support_lang_id'] = $this->session->userdata('support_lang_id');	
								 	$insert_exercise = $this->admin_model->add_category_exercise($data);
								}
						}

				}else{
					$data['support_lang_id'] = $this->session->userdata('support_lang_id');	
					$insert = $this->admin_model->add_subcategory($data);
					$catid = $this->input->post('category');
					if (!file_exists('./uploads/words/'.$catid.'/'.$insert)) {
							
							 mkdir('./uploads/words/'.$catid.'/'.$insert, 0777, true);
							 $old = umask(0);
						 chmod("./uploads/words/".$catid.'/'.$insert, 0777);
						 umask($old);
					}

					if (!file_exists('./uploads/audio/'.$catid.'/'.$insert)) {
							 mkdir('./uploads/audio/'.$catid.'/'.$insert, 0777, true);
							 $old = umask(0);
						 chmod("./uploads/audio/".$catid.'/'.$insert, 0777);
						 umask($old);
					}
					$path = getcwd().'/uploads/words/'.$catid.'/'.$insert.'/index.php';
					$this->makePathNotAccessible($path);
					$path1 = getcwd().'/uploads/audio/'.$catid.'/'.$insert.'/index.php';
					$this->makePathNotAccessible($path1);

					$types = $objWorksheet->getCellByColumnAndRow(1,$i);
					if($types=="" || $types==null || $types==" "){
						if($this->input->post('mode')=="4"){
									$types="10,11";
						}else if($this->input->post('mode')=="1"){
							$types="1,2,3,4,5,6,7,8,9,16,17,18";
						}
						else if($this->input->post('mode')=="2"){
							$types="13,14";
						}else if($this->input->post('mode')=="3"){
							$types="12";
						}else if($this->input->post('mode')=="5"){
							$types="15";
						}
						$types = explode(',', $types);
						foreach($types as $key){
					 		$data = array(
								"exercise_type_id"=>$key,
								"category_id"=>$insert,
							);
							$data['support_lang_id'] = $this->session->userdata('support_lang_id');	
				 			$insert_exercise = $this->admin_model->add_category_exercise($data);
						}
						
					}else{
						if($this->input->post('mode')=="4"){
								
									if($types=="1"){
										$types="10";
									}else{
										$types="11";
									}
									
						}else if($this->input->post('mode')=="2"){
							
							if($types=="1"){
										$types="13";
							}else{
										$types="14";
							}

						}else if($this->input->post('mode')=="3"){
							$types="12";
						}else if($this->input->post('mode')=="5"){
							$types="15";
						}
							$types = explode(',', $types);
							foreach($types as $key){
							 		$data = array(
										"exercise_type_id"=>$key,
										"category_id"=>$insert,
									);
							$data['support_lang_id'] = $this->session->userdata('support_lang_id');	
							$insert_exercise = $this->admin_model->add_category_exercise($data);
							}
					}
				}
			}
        }
        unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
        $this->session->set_flashdata('sucess_msg','SubCategory imported Successfully');	 
        redirect('admin_master/subcategory_list/'.$this->input->post('mode').'/'.$this->input->post('category'), 'refresh');     
    }
    /*
    *************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Vocabulary words Import
   	*************************************
    */
    public function words_import()
    {
		$path = FCPATH.'uploads/excel/';
		$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;
		$this->load->library('excel');//load PHPExcel library 
        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
        $configUpload['allowed_types'] = 'xls|xlsx|csv';
        $configUpload['max_size'] = '5000';
        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
        $file_name = $_FILES["file"]["name"]; //uploded file name
		$extension=$upload_data['file_ext'];    // uploded file extension
		//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
		$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
          //Set to read only
        $objReader->setReadDataOnly(true); 		  
        //Load excel file
		PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
		$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);  
        // if(($objWorksheet->getCellByColumnAndRow(0,1))!="image_name" && ($objWorksheet->getCellByColumnAndRow(0,2))!=" "){
	        	// $this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference ');	 
	            // redirect('admin_master/words_list', 'refresh');
		// }      
          //loop from first data untill last data
        for($i=2;$i<=$totalrows;$i++)
        {
        	$trgt_lang_col = $objWorksheet->getCellByColumnAndRow(4,$i);
        	if(isset($trgt_lang_col) && !empty(trim($trgt_lang_col)) && strlen($trgt_lang_col) > 0)
        	{
        		$target_lang_id = $this->session->userdata('support_lang_id');
				$getLang = $this->admin_model->master_function_get_data_by_condition("tbl_source_language",array("source_language_id"=>$target_lang_id,"status"=>"1"));
				$category = $this->input->post('category');
				$subcategoty = $this->input->post('subcategory');
				$mode = $this->input->post('mode');
				$data = array(
								"category_id"=>$category,
								"subcategory_id"=>$subcategoty,
								"exercise_mode_id"=>$mode,
								"image_file"=>strtolower(str_replace(" ", "_", $objWorksheet->getCellByColumnAndRow(0,$i))),
								"audio_file"=>strtolower(str_replace(" ", "_", $objWorksheet->getCellByColumnAndRow(1,$i))),
								"is_image_available"=>$objWorksheet->getCellByColumnAndRow(3,$i),
								"is_audio_available"=>$objWorksheet->getCellByColumnAndRow(2,$i)
						);	
				$source_lang=$this->admin_model->get_source_lang_by_target($target_lang_id);
				$ctn=0;
				foreach($source_lang as $langkey)
				{
					if($ctn==0){
						$j=4;

					}else{

						$j=$j+1;
					}
					$name=$objWorksheet->getCellByColumnAndRow($j,$i);
					$data[$langkey['field_name']] = $name;
					$ctn++;
				}
				$res = $this->admin_model->master_function_get_data_by_condition("tbl_word",array(
					$getLang[0]['field_name'] => $objWorksheet->getCellByColumnAndRow(4,$i),"category_id"=>$category, "subcategory_id"=>$subcategoty,
					"is_active"=>"1"));
				if(count($res) > 0){
					$data['support_lang_id'] = $this->session->userdata('support_lang_id');
					$update = $this->admin_model->master_function_for_update_by_conditions("tbl_word",array($getLang[0]['field_name'] => $objWorksheet->getCellByColumnAndRow(4,$i)),$data);
				}else{
					$data['support_lang_id'] = $this->session->userdata('support_lang_id');	
					$insert = $this->admin_model->add_words($data);
				}
			}
        }
        unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
        $this->session->set_flashdata('sucess_msg','Words imported Successfully');	 
        redirect('admin_master/words_list', 'refresh');
    }

    /*
    *************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Download category sample file
   	*************************************
    */
	public function download_category_sample() {
			$this->load->helper('download');
			$target_lang_id = $this->session->userdata('support_lang_id');
			$data = file_get_contents("./assets/sample_xls_file/".$target_lang_id."/IL_Category_names_sample.xlsx");
			$name = 'IL_Category_names_sample.xlsx';
			force_download($name, $data);
	
	}
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Download sub-category sample file
   	*************************************
    */
	public function download_subcategory_sample() {

			$this->load->helper('download');
			$target_lang_id = $this->session->userdata('support_lang_id');
			$data = file_get_contents("./assets/sample_xls_file/".$target_lang_id."/IL_Subcategory_names_sample.xlsx");
			$name = 'IL_Subcategory_names_sample.xlsx';
			force_download($name, $data);
	}
	 /*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Download vocabulary words sample file
   	*************************************
    */
	public function download_words_sample() {

			$this->load->helper('download');
			$target_lang_id = $this->session->userdata('support_lang_id');
			$data = file_get_contents("./assets/sample_xls_file/".$target_lang_id."/IL_Vocabulary_content_sample.xlsx");
			$name = 'IL_Vocabulary_content_sample.xlsx';
			force_download($name, $data);
	}
	 /*
	 *************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Download phrase sample file
   	*************************************
    */
	public function download_phrase_sample() {

			$this->load->helper('download');
			$target_lang_id = $this->session->userdata('support_lang_id');
			$data = file_get_contents("./assets/sample_xls_file/".$target_lang_id."/IL_phrase_mode.xlsx");
			$name = 'IL_phrase_mode.xlsx';
			force_download($name, $data);
	}
	 /*
	 *************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Download dialogue sample file
   	*************************************
    */
	public function download_dialogue_sample() {

			$this->load->helper('download');
			$target_lang_id = $this->session->userdata('support_lang_id');
			$data = file_get_contents("./assets/sample_xls_file/".$target_lang_id."/IL_Dialogue_content_sample.xlsx");
			$name = 'IL_Dialogue_content_sample.xlsx';
			force_download($name, $data);
	}
 	/*
 	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Download culture sample file
   	*************************************
    */
	public function download_culture_sample(){
			$this->load->helper('download');
			$target_lang_id = $this->session->userdata('support_lang_id');
			$data = file_get_contents("./assets/sample_xls_file/".$target_lang_id."/IL_Culture_content_sample.xlsx");
			$name = 'IL_Culture_content_sample.xlsx';
			force_download($name, $data);
	}
	 /*
	 *************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Download grammer sample file
   	*************************************
    */
	public function download_grammar_sample() {

			$this->load->helper('download');
			$target_lang_id = $this->session->userdata('support_lang_id');
			$data = file_get_contents("./assets/sample_xls_file/".$target_lang_id."/IL_Grammar_content_sample.xlsx");
			$name = 'IL_Grammar_content_sample.xlsx';
			force_download($name, $data);

	}

	public function get_cat_from_mode(){

		$mid = $this->input->post('mode_id');
		$lang_name = $this->session->userdata('support_lang_id');
		$result = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_categories',array('exercise_mode_id'=>$mid, 'support_lang_id'=>$lang_name, 'is_active'=>'1','is_delete'=>'0'),"category_name_in_en","asc");
		echo "<option value=''>Select Category</option>";
		foreach ($result as $key) {

			echo "<option value=".$key['exercise_mode_category_id'].">".$key['category_name_in_en']."</option>";
		}
	}
	public function get_type_from_mode(){

		$mid = $this->input->post('mode_id');
		$result = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_type',array('exercise_mode_id'=>$mid),"mode_name","asc");
		foreach ($result as $key) {

			echo "<option value=".$key['id'].">".$key['type_en']."</option>";
		}
	}
	
	public function get_subcat_from_cate(){

		$cid = $this->input->post('cate_id');
		$result = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_subcategories',array('category_id'=>$cid,'is_active'=>'1','is_delete'=>'0'),"subcategory_name_in_en","asc");
		echo "<option value=''>Select SubCategory</option>";
		foreach ($result as $key) {
			echo "<option value=".$key['exercise_mode_subcategory_id']." >  ".$key['subcategory_name_in_en']."</option>";
		}
	}

	public function get_subcat_from_cate_image_upload(){

		$cid = $this->input->post('cate_id');
		$result = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_subcategories',array('category_id'=>$cid,'is_active'=>'1','is_delete'=>'0'));
		echo "<option value=''>Select SubCategory</option>";
		foreach ($result as $key) {

			echo "<option value=".$key['subcategory_name_in_en'].">".$key['subcategory_name_in_en']."</option>";
		}
	}
	
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for upload bulk images
   	*************************************
    */

	public function upload_word_images(){

			if ($this->session->userdata('logged_in')){
				$sessiondata = $this->session->userdata('logged_in');
				$data['useremail']=$sessiondata[0]['email'];
				$data['userefirst_name']=$sessiondata[0]['first_name'];
				$data['userelast_name']=$sessiondata[0]['last_name'];
				$this->form_validation->set_rules('category', 'Category', 'required');
				$this->form_validation->set_rules('subcategory', 'SubCategory', 'required');
				if($this->input->post()){

					$this->session->set_userdata('selected_mode',$this->input->post('mode'));
					$this->session->set_userdata('selected_cateid',$this->input->post('category'));
					$this->session->set_userdata('selected_subcateid',$this->input->post('subcategory'));
				}

				 $mode =  $this->session->userdata('selected_mode');
				 $data['mode']=$mode; 
				 $cateid =  $this->session->userdata('selected_cateid');
				 $data['cateid']=$cateid; 
				 $subcateid =  $this->session->userdata('selected_subcateid');
				 $data['subcateid']=$subcateid; 
					if($this->form_validation->run() == FALSE)
			       	{
				        $data['category']=$this->admin_model->get_category_list($mode);
				        $data['subcategory']=$this->admin_model->get_subcategory_list($cateid);
				        $data['success_msg']=$this->session->flashdata('sucess_msg');
				        $data['error_msg']=$this->session->flashdata('error_upload');
				        $data['active_class']="image";
						$data['exercise_mode']=$this->admin_model->get_exercise_mode();
						$admin_data = $this->session->userdata('logged_in');
						$admin_language = $admin_data[0]['support_lang_ids'];
						$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
						$slang_name = $this->session->userdata('support_lang_name');
						$data['current_support_lang'] = $slang_name;

						$this->load->view('admin/header',$data);
						$this->load->view('admin/upload_words_images',$data);
						$this->load->view('admin/side_menu',$data);
						$this->load->view('admin/footer');
						
			     	}else{
						$category = $this->input->post('category');
						$subcate = $this->input->post('subcategory');

						

			   			if(!file_exists('./uploads/words/'.$category )) {
						 mkdir('./uploads/words/'.$category , 0777, true);
			   			 $old = umask(0);
			   			 chmod('./uploads/words/'.$category , 0777);
			   			 umask($old);
			   			}
			   			if(!file_exists('./uploads/words/'.$category.'/'.$subcate )) {
						 mkdir('./uploads/words/'.$category.'/'.$subcate  , 0777, true);
			   			 $old = umask(0);
			   			 chmod('./uploads/words/'.$category.'/'.$subcate  , 0777);
			   			 umask($old);
			   			}
						$path = getcwd().'/uploads/words/'.$category.'/'.$subcate.'/index.php';
						$this->makePathNotAccessible($path);

						$errorUploadmsg = '';
						$count = count($_FILES['userfile']['size']);
				        foreach($_FILES as $key=>$value)
				        for($s=0; $s<=$count-1; $s++) {
					        $_FILES['userfile']['name'] = $value['name'][$s];
					        $_FILES['userfile']['type'] = $value['type'][$s];
					        $_FILES['userfile']['tmp_name'] = $value['tmp_name'][$s];
					        $_FILES['userfile']['error'] = $value['error'][$s];
					        $_FILES['userfile']['size']  = $value['size'][$s];

					        $config['upload_path'] = "./uploads/words/$category/$subcate";
					        $config['allowed_types'] = 'gif|jpg|png|jpeg';
					        $config['overwrite'] = TRUE;
					        $config['max_size']  = '0';
							$new_name = $value['name'][$s];
							$new_name =  str_replace(" ","_",$new_name);
							$config['file_name'] = strtolower($new_name);

					        $this->load->library('upload', $config);
					        $this->upload->initialize($config);
		                    // Upload file to server 
		                    if($this->upload->do_upload('userfile')){ 
		                        // Uploaded file data 
		                        $fileData = $this->upload->data(); 
		                    }else{  
		                        $errorUploadType .= $_FILES['userfile']['name'].' | ';  
		                    }
                			$errorUploadmsg .= !empty($errorUploadType)?'Error in file upload due to large size or invalid file type: '.trim($errorUploadType, ' | '):''; 
					        // $this->upload->do_upload();
					        // $data = $this->upload->data();
				        }
						$this->session->set_flashdata('sucess_msg','Images uploaded Successfully! ');
						if(!empty($errorUploadmsg))
						{
							$this->session->set_flashdata('error_upload',$errorUploadmsg);
						}
						redirect('admin_master/upload_word_images', 'refresh');
					}

			}else{
					redirect('admin_master/login', 'refresh');
			}
	}

	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for upload bulk Audios
   	*************************************
    */
	public function upload_word_audio(){

		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			$this->form_validation->set_rules('category', 'Category', 'required');
			$this->form_validation->set_rules('subcategory', 'SubCategory', 'required');
			if($this->input->post()){
				$this->session->set_userdata('selected_mode',$this->input->post('mode'));
				$this->session->set_userdata('selected_cateid',$this->input->post('category'));
				$this->session->set_userdata('selected_subcateid',$this->input->post('subcategory'));		
			}		 
			 $mode =  $this->session->userdata('selected_mode');
			 $data['mode']=$mode; 
			 $cateid =  $this->session->userdata('selected_cateid');
			 $data['cateid']=$cateid; 
			 $subcateid =  $this->session->userdata('selected_subcateid');
			 $data['subcateid']=$subcateid; 
			if($this->form_validation->run() == FALSE)
		    {
			        $data['category']=$this->admin_model->get_category_list($mode);
			        $data['subcategory']=$this->admin_model->get_subcategory_list($cateid);
			        $data['success_msg']=$this->session->flashdata('sucess_msg');
			        $data['error_msg']=$this->session->flashdata('error_upload');
			        $data['active_class']="audio";
					$data['exercise_mode']=$this->admin_model->get_exercise_mode();
					$admin_data = $this->session->userdata('logged_in');
					$admin_language = $admin_data[0]['support_lang_ids'];
					$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
					$data['current_support_lang'] = $slang_name;
					
					$this->load->view('admin/header',$data);
					$this->load->view('admin/upload_words_audio',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');
					
		     	}else{

					$category = $this->input->post('category'); 
					$subcate = $this->input->post('subcategory');

					if(!file_exists('./uploads/audio/'.$category )) {
						 mkdir('./uploads/audio/'.$category , 0777, true);
			   			 $old = umask(0);
			   			 chmod('./uploads/audio/'.$category , 0777);
			   			 umask($old);
			   			}

		   			if(!file_exists('./uploads/audio/'.$category.'/'.$subcate )) {
					 mkdir('./uploads/audio/'.$category.'/'.$subcate  , 0777, true);
		   			 $old = umask(0);
		   			 chmod('./uploads/audio/'.$category.'/'.$subcate  , 0777);
		   			 umask($old);
		   			}

					$path1 = getcwd().'/uploads/audio/'.$category.'/'.$subcate.'/index.php';
					$this->makePathNotAccessible($path1);

					$count = count($_FILES['userfile']['size']);
					
			        foreach($_FILES as $key=>$value)
			        for($s=0; $s<=$count-1; $s++) {
					        $_FILES['userfile']['name']=$value['name'][$s];
					        $_FILES['userfile']['type']    = $value['type'][$s];
					        $_FILES['userfile']['tmp_name'] = $value['tmp_name'][$s];
					        $_FILES['userfile']['error']       = $value['error'][$s];
					        $_FILES['userfile']['size']    = $value['size'][$s];  
					        $config['upload_path'] = "./uploads/audio/$category/$subcate";
					        $config['allowed_types'] = '*';
					        
					        $config['overwrite'] = TRUE;
							$new_name = $value['name'][$s];
					        $new_name =  str_replace(" ","_",$new_name);
							$config['file_name'] = strtolower($new_name);
					        $this->load->library('upload', $config);
					        $this->upload->initialize($config);
					        $this->upload->do_upload();
					        $data = $this->upload->data();
					       
			         }
					$this->session->set_flashdata('sucess_msg','Audio uploaded Successfully');
					redirect('admin_master/upload_word_audio', 'refresh');
				}

			}else{
					redirect('admin_master/login', 'refresh');
			}
	}

	public function upload_word_audio_old() {
		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			$data['success_msg']=$this->session->flashdata('sucess_msg');
	        $data['error_msg']=$this->session->flashdata('error_upload');
	        $data['active_class']="audio";
	        $data['category']=$this->admin_model->get_category_list();
			$data['subcategory']=$this->admin_model->get_subcategory_list();
			$admin_data = $this->session->userdata('logged_in');
			$admin_language = $admin_data[0]['support_lang_ids'];
			$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
			$data['current_support_lang'] = $slang_name;
							
			$this->load->view('admin/header',$data);
			$this->load->view('admin/upload_words_audio',$data);
			$this->load->view('admin/side_menu',$data);
			$this->load->view('admin/footer');
		}else{
				redirect('admin_master/login', 'refresh');
		}	
	}
	     	
	public function upload_audio(){
		if($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
				$count = count($_FILES['userfile']['size']);
		        foreach($_FILES as $key=>$value)
		        for($s=0; $s<=$count-1; $s++) {
				        $_FILES['userfile']['name']=$value['name'][$s];
				        $_FILES['userfile']['type']    = $value['type'][$s];
				        $_FILES['userfile']['tmp_name'] = $value['tmp_name'][$s];
				        $_FILES['userfile']['error']       = $value['error'][$s];
				        $_FILES['userfile']['size']    = $value['size'][$s];  
			            $config['upload_path'] = "./uploads/audio/";
			            $config['allowed_types'] = '*';
	                    $config['overwrite'] = TRUE;
                  		$new_name = $value['name'][$s];
	                    $new_name =  str_replace(" ","_",$new_name);
						$config['file_name'] = strtolower($new_name);
				        $this->load->library('upload', $config);
				        $this->upload->initialize($config);
				        $this->upload->do_upload();
				        $data = $this->upload->data();   
		        }
		        
				$this->session->set_flashdata('sucess_msg','Audio uploaded Successfully');
				redirect('admin_master/upload_word_audio', 'refresh');
			}else{
						redirect('admin_master/login', 'refresh');
			}
	}

	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Delete all Category
   	*************************************
    */

	function delete_all_category(){

		//print_r($this->input->post()); die();
		$ids = $this->input->post('delete');
		$submit = $this->input->post('submit');
		
		if($submit=="Delete Selected"){

			if(empty($ids)){
						$this->session->set_flashdata('error_msg','Please select at least one');
						redirect('admin_master/category_list', 'refresh');	
			}else{
					foreach ($ids as $key){
						$data = array('is_active'=>'0','is_delete'=>'1');
						$delete = $this->admin_model->delete_category($data,$key);
						$data1 = array('is_active'=>'0');
						$delete = $this->admin_model->delete_row_by_condition('tbl_word',$data1,array('category_id'=>$key));
						$delete = $this->admin_model->delete_row_by_condition('tbl_grammer_master',$data,array('category_id'=>$key));
						$delete = $this->admin_model->delete_row_by_condition('tbl_culture_master',$data,array('category_id'=>$key));
						$delete = $this->admin_model->delete_row_by_condition('tbl_phrases',$data,array('category_id'=>$key));
						$delete = $this->admin_model->delete_row_by_condition('tbl_dialogue_master',$data,array('category_id'=>$key));	
						$delete = $this->admin_model->delete_row_by_condition('tbl_aural_composition',$data1,array('category_id'=>$key));
					}
					if($delete){
						$this->session->set_flashdata('sucess_msg','Category Deleted Successfully');
						//redirect('admin_master/category_list', 'refresh');	
						header('Location: '.$_SERVER['HTTP_REFERER']);
					}
			}
		}
		if($submit=="Delete Images for Selected"){

			if(empty($ids)){
						$this->session->set_flashdata('error_msg','Please select at least one');
						//redirect('admin_master/category_list', 'refresh');	
						header('Location: '.$_SERVER['HTTP_REFERER']);
			}
			
			foreach ($ids as $key){
				$res = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_categories',array('exercise_mode_category_id'=>$key));
				if(count($res)>0){
					$image=$res[0]['image'];
					$root_path  = $this->config->item('root_path');   
					$file = $root_path.'uploads/'.$image;
					if($image != "")
						unlink($file);
				}
				$update = $this->admin_model->master_function_for_update_by_conditions('tbl_exercise_mode_categories',array('exercise_mode_category_id'=>$key),array("image"=>""));
			}

				$this->session->set_flashdata('sucess_msg','Category Image Deleted Successfully');
				//redirect('admin_master/category_list', 'refresh');	
				header('Location: '.$_SERVER['HTTP_REFERER']);

		}
		
	}
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Delete all sub-Category
   	*************************************
    */
	function delete_all_subcategory(){

		$ids = $this->input->post('delete');
		$submit = $this->input->post('submit');

		if($submit=="Delete Selected"){
				if(empty($ids)){
					$this->session->set_flashdata('error_msg','Please select at least one');
					//redirect('admin_master/subcategory_list', 'refresh');	
					header('Location: '.$_SERVER['HTTP_REFERER']);
				}else{
					foreach ($ids as $key){
						$data = array('is_active'=>'0','is_delete'=>'1');
						$delete = $this->admin_model->delete_subcategory($data,$key);
						$data1 = array('is_active'=>'0');
						$delete = $this->admin_model->delete_row_by_condition('tbl_word',$data1,array('subcategory_id'=>$key));
						$delete = $this->admin_model->delete_row_by_condition('tbl_grammer_master',$data,array('subcategory_id'=>$key));
						$delete = $this->admin_model->delete_row_by_condition('tbl_culture_master',$data,array('subcategory_id'=>$key));
						$delete = $this->admin_model->delete_row_by_condition('tbl_phrases',$data,array('subcategory_id'=>$key));
						$delete = $this->admin_model->delete_row_by_condition('tbl_dialogue_master',$data,array('subcategory_id'=>$key));
						$delete = $this->admin_model->delete_row_by_condition('tbl_aural_composition',$data1,array('subcategory_id'=>$key));
					}
					if($delete){
						$this->session->set_flashdata('sucess_msg','SubCategory Deleted Successfully');
						//redirect('admin_master/subcategory_list', 'refresh');	
						header('Location: '.$_SERVER['HTTP_REFERER']);
					}
				}
		}

		if($submit=="Delete Images for Selected"){

			if(empty($ids)){
					$this->session->set_flashdata('error_msg','Please select at least one');
					//redirect('admin_master/subcategory_list', 'refresh');	
					header('Location: '.$_SERVER['HTTP_REFERER']);
			}
			foreach ($ids as $key){
				$res = $this->admin_model->master_function_get_data_by_condition('tbl_exercise_mode_subcategories',array('exercise_mode_subcategory_id'=>$key));
				if(count($res)>0){
					$image=$res[0]['image'];
					$root_path  = $this->config->item('root_path');   
					$file = $root_path.'uploads/'.$image;
					if($image != "")
						unlink($file);
				}

				$update = $this->admin_model->master_function_for_update_by_conditions('tbl_exercise_mode_subcategories',array('exercise_mode_subcategory_id'=>$key),array("image"=>""));

			}

				$this->session->set_flashdata('sucess_msg','SubCategory Image Deleted Successfully');
				//redirect('admin_master/subcategory_list', 'refresh');	
				header('Location: '.$_SERVER['HTTP_REFERER']);

		}


	}
	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : Function for Delete all Vocabulary Words
   	*************************************
    */
	function delete_all_words(){
		$ids = $this->input->post('delete');
		$submit = $this->input->post('submit');
		if(empty($ids)){
				$this->session->set_flashdata('error_msg','Please select at least one');
				//redirect('admin_master/words_list', 'refresh');	
				header('Location: '.$_SERVER['HTTP_REFERER']);
		}

		if($submit=="Delete Selected"){
			if(empty($ids)){
					$this->session->set_flashdata('error_msg','Please select at least one');
					redirect('admin_master/words_list', 'refresh');	
			}else{
					foreach ($ids as $key) {
						if(!empty($key) && $key > 0)
						{
							$data = array('is_active'=>'0');
							$delete = $this->admin_model->delete_word($data,$key);		
						}
					}
					if($delete){
						$this->session->set_flashdata('sucess_msg','Words Deleted Successfully');
						//redirect('admin_master/words_list', 'refresh');	
						header('Location: '.$_SERVER['HTTP_REFERER']);
					}
			}	

		}
		if($submit=="Delete Images"){

			foreach ($ids as $key){
				$res = $this->admin_model->master_function_get_data_by_condition('tbl_word',array('word_id'=>$key));
				if(count($res)>0){
					$image=$res[0]['image_file'];
					$category_id=$res[0]['category_id'];
					$subcategory_id=$res[0]['subcategory_id'];

					$root_path  = $this->config->item('root_path');   
					$file = $root_path.'uploads/words/'.$category_id.'/'.$subcategory_id.'/'.$image;
					if($image != "")
						unlink($file);
				}
				$update = $this->admin_model->master_function_for_update_by_conditions('tbl_word',array('word_id'=>$key),array("image_file"=>""));
			}
				$this->session->set_flashdata('sucess_msg','Words Images Deleted Successfully');
				//redirect('admin_master/words_list', 'refresh');	
				header('Location: '.$_SERVER['HTTP_REFERER']);
		}

		if($submit=="Delete Audios"){

			foreach ($ids as $key){
				$res = $this->admin_model->master_function_get_data_by_condition('tbl_word',array('word_id'=>$key));
				if(count($res)>0){
					$image=$res[0]['audio_file'];
					$category_id=$res[0]['category_id'];
					$subcategory_id=$res[0]['subcategory_id'];

					$root_path  = $this->config->item('root_path');   
					$file = $root_path.'uploads/audio/'.$category_id.'/'.$subcategory_id.'/'.$image;
					if($image != "")
						unlink($file);
				}
				$update = $this->admin_model->master_function_for_update_by_conditions('tbl_word',array('word_id'=>$key),array("audio_file"=>""));
			}
				$this->session->set_flashdata('sucess_msg','Words Images Deleted Successfully');
				//redirect('admin_master/words_list', 'refresh');	
				header('Location: '.$_SERVER['HTTP_REFERER']);
		}

	}

	/*
	*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is only use for Developer to add new daynamic lanuages, this function will create table's fields daynamic
   	*************************************
    */
	public function add_dyamic_lang(){

		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			$this->form_validation->set_rules('name', 'Name', 'required|is_unique[tbl_source_language.language_name]');
			$this->form_validation->set_rules('code','Code','required|is_unique[tbl_source_language.language_code]');
			$admin_data = $this->session->userdata('logged_in');
			$admin_language = $admin_data[0]['support_lang_ids'];
			$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
			$slang_name = $this->session->userdata('support_lang_name');
			$data['current_support_lang'] = $slang_name;
							
			if($this->form_validation->run() == FALSE)
		    {
			        $data['success_msg']=$this->session->flashdata('insert_cat');
			        $data['error_msg']=$this->session->flashdata('error_upload');
			        $data['active_class']="subcategory";
					$this->load->view('admin/header',$data);
					$this->load->view('admin/add_dyamic_lang',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');

		    }else{
					$name = strtolower($this->input->post('name'));
					$code = $this->input->post('code');
							$data = array(
									"language_name"=>$name,
									"language_code"=>$code,
									"field_name"=>'word_'.$name
							);
					$data['support_lang_id'] = $this->session->userdata('support_lang_id');	
					$insert = $this->admin_model->add_lang($data);
					if($insert){
						$this->session->set_flashdata('sucess_msg','Language Inserted Successfully');
						redirect('admin_master/add_dyamic_lang', 'refresh');
					}
			}

		}else{
			redirect('admin_master/login', 'refresh');
		}
	}

	/*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for Dialouge List
   	*************************************
    */
	public function dialogue_list(){

		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
					 if($this->input->post()){
 								$this->session->set_userdata('lang',$this->input->post('lang'));
 								$this->session->set_userdata('cateid',$this->input->post('cate_id'));
 								$this->session->set_userdata('subcateid',$this->input->post('subcate_id'));
 								$this->session->set_userdata('sort',$this->input->post('sort'));
 								$this->session->set_userdata('per_page',$this->input->post('per_page'));
					 }

			$lang =  $this->session->userdata('lang');
			$data['lang']=$lang; 	
			$category = $this->session->userdata('cateid');
			$data['category_select']=$category;
			$subcategory = $this->session->userdata('subcateid');
			$data['subcategory_select']=$subcategory;
			$sort = $this->session->userdata('sort');
			$data['sort_select']=$sort;
		    $per_page = $this->session->userdata('per_page');
		    $data['per_page_select']=$per_page;
					/// for pagination
					if(!isset($per_page)){

						$per_page=100;
					}
			$config = array();
	        $res = $this->admin_model->get_dialogue_list($lang,$category,$subcategory);
	        $config["total_rows"] = count($res);
	        $config["per_page"] = $per_page;
			$config["uri_segment"] = 3;
			$config["base_url"] = base_url() . "admin_master/dialogue_list";
			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        	$config['full_tag_open'] = "<ul class='pagination pagination-small pagination-centered'>";
			$config['full_tag_close'] ="</ul>";
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
			$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
			$config['next_tag_open'] = "<li>";
			$config['next_tagl_close'] = "</li>";
			$config['prev_tag_open'] = "<li>";
			$config['prev_tagl_close'] = "</li>";
			$config['first_tag_open'] = "<li>";
			$config['first_tagl_close'] = "</li>";
			$config['last_tag_open'] = "<li>";
			$config['last_tagl_close'] = "</li>";
	        $this->pagination->initialize($config);
	        $data['grammer_list'] = $this->admin_model->get_dialogue_list_pagination($config["per_page"], $page,$lang,$category,$subcategory);
	        $data["links"] = $this->pagination->create_links();		
            $data["page_info"] =  "Showing ".($config["per_page"])." of ".$config["total_rows"]." total results";
			//end pagination
            $mode = "2";
			$data['success_msg']=$this->session->flashdata('sucess_msg');
			$data['error_msg']=$this->session->flashdata('error_msg');
			$data['category']=$this->admin_model->get_category_list($mode);
	        $data['subcategory'] = $this->admin_model->get_subcategory_list($category);
	        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
	        $data['source_lang']=$this->admin_model->get_support_lang_new();
	        $admin_data = $this->session->userdata('logged_in');
			$admin_language = $admin_data[0]['support_lang_ids'];
			$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
	        $slang_name = $this->session->userdata('support_lang_name');
			$data['current_support_lang'] = $slang_name;
			$data['active_class']="dialogue";
			$data['target_language_id'] = $this->session->userdata('support_lang_id');
			$this->load->view('admin/header',$data);
			$this->load->view('admin/dialogue_list',$data);
			$this->load->view('admin/side_menu',$data);
			$this->load->view('admin/footer');

		}else{

			redirect('admin_master/login', 'refresh');

		}
	}

	/*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for add Dialouge 
   	*************************************
    */
	public function add_dialogue(){
		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			$source_lang=$this->admin_model->get_source_lang();
			$this->form_validation->set_rules('category', 'Category', 'required');
			$this->form_validation->set_rules('subcategory','SubCategory','required');
			// $this->form_validation->set_rules('lang','Language','required');
			if($this->form_validation->run() == FALSE)
	       	{
			        $data['category']=$this->admin_model->get_category_list('2');
			        $data['subcategory'] = $this->admin_model->get_subcategory_list();
			        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
			        $data['success_msg']=$this->session->flashdata('insert_cat');
			        $data['error_msg']=$this->session->flashdata('error_upload');
			        $data['active_class']="dialogue";
					$data['source_lang']=$this->admin_model->get_support_lang_new();
					$admin_data = $this->session->userdata('logged_in');
			$admin_language = $admin_data[0]['support_lang_ids'];
			$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
					$slang_name = $this->session->userdata('support_lang_name');
					$data['current_support_lang'] = $slang_name;
					$this->load->view('admin/header',$data);
					$this->load->view('admin/add_dialogue',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');
				
	     	}else{
					$category = $this->input->post('category');
					$subcategoty = $this->input->post('subcategory');
					$lang = $this->input->post('lang');
					$title = $this->input->post('title');
				  	$full_audio = $this->input->post('full_audio');
				  	$full_audio = strtolower(str_replace(" ", "_", $full_audio));
				  	$phrase = $this->input->post('phrase[]');
				  	$type = $this->input->post('type[]');
				  	$audio = $this->input->post('audio[]');
					$data = array(
								"title"=>$title,
								"full_audio"=>$full_audio,
								"support_lang_id"=>$lang,
								"category_id"=>$category,
								"subcategory_id"=>$subcategoty	
							);
					$data['target_language_id'] = $this->session->userdata('support_lang_id');	
					$insert = $this->admin_model->add_dialogue_master($data);
					if($insert){
							for($i=0;$i < count($phrase);$i++){
								$audioi = strtolower(str_replace(" ", "_", $audio[$i]));
								$data1 = array(
										"dialogue_master_id"=>$insert,
										"phrase"=>$phrase[$i],
										"audio_name"=>$audioi,
										"speaker"=>$type[$i],
										"sequence_no"=>$i+1,
									);	
								$data1['support_lang_id'] = $this->session->userdata('support_lang_id');	
								$insert_list = $this->admin_model->add_dialogue_list($data1);
							}
							$this->session->set_flashdata('sucess_msg','Dialogue Inserted Successfully');
							redirect('admin_master/dialogue_list', 'refresh');
					}
			}

		}else{

				redirect('admin_master/login', 'refresh');
		}
		
	}
	/*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for Edit Dialouge 
   	*************************************
    */
	public function edit_dialogue($id){

			if($this->session->userdata('logged_in'))
			{
				$sessiondata = $this->session->userdata('logged_in');
				$data['useremail']=$sessiondata[0]['email'];
				$data['userefirst_name']=$sessiondata[0]['first_name'];
				$data['userelast_name']=$sessiondata[0]['last_name'];
				$source_lang=$this->admin_model->get_source_lang();
				$this->form_validation->set_rules('category', 'Category', 'required');
				$this->form_validation->set_rules('subcategory','SubCategory','required');
					if($this->form_validation->run() == FALSE)
				    {
					    $data['edit_data'] = $this->admin_model->get_dialogue_from_id($id);
					    $data['edit_data_list'] = $this->admin_model->get_dialogue_list_from_id($id);
						$data['category']=$this->admin_model->get_category_list('2');
				       	$data['subcategory'] = $this->admin_model->get_subcategory_list();
				        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
						$data['source_lang']=$this->admin_model->get_support_lang_new();
						$admin_data = $this->session->userdata('logged_in');
						$admin_language = $admin_data[0]['support_lang_ids'];
						$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
						$slang_name = $this->session->userdata('support_lang_name');
						$data['current_support_lang'] = $slang_name;
						$data['active_class']="dialogue";
						$this->load->view('admin/header',$data);
						$this->load->view('admin/edit_dialogue',$data);
						$this->load->view('admin/side_menu',$data);
						$this->load->view('admin/footer');

				    }else{

			    		$category = $this->input->post('category');
						$subcategoty = $this->input->post('subcategory');
						$lang = $this->input->post('lang');
						$title = $this->input->post('title');
					  	$full_audio = $this->input->post('full_audio');
					  	$full_audio = strtolower(str_replace(" ", "_", $full_audio));
					  	$phrase = $this->input->post('phrase[]');
					  	$type = $this->input->post('type[]');
					  	$audio = $this->input->post('audio[]');
						$data = array(		
										"title"=>$title,
										"full_audio"=>$full_audio,
										"support_lang_id"=>$lang,
										"category_id"=>$category,
										"subcategory_id"=>$subcategoty	
								);
						$data['target_language_id'] = $this->session->userdata('support_lang_id');
						$insert = $this->admin_model->update_dialogue($data,$id);
								if($insert){

									$delete_list = $this->admin_model->delete_dialogue_list($id);
									for($i=0;$i < count($phrase);$i++){
										$audioi = strtolower(str_replace(" ", "_", $audio[$i]));
										$data1 = array(
												"dialogue_master_id"=>$id,
												"phrase"=>$phrase[$i],
												"audio_name"=>$audioi,
												"speaker"=>$type[$i],
												"sequence_no"=>$i+1,
										);	
										$data1['support_lang_id'] = $this->session->userdata('support_lang_id');
										$insert_list = $this->admin_model->add_dialogue_list($data1);
									}
									$this->session->set_flashdata('sucess_msg','Dialouge Updated Successfully');
									redirect('admin_master/dialogue_list', 'refresh');
								}
									
					}
			}else{

					redirect('admin_master/login', 'refresh');
			}

	}

	/*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for Delete Dialouge 
   	*************************************
    */
	public function delete_dialogue(){
				$id = $this->uri->segment('3');
				$data = array('is_active'=>'0','is_delete'=>'1');
				$delete = $this->admin_model->delete_dialogue($data,$id);
				$delete_list = $this->admin_model->delete_dialogue_list($id);
				if($delete){
					$this->session->set_flashdata('sucess_msg','Dialouge Deleted Successfully');
					//redirect('admin_master/dialogue_list', 'refresh');	
					header('Location: '.$_SERVER['HTTP_REFERER']);
				}
	}

	/*************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for Delete all Dialouge 
   	*************************************
    */
	function delete_all_dialogue(){

		$ids = $this->input->post('delete');
		if(empty($ids)){
					$this->session->set_flashdata('error_msg','Please select at least one');
					//redirect('admin_master/dialogue_list', 'refresh');
					header('Location: '.$_SERVER['HTTP_REFERER']);	
		}else{
			foreach ($ids as $key) {
							
				$data = array('is_active'=>'0','is_delete'=>'1');
				$delete = $this->admin_model->delete_dialogue($data,$key);
				$delete_list = $this->admin_model->delete_dialogue_list($key);
			}

			if($delete){
				$this->session->set_flashdata('sucess_msg','Dialouge Deleted Successfully');
			//	redirect('admin_master/dialogue_list', 'refresh');

				header('Location: '.$_SERVER['HTTP_REFERER']);
			}
		}		
	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for Import Dialouge 
   	*************************************
    */
	public function dialogue_import(){
				$path = FCPATH.'uploads/excel/';
				$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;
				$this->load->library('excel');//load PHPExcel library 
		        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
		        $configUpload['allowed_types'] = 'xls|xlsx|csv';
		        $configUpload['max_size'] = '5000';	
		        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
		        $file_name = $_FILES["file"]["name"]; //uploded file name
				$extension=$upload_data['file_ext'];    // uploded file extension
				//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
				$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
		          //Set to read only
		        $objReader->setReadDataOnly(true); 		  
		        //Load excel file
				PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
				$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
		        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
		        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);  
		        // if(($objWorksheet->getCellByColumnAndRow(0,1))!="Title" && ($objWorksheet->getCellByColumnAndRow(0,2))!=" "){
		        	// $this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference ');	 
		            // redirect('admin_master/dialogue_list', 'refresh');
        		// }
		          //loop from first data untill last data
        		$audio_list_array = array();
        		$insert= false;

		        for($i=2;$i<=$totalrows;$i++)
		        {
		        	$au = $objWorksheet->getCellByColumnAndRow(0,$i);
		        	$category = $this->input->post('category');
					$subcategoty = $this->input->post('subcategory');
					$lang = $this->input->post('lang');
					array_push($audio_list_array, $au);
					if($objWorksheet->getCellByColumnAndRow(0,$i)!=" " && $objWorksheet->getCellByColumnAndRow(0,$i)!=""){
						$data = array(
									"title"=>$objWorksheet->getCellByColumnAndRow(0,$i),
									"full_audio"=>strtolower(str_replace(" ", "_", $objWorksheet->getCellByColumnAndRow(1,$i))),
									"support_lang_id"=>$lang,
									"category_id"=>$category,
									"subcategory_id"=>$subcategoty		
							);	
						$res = $this->admin_model->master_function_get_data_by_condition("tbl_dialogue_master",array(
							"title"=>$objWorksheet->getCellByColumnAndRow(0,$i),
							"category_id"=>$category,
							"subcategory_id"=>$subcategoty,
							"is_active"=>"1"));

						if($res > 0){
							$deleted_dia_id  = $res[0]['dialogue_master_id'];
						}
						

						if(count($res) == 0){
							$data['target_language_id'] = $this->session->userdata('support_lang_id');
							$insert = $this->admin_model->add_dialogue_master($data);
						}else{

							$this->db->where('dialogue_master_id', $deleted_dia_id);
							$delete = $this->db->delete('tbl_dialogue_list');
							$this->db->where('dialogue_master_id', $deleted_dia_id);
							$delete = $this->db->delete('tbl_dialogue_master');
							sleep(1);
							$data['target_language_id'] = $this->session->userdata('support_lang_id');
							$insert = $this->admin_model->add_dialogue_master($data);

							//$update = $this->admin_model->master_function_for_update_by_conditions("tbl_dialogue_master",array("title"=>$objWorksheet->getCellByColumnAndRow(0,$i)),$data);
						}
					}
					
							$data = array(
											"dialogue_master_id"=>$insert,
											"phrase"=>$objWorksheet->getCellByColumnAndRow(2,$i),
											"audio_name"=>strtolower(str_replace(" ", "_", $objWorksheet->getCellByColumnAndRow(3,$i))),
											"speaker"=>$objWorksheet->getCellByColumnAndRow(4,$i),
											"sequence_no"=>$objWorksheet->getCellByColumnAndRow(5,$i),
									);
							$data['support_lang_id'] = $this->session->userdata('support_lang_id');	
							$insert_list = $this->admin_model->add_dialogue_list($data);
						

				}
					//print_r($audio_list_array); die();

	        unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
	        $this->session->set_flashdata('sucess_msg','dialogue imported Successfully');	 
	        redirect('admin_master/dialogue_list', 'refresh');
	              
    }
	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for Grammer List
   	*************************************
    */
	public function grammar_list(){
		if($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			if($this->input->post()){
				$this->session->set_userdata('lang',$this->input->post('lang'));
				$this->session->set_userdata('cateid',$this->input->post('cate_id'));
				$this->session->set_userdata('subcateid',$this->input->post('subcate_id'));
				$this->session->set_userdata('sort',$this->input->post('sort'));
				$this->session->set_userdata('per_page',$this->input->post('per_page'));
			}
			$lang =  $this->session->userdata('lang');
			$data['lang']=$lang; 
			$category = $this->session->userdata('cateid');
			$data['category_select']=$category;
			$subcategory = $this->session->userdata('subcateid');
			$data['subcategory_select']=$subcategory;
			$per_page = $this->session->userdata('per_page');
			$data['per_page_select']=$per_page;
			/// for pagination
			if(!isset($per_page)){
				$per_page=100;
			}
			$config = array();
	        $res = $this->admin_model->get_grammer_list($lang,$category,$subcategory);
	        $config["total_rows"] = count($res);
	        $config["per_page"] = $per_page;
			$config["uri_segment"] = 3;
			$config["base_url"] = base_url() . "admin_master/grammar_list";
			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        	$config['full_tag_open'] = "<ul class='pagination pagination-small pagination-centered'>";
			$config['full_tag_close'] ="</ul>";
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
			$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
			$config['next_tag_open'] = "<li>";
			$config['next_tagl_close'] = "</li>";
			$config['prev_tag_open'] = "<li>";
			$config['prev_tagl_close'] = "</li>";
			$config['first_tag_open'] = "<li>";
			$config['first_tagl_close'] = "</li>";
			$config['last_tag_open'] = "<li>";
			$config['last_tagl_close'] = "</li>";
	        $this->pagination->initialize($config);
	        $data['grammer_list'] = $this->admin_model->get_grammer_list_pagination($config["per_page"], $page,$lang,$category,$subcategory);
	        $data["links"] = $this->pagination->create_links();		
            $data["page_info"] =  "Showing ".($config["per_page"])." of ".$config["total_rows"]." total results";
			//end pagination
            $mode = "4";
			$data['success_msg']=$this->session->flashdata('sucess_msg');
			$data['error_msg']=$this->session->flashdata('error_msg');
			$data['category']=$this->admin_model->get_category_list($mode);
	        $data['subcategory'] = $this->admin_model->get_subcategory_list($category);
	        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
	        $data['source_lang']=$this->admin_model->get_support_lang_new();
	        /*echo "<pre>";
	        print_r($data['source_lang']);
	        exit;*/
	        $admin_data = $this->session->userdata('logged_in');
			$admin_language = $admin_data[0]['support_lang_ids'];
			$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
			$data['active_class']="grammar";
			$slang_name = $this->session->userdata('support_lang_name');
			$data['current_support_lang'] = $slang_name;
			$data['target_language_id'] = $this->session->userdata('support_lang_id');
			$this->load->view('admin/header',$data);
			$this->load->view('admin/grammer_list',$data);
			$this->load->view('admin/side_menu',$data);
			$this->load->view('admin/footer');

		}else{

			redirect('admin_master/login', 'refresh');

		}
	
	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for add New Grammer question
   	*************************************
    */
	public function add_grammar(){

		if ($this->session->userdata('logged_in')){
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					$source_lang=$this->admin_model->get_source_lang();
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
					$this->form_validation->set_rules('question','Question','required');
		
					// $this->form_validation->set_rules('lang','Language','required');

						if($this->form_validation->run() == FALSE)
				       	{

						        $data['category']=$this->admin_model->get_category_list('4');
						        $data['subcategory'] = $this->admin_model->get_subcategory_list();
						        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
						        $data['success_msg']=$this->session->flashdata('insert_cat');
						        $data['error_msg']=$this->session->flashdata('error_upload');
						        $data['active_class']="grammar";
								$data['source_lang']=$this->admin_model->get_support_lang_new();
								$admin_data = $this->session->userdata('logged_in');
								$admin_language = $admin_data[0]['support_lang_ids'];
								$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
								$slang_name = $this->session->userdata('support_lang_name');
								$data['current_support_lang'] = $slang_name;
								$this->load->view('admin/header',$data);
								$this->load->view('admin/add_grammer',$data);
								$this->load->view('admin/side_menu',$data);
								$this->load->view('admin/footer');
							
				     	}else{

								$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								$lang = $this->input->post('lang');
								$type = $this->input->post('type');
								$question = $this->input->post('question');
								if(!empty($this->input->post('notes')))
								{
									$notes = $this->input->post('notes');
								}else{
									$notes = "";
								}
								
								if(!empty($this->input->post('audio_file')))
								{
									$audio = $this->input->post('audio_file');
								}else{
									$audio = "";
								}
							  	$option = $this->input->post('option[]');
							  	$option_ans = $this->input->post('correct_ans');
							  	if(!empty($audio))
							  	{
							  		$is_video_available = '1';
							  	}else{
							  		$is_video_available = '0';
							  		$audio = "";
							  	}
							  	if($type=="1"){
							   		$option_ans = implode("#", $option);
							  	}
								$data = array(
											"category_id"=>$category,
											"subcategory_id"=>$subcategoty,
											"support_lang_id"=>$lang,
											"question_type"=>$type,
											"question"=>$question,
											"options"=>$option_ans,
											"notes"=>$notes,
											"is_audio_available"=>$is_video_available,
											"audio_file"=>$audio
										);
								$data['target_language_id'] = $this->session->userdata('support_lang_id');
								$insert = $this->admin_model->add_grammer($data);
								if($insert){
										$this->session->set_flashdata('sucess_msg','Question Inserted Successfully');
										redirect('admin_master/grammar_list', 'refresh');
								}
						}

		}else{

				redirect('admin_master/login', 'refresh');
		}
		
	}
	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for Edit Grammer question
   	*************************************
    */
	public function edit_grammar($id){

		//	echo "here"; die();
			if($this->session->userdata('logged_in'))
			{
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					$source_lang=$this->admin_model->get_source_lang();
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
					// $this->form_validation->set_rules('lang','lang','required');
					$this->form_validation->set_rules('question','Question','required');

						if($this->form_validation->run() == FALSE)
					    {
						    $data['edit_data'] = $this->admin_model->get_grammar_from_id($id);

						    //print_r($data['edit_data']); die();
							$data['category']=$this->admin_model->get_category_list('4');
					       	$data['subcategory'] = $this->admin_model->get_subcategory_list();
					        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
	  						$data['source_lang']=$this->admin_model->get_support_lang_new();
	  						$admin_data = $this->session->userdata('logged_in');
							$admin_language = $admin_data[0]['support_lang_ids'];
							$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
							$data['active_class']="grammar";
							$slang_name = $this->session->userdata('support_lang_name');
							$data['current_support_lang'] = $slang_name;
							$this->load->view('admin/header',$data);
							$this->load->view('admin/edit_grammar',$data);
							$this->load->view('admin/side_menu',$data);
							$this->load->view('admin/footer');

								
					    }else{

					    		$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								$lang = $this->input->post('lang');
								$type = $this->input->post('type');
								$question = $this->input->post('question');
								if(!empty($this->input->post('notes')))
								{
									$notes = $this->input->post('notes');
								}else{
									$notes = "";
								}
								
								if(!empty($this->input->post('audio_file')))
								{
									$audio = $this->input->post('audio_file');
								}else{
									$audio = "";
								}
								
							  	$option = $this->input->post('option[]');
							  	$option_ans = $this->input->post('correct_ans');
								if(!empty($audio))
							  	{
							  		$is_video_available = '1';
							  	}else{
							  		$is_video_available = '0';
							  		$audio = "";
							  	}	
							   if($type=="1"){

							   		$option_ans = implode("#", $option);
							   }
							   
									$data = array(
												
												"category_id"=>$category,
												"subcategory_id"=>$subcategoty,
												"support_lang_id"=>$lang,
												"question_type"=>$type,
												"question"=>$question,
												"options"=>$option_ans,
												"notes"=>$notes,
												"is_audio_available"=>$is_video_available,
												"audio_file"=>$audio
									
											);
										 	
								$data['target_language_id'] = $this->session->userdata('support_lang_id');
								$insert = $this->admin_model->update_grammar($data,$id);

									if($insert){
												
												$this->session->set_flashdata('sucess_msg','Question Updated Successfully');
												redirect('admin_master/grammar_list', 'refresh');
									}

										
						}

			}else{

					redirect('admin_master/login', 'refresh');
			}

	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for Delete Grammer Question
   	*************************************
    */
	public function delete_grammar(){
				$id = $this->uri->segment('3');
				$data = array('is_active'=>'0','is_delete'=>'1');
				$delete = $this->admin_model->delete_grammar($data,$id);
				if($delete){
					$this->session->set_flashdata('sucess_msg','Question Deleted Successfully');
					//redirect('admin_master/grammar_list', 'refresh');	
					header('Location: '.$_SERVER['HTTP_REFERER']);
				}
	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for Delete All Selected Grammar
   	*************************************
    */
	function delete_all_grammar(){

		$ids = $this->input->post('delete');
		//$id = $this->uri->segment('3');
		if(empty($ids)){
			$this->session->set_flashdata('error_msg','Please select at least one');
			//redirect('admin_master/grammar_list', 'refresh');	
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}else{
			foreach ($ids as $key) {
				$data = array('is_active'=>'0','is_delete'=>'1');
				$delete = $this->admin_model->delete_grammar($data,$key);
			}
			if($delete){
				$this->session->set_flashdata('sucess_msg','Question Deleted Successfully');
			//	redirect('admin_master/grammar_list', 'refresh');	
				header('Location: '.$_SERVER['HTTP_REFERER']);
			}
		}
	}
	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for Import Grammer questions
   	*************************************
    */
	public function grammar_import()
	{

		$path = FCPATH.'uploads/excel/';
		$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;

		$this->load->library('excel');//load PHPExcel library 
        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
        $configUpload['allowed_types'] = 'xls|xlsx|csv';
        $configUpload['max_size'] = '5000';
        // $this->load->library('upload', $configUpload);
         //$this->upload->do_upload('file');	
        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.

        $file_name = $_FILES["file"]["name"]; //uploded file name
		$extension=$upload_data['file_ext'];    // uploded file extension
		
		//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
		$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
          //Set to read only
        $objReader->setReadDataOnly(true); 		  
        //Load excel file
		PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
		$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestDataRow();   //Count Numbe of rows avalable in excel      	 
        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);  

        // if(($objWorksheet->getCellByColumnAndRow(1,1))!="Question"){
        	// $this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference');	 
            // redirect('admin_master/grammar_list', 'refresh');
		// }
        /*$worksheetData = $objReader->listWorksheetInfo(FCPATH.'uploads/excel/'.$file_name);
		$totalRows     = $worksheetData[0]['totalRows'];
		$totalColumns  = $worksheetData[0]['totalColumns'];*/
		
          //loop from first data untill last data
        for($i=2;$i<=$totalrows;$i++)
        {
    		//$data = array("category_id"=>$this->input->post('category'),"subcategory_name"=>$objWorksheet->getCellByColumnAndRow(0,$i),"image"=>$objWorksheet->getCellByColumnAndRow(1,$i),"difficulty_level_id"=>$objWorksheet->getCellByColumnAndRow(2,$i));
			$category = $this->input->post('category');
			$subcategoty = $this->input->post('subcategory');
			$lang = $this->input->post('lang');
			
			$data = array(
						"category_id"=>$category,
						"subcategory_id"=>$subcategoty,
						// "support_lang_id"=>$lang,
						"question_type"=>$objWorksheet->getCellByColumnAndRow(0,$i),
						"question"=>$objWorksheet->getCellByColumnAndRow(1,$i),
						"options"=>trim($objWorksheet->getCellByColumnAndRow(2,$i)),
						"notes"=>trim($objWorksheet->getCellByColumnAndRow(3,$i)),
						"audio_file"=>trim($objWorksheet->getCellByColumnAndRow(4,$i)),
						"is_audio_available"=>trim($objWorksheet->getCellByColumnAndRow(5,$i))
					);
			$data['target_language_id'] = $this->session->userdata('support_lang_id');
			$insert = $this->admin_model->add_grammer($data);
        }
        unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
        $this->session->set_flashdata('sucess_msg','Questions imported Successfully');	 
        redirect('admin_master/grammar_list', 'refresh');
    }
	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for import Phrase
   	*************************************
    */
	public function phrases_import()
	{

		$path = FCPATH.'uploads/excel/';
		$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;
		$this->load->library('excel');//load PHPExcel library 
		
        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
        $configUpload['allowed_types'] = 'xls|xlsx|csv';
        $configUpload['max_size'] = '5000';
        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.

        $file_name = $_FILES["file"]["name"]; //uploded file name
		$extension=$upload_data['file_ext'];    // uploded file extension
		
		//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
		$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
          //Set to read only
        $objReader->setReadDataOnly(true); 		  
        //Load excel file
		PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);

		$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);  

        // if(($objWorksheet->getCellByColumnAndRow(2,1))!="English" || empty($objWorksheet->getCellByColumnAndRow(1,1))){
			// $this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference ');	 
			// redirect('admin_master/subcategory_list', 'refresh');
		// }
              
        //loop from first data untill last data
        for($i=2;$i<=$totalrows;$i++)
        {
        	$trgt_lang_col = $objWorksheet->getCellByColumnAndRow(2,$i);
        	if(isset($trgt_lang_col) && !empty(trim($trgt_lang_col)) && strlen($trgt_lang_col) > 0)
        	{
        		$target_lang_id = $this->session->userdata('support_lang_id');
				$getLang = $this->admin_model->master_function_get_data_by_condition("tbl_source_language",array("source_language_id"=>$target_lang_id,"status"=>"1"));

				$category = $this->input->post('category');
				$subcategoty = $this->input->post('subcategory');
				$mode = $this->input->post('mode');
				$data = array(
								"category_id"=>$category,
								"subcategory_id"=>$subcategoty,
								"audio_file"=>strtolower(str_replace(" ", "_", $objWorksheet->getCellByColumnAndRow(0,$i))),
						);
				$data['support_lang_id'] = $this->session->userdata('support_lang_id');
				$source_lang=$this->admin_model->get_source_lang_by_target($target_lang_id);
				$ctn=0;
				foreach($source_lang as $langkey)
				{
					if($ctn==0){
						$j=1;

					}else{

						$j=$j+1;
					}
					$name=$objWorksheet->getCellByColumnAndRow($j,$i);
					$data['phrase_'.$langkey['language_code']] = $name;
					$ctn++;
				}

				$res = $this->admin_model->master_function_get_data_by_condition("tbl_phrases",array(
					"phrase_".$getLang[0]['language_code'] => $objWorksheet->getCellByColumnAndRow(1,$i),
					"category_id"=>$category,
					"subcategory_id"=>$subcategoty,
					"is_active"=>"1","is_delete"=>"0"));
				if(count($res) >= "1")
				{
					$update = $this->admin_model->master_function_for_update_by_conditions("tbl_phrases",array("phrase_".$getLang[0]['language_code'] => $objWorksheet->getCellByColumnAndRow(1,$i)),$data);
				}else{
					$insert = $this->admin_model->add_phrases($data);
				}	
			}  
        }
        unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
        $this->session->set_flashdata('sucess_msg','Words imported Successfully');	 
        redirect('admin_master/phrases_list', 'refresh');
    }

    /*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for phrases List
   	*************************************
    */

	public function phrases_list(){

		if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];

								// $this->session->unset_userdata('modeid');
 							// 	$this->session->unset_userdata('cateid');
 							// 	$this->session->unset_userdata('subcateid');

					 if($this->input->post()){

 								//$this->session->set_userdata('modeid',$this->input->post('mode_id'));
 								$this->session->set_userdata('cateid',$this->input->post('cate_id'));
 								$this->session->set_userdata('subcateid',$this->input->post('subcate_id'));
 								$this->session->set_userdata('sort',$this->input->post('sort'));
 								$this->session->set_userdata('per_page',$this->input->post('per_page'));
					 }

					// print_r($this->input->post());
					
					 //$mode =  $this->session->userdata('modeid');
					// $data['mode']=3; 

					
					$category = $this->session->userdata('cateid');
					$data['category_select']=$category;

					
					$subcategory = $this->session->userdata('subcateid');
					$data['subcategory_select']=$subcategory;

					$sort = $this->session->userdata('sort');
					$data['sort_select']=$sort;
		 

					/// for pagination
					$per_page = $this->session->userdata('per_page');
					$data['per_page_select']=$per_page;
		 

					/// for pagination
					if(!isset($per_page)){

						$per_page=100;
					}

					$config = array();
			       
			        $res = $this->admin_model->get_phrases_list($category,$subcategory);
			        $config["total_rows"] = count($res);
			        $config["per_page"] = $per_page;
			     

        			$config["uri_segment"] = 3;
        			$config["base_url"] = base_url() . "admin_master/phrases_list";
        			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		        			
		        	$config['full_tag_open'] = "<ul class='pagination pagination-small pagination-centered'>";
					$config['full_tag_close'] ="</ul>";
					$config['num_tag_open'] = '<li>';
					$config['num_tag_close'] = '</li>';
					$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
					$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
					$config['next_tag_open'] = "<li>";
					$config['next_tagl_close'] = "</li>";
					$config['prev_tag_open'] = "<li>";
					$config['prev_tagl_close'] = "</li>";
					$config['first_tag_open'] = "<li>";
					$config['first_tagl_close'] = "</li>";
					$config['last_tag_open'] = "<li>";
					$config['last_tagl_close'] = "</li>";

			        $this->pagination->initialize($config);
			        // var_dump($this->pagination);
			        // die();
			        //$data["results"] = $this->Countries->
			           // fetch_countries($config["per_page"], $page);
			        $data['words_list'] = $this->admin_model->get_phrases_list_pagination($config["per_page"], $page,$category,$subcategory);
			     //print_r($data['words_list']); die();
			        $data["links"] = $this->pagination->create_links();		
                    $data["page_info"] =  "Showing ".($config["per_page"])." of ".$config["total_rows"]." total results";
					//end pagination

					$data['success_msg']=$this->session->flashdata('sucess_msg');
					$data['error_msg']=$this->session->flashdata('error_msg');
					$data['category']=$this->admin_model->get_category_list(3);
			        $data['subcategory'] = $this->admin_model->get_subcategory_list($category);
			        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
			        // $data['source_lang']=$this->admin_model->get_source_lang();		// New Target change
					$target_lang_id = $this->session->userdata('support_lang_id');
					$data['source_lang'] = $this->admin_model->get_source_lang_by_target($target_lang_id);

			        $admin_data = $this->session->userdata('logged_in');
					$admin_language = $admin_data[0]['support_lang_ids'];
					$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
			        $slang_name = $this->session->userdata('support_lang_name');
					$data['current_support_lang'] = $slang_name;
					$data['active_class']="phrase";
					$this->load->view('admin/header',$data);
					$this->load->view('admin/phrases_list',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');

		}else{

			redirect('admin_master/login', 'refresh');

		}
	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for add new phrases
   	*************************************
    */

	public function add_phrases(){

		if ($this->session->userdata('logged_in')){
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					// $source_lang=$this->admin_model->get_source_lang();		// New Target change
					$target_lang_id = $this->session->userdata('support_lang_id');
					$source_lang= $this->admin_model->get_source_lang_by_target($target_lang_id);
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');

					foreach($source_lang as $key){
						$this->form_validation->set_rules('phrase_'.$key['language_code'],'Phrase in '.$key['language_name'],'required');
					}

						if($this->form_validation->run() == FALSE)
				       	{

						        $data['category']=$this->admin_model->get_category_list(3);
						        $data['subcategory'] = $this->admin_model->get_subcategory_list();
						        $data['success_msg']=$this->session->flashdata('insert_cat');
						        $data['error_msg']=$this->session->flashdata('error_upload');
						        $data['active_class']="phrase";
						        // $data['source_lang']=$this->admin_model->get_source_lang();		// New Target change
								$target_lang_id = $this->session->userdata('support_lang_id');
								$data['source_lang'] = $this->admin_model->get_source_lang_by_target($target_lang_id);
								$admin_data = $this->session->userdata('logged_in');
			$admin_language = $admin_data[0]['support_lang_ids'];
			$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
								$slang_name = $this->session->userdata('support_lang_name');
								$data['current_support_lang'] = $slang_name;
								$this->load->view('admin/header',$data);
								$this->load->view('admin/add_phrases',$data);
								$this->load->view('admin/side_menu',$data);
								$this->load->view('admin/footer');
							 
				     	}else{

								$category = $this->input->post('category');
								$subcategoty = $this->input->post('subcategory');
								$audio_name = $this->input->post('audio_name');
								
										$data = array(
												"category_id"=>$category,
												"subcategory_id"=>$subcategoty,
												"audio_file"=>$audio_name,
										);

									foreach($source_lang as $langkey){
										$name = $this->input->post('phrase_'.$langkey['language_code']);
										$data['phrase_'.$langkey['language_code']] = $name;
									}
										//print_r($data); die();
									$data['support_lang_id'] = $this->session->userdata('support_lang_id');
									$insert = $this->admin_model->add_phrases($data);
									//	echo "here"; die();
										if($insert){
											$this->session->set_flashdata('sucess_msg','Phrases Inserted Successfully');
											redirect('admin_master/phrases_list', 'refresh');
										}
						}

		}else{

				redirect('admin_master/login', 'refresh');
		}
		
	}
	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for Edit phrases
   	*************************************
    */
	public function edit_phrases($id){
			if($this->session->userdata('logged_in'))
			{
				$sessiondata = $this->session->userdata('logged_in');
				$data['useremail']=$sessiondata[0]['email'];
				$data['userefirst_name']=$sessiondata[0]['first_name'];
				$data['userelast_name']=$sessiondata[0]['last_name'];
				// $source_lang=$this->admin_model->get_source_lang();		// New Target change
				$target_lang_id = $this->session->userdata('support_lang_id');
				$source_lang= $this->admin_model->get_source_lang_by_target($target_lang_id);
				$this->form_validation->set_rules('category', 'Category', 'required');
				$this->form_validation->set_rules('subcategory','SubCategory','required');
					foreach($source_lang as $key){
						$this->form_validation->set_rules('phrase_'.$key['language_code'],'Phrase in '.$key['language_name'],'required');
					}
				if($this->form_validation->run() == FALSE)
			    {
				    $data['edit_data'] = $this->admin_model->get_phrases_from_id($id);
					$data['category']=$this->admin_model->get_category_list(3);
			       	$data['subcategory'] = $this->admin_model->get_subcategory_list();
			        // $data['source_lang']=$this->admin_model->get_source_lang();		// New Target change
					$target_lang_id = $this->session->userdata('support_lang_id');
					$data['source_lang'] = $this->admin_model->get_source_lang_by_target($target_lang_id);
					$admin_data = $this->session->userdata('logged_in');
					$admin_language = $admin_data[0]['support_lang_ids'];
					$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
					$data['active_class']="phrase";
					$slang_name = $this->session->userdata('support_lang_name');
					$data['current_support_lang'] = $slang_name;
					$this->load->view('admin/header',$data);
					$this->load->view('admin/edit_phrases',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');
			    }else{
			    		$category = $this->input->post('category');
						$subcategoty = $this->input->post('subcategory');
						$audio_name = $this->input->post('audio_name');
						$data = array(
										"category_id"=>$category,
										"subcategory_id"=>$subcategoty,
										"audio_file"=>$audio_name,
								);		 	
					foreach($source_lang as $langkey){

							$name = $this->input->post('phrase_'.$langkey['language_code']);
							$data['phrase_'.$langkey['language_code']] = $name;
					}
					$data['support_lang_id'] = $this->session->userdata('support_lang_id');
					$insert = $this->admin_model->update_phrase($data,$id);
					if($insert){
						$this->session->set_flashdata('sucess_msg','Phrase Updated Successfully');
						redirect('admin_master/phrases_list', 'refresh');
					}			
				}

			}else{

					redirect('admin_master/login', 'refresh');
			}

	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for Delete phrases
   	*************************************
    */
	public function delete_phrases(){
			$id = $this->uri->segment('3');
			$data = array('is_active'=>'0','is_delete'=>'1');
			$delete = $this->admin_model->delete_row_by_condition('tbl_phrases',$data,array('phrases_id'=>$id));
			if($delete){
				$this->session->set_flashdata('sucess_msg','Phrase Deleted Successfully');
			//	redirect('admin_master/phrases_list', 'refresh');	
				header('Location: '.$_SERVER['HTTP_REFERER']);
			}
	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for Delete all Selected phrases
   	*************************************
    */
	function delete_all_phrases(){
		$ids = $this->input->post('delete');
		if(empty($ids)){
				$this->session->set_flashdata('error_msg','Please select at least one');
				//redirect('admin_master/phrases_list', 'refresh');	
				header('Location: '.$_SERVER['HTTP_REFERER']);
		}else{
				foreach ($ids as $key) {
					$data = array('is_active'=>'0','is_delete'=>'1');
					$delete = $this->admin_model->delete_row_by_condition('tbl_phrases',$data,array('phrases_id'=>$key));
				}
				if($delete){
					$this->session->set_flashdata('sucess_msg','Phrases Deleted Successfully');
				//	redirect('admin_master/phrases_list', 'refresh');	
					header('Location: '.$_SERVER['HTTP_REFERER']);
				}
		}	
	}
	
	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for import culture
   	*************************************
    */
	public function culture_import(){

				$path = FCPATH.'uploads/excel/';
				$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;
				$this->load->library('excel');//load PHPExcel library 
		        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
		        $configUpload['allowed_types'] = 'xls|xlsx|csv';
		        $configUpload['max_size'] = '5000';	
		        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
		        $file_name = $_FILES["file"]["name"]; //uploded file name
				$extension=$upload_data['file_ext'];    // uploded file extension
				$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
		        $objReader->setReadDataOnly(true); 		  
				PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
				$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
		        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
		        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);  
		        // if(($objWorksheet->getCellByColumnAndRow(1,1))!="Title" && ($objWorksheet->getCellByColumnAndRow(0,2))!=" "){
		        	// $this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference ');	 
		            // redirect('admin_master/culture_list', 'refresh');
        		// }      
        		$audio_list_array = array();
        		$insert= 0;
		        for($i=2;$i<=$totalrows;$i++)
		        {
		        	$category = $this->input->post('category');
					$subcategoty = $this->input->post('subcategory');
					$lang = $this->input->post('lang');

					if($objWorksheet->getCellByColumnAndRow(0,$i)!=" " && $objWorksheet->getCellByColumnAndRow(0,$i)!=""){
						$data = array(
									"title_text"=>$objWorksheet->getCellByColumnAndRow(1,$i),
									"external_link"=>$objWorksheet->getCellByColumnAndRow(2,$i),
									"paragraph"=>$objWorksheet->getCellByColumnAndRow(3,$i),
									"image_name"=>strtolower(str_replace(" ", "_", $objWorksheet->getCellByColumnAndRow(4,$i))),
									"support_lang_id"=>$lang,
									"category_id"=>$category,
									"subcategory_id"=>$subcategoty		
							);	
						$res = $this->admin_model->master_function_get_data_by_condition("tbl_culture_master",array(
							"title_text"=>$objWorksheet->getCellByColumnAndRow(1,$i),
							"category_id"=>$category,
							"subcategory_id"=>$subcategoty,
							"is_active"=>"1"
						));
						if(count($res)== "0"){
							$data['target_language_id'] = $this->session->userdata('support_lang_id');
							$insert = $this->admin_model->add_culture_master($data);
						}else{
							$data['target_language_id'] = $this->session->userdata('support_lang_id');
							$insert = $res[0]['culture_master_id'];
							$update = $this->admin_model->master_function_for_update_by_conditions("tbl_culture_master",array("title_text"=>$objWorksheet->getCellByColumnAndRow(1,$i)),$data);
						}

						// if(count($res)== "0"){
						// 	$data = array(
						// 				"culture_master_id"=>$insert,
						// 				"support_lang_id"=>$this->session->userdata('support_lang_id'),
						// 				"question"=>$objWorksheet->getCellByColumnAndRow(5,$i),
						// 				"options"=>$objWorksheet->getCellByColumnAndRow(6,$i),
						// 				"notes"=>$objWorksheet->getCellByColumnAndRow(7,$i),
						// 			);	
						// 	$insert_list = $this->admin_model->add_culture_que($data);
						// }else{
						// }	
					}

					if( !empty(trim($objWorksheet->getCellByColumnAndRow(5,$i))) && !empty(trim($objWorksheet->getCellByColumnAndRow(6,$i))) && !empty(trim($insert)) ){
						$data = array(
									"culture_master_id"=>$insert,
									"support_lang_id"=>$this->session->userdata('support_lang_id'),
									"question"=>$objWorksheet->getCellByColumnAndRow(5,$i),
									"options"=>$objWorksheet->getCellByColumnAndRow(6,$i),
									"notes"=>$objWorksheet->getCellByColumnAndRow(7,$i),
								);	
						$culq = $this->admin_model->master_function_get_data_by_condition("tbl_culture_question",array("question"=>$objWorksheet->getCellByColumnAndRow(5,$i),"culture_master_id"=>$insert,'support_lang_id' => $this->session->userdata('support_lang_id')));
						if(count($culq)== "0"){
							$insert_list = $this->admin_model->add_culture_que($data);
						}else{
							$update_list = $this->admin_model->update_culture_question($data,$culq[0]['culture_question_id']);
						}
					}
				}
	        unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
	        $this->session->set_flashdata('sucess_msg','Culture imported Successfully');	 
	        redirect('admin_master/culture_list', 'refresh');
    }
	
	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for culture list
   	*************************************
    */
	public function culture_list(){

		if($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
		   if($this->input->post()){
				$this->session->set_userdata('lang',$this->input->post('lang'));
				$this->session->set_userdata('cateid',$this->input->post('cate_id'));
				$this->session->set_userdata('subcateid',$this->input->post('subcate_id'));
				$this->session->set_userdata('sort',$this->input->post('sort'));
		    }
			$lang =  $this->session->userdata('lang');
			$data['lang']=$lang; 
			$category = $this->session->userdata('cateid');
			$data['category_select']=$category;
			$subcategory = $this->session->userdata('subcateid');
			$data['subcategory_select']=$subcategory;
			$sort = $this->session->userdata('sort');
			$data['sort_select']=$sort;
			/// for pagination
			$config = array();
	        $res = $this->admin_model->get_culture_list($lang,$category,$subcategory);
	        $config["total_rows"] = count($res);
	        $config["per_page"] = 10;
			$config["uri_segment"] = 3;
			$config["base_url"] = base_url() . "admin_master/culture_list";
			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        	$config['full_tag_open'] = "<ul class='pagination pagination-small pagination-centered'>";
			$config['full_tag_close'] ="</ul>";
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
			$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
			$config['next_tag_open'] = "<li>";
			$config['next_tagl_close'] = "</li>";
			$config['prev_tag_open'] = "<li>";
			$config['prev_tagl_close'] = "</li>";
			$config['first_tag_open'] = "<li>";
			$config['first_tagl_close'] = "</li>";
			$config['last_tag_open'] = "<li>";
			$config['last_tagl_close'] = "</li>";
	        $this->pagination->initialize($config);
	        $data['grammer_list'] = $this->admin_model->get_culture_list_pagination($config["per_page"], $page,$lang,$category,$subcategory);
	        $data["links"] = $this->pagination->create_links();		
            $data["page_info"] =  "Showing ".($config["per_page"])." of ".$config["total_rows"]." total results";
			//end pagination
            $mode = "5";
			$data['success_msg']=$this->session->flashdata('sucess_msg');
			$data['error_msg']=$this->session->flashdata('error_msg');
			$data['category']=$this->admin_model->get_category_list($mode);
	        $data['subcategory'] = $this->admin_model->get_subcategory_list($category);
	        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
	        $data['source_lang']=$this->admin_model->get_support_lang_new();
	        $admin_data = $this->session->userdata('logged_in');
			$admin_language = $admin_data[0]['support_lang_ids'];
			$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
	        $slang_name = $this->session->userdata('support_lang_name');
			$data['current_support_lang'] = $slang_name;
			$data['active_class']="culture";
			$data['target_language_id'] = $this->session->userdata('support_lang_id');
			$this->load->view('admin/header',$data);
			$this->load->view('admin/culture_list',$data);
			$this->load->view('admin/side_menu',$data);
			$this->load->view('admin/footer');
		}else{

			redirect('admin_master/login', 'refresh');

		}
	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for add new culture
   	*************************************
    */
	public function add_culture(){

		if ($this->session->userdata('logged_in')){
					$sessiondata = $this->session->userdata('logged_in');
					$data['useremail']=$sessiondata[0]['email'];
					$data['userefirst_name']=$sessiondata[0]['first_name'];
					$data['userelast_name']=$sessiondata[0]['last_name'];
					$source_lang=$this->admin_model->get_source_lang();
					$this->form_validation->set_rules('category', 'Category', 'required');
					$this->form_validation->set_rules('subcategory','SubCategory','required');
					$this->form_validation->set_rules('title','Title','required');
					$this->form_validation->set_rules('para','paragraph','required');
					$this->form_validation->set_rules('link','Link','required');
					$this->form_validation->set_rules('title','Title','required');
					$this->form_validation->set_rules('image_name','Image Name','required');
					$this->form_validation->set_rules('question[]','Question','required');
					// $this->form_validation->set_rules('lang','Language','required');
					if($this->form_validation->run() == FALSE)
				    {
				        $data['category']=$this->admin_model->get_category_list('5');
				        $data['subcategory'] = $this->admin_model->get_subcategory_list();
				        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
				        $data['success_msg']=$this->session->flashdata('insert_cat');
				        $data['error_msg']=$this->session->flashdata('error_upload');
				        $data['active_class']="culture";
						$data['source_lang']=$this->admin_model->get_support_lang_new();
						$admin_data = $this->session->userdata('logged_in');
						$admin_language = $admin_data[0]['support_lang_ids'];
						$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
						$slang_name = $this->session->userdata('support_lang_name');
						$data['current_support_lang'] = $slang_name;
						$this->load->view('admin/header',$data);
						$this->load->view('admin/add_culture',$data);
						$this->load->view('admin/side_menu',$data);
						$this->load->view('admin/footer');
							
				    }else{

						$category = $this->input->post('category');
						$subcategoty = $this->input->post('subcategory');
						$lang = $this->input->post('lang');
						$title = $this->input->post('title');
					  	$image_name = $this->input->post('image_name');
					  	
					  	$para = $this->input->post('para');
					  	$link = $this->input->post('link');
					  	$question = $this->input->post('question[]');
					  	$notes = $this->input->post('notes[]');
					  	$option1 = $this->input->post('option1[]');
					  	$option2 = $this->input->post('option2[]');
					  	$option3 = $this->input->post('option3[]');
					  	$option4 = $this->input->post('option4[]');
						$data = array(			
									"title_text"=>$title,
									"external_link"=>$link,
									"paragraph"=>$para,
									"image_name"=>$image_name,
									"support_lang_id"=>$lang,
									"category_id"=>$category,
									"subcategory_id"=>$subcategoty	
								);
						$data['target_language_id'] = $this->session->userdata('support_lang_id');
						$insert = $this->admin_model->add_culture_master($data);
						if($insert){
							for($i=0;$i < count($question);$i++){
								$data1 = array(
											"culture_master_id"=>$insert,
											"question"=>$question[$i],
											"options"=>$option1[$i].'#'.$option2[$i].'#'.$option3[$i].'#'.$option4[$i],
											"notes"=>$notes[$i]
										);
								$data1['support_lang_id'] = $this->session->userdata('support_lang_id');	
								$insert_list = $this->admin_model->add_culture_que($data1);
							}
							$this->session->set_flashdata('sucess_msg','Culture Inserted Successfully');
							redirect('admin_master/culture_list', 'refresh');
						}
					}
		}else{
				redirect('admin_master/login', 'refresh');
		}
	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for edit culture
   	*************************************
    */
	public function edit_culture($id){
			if($this->session->userdata('logged_in'))
			{
				$sessiondata = $this->session->userdata('logged_in');
				$data['useremail']=$sessiondata[0]['email'];
				$data['userefirst_name']=$sessiondata[0]['first_name'];
				$data['userelast_name']=$sessiondata[0]['last_name'];
				$source_lang=$this->admin_model->get_source_lang();
				$this->form_validation->set_rules('category', 'Category', 'required');
				$this->form_validation->set_rules('subcategory','SubCategory','required');
				if($this->form_validation->run() == FALSE)
			    {
				    $data['edit_data'] = $this->admin_model->get_culture_from_id($id);
				    $data['edit_data_list'] = $this->admin_model->get_culture_list_from_id($id);
					$data['category']=$this->admin_model->get_category_list('5');
			       	$data['subcategory'] = $this->admin_model->get_subcategory_list();
			        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
					$data['source_lang']=$this->admin_model->get_support_lang_new();
					$admin_data = $this->session->userdata('logged_in');
			$admin_language = $admin_data[0]['support_lang_ids'];
			$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
					$data['active_class']="culture";
					$slang_name = $this->session->userdata('support_lang_name');
						$data['current_support_lang'] = $slang_name;
					$this->load->view('admin/header',$data);
					$this->load->view('admin/edit_culture',$data);
					$this->load->view('admin/side_menu',$data);
					$this->load->view('admin/footer');
						
			    }else{ 
		    		$category = $this->input->post('category');
					$subcategoty = $this->input->post('subcategory');
					$lang = $this->input->post('lang');
					$title = $this->input->post('title');
				  	$image_name = $this->input->post('image_name');
				  	$notes = $this->input->post('notes[]');
				  	$para = $this->input->post('para');
				  	$link = $this->input->post('link');
				  	$question = $this->input->post('question[]');
				  	$option1 = $this->input->post('option1[]');
				  	$option2 = $this->input->post('option2[]');
				  	$option3 = $this->input->post('option3[]');
				  	$option4 = $this->input->post('option4[]');
					$data = array(
									"title_text"=>$title,
									"external_link"=>$link,
									"paragraph"=>$para,
									"image_name"=>$image_name,
									"support_lang_id"=>$lang,
									"category_id"=>$category,
									"subcategory_id"=>$subcategoty	
							);
					$data['target_language_id'] = $this->session->userdata('support_lang_id');
					$insert = $this->admin_model->update_culture($data,$id);
					if($insert){
								$delete_list = $this->admin_model->delete_culture_question($id);
								for($i=0;$i < count($question);$i++){
									$data1 = array(
												"culture_master_id"=>$id,
												"question"=>$question[$i],
												"options"=>$option1[$i].'#'.$option2[$i].'#'.$option3[$i].'#'.$option4[$i],
												"notes"=>$notes[$i]
											);
									$data1['support_lang_id'] = $this->session->userdata('support_lang_id');	
									$insert_list = $this->admin_model->add_culture_que($data1);
								}
						$this->session->set_flashdata('sucess_msg','Culture Updated Successfully');
						redirect('admin_master/culture_list', 'refresh');
					}			
				}

			}else{
					redirect('admin_master/login', 'refresh');
			}
	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for delete culture
   	*************************************
    */

	public function delete_culture(){
			$id = $this->uri->segment('3');
			$data = array('is_active'=>'0','is_delete'=>'1');
			$delete = $this->admin_model->delete_culture($data,$id);
			$delete_list = $this->admin_model->delete_culture_question($id);
			if($delete){
				$this->session->set_flashdata('sucess_msg','culture Deleted Successfully');
				//redirect('admin_master/culture_list', 'refresh');	
				header('Location: '.$_SERVER['HTTP_REFERER']);
			}
	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for delete all selected culture
   	*************************************
    */
	function delete_all_culture(){
		$ids = $this->input->post('delete');
		if(empty($ids)){
			$this->session->set_flashdata('error_msg','Please select at least one');
			//redirect('admin_master/culture_list', 'refresh');	
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}else{
				foreach ($ids as $key) {
					$data = array('is_active'=>'0','is_delete'=>'1');
					$delete = $this->admin_model->delete_culture($data,$key);
					$delete_list = $this->admin_model->delete_culture_question($key);
				}
			if($delete){
				$this->session->set_flashdata('sucess_msg','culture Deleted Successfully');
			//	redirect('admin_master/culture_list', 'refresh');	
				header('Location: '.$_SERVER['HTTP_REFERER']);
			}
		}
					
	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for edit profile of admin
   	*************************************
    */
	public function edit_profile(){

		if($this->session->userdata('logged_in'))
		{
				$sessiondata = $this->session->userdata('logged_in');
				$data['useremail']=$sessiondata[0]['email'];
				$data['userefirst_name']=$sessiondata[0]['first_name'];
				$data['userelast_name']=$sessiondata[0]['last_name'];
				$data['userpass']=$sessiondata[0]['password'];
				$userid= $sessiondata[0]['user_id'];
				$data['success_msg']=$this->session->flashdata('sucess_msg');
				$source_lang=$this->admin_model->get_source_lang();
				$this->form_validation->set_rules('first_name', 'Category', 'required');
				$this->form_validation->set_rules('last_name','SubCategory','required');
				$this->form_validation->set_rules('email','SubCategory','required');
					if($this->input->post('old_pass')!="" || $this->input->post('new_pass')!="" || $this->input->post('c_new_pass')!=""){
						$this->form_validation->set_rules('old_pass','Old password','callback_oldpass_check');
						$this->form_validation->set_rules('new_pass','New Password','required');
						$this->form_validation->set_rules('c_new_pass','Confim Password','trim|matches[new_pass]|required');
					}
					$admin_data = $this->session->userdata('logged_in');
			$admin_language = $admin_data[0]['support_lang_ids'];
			$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
					$slang_name = $this->session->userdata('support_lang_name');
						$data['current_support_lang'] = $slang_name;
					if($this->form_validation->run() == FALSE)
				    {
						$data['active_class']="";
						$this->load->view('admin/header',$data);
						$this->load->view('admin/edit_profile',$data);
						$this->load->view('admin/side_menu',$data);
						$this->load->view('admin/footer');
					}else{
				    	$first_name = $this->input->post('first_name');
				    	$last_name = $this->input->post('last_name');
				    	$email = $this->input->post('email');
				    	$new_pass = $this->input->post('new_pass');
						$data = array(			
										"first_name"=>$first_name,
										"last_name"=>$last_name,
										"email"=>$email,	
									);
							if($new_pass!=""){
								$data['password']=md5($new_pass);
							}
						$insert = $this->admin_model->update_profile($data,$userid);

							if($insert){
								$data = array('user_id'=>$userid);
								$sessiondata = $this->admin_model->master_function_get_data_by_condition('tbl_users',$data);
								$this->session->set_userdata('logged_in',$sessiondata);
								$this->session->set_flashdata('sucess_msg','Profile Updated Successfully');
								redirect('admin_master/edit_profile', 'refresh');
							}			
					}

		}else{

				redirect('admin_master/login', 'refresh');
		}

	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for checking old password
   	*************************************
    */
	public function oldpass_check($str)
    {

    	if($this->session->userdata('logged_in'))
		{
			$sessiondata = $this->session->userdata('logged_in');
			$password = $sessiondata[0]['password'];
                if (md5($str) != $password)
                {
                        $this->form_validation->set_message('oldpass_check', 'Old password not matched');
                        return FALSE;
                }
                else
                {
                        return TRUE;
                }
        }
    }
    /*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for export category
   	*************************************
    */
	public function excel_export_category()
    {			
    			$newarray_code = array();
                $newarray_name = array();
                $res = $this->db->query("select language_code,language_name from tbl_source_language where isinput = '1' OR status ='1' ")->result_array();
		               foreach ($res as $key) {
		              		$newarray[]="`category_name_in_".$key['language_code']."`";
		              		$newarray_name[] = $key['language_name'];
		               }

    			$this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Category');
                //set cell A1 content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', 'image_name');
                $this->excel->getActiveSheet()->setCellValue('B1', 'English');
                $this->excel->getActiveSheet()->setCellValue('C1', 'Finnish');
                $this->excel->getActiveSheet()->setCellValue('D1', 'Swedish');
                $this->excel->getActiveSheet()->setCellValue('E1', 'Spanish');
                $this->excel->getActiveSheet()->setCellValue('F1', 'Norwegian');
                $this->excel->getActiveSheet()->setCellValue('G1', 'Scots');
                $this->excel->getActiveSheet()->setCellValue('H1', 'Galiec');
                $this->excel->getActiveSheet()->setCellValue('I1', 'Northern Saami');
                $this->excel->getActiveSheet()->setCellValue('J1', 'Cornish');
                $this->excel->getActiveSheet()->setCellValue('K1', 'Galician');
                $this->excel->getActiveSheet()->setCellValue('L1', 'Basque');
                /*$this->excel->getActiveSheet()->setCellValue('M1', 'Serbian');
                $this->excel->getActiveSheet()->setCellValue('N1', 'Croatian');
                $this->excel->getActiveSheet()->setCellValue('O1', 'Bulgarian');
                $this->excel->getActiveSheet()->setCellValue('P1', 'Chinese');
                $this->excel->getActiveSheet()->setCellValue('Q1', 'Hungarian');
                $this->excel->getActiveSheet()->setCellValue('R1', 'Sorani');
                $this->excel->getActiveSheet()->setCellValue('S1', 'Punjabi');
                $this->excel->getActiveSheet()->setCellValue('T1', 'Somali');
                $this->excel->getActiveSheet()->setCellValue('U1', 'Tigrigna');
                $this->excel->getActiveSheet()->setCellValue('V1', 'Urdu');
                $this->excel->getActiveSheet()->setCellValue('W1', 'Polish');
                $this->excel->getActiveSheet()->setCellValue('X1', 'Turkish');
                $this->excel->getActiveSheet()->setCellValue('Y1', 'Romanian');
                $this->excel->getActiveSheet()->setCellValue('Z1', 'Ukrainian');
                $this->excel->getActiveSheet()->setCellValue('AA1', 'Albanian');*/
                //merge cell A1 until C1
                $this->excel->getActiveSheet()->getStyle('A1:AA1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1:AA1')->getFont()->setBold(true);
          
		       for($col = ord('A'); $col <= ord('C'); $col++){ 
		                 //change the font size
		                $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
		                $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		        }

		        $all_fields = implode(',', $newarray); 
                $mid = $this->uri->segment('3');
                $support_lang_id = $this->session->userdata('support_lang_id');
 				$query = "SELECT image,$all_fields from tbl_exercise_mode_categories WHERE is_active='1' AND is_delete='0' AND support_lang_id=".$support_lang_id."";
              
               if($mid!=""){
               	 $query .= " AND  exercise_mode_id='$mid'";
               }
              	
                $rs = $this->db->query($query);
                $exceldata= array();
		        foreach ($rs->result_array() as $row){
		                $exceldata[] = $row;
		        }
		        
                //Fill data 
                $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');
                $filename='category.xls';
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); 
                header('Cache-Control: max-age=0'); //no cache
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                $objWriter->save('php://output');
                 
    }
    /*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for export sub-category
   	*************************************
    */

    public function excel_export_subcategory()
    {			

    			$newarray_code = array();
                $newarray_name = array();
                $res = $this->db->query("select language_code,language_name from tbl_source_language WHERE isinput = '1' OR status ='1' ")->result_array();
                
		               foreach ($res as $key) {
		              		$newarray[]="`subcategory_name_in_".$key['language_code']."`";
		              		$newarray_name[] = $key['language_name'];
		               }

    			$this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('SubCategory');
                //set cell A1 content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', 'image_name');
                $this->excel->getActiveSheet()->setCellValue('B1', 'English');
                $this->excel->getActiveSheet()->setCellValue('C1', 'Finnish');
                $this->excel->getActiveSheet()->setCellValue('D1', 'Swedish');
                $this->excel->getActiveSheet()->setCellValue('E1', 'Spanish');
                $this->excel->getActiveSheet()->setCellValue('F1', 'Norwegian');
                $this->excel->getActiveSheet()->setCellValue('G1', 'Scots');
                $this->excel->getActiveSheet()->setCellValue('H1', 'Galiec');
                $this->excel->getActiveSheet()->setCellValue('I1', 'Northern Saami');
                $this->excel->getActiveSheet()->setCellValue('J1', 'Cornish');
                $this->excel->getActiveSheet()->setCellValue('K1', 'Galician');
                $this->excel->getActiveSheet()->setCellValue('L1', 'Basque');
                //merge cell A1 until C1
                $this->excel->getActiveSheet()->getStyle('A1:AA1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1:AA1')->getFont()->setBold(true);
		       	for($col = ord('A'); $col <= ord('C'); $col++){
		                 //change the font size
		                $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
		                 
		                $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		        }
               
               $all_fields = implode(',', $newarray); 
		       $cid = $this->uri->segment('4');
		       $support_lang_id = $this->session->userdata('support_lang_id');
		       $query="SELECT image, $all_fields from tbl_exercise_mode_subcategories WHERE is_active='1' AND is_delete='0' AND support_lang_id=".$support_lang_id."";
               if($cid !=""){
               		$query .= " AND category_id=$cid";
               }
                $rs = $this->db->query($query);
               
                $exceldata= array();
		        foreach ($rs->result_array() as $row){
		                $exceldata[] = $row;
		        }
                //Fill data 
                $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3'); 
                $filename='SubCategory.xls'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                $objWriter->save('php://output');
                 
    }

    /*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for export vocabulary words
   	*************************************
    */
    public function excel_export_vocabulary()
    {			

    			$newarray_code = array();
                $newarray_name = array();
                $res = $this->db->query("select language_code,language_name,field_name from tbl_source_language where isinput='1' OR status ='1' ")->result_array();
		               foreach ($res as $key) {
		              		$newarray[]=$key['field_name'];
		              		$newarray_name[] = $key['language_name'];
		               }
				$this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('vocabulary');
                //set cell A1 content with some text 
                $this->excel->getActiveSheet()->setCellValue('A1', 'image_name');
                $this->excel->getActiveSheet()->setCellValue('B1', 'Audio_name');
                $this->excel->getActiveSheet()->setCellValue('C1', 'is_audio_avalible');
                $this->excel->getActiveSheet()->setCellValue('D1', 'is_image_avalible');
                $this->excel->getActiveSheet()->setCellValue('B1', 'English');
                $this->excel->getActiveSheet()->setCellValue('C1', 'Finnish');
                $this->excel->getActiveSheet()->setCellValue('D1', 'Swedish');
                $this->excel->getActiveSheet()->setCellValue('E1', 'Spanish');
                $this->excel->getActiveSheet()->setCellValue('F1', 'Norwegian');
                $this->excel->getActiveSheet()->setCellValue('G1', 'Scots');
                $this->excel->getActiveSheet()->setCellValue('H1', 'Galiec');
                $this->excel->getActiveSheet()->setCellValue('I1', 'Northern Saami');
                $this->excel->getActiveSheet()->setCellValue('J1', 'Cornish');
                $this->excel->getActiveSheet()->setCellValue('K1', 'Galician');
                $this->excel->getActiveSheet()->setCellValue('L1', 'Basque');
                //merge cell A1 until C1
                $this->excel->getActiveSheet()->getStyle('A1:AD1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1:AD1')->getFont()->setBold(true);
          
		       for($col = ord('A'); $col <= ord('C'); $col++){
		                $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
		                $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		        }
		        $cid = $this->uri->segment('3');
		        $scid = $this->uri->segment('4');
		        $all_fields = implode(',', $newarray); 
		        $support_lang_id = $this->session->userdata('support_lang_id');

		        $query="SELECT image_file,audio_file,is_audio_available,is_image_available,$all_fields from tbl_word WHERE is_active='1' AND support_lang_id=".$support_lang_id."";
             
               $filename='vocabulary'; 
               if($cid!=""){

		               	$query .= " AND category_id='$cid'";
						$sql = "SELECT category_name_in_en FROM tbl_exercise_mode_categories WHERE exercise_mode_category_id='$cid'";
		           		$rs1 = $this->db->query($sql)->row();
		           		$cat =$rs1->category_name_in_en;
		           		$cat = str_replace(" ","-",$cat);
		           		$filename .= "_$cat";
               } 
               if($scid!=""){

               		 $query .= " AND subcategory_id='$scid'";
               		 $sql = "SELECT subcategory_name_in_en FROM tbl_exercise_mode_subcategories WHERE exercise_mode_subcategory_id='$scid'";
               		  $rs1 = $this->db->query($sql)->row();
               		  $subcat =$rs1->subcategory_name_in_en;
               		  $subcat = str_replace(" ","-",$subcat);
               		  $filename .= "_$subcat";

               } 
                $rs = $this->db->query($query);
                $exceldata = array();
		        foreach ($rs->result_array() as $row){
		                $exceldata[] = $row;
		        }
                //Fill data                 
                $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');
                $filename .='.xls'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                $objWriter->save('php://output');
                 
    }
    /*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for export phrases
   	*************************************
    */
    public function excel_export_phrase()
    {			

    		$newarray_code = array();
            $newarray_name = array();
            $res = $this->db->query("select language_code,language_name from tbl_source_language where isinput = '1' OR status ='1'")->result_array();
	           foreach ($res as $key) {
	          		$newarray[]="phrase_".$key['language_code'];
	          		$newarray_name[] = $key['language_name'];
	           }

			$this->load->library('excel');
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Phrases');
            $this->excel->getActiveSheet()->setCellValue('A1', 'Audio_name');
        	$this->excel->getActiveSheet()->setCellValue('B1', 'English');
                $this->excel->getActiveSheet()->setCellValue('C1', 'Finnish');
                $this->excel->getActiveSheet()->setCellValue('D1', 'Swedish');
                $this->excel->getActiveSheet()->setCellValue('E1', 'Spanish');
                $this->excel->getActiveSheet()->setCellValue('F1', 'Norwegian');
                $this->excel->getActiveSheet()->setCellValue('G1', 'Scots');
                $this->excel->getActiveSheet()->setCellValue('H1', 'Galiec');
                $this->excel->getActiveSheet()->setCellValue('I1', 'Northern Saami');
                $this->excel->getActiveSheet()->setCellValue('J1', 'Cornish');
                $this->excel->getActiveSheet()->setCellValue('K1', 'Galician');
                $this->excel->getActiveSheet()->setCellValue('L1', 'Basque');
            $this->excel->getActiveSheet()->getStyle('A1:AA1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //make the font become bold
            $this->excel->getActiveSheet()->getStyle('A1:AA1')->getFont()->setBold(true);
				for($col = ord('A'); $col <= ord('C'); $col++){
	                $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
	                $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        		}
            $cid = $this->uri->segment('3');
		    $scid = $this->uri->segment('4');
		    $all_fields = implode(',', $newarray);
		    $support_lang_id = $this->session->userdata('support_lang_id'); 
		    $query="SELECT audio_file,$all_fields from tbl_phrases WHERE is_active='1' AND is_delete='0' AND support_lang_id=".$support_lang_id."";
            $filename='phrase';
            if($cid!=""){
               		$query .= " AND category_id='$cid'";
               		$sql = "SELECT category_name_in_en FROM tbl_exercise_mode_categories WHERE exercise_mode_category_id='$cid'";
               		$rs1 = $this->db->query($sql)->row();
               		$cat =$rs1->category_name_in_en;
               		$cat = str_replace(" ","-",$cat);
               		$filename .= "_$cat";
            } 
            if($scid!=""){
               		$query .= " AND subcategory_id='$scid'";
               		$sql = "SELECT subcategory_name_in_en FROM tbl_exercise_mode_subcategories WHERE exercise_mode_subcategory_id='$scid'";
               		$rs1 = $this->db->query($sql)->row();
               		$subcat =$rs1->subcategory_name_in_en;
               		$subcat = str_replace(" ","-",$subcat);
               		$filename .= "_$subcat"; 		  
            } 
            $rs = $this->db->query($query);
            $exceldata= array();
		        foreach ($rs->result_array() as $row){
		                $exceldata[] = $row;
		        }
            //Fill data 
            $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');
            $filename .='.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); 
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
            $objWriter->save('php://output'); 
    }
    /*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for export grammar
   	*************************************
    */
    public function excel_export_grammer()
    {
				$this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Countries');
                $this->excel->getActiveSheet()->setCellValue('A1', 'Type');
                $this->excel->getActiveSheet()->setCellValue('B1', 'Question');
                $this->excel->getActiveSheet()->setCellValue('C1', 'Option');
                $this->excel->getActiveSheet()->setCellValue('D1', 'Notes');
                $this->excel->getActiveSheet()->setCellValue('E1', 'Audio File');
                $this->excel->getActiveSheet()->setCellValue('F1', 'Is Audio Available');
                $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
			       for($col = ord('A'); $col <= ord('C'); $col++){
			                 //change the font size
			                $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
			                $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			        }              
			    $land = $this->uri->segment('3');
			    $cid = $this->uri->segment('4');
			    $scid = $this->uri->segment('5');
		    	$support_lang_id = $this->session->userdata('support_lang_id'); 

			    $query="SELECT question_type,question,options,notes,audio_file,is_audio_available from tbl_grammer_master WHERE is_active='1' AND is_delete='0' AND target_language_id=".$support_lang_id."";
	            $filename='grammar'; 

		           if($land !=""){

		           			$query .= " AND support_lang_id='$land'";
		           }
		            if($cid!=""){
		               		$query .= " AND category_id='$cid'";
							$sql = "SELECT category_name_in_en FROM tbl_exercise_mode_categories WHERE exercise_mode_category_id='$cid'";
		               		$rs1 = $this->db->query($sql)->row();
		               		$cat =$rs1->category_name_in_en;
		               		$cat = str_replace(" ","-",$cat);
		               	    $filename .= "_$cat";
		           } 
		           if($scid!=""){
		               		$query .= " AND subcategory_id='$scid'";
		               		$sql = "SELECT subcategory_name_in_en FROM tbl_exercise_mode_subcategories WHERE exercise_mode_subcategory_id='$scid'";
		           		    $rs1 = $this->db->query($sql)->row();
		               		$subcat =$rs1->subcategory_name_in_en;
		               		$subcat = str_replace(" ","-",$subcat);
		               		$filename .= "_$subcat";
		           } 
                $rs = $this->db->query($query);
                $exceldata= array();
		        foreach ($rs->result_array() as $row){
		                $exceldata[] = $row;
		        }
                //Fill data 
                $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');
                $filename .='.xls';//save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0');
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                $objWriter->save('php://output');
    }
    /*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for export culture
   	*************************************
    */
    public function excel_export_culture()
    {
				$this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Countries');
                $this->excel->getActiveSheet()->setCellValue('A1', 'Title');
                $this->excel->getActiveSheet()->setCellValue('B1', 'external_link');
                $this->excel->getActiveSheet()->setCellValue('C1', 'paragraph');
                $this->excel->getActiveSheet()->setCellValue('D1', 'Image');
                $this->excel->getActiveSheet()->setCellValue('E1', 'question');
                $this->excel->getActiveSheet()->setCellValue('F1', 'option');
                $this->excel->getActiveSheet()->setCellValue('G1', 'notes');
                $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
			    for($col = ord('A'); $col <= ord('C'); $col++){ 
			                 //change the font size
	                $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
	                $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			    }
       		   $land = $this->uri->segment('3');
		       $cid = $this->uri->segment('4');
		       $scid = $this->uri->segment('5');
		       $support_lang_id = $this->session->userdata('support_lang_id');
		       $query="SELECT m.title_text,m.external_link,m.paragraph,m.image_name,q.question,q.options,q.notes FROM tbl_culture_question q LEFT JOIN tbl_culture_master m ON  m.culture_master_id=q.culture_master_id WHERE is_active='1' AND is_delete='0' AND q.target_language_id=".$support_lang_id."";
               if($land !=""){

               		$query .= " AND m.support_lang_id='$land'";
               }

               if($cid !=""){

               		$query .= " AND m.category_id='$cid'";
               }
               if($scid !=""){

               		$query .= " AND m.subcategory_id='$scid'";
               }

	            $rs = $this->db->query($query);
	            $exceldata= array();
		        foreach ($rs->result_array() as $row){
		                $exceldata[] = $row;
		        }
                //Fill data 
                $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');
                $filename='culture.xls'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                $objWriter->save('php://output');
                 
    }
    /*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for export dialogue
   	*************************************
    */
    public function excel_export_dialogue()
    {
				$this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle('Countries');
                $this->excel->getActiveSheet()->setCellValue('A1', 'Title');
                $this->excel->getActiveSheet()->setCellValue('B1', 'Full_audio');
                $this->excel->getActiveSheet()->setCellValue('C1', 'phrase');
                $this->excel->getActiveSheet()->setCellValue('D1', 'phrase_audio');
                $this->excel->getActiveSheet()->setCellValue('E1', 'Speaker – 1/ Speaker – 2');
                $this->excel->getActiveSheet()->setCellValue('F1', 'sequence_no');
                $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
		       	for($col = ord('A'); $col <= ord('C'); $col++){ 
		                $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
		                $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		        }
	            $land = $this->uri->segment('3');
			    $cid = $this->uri->segment('4');
			    $scid = $this->uri->segment('5');
				$filename='Dialouge';
				$support_lang_id = $this->session->userdata('support_lang_id');	
		        $query="SELECT m.title,m.full_audio,l.phrase,l.audio_name,l.speaker,l.sequence_no FROM tbl_dialogue_list l LEFT JOIN tbl_dialogue_master m ON  m.dialogue_master_id=l.dialogue_master_id WHERE l.target_language_id=".$support_lang_id."";
                if($land !=""){
               		$query .= " AND m.support_lang_id='$land'";
              	}
                if($cid!=""){
               		$query .= " AND m.category_id='$cid'";
               		$sql = "SELECT category_name_in_en FROM tbl_exercise_mode_categories WHERE exercise_mode_category_id='$cid'";
               		$rs1 = $this->db->query($sql)->row();
               		$cat =$rs1->category_name_in_en;
               		$cat = str_replace(" ","-",$cat);
               		$filename .= "_$cat";
               } 
               if($scid!=""){
               		$query .= " AND m.subcategory_id='$scid'";              		
               		$sql = "SELECT subcategory_name_in_en FROM tbl_exercise_mode_subcategories WHERE exercise_mode_subcategory_id='$scid'";
               		$rs1 = $this->db->query($sql)->row();
               		$subcat =$rs1->subcategory_name_in_en;
               		$subcat = str_replace(" ","-",$subcat);
               		$filename .= "_$subcat";             		  
               } 
            $rs = $this->db->query($query);
            $exceldata="";
	        foreach ($rs->result_array() as $row){
	                $exceldata[] = $row;
	        }
            //Fill data 
            $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');
            $filename .='.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
            $objWriter->save('php://output');
                 
    }

    /*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for list user list
   	*************************************
    */
    public function user_list(){
		
		if ($this->session->userdata('logged_in')){

			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			$data['user_list'] = $this->admin_model->get_user_list();
			$data['active_class']="user";
			$slang_name = $this->session->userdata('support_lang_name');
			$admin_data = $this->session->userdata('logged_in');
			$admin_language = $admin_data[0]['support_lang_ids'];
			$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
			$data['current_support_lang'] = $slang_name;
			$this->load->view('admin/header',$data);
			$this->load->view('admin/user_list',$data);
			$this->load->view('admin/side_menu',$data);
			$this->load->view('admin/footer');

		}else{

			redirect('admin_master/login', 'refresh');

		}
	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is reset password for application users
   	*************************************
    */
	function reset_password($id){

			$this->form_validation->set_rules('password','Password','required');
			$this->form_validation->set_rules('confirm_password','Confirm Password','required|matches[password]');
			if($this->form_validation->run()==true){
				$password = $this->input->post('password');
				$confirm_password = $this->input->post('confirm_password');
				$data = array('password'=>md5($confirm_password),'reset_token'=>"");
				$check = $this->admin_model->reset_password($data,$id);
				if($check){

						$this->session->set_flashdata('sucess_msg','Password reset Successfully');
						redirect('admin_master/password_reset_success','refresh');
				}
			 }else{
			  		$data['error'] = $this->session->flashdata('error');
			  		$data['id']=$id;
			  		$this->load->view('admin/reset_password',$data);
			 }
	}
	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for success page after reset password
   	*************************************
    */
	function password_reset_success(){

		$this->load->view('admin/reset_password_success');
	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is check and list of missing images and audio words
   	*************************************
    */
	public function missing_word_image_audio(){

		if ($this->session->userdata('logged_in')){
			$sessiondata = $this->session->userdata('logged_in');
			$data['useremail']=$sessiondata[0]['email'];
			$data['userefirst_name']=$sessiondata[0]['first_name'];
			$data['userelast_name']=$sessiondata[0]['last_name'];
			$data['category_select']= $this->input->post('category');
			$data['subcategory_select']=$this->input->post('subcategory');
			$data['success_msg']=$this->session->flashdata('sucess_msg');
			$data['error_msg']=$this->session->flashdata('error_msg');
			$data['category']=$this->admin_model->get_category_list();
	        $data['subcategory'] = $this->admin_model->get_subcategory_list($this->input->post('category'));
	        $data['exercise_mode']=$this->admin_model->get_exercise_mode();
	        $data['source_lang']=$this->admin_model->get_source_lang();
	        $admin_data = $this->session->userdata('logged_in');
			$admin_language = $admin_data[0]['support_lang_ids'];
			$data['master_lang']= $this->admin_model->get_support_languages($admin_language);
	        $slang_name = $this->session->userdata('support_lang_name');
						$data['current_support_lang'] = $slang_name;
	        if($this->input->post()){
		 		$data['missing_data']=$this->admin_model->get_missing_image_audio($this->input->post('category'),$this->input->post('subcategory'));
	      	 }else{
	       		$data['missing_data']= array();
	       	}

			$data['active_class']="missing";
			$this->load->view('admin/header',$data);
			$this->load->view('admin/missing_word_image_audio',$data);
			$this->load->view('admin/side_menu',$data);
			$this->load->view('admin/footer');
			$this->session->unset_userdata('search');

		}else{

			redirect('admin_master/login', 'refresh');

		}
	}

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for upload category and subcategory bulk images
   	*************************************
    */

	function cate_subcate_bulk_upload_images(){

		$count = count($_FILES['userfile']['size']);
			        foreach($_FILES as $key=>$value)
			        for($s=0; $s<=$count-1; $s++) {
					        $_FILES['userfile']['name']=$value['name'][$s];
					        $_FILES['userfile']['type']    = $value['type'][$s];
					        $_FILES['userfile']['tmp_name'] = $value['tmp_name'][$s];
					        $_FILES['userfile']['error']       = $value['error'][$s];
					        $_FILES['userfile']['size']    = $value['size'][$s];  
				            $config['upload_path'] = "./uploads";
				            $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    		$config['overwrite'] = TRUE;

		                    $new_name = $value['name'][$s];
		                    $new_name =  str_replace(" ","_",$new_name);
							$config['file_name'] = strtolower($new_name);

					        $this->load->library('upload', $config);
					        $this->upload->initialize($config);
					        $this->upload->do_upload();
					        $data = $this->upload->data();
			     
			            }
					$this->session->set_flashdata('sucess_msg','Images uploaded Successfully');
					if($this->input->post('cate_sub') == "cate"){

							redirect('admin_master/category_list', 'refresh');
					}else{
							redirect('admin_master/subcategory_list', 'refresh');
					}			
	}


	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for deleveloper : not being used in admin
   	*************************************
    */
	public function type_import(){

		$path = FCPATH.'uploads/excel/';
		$varname = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$_FILES["file"]["name"]) ;
		$this->load->library('excel');//load PHPExcel library 
        $configUpload['upload_path'] = FCPATH.'uploads/excel/';
        $configUpload['allowed_types'] = 'xls|xlsx|csv';
        $configUpload['max_size'] = '5000';
        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
        $file_name = $_FILES["file"]["name"]; //uploded file name
		$extension=$upload_data['file_ext'];    // uploded file extension
		$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
          //Set to read only
        $objReader->setReadDataOnly(true); 		  
        //Load excel file
		PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
		$objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);		 
        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);      
		    // if(($objWorksheet->getCellByColumnAndRow(0,1))!="image_name"){
		    //     	$this->session->set_flashdata('error_msg','File formate is incorrect! Please download a sample file for reference ');	 
		    //         redirect('admin_master/category_list', 'refresh');
		    // }	          
          //loop from first data untill last data
	        for($i=2;$i<=$totalrows;$i++)
	        {
					$data = array(
								//"exercise_mode_id"=>$this->input->post('exercise_mode'),
								"exercise_mode_id"=>strtolower($objWorksheet->getCellByColumnAndRow(0,$i))
							);
					$source_lang=$this->admin_model->get_source_lang();
					$ctn=0;
					foreach($source_lang as $langkey){

							if($ctn==0){
								$j=1;
							}else{
								$j=$j+1;
							}
							$name=$objWorksheet->getCellByColumnAndRow($j,$i);
							$data["type_".$langkey['language_code']] = $name;
							$ctn++;
					}
					$data['support_lang_id'] = $this->session->userdata('support_lang_id');
					$insert = $this->db->insert('tbl_exercise_type',$data);			
	        }
            unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .		
            $this->session->set_flashdata('sucess_msg','Category imported Successfully');	 
            redirect('admin_master/type_list/'.$this->input->post('exercise_mode'), 'refresh');
    }

	/*
	************************************
    @@ Developer : Nimesh Patel 
   	@@ Description : This Function is for reset all dropdown session value , call onclick sidemenu list
   	*************************************
    */
	function unset_session()
	{
		$this->session->unset_userdata('modeid');
		$this->session->unset_userdata('cateid');
		$this->session->unset_userdata('subcateid');
		$this->session->unset_userdata('sort');
		$this->session->unset_userdata('per_page');
		$this->session->unset_userdata('search');
		echo json_encode(array("status"=>1,"message"=>"success")); die;
	}
	public function change_language()
	{
		$this->session->unset_userdata('support_lang_name');
		$this->session->unset_userdata('support_lang_code');
		$this->session->unset_userdata('support_lang_id');
		$this->session->unset_userdata('support_lang_field_name');
		$support_lang_id = $this->input->post('support_lang_id');
		
		$support_language = $this->admin_model->get_current_target_lang($support_lang_id);
		/*echo "<pre>";
		print_r($support_language);
		exit;*/
		if(!empty($support_language))
		{	$this->load->library('session');
			$support_lang_name = $support_language['language_name'];
			$support_lang_code = $support_language['language_code'];
			$support_lang_id = $support_language['source_language_id'];
			$support_lang_field_name = $support_language['field_name'];
			$this->session->set_userdata('support_lang_name',$support_lang_name);
			$this->session->set_userdata('support_lang_code',$support_lang_code);
			$this->session->set_userdata('support_lang_id',$support_lang_id);
			$this->session->set_userdata('support_lang_field_name',$support_lang_field_name);
		}
		$data = array('support_lang_id'=>$support_lang_id);
		echo json_encode($support_language);
		
	}

	function makePathNotAccessible($path)
	{
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
		    $link = "https"; 
		}else{
		    $link = "http"; 
		}
		$link .= "://"; 
		$link .= $_SERVER['HTTP_HOST']; 
	    $contents = '<?php header("Location: '.$link.'/404"); ?>';
		if(!is_file($path)){
		    file_put_contents($path, $contents);
		}
		return true;
	}
}
