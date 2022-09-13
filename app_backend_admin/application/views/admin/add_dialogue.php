	
	<!-- BEGIN BASE -->
	<div id="base">
		<!-- BEGIN CONTENT-->
		<div id="content">
			<!-- BEGIN SECTION -->
			<section>
				<div class="section-header">
					<ol class="breadcrumb">
						<li><a href="index.html">Home</a></li>
						<li class="active">Add Dialogue</li>
						<a href="<?php echo base_url(); ?>admin_master/dialogue_list" class="btn btn-primary btn-sm btn-raised pull-right">Back To List</a>
					</ol>
				</div><!--end .section-header -->
				<div class="section-body">
					<!-- BEGIN HORIZONTAL FORM - SIZES -->
					<div class="card">

						<div class="card-body">
							<?php //print_r($exercise_mode); 
							$attributes = array('action' => base_url().'admin_master/add_dialogue' );
							echo form_open_multipart('admin_master/add_dialogue', $attributes);
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
											<!-- <div class="col-md-12 mrgt10">
												<label>Language</label>
												<select id="lang" name="lang" class="form-control">
												<option value="">Select Language</option>
												<?php foreach($source_lang as $key){ ?>
												<option <?php if(isset($lang)) if($key['source_language_id']==$lang){ echo "selected";} ?> value="<?php echo $key['source_language_id'];?>"><?php echo ucfirst($key['language_name']);?> </option>
												<?php } ?>
											</select>
												<?php echo form_error('lang', '<div class="errormsg">', '</div>'); ?>
											</div> -->
											<div class="clearfix"></div>
											<div class="col-md-12 mrgt10">
												<label>Category</label>
												<select name="category" id="category" class="form-control">
													<option value="">Category</option>
													<?php foreach($category as $key){ ?>
													<option  <?php echo set_select('category', $key['exercise_mode_category_id']); ?>  value="<?php echo $key['exercise_mode_category_id'];?>"><?php echo ucfirst($key['category_name_in_en']);?> </option>
													<?php } ?>
												</select>
												<?php echo form_error('category', '<div class="errormsg">', '</div>'); ?>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-12 mrgt10">
												<label>Sub Category</label>
												<select id="subcate" name="subcategory" class="form-control">
													<option value="">SubCategory</option>
													
												</select>
												<?php echo form_error('subcategory', '<div class="errormsg">', '</div>'); ?>
											</div>
											<div class="clearfix"></div>
										
											<div class="clearfix"></div>
											<div class="col-md-12 mrgt10">
													<label>Title</label>
													<input type="text" name="title" class="form-control" placeholder="Title">
													<?php echo form_error('title', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
											
												<div class="col-md-12 mrgt10 correct_ans">
													<label>Audio Name</label>
													<input type="text" name="full_audio" class="form-control" placeholder="Full Audio Name ">
													<?php echo form_error('full_audio', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>


												
											
										</div>

										<div class="col-md-5 col-sm-6">
											<input class="btn btn-primary btn-raised" id="add_new" type="button" name="add_new" value="ADD NEW"> 
											
											<div class="cls-append">

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


	<!-- Reapet DIV START -->
									<div class="repeat_cls hide">
												<div class="card contain_box mrgt10">
													<div class="col-md-10 mrgt10">
													<label>Phrase</label>
													<input type="text" name="phrase[]" disabled class="form-control" placeholder="Phrase">
														<?php echo form_error('phrase', '<div class="errormsg">', '</div>'); ?>
													</div>
													<div class="col-md-2 mrgt10">
														<a href="javascript:void(0);" class="remove" > <i class="btn-sm btn-danger fa fa-trash-o "></i> </a>
													</div>

													<div class="col-md-6 mrgt10">
														<label>Type</label>
														<select id="type" disabled name="type[]" class="form-control">
															
															<option value="">Select Type</option>
															<option value="1">Sender</option>
															<option value="2">Receiver</option>
														
														</select>
														<?php echo form_error('type', '<div class="errormsg">', '</div>'); ?>
													</div>
													<div class="col-md-6 mrgt10 correct_ans">
															<label>Audio Name</label>
															<input type="text" name="audio[]"  disabled class="form-control" placeholder="Audio Name ">
															<?php echo form_error('audio', '<div class="errormsg">', '</div>'); ?>
													</div>
												</div>
									</div>
									<!-- Reapet DIV END -->

<script type="text/javascript">


	$( document ).ready(function(){

	


	 	$(".contain_box .form-control").each(function(index){
			$(".contain_box .form-control").removeAttr("disabled");
		});

		 var htmldata = $(".repeat_cls").html();
		 $('.cls-append').append(htmldata);
		 checkCount();

			$(".contain_box .form-control").each(function(index){
				$(".repeat_cls .form-control").attr("disabled", "disabled"); 
			});



	});






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

	});

	function checkCount()
	{
		var tmpLen = 0;
		$(".cls-append .contain_box").each(function(index){
			tmpLen = tmpLen + 1;
		});
		if(tmpLen == 1) {
			$(".cls-append .contain_box:first-child").find(".remove").addClass("hide");
		}
	}

	$("#add_new").click(function(){

		$(".contain_box .form-control").each(function(index){
			$(".contain_box .form-control").removeAttr("disabled");
		});

		var htmldata = $(".repeat_cls").html();
		//alert(htmldata);
		$('.cls-append').append(htmldata);
		checkCount();


		$(".contain_box .form-control").each(function(index){
				$(".repeat_cls .form-control").attr("disabled", "disabled"); 
			});
	});

	// $(".remove").click(function(){
	// 	//$(this).parent().parent().remove();
	// 	//alert($(this).parent().parent().html());
	// 	//checkCount();
	// 	alert('hi');

	// });


	$(document).on("click", ".remove", function () {
		$(this).parent().parent().remove();
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