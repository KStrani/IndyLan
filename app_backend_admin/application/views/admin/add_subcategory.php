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
							<li class="active">Add SubCategory</li>
							<a href="<?php echo base_url(); ?>admin_master/subcategory_list" class="btn btn-primary btn-sm btn-raised pull-right">Back To List</a>
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
$attributes = array('action' => base_url().'admin_master/add_subcategory','onsubmit'=>'return checkvalid()');
echo form_open_multipart('admin_master/add_subcategory', $attributes);
?>
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

												

												<div class="col-md-12 mrgt10">
													<label> Exercise Mode</label>
													<select id="mode" name="mode" class="form-control">
														<option value="">Exercise Mode</option>
														<?php foreach($exercise_mode as $key){ ?>
												<option  value="<?php echo $key['id'];?>"><?php echo $key['mode_name'];?> </option>
												<?php } ?>
													</select>
													<?php echo form_error('mode', '<div class="errormsg">', '</div>'); ?>
												</div>


												<div class="col-md-12 mrgt10">
													<label>Category</label>
													<select id="category" name="category" class="form-control">
														<option value="">Category</option>
														<?php foreach($category as $key){ ?>
															<option <?php echo set_select('category', $key['exercise_mode_category_id']) ?>  value="<?php echo $key['exercise_mode_category_id'];?>"><?php echo $key['category_name_in_en'];?> </option>
														<?php } ?>
													</select>
													<?php echo form_error('category', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
											
												<?php foreach($source_lang as $key){?>
												<div class="col-md-12 mrgt10">
													<label>SubCategory Name In <?= $key['language_name'];?></label>
													<input type="text" name="subcate_name_<?= $key['language_code']; ?>" class="form-control" placeholder="SubCategory Name in <?= $key['language_name']; ?>" value="<?php echo set_value('subcate_name_'.$key['language_code']);?>"  >
													<?php echo form_error('subcate_name_'.$key['language_code'], '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
												<?php } ?>

												<div class="col-md-12 mrgt10">
													<label>Difficulty Level</label>
													<select id="select13" name="level" class="form-control">
														<option  value="">Difficulty Level</option>
														<option <?php echo set_select('level', '1') ?>  value="1">Easy</option>
														<option <?php echo set_select('level', '2') ?>  value="2">Medium</option>
														<option <?php echo set_select('level', '3') ?>  value="3">Difficult</option>
														
													</select>
													<?php echo form_error('level', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
												<label class="col-md-12 mrgt10">Image</label>
												<div class="col-md-12 mrgt5">
													<div class="btn btn-primary btn-raised">
														<span>Choose File</span>
														<input type="file" name="userfile" class="fileUpload" id="image_id" />
													</div>
												</div>
												<span id="file_error" class="errormsg"> </span>
												<div class="clearfix"></div>
												<div class="col-md-5 col-sm-3 col-xs-5 mrgt15">
													
													<input class="btn btn-primary btn-raised" type="submit" name="save" value="save"> 
												</div>
											</div>
											<div class="col-md-3 col-sm-6">
												<div class="col-md-12 mrgt10">
													<label>Exercise Type</label>
													<select name="type[]" class="selectpicker" id="exe_type"  multiple data-selected-text-format="count > 3">
		
											<?php foreach($exercise_type as $key){ ?>
											<option <?php echo set_select('type[]', $key['id']); ?> value="<?php echo $key['id'];?>"><?php echo ucfirst($key['type_name']);?> </option>
											<?php } ?>
													</select>
													<?php echo form_error('type[]', '<div class="errormsg">', '</div>'); ?>
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


$('#mode').change(function(){
	var modeid = this.value;

		$.ajax({
					url:'<?php echo base_url();?>admin_master/get_cat_from_mode',
					type:'POST',
					data:{mode_id:modeid},
					success:function(data){

						//$('#category').html("");
						//alert('here');
						$('#category').find('option').remove().end().append(data);
						
						//$().redirect('<?php echo base_url();?>admin_master/words_list', {'mode_id': modeid,'sort':sort,'per_page':per_page});

					//	window.location.href = '<?php echo base_url();?>admin_master/words_list/'+modeid;
					},
		});

		$.ajax({
					url:'<?php echo base_url();?>admin_master/get_type_from_mode',
					type:'POST',
					data:{mode_id:modeid},
					success:function(data){

						//$('#category').html("");
						//alert('here');
						$('#exe_type').find('option').remove().end().append(data);
						$('.selectpicker').selectpicker('refresh');
						
						//$().redirect('<?php echo base_url();?>admin_master/words_list', {'mode_id': modeid,'sort':sort,'per_page':per_page});

					//	window.location.href = '<?php echo base_url();?>admin_master/words_list/'+modeid;
					},
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

		//}


	// filter for image files
			

	

		if(Validat==1){
					return true;
				}else{
					return false;
				}



}



			</script>