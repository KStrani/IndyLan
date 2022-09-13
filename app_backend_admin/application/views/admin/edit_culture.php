<div id="base">

			<!-- BEGIN CONTENT-->
			<div id="content">

				<!-- BEGIN BLANK SECTION -->
				<section>
					<div class="section-header">
						<ol class="breadcrumb">
							<li><a href="index.html">Home</a></li>
							<li class="active">Edit Culture</li>
							<a href="<?php echo base_url(); ?>admin_master/culture_list" class="btn btn-primary btn-sm btn-raised pull-right">Back To List</a>
						</ol>
					</div><!--end .section-header -->
					<div class="section-body">
						
						<!-- BEGIN HORIZONTAL FORM - SIZES -->
						<div class="card">

						<div clv class="card-body">
<?php //print_r($exercise_mode); 
$attributes = array('action' => base_url().'admin_master/edit_culture/'.$edit_data[0]['culture_master_id']);
echo form_open_multipart('admin_master/edit_culture/'.$edit_data[0]['culture_master_id'], $attributes);
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
											<!-- <div class="col-md-12 mrgt10">
												<label>Language</label>
												<select id="lang" name="lang" class="form-control">
												<option value="">Select Language</option>
												<?php foreach($source_lang as $key){ ?>
												<option <?php if($key['source_language_id']==$edit_data[0]['target_language_id']){ echo "selected"; } ?>  <?php if(isset($lang)) if($key['source_language_id']==$lang){ echo "selected";} ?> value="<?php echo $key['source_language_id'];?>"><?php echo ucfirst($key['language_name']);?> </option>
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
													<option <?php if($key['exercise_mode_category_id']==$edit_data[0]['category_id']){ echo "selected"; } ?> <?php echo set_select('category', $key['exercise_mode_category_id']); ?>  value="<?php echo $key['exercise_mode_category_id'];?>"><?php echo ucfirst($key['category_name_in_en']);?> </option>
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
													<label>Title</label>
													<input type="text" name="title" class="form-control"  value="<?php echo $edit_data[0]['title_text']; ?>">
													<?php echo form_error('title', '<div class="errormsg">', '</div>'); ?>
											</div>
												<div class="clearfix"></div>
												<div class="col-md-12 mrgt10">
													<label>External Link</label>
													<input type="text" name="link" class="form-control" value="<?php echo $edit_data[0]['external_link']; ?>">
													<?php echo form_error('title', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>

												<div class="col-md-12 mrgt10">
													<label>Paragraph</label>
													<textarea  id="ckeditor" class="form-control" name="para" > <?php echo $edit_data[0]['paragraph']; ?></textarea>
													<?php echo form_error('title', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
											
												<div class="col-md-12 mrgt10 correct_ans">
													<label>Image Name</label>
													<input type="text" name="image_name" id="image_name" class="form-control" value="<?php echo $edit_data[0]['image_name']; ?>">
													<?php echo form_error('image_name', '<div class="errormsg">', '</div>'); ?>

													<img id="word_image" src="<?php echo base_url(); ?>uploads/words/<?php echo $edit_data[0]['category_id']; ?>/<?php echo $edit_data[0]['subcategory_id']; ?>/<?php echo $edit_data[0]['image_name']; ?>" class="mrgt10 img-responsive" width="60px" onerror="this.onerror=null;this.src='<?php echo base_url(); ?>assets/thumb_image_not_available.png';"/>
												<?php $root_path  = $this->config->item('root_path');   $file = $root_path.'uploads/words/'.$edit_data[0]['category_id'].'/'.$edit_data[0]['subcategory_id'].'/'.$edit_data[0]['image_name'] ;?>
												
												<?php $temp=0; if(file_exists($file)){ $temp=1;?>
														<a href="javascript:void(0);" onclick="remove_image();" id="remove_btn"  class="errormsg"> <i class="fa fa-remove"></i> </a>
												<?php } ?>
												
												</div>
												<div class="clearfix"></div>
												
												

												
										</div>

										<div class="col-md-5 col-sm-6">
											<input class="btn btn-primary btn-raised" id="add_new" type="button" name="add_new" value="ADD NEW question"> 
											
											<div class="cls-append">

												<?php foreach ($edit_data_list as $k => $v) { ?>
												

												<div class="card contain_box mrgt10">
													<div class="col-md-10 mrgt10">
													<label>Question</label>
													<input type="text" name="question[]"  class="form-control" value="<?php echo $v['question'] ?>">
														<?php echo form_error('phrase', '<div class="errormsg">', '</div>'); ?>
													</div>
													<div class="col-md-2 mrgt10">
														<a href="javascript:void(0);" class="remove" > <i class="btn-sm btn-danger fa fa-trash-o "></i> </a>
													</div>
													<?php $op = explode("#", $v['options']);  $ctn=1; foreach ($op as $k1 => $v1) { ?>

														<div class="col-md-5 mrgt10 correct_ans">
															<label>Option <?php echo $ctn; if($ctn=="1"){ ?> (correct) <?php } ?></label>
															<input type="text" name="option<?php echo $ctn; ?>[]"   class="form-control" value="<?php echo $v1;?>">
															<?php echo form_error('option'.$ctn, '<div class="errormsg">', '</div>'); ?>
														</div>
													
													<?php $ctn++; } ?>

													<div class="col-md-10 mrgt10">
													<label>Note</label>
													<input type="text" name="notes[]"  class="form-control" value="<?php echo $v['notes'] ?>">
														<?php echo form_error('notes', '<div class="errormsg">', '</div>'); ?>
													</div>
												</div>

												<?php } ?>
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

				<!-- BEGIN BLANK SECTION -->
			</div>
			<!-- END CONTENT -->


			<!-- Reapet DIV START -->
									<div class="repeat_cls hide">
												<div class="card contain_box mrgt10">
													<div class="col-md-10 mrgt10">
													<label>Question</label>
													<input type="text" name="question[]" disabled class="form-control" placeholder="Question">
														<?php echo form_error('phrase', '<div class="errormsg">', '</div>'); ?>
													</div>
													<div class="col-md-2 mrgt10">
														<a href="javascript:void(0);" class="remove" > <i class="btn-sm btn-danger fa fa-trash-o "></i> </a> 
													</div>
													<div class="col-md-5 mrgt10 correct_ans">
															<label>Option 1 (correct)</label>
															<input type="text" name="option1[]"  disabled class="form-control" placeholder="Option1">
															<?php echo form_error('option1', '<div class="errormsg">', '</div>'); ?>
													</div>
													<div class="col-md-5 mrgt10 correct_ans">
															<label>Option 2</label>
															<input type="text" name="option2[]"  disabled class="form-control" placeholder="Option2">
															<?php echo form_error('option2', '<div class="errormsg">', '</div>'); ?>
													</div>
													<div class="col-md-5 mrgt10 correct_ans">
															<label>Option 3</label>
															<input type="text" name="option3[]"  disabled class="form-control" placeholder="Option3">
															<?php echo form_error('option3', '<div class="errormsg">', '</div>'); ?>
													</div>
													<div class="col-md-5 mrgt10 correct_ans">
															<label>Option 4</label>
															<input type="text" name="option4[]"  disabled class="form-control" placeholder="Option4">
															<?php echo form_error('option4', '<div class="errormsg">', '</div>'); ?>
													</div>
													<div class="col-md-10 mrgt10">
													<label>Note</label>
													<input type="text" name="notes[]" disabled class="form-control" placeholder="Add Note">
														<?php echo form_error('notes[]', '<div class="errormsg">', '</div>'); ?>
													</div>

												</div>
									</div>
									<!-- Reapet DIV END -->
<script type="text/javascript">

function remove_image(){

	$("#image_name").val('');
	$("#word_image").hide();
	$("#remove_btn").hide();

}



$( document ).ready(function(){

	


	 	$(".contain_box .form-control").each(function(index){
			$(".contain_box .form-control").removeAttr("disabled");
		});

		//  var htmldata = $(".repeat_cls").html();
		//  $('.cls-append').append(htmldata);
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
			