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
								


								<div class="clearfix"></div>
								<!-- BEGIN DATATABLE 2 -->
								<form action="<?php echo base_url(); ?>admin_master/delete_all_category" method="post" onsubmit="return confirm_delete();">
									<div class="row">
										<h4 class="col-md-2 col-sm-3 mrgt15">User List</h4>
										<div class="col-md-10 col-sm-9 mrgt10">
											<a href="<?php echo base_url(); ?>admin_master/add_user" class="btn btn-primary btn-sm btn-raised pull-right ">Add New</a>
											
										</div><!--end .col -->
										<div class="clearfix"></div>
										<!-- BEGIN DATATABLE -->
										<div class="col-lg-12 category mrgt15">
											<div class="table-responsive no-margin">
												<table id="example" class="table order-column hover dataTable no-footer">
													<thead>
														<tr>
															<th></th>
															<th>Action</th>
															<th>Firstname</th>
															<th>Lastname</th>
															<th>Email</th>
															
															
														</tr>
													</thead>
													<tbody id="tbody">
														
														<?php
														foreach ($portal_users as $key) { ?>
														<tr>
															<td><?php echo $key['admin_id']; ?></td>
															
															<td><a href="<?php echo base_url();?>admin_master/edit_portaluser/<?php echo $key['admin_id']; ?>"><i class="fa fa-pencil action"></i> </a><a onclick="return confirm('Are you sure?');" href="<?php echo base_url();?>admin_master/delete_portaluser/<?php echo $key['admin_id']; ?>"> <i class="fa fa-trash-o action"></i> </a></td>	
															<td><?php echo $key['first_name'];?></td>
															<td><?php echo $key['last_name'];?></td>
															<td><?php echo $key['email']; ?></td>
															
															
														</tr>
														<?php } ?>
													</tbody>
												</table>
											</div><!--end .col -->
										
										</div>
										<!-- END DATATABLE 2 -->
									</div><!--end .row -->
								</form>
							</div><!--end .card-body -->
						</div><!--end .card -->
						<!-- END HORIZONTAL FORM - SIZES -->
					
					</div><!--end .section-body -->
				</section>

				<!-- BEGIN BLANK SECTION -->
			</div><!--end #content-->
			<!-- END CONTENT -->


<!-- <form action="<?php echo base_url(); ?>admin_master/type_import" method="post" name="upload_excel" enctype="multipart/form-data">
									<div class="form-group row">
										
										<div class="col-md-2 col-sm-4 col-xs-7 mrgt5">
											<div class="btn btn-primary btn-raised">
												<span>Choose Excel File</span>
												<input type="file" class="fileUpload" name="file" id="file" />
											</div>
											<span id="file_error" class="errormsg"> </span>
											<span id="selecte_file_name" class=""> </span>
										</div>
										<div class="col-md-2 col-sm-3 col-xs-5 mrgt5">
											<button type="submit" class="btn btn-primary btn-raised" id="submit" name="import">Import</button>
										</div>
										
									</div>
								</form> -->


<script type="text/javascript">

	$("#image_id").change(function(e){
	   //alert($('#file').val());
	   var names = [];
	    for (var i = 0; i < $(this).get(0).files.length; ++i) {
	        names.push($(this).get(0).files[i].name);
	    }
	    //$("input[name=file]").val(names);
	   $("#selecte_bulk_file_name").text(names);
	});

	function checkvalid_upload(){
		var Validat = 1;
		$("#bulk_file_error").text("");
		var $fileUpload = $("#image_id");
		if (parseInt($fileUpload.get(0).files.length)>150){
	       //  alert("You can only upload a maximum of 2 files");
	         $("#bulk_file_error").text("You can only upload a maximum of 150 files");
	         Validat = 0;
		}
		if (parseInt($fileUpload.get(0).files.length)==0){
	       //  alert("You can only upload a maximum of 2 files");
	         $("#bulk_file_error").text("Please Choose at least one file");
	         Validat = 0;
		}
			if(Validat==1){
				return true;
			}else{
				return false;
			}
	}
	$('#mode').change(function(){
	var modeid = this.value;

		$("#tbody").html("");
		$.ajax({
				url:'<?php echo base_url();?>admin_master/category_list/'+modeid,
				success:function(data){
					window.location.href = '<?php echo base_url();?>admin_master/category_list/'+modeid;		
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
	}else{

		var ext = file.split('.').pop();
		//console.log(ext);
		if(ext!="xlsx"){
			Validat=0;
			$("#file_error").text("file type not supported");
		}
	}
 		// return false;
		//var oFile = document.getElementById('file').files[0];
		//console.log(oFile);
		// var rFilter = /^(image\/png)$/i;
		// 	if (! rFilter.test(oFile.type)) {
		// 		//alert('unspoerted');
		// 		//alert('file not supported');
		// 		$("#file_error").text("file type not supported");
		// 		Validat=0;
		// 	}

		if(Validat==1){
			return true;
		}else{
			return false;
		}
}


	function confirm_delete(){

		var cm =  confirm("Are you sure ?");
		var Validat=1;
		if(cm==false){
			Validat=0;
		}
			if(Validat==1){
				return true;
			}else{
				return false;
			}
	}


// $("#ckbCheckAll").click(function () {
//     $(".checkBoxClass").prop('checked', $(this).prop('checked'));
// });

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

   // $('#ckbCheckAll').on('click', function(){
   //    // Get all rows with search applied
   //    var rows = oTable.rows({ 'search': 'applied' }).nodes();
   //    // Check/uncheck checkboxes for all rows in the table
   //    $('input[type="checkbox"]', rows).prop('checked', this.checked);
   // });

  $("#ckbCheckAll").click(function () {
        $('#example tbody input[type="checkbox"]').prop('checked', this.checked);
    });

   $('#example tbody').on('change', 'input[type="checkbox"]', function(){
      // If checkbox is not checked
      if(!this.checked){
      	 $('#ckbCheckAll').prop('checked','');
         // var el = $('#ckbCheckAll').get(0);
         // // If "Select all" control is checked and has 'indeterminate' property
         // if(el && el.checked && ('indeterminate' in el)){
         //    // Set visual state of "Select all" control 
         //    // as 'indeterminate'
         //    el.indeterminate = true;
         // }
      }
   });


  //  $('#mode1').on("change", function(e) {
  //   	var criteria = $("#mode1").val();
  //   //	alert(criteria);
  //   	if(criteria != "") {
		// 	criteria = "^"+criteria+"$";
		// }
		// oTable.fnFilter(criteria,"0",true,false);
  //   });
});






// var tbl = $('#datatable1').DataTable();
// $("input:checked", tbl.fnGetNodes()).each(function(){
// console.log($(this).val());
// });

$('input[type=file]').change(function(e){
   //alert($('#file').val());
   var filePath= $('#file').val();
   if(filePath.match(/fakepath/)) {
                        // update the file-path text using case-insensitive regex
                        filePath = filePath.replace(/C:\\fakepath\\/i, '');
                    }

   $("#selecte_file_name").text(filePath);
});
</script>
