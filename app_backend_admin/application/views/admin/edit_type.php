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
							<li class="active">Edit Type</li>
							<a href="<?php echo base_url(); ?>admin_master/type_list" class="btn btn-primary btn-sm btn-raised pull-right">Back To List</a>
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
$attributes = array('action' => base_url().'admin_master/edit_type/'.$edit_data[0]['id']);
echo form_open_multipart('admin_master/edit_type/'.$edit_data[0]['id'], $attributes);
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
												
												<div class="clearfix"></div>
												

												<?php foreach($source_lang as $key){?>
												<div class="col-md-12 mrgt10">
													<label>Type Name In <?= $key['language_name'];?></label>
													<input type="text" name="type_name_<?= $key['language_code']; ?>" class="form-control" placeholder="Type Name in <?= $key['language_name']; ?>" value="<?php echo $edit_data[0]['type_'.$key['language_code']];?>">
													<?php echo form_error('type_name_'.$key['language_code'], '<div class="errormsg">', '</div>'); ?>
												</div>
												<div class="clearfix"></div>
												<?php } ?>


												<div class="clearfix"></div>
												<label class="col-md-12 mrgt10">Image</label>
												<div class="col-md-12 mrgt5">
													<div class="btn btn-primary btn-raised">
														<span>Choose File</span>
														<input type="file" name="userfile" class="fileUpload" />
													</div>
												</div>
												<div class="clearfix"></div>
												<div class="col-md-5 col-sm-3 col-xs-5 mrgt15">
													
													<input class="btn btn-primary btn-raised" type="submit" name="save" value="Update"> 
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