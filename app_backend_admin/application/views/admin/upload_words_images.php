<!-- BEGIN BASE-->
		<div id="base">

			<!-- BEGIN CONTENT-->
			<div id="content">

				<!-- BEGIN BLANK SECTION -->
				<section>
					<!-- SECTION-HEADER -->
					<div class="section-header">
						<ol class="breadcrumb">
							<li><a href="#">Home</a></li>
							<li class="active">Bulk Images Upload</li>
							
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
//$attributes = array('action' => base_url().'admin_master/upload_word_images');
//echo form_open_multipart('admin_master/upload_word_images', $attributes);
?>
<form action="<?php echo base_url(); ?>admin_master/upload_word_images" 
method="post" name="upload_excel" enctype="multipart/form-data" onsubmit="return checkvalid();">
<?php if(isset($success_msg)){ ?>
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
													<select id="mode" name="mode" class="chosen-select">
														<option value="">Exercise Mode</option>
														<?php foreach($exercise_mode as $key){ ?>
												<option <?php if($mode==$key['id']) { echo "selected"; } ?> <?php echo set_select('mode', $key["id"]); ?> value="<?php echo $key['id'];?>"><?php echo $key['mode_name'];?> </option>
												<?php } ?>
													</select>
													<?php echo form_error('mode', '<div class="errormsg">', '</div>'); ?>
												</div>



												<div class="col-md-12 mrgt10">
													<label>Category</label>
													<select class="chosen-select" id="category" name="category" class="form-control">
														<option value="">Select Category</option>
														<?php foreach($category as $key){ ?>
							<option <?php if($cateid==$key['exercise_mode_category_id']) { echo "selected"; } ?>  value="<?php echo $key['exercise_mode_category_id'];?>"><?php echo $key['category_name_in_en'];?> </option>
						<?php } ?>
													</select>
													<?php echo form_error('category', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
											
											<div class="col-md-12 mrgt10">
													<label>SubCategory</label>
													<select class="chosen-select" id="subcate" name="subcategory" class="form-control">
														<option value="">Select SubCategory</option>
															<?php foreach($subcategory as $key){ ?>
						<option <?php if($subcateid==$key['exercise_mode_subcategory_id']) { echo "selected"; } ?>  value="<?php echo $key['exercise_mode_subcategory_id'];?>"><?php echo $key['subcategory_name_in_en'];?> </option>
						<?php } ?>
													</select>
													<?php echo form_error('subcategory', '<div class="errormsg">', '</div>'); ?>
												</div>

												<div class="clearfix"></div>
												<label class="col-md-12 mrgt10">Image</label>
												<div class="col-md-12 mrgt5">
													<div class="btn btn-primary btn-raised">
														<span>Choose File</span>
														<input type="file" name="userfile[]" class="fileUpload" multiple id="image_id" />
													</div>
<span id="selecte_file_name" class="" style="overflow-wrap: break-word;"> </span>
												</div>
												<span id="file_error" class="col-md-12 errormsg"> </span>
												<div class="clearfix"></div>
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
						$('#category').trigger("chosen:updated");
						
						//$().redirect('<?php echo base_url();?>admin_master/words_list', {'mode_id': modeid,'sort':sort,'per_page':per_page});

					//	window.location.href = '<?php echo base_url();?>admin_master/words_list/'+modeid;
					},
		});

	});
function checkvalid(){


	var Validat = 1;
	$("#file_error").text("");
	var $fileUpload = $("input[type='file']");


	if (parseInt($fileUpload.get(0).files.length)>150){

       //  alert("You can only upload a maximum of 2 files");
         $("#file_error").text("You can only upload a maximum of 150 files");
         Validat = 0;
	}
	if (parseInt($fileUpload.get(0).files.length)==0){

       //  alert("You can only upload a maximum of 2 files");
         $("#file_error").text("Please Choose at least one file");
         Validat = 0;
	}


		     if(Validat==1){
					return true;
				}else{
					return false;
				}

 }

//  $('.fileUpload').change(function(){
//     if(this.files.length>10)
//         alert('to many files')
// });

$('input[type=file]').change(function(e){
   //alert($('#file').val());
   var names = [];
    for (var i = 0; i < $(this).get(0).files.length; ++i) {
        names.push($(this).get(0).files[i].name);
    }
    //$("input[name=file]").val(names);

   $("#selecte_file_name").text(names);
});


	$('#category').change(function(){
	var cate_id = this.value;
	//var sp = cate_id.split("_");
///	var cate_id = sp[0];
	//var subcate = this.value;
	//var subcate= "";
	//var mode= $('#mode').val();

			$.ajax({
					url:'<?php echo base_url();?>admin_master/get_subcat_from_cate',
					type:'POST',
					data:{cate_id:cate_id},
					success:function(data){
						//$('#category').html("");
						//alert('here');
						$('#subcate').find('option').remove().end().append(data);
						$('#subcate').trigger("chosen:updated");
						//window.location.href = '<?php echo base_url();?>admin_master/words_list/'+mode+'/'+cate_id+'/'+subcate;
						//$().redirect('<?php echo base_url();?>admin_master/words_list', {'mode_id': mode,'cate_id':cate_id,'subcate_id':subcate});
					},			
			}); 

	});
		</script>