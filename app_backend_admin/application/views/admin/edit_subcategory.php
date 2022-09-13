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
$attributes = array('action' => base_url().'admin_master/edit_subcategory/'.$edit_data[0]['exercise_mode_subcategory_id'],'onsubmit'=>'return checkvalid()');
echo form_open_multipart('admin_master/edit_subcategory/'.$edit_data[0]['exercise_mode_subcategory_id'], $attributes);
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
													<label>Exercise Mode</label>
													<select id="mode" name="mode" class="form-control">
														<option value="">Exercise Mode</option>
														<?php foreach($exercise_mode as $key){ ?>
							<option <?php if($key['id']==$selected_mode){ echo "selected"; } ?> value="<?php echo $key['id'];?>"><?php echo $key['mode_name'];?> </option>
						<?php } ?>
													</select>
													<?php echo form_error('mode', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>


												<div class="col-md-12 mrgt10">
													<label>Category</label>
													<select id="category" name="category" class="form-control">
														<option value="">Category</option>
														<?php foreach($category as $key){ ?>
							<option <?php if($key['exercise_mode_category_id']==$edit_data[0]['category_id']){ echo "selected"; } ?> value="<?php echo $key['exercise_mode_category_id'];?>"><?php echo $key['category_name_in_en'];?> </option>
						<?php } ?>
													</select>
													<?php echo form_error('category', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
											

												<?php foreach($source_lang as $key){?>
												<div class="col-md-12 mrgt10">
													<label>SubCategory Name In <?= $key['language_name'];?></label>
													<input type="text" name="subcate_name_<?= $key['language_code']; ?>" class="form-control" value="<?php echo $edit_data[0]['subcategory_name_in_'.$key['language_code']];?>" >
													<?php echo form_error('subcate_name'.$key['language_code'], '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
												<?php } ?>

												<div class="col-md-12 mrgt10">
													<label>Difficulty Level</label>
													<select id="select13" name="level" class="form-control">
														<option value="">Difficulty Level</option>
														<option <?php if($edit_data[0]['difficulty_level_id']=="1"){ echo "selected"; } ?> value="1">Easy</option>
														<option <?php if($edit_data[0]['difficulty_level_id']=="2"){ echo "selected"; } ?> value="2">Medium</option>
														<option <?php if($edit_data[0]['difficulty_level_id']=="3"){ echo "selected"; } ?>  value="3">Difficult</option>
														
													</select>
													<?php echo form_error('level', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
												<label class="col-md-12 mrgt10">Image</label>
												<div class="col-md-12 mrgt5">
													<div class="btn btn-primary btn-raised">
														<span>Choose File</span>
														<input type="file" name="userfile" class="fileUpload" id="image_id"/>
													</div>
													<span id="selecte_file_name"></span>

														<img id="word_image" src="<?php echo base_url(); ?>uploads/<?php echo$edit_data[0]['image']; ?>" class="mrgt10 img-responsive" width="60px" onerror="this.onerror=null;this.src='<?php echo base_url(); ?>assets/thumb_image_not_available.png';"/>
												<?php $root_path  = $this->config->item('root_path');   $file = $root_path.'uploads/'.$edit_data[0]['image'] ;?>
												
												<?php  $temp=0; if(file_exists($file)){ $temp=1;?>
														<a href="javascript:void(0);" onclick="remove_image();" id="remove_btn"  class="errormsg"> <i class="fa fa-remove"></i> </a>
												<?php } ?>
												<input type="hidden" name="is_image_delete" id="image_delete" value="" />
												<input type="hidden" name="old_image_name" id="" value="<?php echo $file; ?>" />


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
													<select name="type[]" id="exe_type" class="selectpicker" multiple data-selected-text-format="count > 3">
														<option value=""> Select Type </option>
	<?php $newarray = array();for($i=0; $i < count($get_selected_type); $i++){ array_push($newarray, $get_selected_type[$i]['exercise_type_id']);}?>
											<?php foreach($exercise_type as $key){  ?>
												<option value="<?php echo $key['id'];?>" <?php if(in_array($key['id'],$newarray)) { echo "selected";}?>><?php echo ucfirst($key['type_name']);?> </option>
											<?php }?>
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

function remove_image(){

	$("#image_delete").val('0');
	$("#word_image").hide();
	$("#remove_btn").hide();
}
$('#mode').change(function(){
	var modeid = this.value;

		$.ajax({
					url:'<?php echo base_url();?>admin_master/get_cat_from_mode',
					type:'POST',
					data:{mode_id:modeid},
					success:function(data){
						$('#category').find('option').remove().end().append(data);
					},
		});

		$.ajax({
					url:'<?php echo base_url();?>admin_master/get_type_from_mode',
					type:'POST',
					data:{mode_id:modeid},
					success:function(data){
						$('#exe_type').find('option').remove().end().append(data);
						$('.selectpicker').selectpicker('refresh');
					},
		});


	});

	function checkvalid(){
		var Validat=1;
		var oFile = document.getElementById('image_id').files[0];
		//console.log(oFile);
		//return false;
		$("#file_error").text("");
			var rFilter = /^(image\/bmp|image\/gif|image\/jpeg|image\/png|image\/tiff)$/i;
			if (! rFilter.test(oFile.type)) {
				//alert('unspoerted');
				//alert('file not supported');
				$("#file_error").text("file type not supported");
				Validat=0;
			}

		if(Validat==1){
					return true;
				}else{
					return false;
				}
}
$('input[type=file]').change(function(e){
   var filePath= $('#image_id').val();
   				if(filePath.match(/fakepath/)) {
                   
                    filePath = filePath.replace(/C:\\fakepath\\/i, '');
              	}

   $("#selecte_file_name").text(filePath);
});
</script>