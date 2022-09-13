	<!-- BEGIN BASE-->
		<div id="base">

			<!-- BEGIN CONTENT-->
			<div id="content">

				<!-- BEGIN BLANK SECTION -->
				<section>
					<div class="section-header">
						<ol class="breadcrumb">
							<li><a href="#">Home</a></li>
							<li class="active">User List</li>
						</ol>
					</div><!--end .section-header -->
					<div class="section-body">
						
						<!-- BEGIN HORIZONTAL FORM - SIZES -->
						<div class="card">
							<div class="card-body">
								<?php if(isset($success_msg)){ ?>
								<div class="success-message"><?php echo $success_msg; ?></div>
								<?php } ?>
								<?php 
								if(isset($error_msg)){ ?>
								<div class="error-message"><?php echo $error_msg; ?></div>
								<?php } ?>
							
								<!-- BEGIN DATATABLE 2 -->
								<div class="row">
									<div class="col-md-12 mrgt5">
										<h4>User List</h4>
									</div><!--end .col -->
									<div class="col-lg-12 category mrgt15">
										<div class="table-responsive no-margin">
											<table id="example" class="table hover">
												<thead>
													<tr>
														<th>Id</th>
														<th>Profile Image</th>
														<th>User Name</th>
														<th>Email</th>
														
													</tr>
												</thead>
												<tbody>
													<?php $ctn=1;
													foreach ($user_list as $key) { ?>
														<tr>
															<td><?php echo $ctn; ?></td>	
															<?php if($key['social_type'] == "0"){?>
															<td><img src="<?php echo base_url(); ?><?php echo $key['profile_pic']; ?>" class="img-responsive" width="60px" onerror="this.onerror=null;this.src='<?php echo base_url(); ?>assets/thumb_image_not_available.png';"/></td>
															<?php } else{ ?>
															<td><img src="<?php echo $key['social_pic']; ?>" class="img-responsive" width="60px" onerror="this.onerror=null;this.src='<?php echo base_url(); ?>assets/thumb_image_not_available.png';"/></td>
															<?php } ?>
															<td><?php echo $key['first_name']; ?></td>
															<td><?php echo $key['email']; ?></td>
														</tr>
													<?php $ctn++; } ?>
												</tbody>
											</table>
										</div><!--end .col -->
									</div><!--end .col -->
								</div><!--end .row -->
								<!-- END DATATABLE 2 -->
								
							</div><!--end .card-body -->
						</div><!--end .card -->
						<!-- END HORIZONTAL FORM - SIZES -->
					
					</div><!--end .section-body -->
				</section>

				<!-- BEGIN BLANK SECTION -->
			</div><!--end #content-->
			<!-- END CONTENT -->

<script type="text/javascript">


	var oTable;
$(document).ready(function (){
    oTable = $('#example').dataTable({
      'columnDefs': [{
         'targets': 0,
         'searchable': false,
         'orderable': false,
		 'visible': false,
         'className': 'dt-body-center',
         
      }],
      "bFilter": true,
      "aLengthMenu": [[10, 50, 100, 500, 1000], [10, 50, 100, 500, 1000]],
      "iDisplayLength": 100,
      'order': [[1, 'asc']]
   });

});
	$('#mode').change(function(){
		var modeid = this.value;
		$.ajax({
				
				url:'<?php echo base_url();?>admin_master/get_mode_category',
				type:'POST',
				data:{mode_id:modeid},
				sucess:function(data){
					var results = JSON.parse(data);
					var arrayReturn = [], results = returnData;
			        for (var i = 0, len = results.length; i < len; i++){
		                var result = results[i];
		                arrayReturn.push([ result.Age, result.Name]);
			       	}
				}
		});
	});

	function checkvalid(){
					var mode = $("#mode").val();
					var file = $("#file").val();
					var Validat=1;
					$("#mode_error").text("");
					$("#file_error").text("");
					if(mode==""){
						Validat=0;
						$("#mode_error").text("Select Exercise Mode");
					}
					if(file==""){
						Validat=0;
						$("#file_error").text("Choose Excel File");
					}

					if(Validat==1){
						return true;
					}else{
						return false;
					}
	}

</script>
