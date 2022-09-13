

		<!-- BEGIN BASE-->
		<div id="base">

			<!-- BEGIN CONTENT-->
			<div id="content">

				<!-- BEGIN BLANK SECTION -->
				<section>
					<div class="section-header">
						<ol class="breadcrumb">
							<li><a href="#">Home</a></li>
							<li class="active">Exercise Type List</li>
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
										<h4>Exercise Type List</h4>
									</div><!--end .col -->
									<div class="col-lg-12 category mrgt15">
										<div class="table-responsive no-margin">
											<table id="example" class="table hover">
												<thead>
													<tr>
														
														<th>Id</th>
														<th>Action</th>
														<th>Image</th>
														<th>Exercise Mode</th>
														<?php foreach($source_lang as $key){ ?>
														<th> Type Name in <?= $key['language_name'];?></th>
														<?php }?>
													</tr>
												</thead>
												<tbody>
													<?php $ctn=1;
													foreach ($type_list as $key) { ?>
													<tr>
														<td><?php echo $ctn; ?></td>
																											    <td><a href="<?php echo base_url();?>admin_master/edit_type/<?php echo $key['id']; ?>"><i class="fa fa-pencil action"></i> </a> </td>

														<td><img src="<?php echo base_url(); ?>uploads/<?php echo $key['image']; ?>" class="img-responsive" width="60px" onerror="this.onerror=null;this.src='<?php echo base_url(); ?>assets/thumb_image_not_available.png';"/></td>
														<td><?php echo $key['mode_name']; ?></td>
															<?php foreach($source_lang as $langkey){ ?>
														<td><?php echo ucfirst($key['type_'.$langkey['language_code']]); ?></td>
															<?php } ?>
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
				//console.log('here');
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
</script>
