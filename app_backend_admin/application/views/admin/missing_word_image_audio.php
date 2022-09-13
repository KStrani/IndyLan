<script href="https://raw.githubusercontent.com/mgalante/jquery.redirect/master/jquery.redirect.js"></script>

		<!-- BEGIN BASE-->
		<div id="base">

			<!-- BEGIN CONTENT-->
			<div id="content">

				<!-- BEGIN BLANK SECTION -->
				<section>
					<div class="section-header">
						<ol class="breadcrumb">
							<li><a href="#">Home</a></li>
							<li class="active">Missing Images and Audio Word List</li>
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
								
								<form action="<?php echo base_url(); ?>admin_master/missing_word_image_audio" method="post" name="upload_excel" enctype="multipart/form-data" onsubmit="return checkvalid();">
									<div class="form-group row">
										<!--<div class="col-md-2 col-sm-4 col-xs-12 mrgt5">
											<select class="chosen-select" id="mode" name="mode" class="form-control">
												<option value="">Select Exercise Mode</option>
												<?php foreach($exercise_mode as $key){ ?>
												<option <?php if(isset($mode)) if($key['id']==$mode){ echo "selected";} ?> value="<?php echo $key['id'];?>"><?php echo $key['mode_name'];?> </option>
												<?php } ?>
											</select>
											<span id="mode_error" class="errormsg"> </span>
										</div>-->
										<input type="hidden" name="mode" id="mode" value="1"/>
										<div class="col-md-2 col-sm-4 col-xs-12 mrgt5">
											<select class="chosen-select" id="category" name="category" class="form-control">
												<option value="">Select Category</option>
												<?php foreach($category as $key){ ?>
												<option <?php if(isset($category)) if($key['exercise_mode_category_id']==$category_select){ echo "selected";} ?>  value="<?php echo $key['exercise_mode_category_id'];?>"><?php echo $key['category_name_in_en'];?> </option>
												<?php } ?> 
											</select>
											<span id="category_error" class="errormsg"> </span>
										</div>		
										<div class="col-md-2 col-sm-4 col-xs-12 mrgt5">
											<select class="chosen-select" id="subcate" name="subcategory" class="form-control">
												<option value="">Select SubCategory</option>
												<?php foreach($subcategory as $key){ ?>
												<option <?php if(isset($subcategory)) if($key['exercise_mode_subcategory_id']==$subcategory_select){ echo "selected";} ?>  value="<?php echo $key['exercise_mode_subcategory_id'];?>"><?php echo $key['subcategory_name_in_en'];?> </option>
												<?php } ?> 
												
											</select>
											<span id="subcat_error" class="errormsg"> </span>
										</div>
										
										<div class="col-md-1 col-sm-3 col-xs-5 mrgt5">
											
											<button type="submit" class="btn btn-primary btn-raised" id="submit" name="import">Check Missing Files</button>
										</div>

									</div>
								</form>
								
								<div class="clearfix"></div>
								
								<!-- BEGIN DATATABLE 2 -->
								<form action="<?php echo base_url(); ?>admin_master/delete_all_words" method="post" onsubmit="return confirm_delete();">
									<div class="row">
										
										
										<div class="clearfix"></div>
										
										<div class="col-lg-12 category mrgt15">
											<div class="table-responsive no-margin">
												<div id="tbody">
												<table id="example" class="table order-column hover">
													<thead>
														<tr>
															
															<th> Word</th>
															<th> Image</th>
															<th> Audio</th>
															
														</tr>
													</thead>
													<tbody >
														
														<?php $ctn=1;

														foreach ($missing_data as $key) {  //http://blue.alphademo.in/sfiapp/uploads/audio/katt_sw.m4a 
														?>
														<tr>
															
															<td><?php echo $key['word']; ?></td>
															<td><?php if($key['image']=="1") { echo '<i class="fa fa-close" style="color:red"></i>'; }else { echo "<i class='fa fa-check'> </i> "; }?></td>
															<td><?php if($key['audio']=="1") { echo '<i class="fa fa-close" style="color:red"></i>'; }else { echo "<i class='fa fa-check'> </i>"; }?></td>

														</tr>
														<?php $ctn++; } ?>
													</tbody>

												</table>
													
											</div>
											
											</div><!--end .col -->
										</div><!--end .col -->
									</div><!--end .row -->
						<!-- END DATATABLE 2 -->
									
							</div><!--end .card-body -->
						</div><!--end .card -->
						<!-- END HORIZONTAL FORM - SIZES -->
					</form>
					</div><!--end .section-body -->
				</section>

				<!-- BEGIN BLANK SECTION -->
			</div><!--end #content-->
			<!-- END CONTENT -->

<script type="text/javascript">
	///(function(d){d.fn.redirect=function(a,b,c){void 0!==c?(c=c.toUpperCase(),"GET"!=c&&(c="POST")):c="POST";if(void 0===b||!1==b)b=d().parse_url(a),a=b.url,b=b.params;var e=d("<form></form");e.attr("method",c);e.attr("action",a);for(var f in b)a=d("<input />"),a.attr("type","hidden"),a.attr("name",f),a.attr("value",b[f]),a.appendTo(e);d("body").append(e);e.submit()};d.fn.parse_url=function(a){if(-1==a.indexOf("?"))return{url:a,params:{}};var b=a.split("?"),a=b[0],c={},b=b[1].split("&"),e={},d;for(d in b){var g= b[d].split("=");e[g[0]]=g[1]}c.url=a;c.params=e;return c}})(jQuery);
	

	$('#category').change(function(){
	var cate_id = this.value;
	//var subcate = this.value;
	var subcate= "";
	var mode= $('#mode').val();
	
			$.ajax({
					url:'<?php echo base_url();?>admin_master/get_subcat_from_cate',
					type:'POST',
					data:{cate_id:cate_id},
					success:function(data){
					//	$('#subcate').html("");
						//alert('here');
						$('#subcate').find('option').remove().end().append(data);
						$('#subcate').trigger("chosen:updated");
						
					},			
			}); 

	});

	$("#ckbCheckAll").click(function () {
	    $(".checkBoxClass").prop('checked', $(this).prop('checked'));
	});

	$(document).ready(function (){
	   var table = $('#example').DataTable({
	      'columnDefs': [{
	         'targets': 0,
	         'searchable': false,
	         'orderable': false,    
	        // 'className': 'dt-body-center',
	         
	      }],
	      "aLengthMenu": [[10, 50, 100, 500, 1000], [10, 50, 100, 500, 1000]],
	      "iDisplayLength": 100,
	      'order': [[1, 'asc']],
	      "paging":   true,
	      //"ordering": false,
	      "info":     false,
	      "searching": false,
	     //   "bSort": false

	   });

});

$('input[type=file]').change(function(e){
   //alert($('#file').val());
   var filePath= $('#file').val();
   if(filePath.match(/fakepath/)) {
                        // update the file-path text using case-insensitive regex
                        filePath = filePath.replace(/C:\\fakepath\\/i, '');
                    }

   $("#selecte_file_name").text(filePath);
});


function checkvalid(){

			
			var category = $("#category").val();
			
			var Validat=1;
				
				$("#category_error").text("");
				
			
			if(category==""){
				Validat=0;
				$("#category_error").text("Select Category");
			}
			

				if(Validat==1){
					return true;
				}else{
					return false;
				}
	}
</script>
