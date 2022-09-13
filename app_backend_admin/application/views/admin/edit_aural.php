<div id="base">

			<!-- BEGIN CONTENT-->
			<div id="content">

				<!-- BEGIN BLANK SECTION -->
				<section>
					<div class="section-header">
						<ol class="breadcrumb">
							<li><a href="index.html">Home</a></li>
							<li class="active">Add Aural Comprehension</li>
							<a href="<?php echo base_url(); ?>admin_master/aural_list" class="btn btn-primary btn-sm btn-raised pull-right">Back To List</a>
						</ol>
					</div><!--end .section-header -->
					<div class="section-body">
						
						<!-- BEGIN HORIZONTAL FORM - SIZES -->
						<div class="card">

						<div clv class="card-body">
<?php //print_r($exercise_mode); 
$attributes = array('action' => base_url().'admin_master/edit_aural/'.$edit_data[0]['aural_id'],'onsubmit'=>'return checkvalid()');
echo form_open_multipart('admin_master/edit_aural/'.$edit_data[0]['aural_id'], $attributes);
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
											<div class="col-md-5 col-sm-6">
												<input type="hidden" name="mode" id="mode" value="6"/>
												<!-- <div class="col-md-12 mrgt10">
													<label>Exercise Mode</label>
													<select id="mode" name="mode" class="form-control">
														<option value="">Exercise Mode</option>
															<?php foreach($exercise_mode as $key){ ?>
														<option <?php if($key['id']==$edit_data[0]['exercise_mode_id']){ echo "selected"; } ?> value="<?php echo $key['id'];?>"><?php echo $key['mode_name'];?> </option>
													<?php } ?>
													</select>
												</div>
												<div class="clearfix"></div> -->
												<div class="col-md-12 mrgt10">
													<label>Category</label>
													<select name="category" id="category" class="form-control">
														<option value="">Category</option>
														<?php foreach($category as $key){ ?>
							<option <?php if($key['exercise_mode_category_id']==$edit_data[0]['category_id']){ echo "selected"; } ?> value="<?php echo $key['exercise_mode_category_id'];?>"><?php echo $key['category_name_in_en'];?> </option>
						<?php } ?>
													</select>
														<?php echo form_error('category', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
												<div class="col-md-12 mrgt10">
													<label>Sub Category</label>
													<select id="subcate" name="subcategory" class="form-control">
														<option value="">SubCategory</option>
														<?php foreach($subcategory as $key){ ?>
							<option <?php if($key['exercise_mode_subcategory_id']==$edit_data[0]['subcategory_id']){ echo "selected"; } ?>  value="<?php echo $key['exercise_mode_subcategory_id'];?>"><?php echo $key['subcategory_name_in_en'];?> </option>
						<?php } ?>
													</select>
														<?php echo form_error('subcategory', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
												<div class="col-md-12 mrgt10">
													<label>Audio Name</label>
													<input type="text" name="audio_name" id="audio_txt" class="form-control" placeholder="Audio Name" value="<?php echo $edit_data[0]['audio_file']; ?>">
													<?php echo form_error('audio_name', '<div class="errormsg">', '</div>'); ?>
												<?php if($edit_data[0]['audio_file']!=""){ ?> 

													<a href="javascript:void(0);" onclick="remove_audio();" id="remove_btn_audio" class="errormsg" > <i class="fa fa-remove"></i> </a>

												<?php } ?>
												
												</div>
												<div class="clearfix"></div>
												<!-- <div class="clearfix"></div>
												<label class="col-md-12 mrgt10">Image</label>
												<div class="col-md-12 mrgt5">
													<div class="btn btn-primary btn-raised">
														<span>Choose File</span>
														<input type="file" name="userfile" class="fileUpload" id="image_id"/>
													</div>
													<img id="word_image" src="<?php // echo base_url(); ?>uploads/words/<?php // echo $edit_data[0]['category_id']; ?>/<?php // echo $edit_data[0]['subcategory_id']; ?>/<?php // echo $edit_data[0]['image_file']; ?>" class="mrgt10 img-responsive" width="60px" onerror="this.onerror=null;this.src='<?php // echo base_url(); ?>assets/thumb_image_not_available.png';"/>
												<?php // $root_path  = $this->config->item('root_path');   $file = $root_path.'uploads/words/'.$edit_data[0]['category_id'].'/'.$edit_data[0]['subcategory_id'].'/'.$edit_data[0]['image_file'] ;?>
												
												<?php // $temp=0; if(file_exists($file)){ $temp=1;?>
														<a href="javascript:void(0);" onclick="remove_image();" id="remove_btn"  class="errormsg"> <i class="fa fa-remove"></i> </a>
												<?php // } ?>
												<input type="hidden" name="is_image_delete" id="image_delete" value="" />
												</div>
												<span id="file_error" class="errormsg"> </span>
												<div class="clearfix"></div> -->
												
												<div class="col-md-5 col-sm-3 col-xs-5 mrgt15">
													<input class="btn btn-primary btn-raised" type="submit" name="save" value="save"> 
												</div>
											</div>
											<div class="col-md-5 col-sm-6">
												<div class="clearfix"></div>
												<?php foreach($source_lang as $key){?>
												<div class="col-md-12 mrgt10">
													<label>Aural Name In <?= $key['language_name'];?></label>
													<input type="text" name="word_<?= $key['language_code']; ?>" class="form-control" value="<?php echo $edit_data[0][$key['field_name']];?>" placeholder="Aural Name in <?= $key['language_name']; ?>" value="<?php echo set_value('word_'.$key['language_code']);?>"  >
													<?php echo form_error('word_'.$key['language_code'], '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
												<?php } ?>
												<div class="col-sm-12 mrgt10">
													<label class="checkbox-inline checkbox-styled">
														<input value="1" name="is_audio" <?php if($edit_data[0]['is_audio_available']=="1") {echo "checked=checked";} ?>  type="checkbox"><span>Audio Available</span>
													</label>
													<!-- <label class="checkbox-inline checkbox-styled">
														<input value="1" name="is_image" <?php //if($edit_data[0]['is_image_available']=="1") {echo "checked=checked";} ?> type="checkbox"><span>Image Available</span>
													</label> -->
												</div>
											</div>
										</div>
									</div>
								</form>
							</div><!--end .card-body -->
						</div><!--end .card -->
						<!-- END HORIZONTAL FORM - SIZES -->
					
					</div><!--end .section-body -->
				</section>

				<!-- BEGIN BLANK SECTION -->
			</div>
			<!-- END CONTENT -->
<script type="text/javascript">

function remove_image(){

	$("#image_delete").val('0');
	$("#word_image").hide();
	$("#remove_btn").hide();

}

function remove_audio(){

	$("#audio_txt").val('');
	
	$("#remove_btn_audio").hide();


}
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


					},

       	 

					
		});

	});


		$('#category').change(function(){
	var modeid = this.value;

		$.ajax({
					url:'<?php echo base_url();?>admin_master/get_subcat_from_cate',
					type:'POST',
					data:{cate_id:modeid},
					success:function(data){

						//$('#category').html("");
						//alert('here');
						$('#subcate').find('option').remove().end().append(data);


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
</script>
			
