<!-- BEGIN BASE-->
		<div id="base">

			<!-- BEGIN CONTENT-->
			<div id="content">

				<!-- BEGIN BLANK SECTION -->
				<section>
					<!-- SECTION-HEADER -->
					<div class="section-header">
						<ol class="breadcrumb">
							<li><a href="index.html">Home</a></li>
							<li class="active">Edit User</li>
							<a href="<?php echo base_url(); ?>admin_master/portal_users" class="btn btn-primary btn-sm btn-raised pull-right">Back To List</a>
						</ol>
					</div>
					<!-- END SECTION-HEADER -->
					
					<!-- SECTION-BODY -->
					<div class="section-body">
						<!-- CARD -->
						<div class="card">
							<!-- CARD-BODY -->
							<div class="card-body">
								<?php //print_r($exercise_mode); 
//$attributes = array('action' => base_url().'admin_master/add_category');
//echo form_open_multipart('admin_master/add_category', $attributes);
?>


								<form action="<?php echo base_url(); ?>admin_master/edit_portaluser/<?php echo $edit_data['admin_id'] ?>" method="post" name="upload_excel" enctype="multipart/form-data">

									<?php 
									if(isset($success_msg)){ ?>
									<div class="success-message"><?php echo $success_msg; ?></div>
									<?php } ?>
									<?php 
									if(isset($error_msg)){ ?>
									<div class="error-message"><?php echo $error_msg; ?></div>
									<?php } ?>
									<div class="form-group">
										<div class="row">
											<div class="col-md-3 col-sm-6">
												
												<div class="form-group">
													<label for="first_name">First Name</label>
													<input type="text" class="form-control" name="first_name" placeholder="FirstName" value="<?php echo $edit_data['first_name'] ?>">
													<?php echo form_error('first_name', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label for="last_name">Last Name</label>
													<input type="text" class="form-control" name="last_name" placeholder="Last Name" value="<?php echo $edit_data['last_name'] ?>">
													<?php echo form_error('last_name', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="form-group">
													<label for="email">Email</label>
													<input type="text" class="form-control" name="email" placeholder="Email" value="<?php echo $edit_data['email'] ?>">
													<?php echo form_error('email', '<div class="errormsg">', '</div>'); ?>
												</div>
											</div>
											<div class="col-md-3 col-sm-6">
												<div class="form-group">
													<label for="phone">Phone</label>
													<input type="text" class="form-control" name="phone" placeholder="Phone" value="<?php echo $edit_data['phone'] ?>">
													<?php echo form_error('phone', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="form-group">
													 <input class="form-check-input" type="checkbox" name="change_password" id="change_password" value="1">
													  <label class="form-check-label" for="change_password">Change Password</label>
													  <?php echo form_error('password', '<div class="errormsg">', '</div>'); ?>
													  <?php echo form_error('confirm_password', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="password_section">
													<div class="form-group">
														<label for="password">Password</label>
														<input type="password" class="form-control" name="password" placeholder="Password" value="">
													</div>
													<div class="form-group">
														<label for="last_name">Confirm Password</label>
														<input type="password" class="form-control" name="confirm_password" placeholder="Last Name" value="">
													</div>
												</div>
											</div>
											<div class="clearfix"></div>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="address_details">
						                        <?php $i=1; ?>
						                        <div class="row">
						                        <?php foreach ($master_lang as $value) { ?>
						                          <div class="col-sm-2">
						                              <div class="form-group">
						                                  <div class="form-check">
						                                    <?php 
						                                    $support_langs = $edit_data['support_lang_ids'];
						                                    $dt = explode(',', $support_langs);
						                                    ?>
						                                    <?php if(in_array($value['source_language_id'], $dt)){ ?>
						                                    <input class="form-check-input" type="checkbox" name="support_lang_ids[]" id="<?php echo $value['source_language_id'] ?>_faclity" value="<?php echo $value['source_language_id'] ?>" checked>
						                                  <?php }else{ ?>
						                                    <input class="form-check-input" type="checkbox" name="support_lang_ids[]" id="<?php echo $value['source_language_id'] ?>_faclity" value="<?php echo $value['source_language_id'] ?>">
						                                  <?php } ?>
						                                    <label class="form-check-label" for="<?php echo $value['source_language_id'] ?>_faclity"><?php echo $value['language_name'] ?></label>
						                                  </div>
						                              </div>
						                          </div>
						                          <?php if($i == 6){ $i = 1; ?>
						                            <div style="clear: both;"></div> 
						                          <?php } ?>
						                        <?php $i++; ?>
						                        <?php } ?>
						                      </div>
						                      </div>
						                      <div class="clearfix"></div>
											<div class="col-md-3 col-sm-6">
												<div class="col-md-5 col-sm-3 col-xs-5 mrgt15">
													<input class="btn btn-primary btn-raised" type="submit" name="save" value="save"> 
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
							<!-- END .CARD-BODY -->
						</div>
						<!-- END .CARD -->
					</div>
					<!-- END .SECTION-BODY -->
				</section>
				<!-- END SECTION -->
			</div>
			<!-- END CONTENT -->

<script type="text/javascript">

jQuery(document).ready(function(){
	$('.password_section').css('display','none');
	$('#change_password').click(function() {
    	$(".password_section").toggle(this.checked);
	});
});
	function checkvalid(){


		var Validat=1;
		var oFile = document.getElementById('image_id').files[0];
		//console.log(oFile);
		//return false;
		$("#file_error").text("");

			//if(oFile==undefined){
					//alert('file not fafdfd');
			//	Validat=0;
			//	$("#file_error").text("Please Choose File");
			//}else{

			var rFilter = /^(image\/bmp|image\/gif|image\/jpeg|image\/png|image\/tiff)$/i;
			if (! rFilter.test(oFile.type)) {
				//alert('unspoerted');
				//alert('file not supported');
				$("#file_error").text("file type not supported");
				Validat=0;
			}

	//	}


	// filter for image files
			

	

		if(Validat==1){
					return true;
				}else{
					return false;
				}



}



			</script>