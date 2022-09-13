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
							<li class="active">Add Lang</li>
							
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
$attributes = array('action' => base_url().'admin_master/add_dyamic_lang','onsubmit'=>'return checkvalid()');
echo form_open_multipart('admin_master/add_dyamic_lang', $attributes);
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
													<label>Name</label>
													<input type="text" name="name" class="form-control" placeholder="lang name">
													<?php echo form_error('name', '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
											
											<div class="col-md-12 mrgt10">
													<label>code</label>
													<input type="text" name="code" class="form-control" placeholder="lang code">
													<?php echo form_error('code', '<div class="errormsg">', '</div>'); ?>
												</div>
												
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