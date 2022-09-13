	
	<!-- BEGIN BASE -->
	<div id="base">
		<!-- BEGIN CONTENT-->
		<div id="content">
			<!-- BEGIN SECTION -->
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

						<div class="card-body">
							<?php //print_r($exercise_mode); 
							$attributes = array('action' => base_url().'admin_master/add_aural','onsubmit'=>'return checkvalid()' );
							echo form_open_multipart('admin_master/add_aural', $attributes);
							?>
							<?php 
							if(isset($success_msg)){ ?>
							<div class="success-message"><?php echo $success_msg; ?></div>
							<?php } ?>
							<?php 
							if(isset($error_msg)){ ?>
							<div class="error-message"><?php echo $error_msg; ?></div>
							<?php } ?>
								
							<form>
								<div class="form-group">
									<div class="row">
										<div class="col-md-5 col-sm-6">
											<input type="hidden" name="mode" id="mode" value="6"/>
											<!-- <div class="col-md-12 mrgt10">
												<label>Exercise Mode</label>
												<select id="mode" name="mode" class="form-control">
													<option value="">Exercise Mode</option>
													<?php //foreach($exercise_mode as $key){ ?>
													<option <?php //echo set_select('mode', $key['id']); ?> value="<?php //echo $key['id'];?>"><?php //echo $key['mode_name'];?> </option>
													<?php //} ?>
												</select>
												<?php echo form_error('mode', '<div class="errormsg">', '</div>'); ?>
											</div> -->
											<div class="clearfix"></div>
											<div class="col-md-12 mrgt10">
												<label>Category</label>
												<select name="category" id="category" class="form-control">
													<option value="">Category</option>
													<?php foreach($category as $key){ ?>
													<option  <?php echo set_select('category', $key['exercise_mode_category_id']); ?>  value="<?php echo $key['exercise_mode_category_id'];?>"><?php echo $key['category_name_in_en'];?> </option>
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
													<option <?php echo set_select('subcategory', $key['exercise_mode_subcategory_id']); ?> value="<?php echo $key['exercise_mode_subcategory_id'];?>"><?php echo $key['subcategory_name_in_en'];?> </option>
													<?php } ?>
												</select>
												<?php echo form_error('subcategory', '<div class="errormsg">', '</div>'); ?>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-12 mrgt10">
													<label>Audio Name</label>
													<input type="text" name="audio_name" class="form-control" placeholder="Audio Name">
													<?php echo form_error('audio_name', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
												<!-- 
											<div class="clearfix"></div>
											<label class="col-md-12 mrgt10">Image</label>
											<div class="col-md-12 mrgt5">
												<div class="btn btn-primary btn-raised">
													<span>Choose File</span>
													<input type="file" name="userfile" class="fileUpload" id="image_id" />
												</div>
											</div> -->
											<span id="file_error" class="col-md-12 errormsg"> </span>
											<div class="clearfix"></div>
										</div>
										<div class="col-md-5 col-sm-6">
											
											<?php foreach($source_lang as $key){?>
												<div class="col-md-12 mrgt10">
													<label>Aural Name In <?= $key['language_name'];?></label>
													<input type="text" name="word_<?= $key['language_code']; ?>" class="form-control" placeholder="Aural Name in <?= $key['language_name']; ?>" value="<?php echo set_value('word_'.$key['language_code']);?>"  >
													<?php echo form_error('word_'.$key['language_code'], '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
												<?php } ?>



											<div class="col-sm-12 mrgt10">
												<label class="checkbox-inline checkbox-styled">
													<input value="1" name="is_audio"  type="checkbox"><span>Audio Available</span>
												</label>
												<!-- <label class="checkbox-inline checkbox-styled">
													<input value="1" name="is_image" type="checkbox"><span>Image Available</span>
												</label> -->
											</div>


										</div>
										<div class="col-md-12 col-sm-12">
											<div class="col-md-5 col-sm-3 col-xs-5 mrgt15">
												<input class="btn btn-primary btn-raised" type="submit" name="save" value="save"> 
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
			<!-- BEGIN SECTION -->
		</div>
		<!-- END CONTENT -->
	</div>
	<!-- END BASE -->

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

			if(oFile==undefined){
					//alert('file not fafdfd');
				Validat=0;
				$("#file_error").text("Please Choose File");
			}else{

				var rFilter = /^(image\/bmp|image\/gif|image\/jpeg|image\/png|image\/tiff)$/i;
			if (! rFilter.test(oFile.type)) {
				//alert('unspoerted');
				//alert('file not supported');
				$("#file_error").text("file type not supported");
				Validat=0;
			}

		}


	// filter for image files
			

	

		if(Validat==1){
					return true;
				}else{
					return false;
				}



}
</script>	
