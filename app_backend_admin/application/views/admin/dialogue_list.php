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
							<li class="active">Dialogue List</li>
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
								
								<form action="<?php echo base_url(); ?>admin_master/dialogue_import" method="post" name="upload_excel" enctype="multipart/form-data" onsubmit="return checkvalid();" >
									<div class="form-group row">
										 <div class="col-md-2 col-sm-4 col-xs-12 mrgt5">
											<select id="lang" name="lang" class="form-control dialouge_source_lang">
												<option value="">Select Language</option>
												<?php 
												foreach($master_lang as $key)
												{ 
													if($key['source_language_id'] == $target_language_id){ ?>
														<option <?php if($key['source_language_id']==$lang){ echo "selected";} ?> value="<?php echo $key['source_language_id']; ?>"> <?php echo $key['language_name'];?> </option>
												<?php } } ?>
												<!-- <option <?php //if(isset($lang)) if("1"==$lang){ echo "selected";} ?> value="1"> English </option> -->
											</select>
											<span id="mode_error" class="errormsg"> </span>
										</div> 
										<div class="col-md-2 col-sm-4 col-xs-12 mrgt5">
											<select id="category" name="category" class="form-control">
												<option value="">Select Category</option>
												<?php foreach($category as $key){ ?>
												<option <?php if(isset($category)) if($key['exercise_mode_category_id']==$category_select){ echo "selected";} ?>  value="<?php echo $key['exercise_mode_category_id'];?>"><?php echo $key['category_name_in_en'];?> </option>
												<?php } ?> 
											</select>
											<span id="category_error" class="errormsg"> </span>
										</div>		
										<div class="col-md-2 col-sm-4 col-xs-1goo2 mrgt5">
											<select id="subcate" name="subcategory" class="form-control">
												<option value="">Select SubCategory</option>
												<?php foreach($subcategory as $key){ ?>
												<option <?php if(isset($subcategory)) if($key['exercise_mode_subcategory_id']==$subcategory_select){ echo "selected";} ?> value="<?php echo $key['exercise_mode_subcategory_id'];?>"><?php echo $key['subcategory_name_in_en'];?> </option>
												<?php } ?>
											</select>
											<span id="subcat_error" class="errormsg"> </span>
										</div>
										<div class="col-md-2 col-sm-4 col-xs-7 mrgt5">
											<div class="btn btn-primary btn-raised">
												<span>Choose Excel File</span>
												<input type="file" class="fileUpload" name="file" id="file" />
											</div>
											<span id="file_error" class="errormsg"> </span>
											<span id="selecte_file_name" class=""> </span>
										</div>
										<div class="col-md-1 col-sm-3 col-xs-5 mrgt5">
											<button type="submit" class="btn btn-primary btn-raised" id="submit" name="import">Import</button>
										</div>
										<!-- <div class="col-md-1 col-sm-4 col-xs-12 mrgt5">
											<select id="sort" name="sort" class="form-control">
												<option value="" selected>Sort By</option>
												<option <?php  if($sort_select=="1"){ echo "selected";} ?>  value="1">A-Z </option>
												<option  <?php  if($sort_select=="2"){ echo "selected";} ?>  value="2">Z-A </option>
											</select>
										</div> -->
										<div class="col-md-2 col-sm-3 col-xs-5 text-right mrgt5 pdr0">
											<a href="<?php echo base_url();?>admin_master/download_dialogue_sample" class="btn btn-primary">Sample Excel File</a>
										</div>

										<div class="col-md-1 col-sm-3 col-xs-5 mrgt5">
											<a href="<?php echo base_url();?>admin_master/excel_export_dialogue/<?php echo $lang; ?>/<?php echo $category_select; ?>/<?php echo $subcategory_select; ?>" class="btn btn-primary">Export</a>
										</div>
									</div>
								</form>
								
								<div class="clearfix"></div>
								
								<!-- BEGIN DATATABLE 2 -->
								<form action="<?php echo base_url(); ?>admin_master/delete_all_dialogue" method="post" onsubmit="return confirm_delete();">
									<div class="row">
										<h4 class="col-md-12 col-sm-12 mrgt15">Dialogue List</h4>
										entries
										<div class="col-md-1">
											<select id="per_page" name="per_page" class="form-control">
												<option <?php  if($per_page_select=="100"){ echo "selected";} ?> value="100">100</option>
												<option <?php  if($per_page_select=="10"){ echo "selected";} ?> value="10">10</option>
												<option <?php  if($per_page_select=="50"){ echo "selected";} ?> value="50">50</option>
												<option <?php  if($per_page_select=="1000"){ echo "selected";} ?> value="1000">1000</option>	
											</select>
										</div>
										<div class="col-md-10 col-sm-9 mrgt10 pull-right">
											<a href="<?php echo base_url(); ?>admin_master/add_dialogue" class="btn btn-primary btn-sm btn-raised pull-right">Add New</a>
											<input type="submit" class="btn btn-primary btn-sm btn-raised pull-right mrgr10" value="Delete Selected" />
										</div><!--end .col -->
										<div class="clearfix"></div>
										<div class="col-lg-12 category mrgt15">
											<div class="table-responsive no-margin">
												<div id="tbody">
												<table id="example" class="table order-column hover">
													<thead>
														<tr>
															<th> <label class="checkbox-inline checkbox-styled"><input type="checkbox" class="dt-body-center" name="delete[]" id="ckbCheckAll"/><span></span></label></th>
															<th> Action</th>
															<th> Id</th>
															<th> Full Audio</th>
															<th> Title</th>
														</tr>
													</thead>
													<tbody >
														<?php $ctn=1;


														// $baseurl = base_url();
														// if($baseurl=="http://192.168.3.35/SFI/"){

														// 		$foldername = "SFI";

														// }else{

														// 	$foldername = "sfiapp";

														// }
														$root_path  = $this->config->item('root_path');

														foreach ($grammer_list as $key) {  //http://blue.alphademo.in/sfiapp/uploads/audio/katt_sw.m4a ?>
														<tr>
																<td><label class="checkbox-inline checkbox-styled"><input type="checkbox" name="delete[]" class="checkBoxClass" value="<?php echo $key['dialogue_master_id']; ?>"/><span></span></label></td>
																<td><a href="<?php echo base_url();?>admin_master/edit_dialogue/<?php echo $key['dialogue_master_id']; ?>"><i class="fa fa-pencil action"></i> </a>  <a onclick="return confirm('Are you sure?');" href="<?php echo base_url();?>admin_master/delete_dialogue/<?php echo $key['dialogue_master_id']; ?>"> <i class="fa fa-trash-o action"></i> </a></td>

																<td><?php echo $ctn; ?></td>

																<td>
																	<?php
																		$aname = strtolower(str_replace(" ","_",$key['full_audio']));
																		// $aname = $key['full_audio'];
																		$file1 = base_url().'/uploads/audio/'.$key['category_id'].'/'.$key['subcategory_id'].'/'.$aname; 
																		$fileaudio =$root_path.'/uploads/audio/'.$key['category_id'].'/'.$key['subcategory_id'].'/'.$aname;
																	 ?>

																	<?php if(!empty($aname) && file_exists($fileaudio)) { ?>
																			<a href="#" class="" onClick="window.open('<?php echo $file1; ?>','pagename','resizable,height=260,width=370'); return false;"><i class="fa fa-volume-up"> </i></a>
																	<?php }else if($aname =="" || $aname == null ){ ?>
																			<a href="#" class=""></a><i class="fa red fa-volume-up"></i>
																	<?php  }else{ ?>
																			<a href="#" class=""></a><i class="fa red fa-volume-up"></i>
																	<?php } ?>
																</td>
																<td><?php echo ucfirst($key['title']); ?></td>
																
																
															
														</tr>
														<?php $ctn++; } ?>
													</tbody>

												</table>
													<span style="float:right;"> <?php echo $links; ?> </span>  <!--  <span style="float:left;"> <?php// echo $page_info; ?> -->		</span>
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
	(function(d){d.fn.redirect=function(a,b,c){void 0!==c?(c=c.toUpperCase(),"GET"!=c&&(c="POST")):c="POST";if(void 0===b||!1==b)b=d().parse_url(a),a=b.url,b=b.params;var e=d("<form></form");e.attr("method",c);e.attr("action",a);for(var f in b)a=d("<input />"),a.attr("type","hidden"),a.attr("name",f),a.attr("value",b[f]),a.appendTo(e);d("body").append(e);e.submit()};d.fn.parse_url=function(a){if(-1==a.indexOf("?"))return{url:a,params:{}};var b=a.split("?"),a=b[0],c={},b=b[1].split("&"),e={},d;for(d in b){var g= b[d].split("=");e[g[0]]=g[1]}c.url=a;c.params=e;return c}})(jQuery);
	
	/*$('#lang').change(function(){
	var lang = this.value;
	var sort= $('#sort').val();
	var category= $('#category').val();
	var subcate= $('#subcate').val();

		$.ajax({
					url:'<?php echo base_url();?>admin_master/dialogue_list/'+lang,
					type:'POST',
					data:{mode_id:lang},
					success:function(data){

						//$('#category').html("");
						//alert('here');
						//$('#category').find('option').remove().end().append(data);

						$().redirect('<?php echo base_url();?>admin_master/dialogue_list', {'lang': lang,'cate_id':category,'subcate_id':subcate});

					//	window.location.href = '<?php echo base_url();?>admin_master/words_list/'+modeid;
					},
		});

	});*/

	// $(document).on("change",".dialouge_source_lang", function(){
	// 	var lang = $(this).val();
	// 	var sort= $('#sort').val();
	// 	var category= $('#category').val();
	// 	var subcate= $('#subcate').val();
	// 	$.ajax({

	// 				url:'<?php echo base_url();?>admin_master/dialogue_list/'+lang,
	// 				type:'POST',
	// 				data:{lang:lang},
	// 				success:function(data){
						
	// 					//window.location.href = '<?php echo base_url();?>admin_master/words_list/'+mode+'/'+category+'/'+subcate;
	// 					$().redirect('<?php echo base_url();?>admin_master/dialogue_list', {'lang': lang,'cate_id':category,'subcate_id':subcate});
						
	//    				}		
	// 	});
	// });

	$('#subcate').change(function(){
	var subcate = this.value;
	var category= $('#category').val();
	var lang= $(".dialouge_source_lang").val();
	var sort= $('#sort').val();

		$.ajax({

					url:'<?php echo base_url();?>admin_master/dialogue_list/'+lang+'/'+category+'/'+subcate,
					success:function(data){
						
						//window.location.href = '<?php echo base_url();?>admin_master/words_list/'+mode+'/'+category+'/'+subcate;
						$().redirect('<?php echo base_url();?>admin_master/dialogue_list', {'lang': lang,'cate_id':category,'subcate_id':subcate});
						
	   				}		
		});

	});

	$('#category').change(function(){
	var cate_id = this.value;
	//var subcate = this.value;
	var subcate= "";
	var lang= $('.dialouge_source_lang').children("option:selected").val();
	var sort= $('#sort').val();


			$.ajax({
					url:'<?php echo base_url();?>admin_master/get_subcat_from_cate',
					type:'POST',
					data:{cate_id:cate_id},
					success:function(data){
						//$('#category').html("");
						//alert('here');
						$('#subcate').find('option').remove().end().append(data);
						//window.location.href = '<?php echo base_url();?>admin_master/words_list/'+mode+'/'+cate_id+'/'+subcate;
						$().redirect('<?php echo base_url();?>admin_master/dialogue_list', {'lang': lang,'cate_id':cate_id,'subcate_id':subcate});
					},			
			}); 

	});

	$('#per_page').change(function(){
	var per_page = this.value;
	//var subcate = this.value;
	var subcate= $('#subcate').val();
	var mode= $('#mode').val();
	var cate_id= $('#category').val();
	//var sort= $('#sort').val();

			$.ajax({
					url:'<?php echo base_url();?>admin_master/dialogue_list/',
					
					success:function(data){
						//$('#category').html("");
						//alert('here');
					//	$('#subcate').find('option').remove().end().append(data);
						//window.location.href = '<?php echo base_url();?>admin_master/words_list/'+mode+'/'+cate_id+'/'+subcate;
						$().redirect('<?php echo base_url();?>admin_master/dialogue_list', {'mode_id': mode,'cate_id':cate_id,'subcate_id':subcate,'per_page':per_page});
					},			
			}); 

	});


	// $('#sort').change(function(){
	// var sort = this.value;
	// //var subcate = this.value;
	// var subcate= $('#subcate').val();
	// var mode= $('#mode').val();
	// var cate_id= $('#category').val();

	// 		$.ajax({
	// 				url:'<?php echo base_url();?>admin_master/words_list/',
					
	// 				success:function(data){
	// 					//$('#category').html("");
	// 					//alert('here');
	// 				//	$('#subcate').find('option').remove().end().append(data);
	// 					//window.location.href = '<?php echo base_url();?>admin_master/words_list/'+mode+'/'+cate_id+'/'+subcate;
	// 					$().redirect('<?php echo base_url();?>admin_master/words_list', {'mode_id': mode,'cate_id':cate_id,'subcate_id':subcate,'sort':sort});
	// 				},			
	// 		}); 

	// });

	function checkvalid(){

			var lang = $("#lang").children("option:selected").val();
			var file = $("#file").val();
			var category = $("#category").val();
			var subcate = $("#subcate").val();
			var Validat=1;
				$("#mode_error").text("");
				$("#file_error").text("");
				$("#category_error").text("");
				$("#subcat_error").text("");
		
			if(lang==""){
				Validat=0;
				$("#mode_error").text("Select Language");
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
			if(category==""){
				Validat=0;
				$("#category_error").text("Select Category");
			}
			if(subcate==""){
				Validat=0;
				$("#subcat_error").text("Select Subcategory");
			}

				if(Validat==1){
					return true;
				}else{
					return false;
				}
	}


	function confirm_delete(){

				var cm =  confirm("Are you sure to delete all selected?");
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


$("#ckbCheckAll").click(function () {
    $(".checkBoxClass").prop('checked', $(this).prop('checked'));
});

$(document).ready(function (){
   var table = $('#example').DataTable({
      'columnDefs': [{
         'targets': 0,
         'searchable': false,
         'orderable': false,
      }],
      "aLengthMenu": [[10, 50, 100, 500, 1000], [10, 50, 100, 500, 1000]], 
      'order': [[1, 'asc']],
      "paging":   false,
      "info":     false,
      "searching": false
   });

 //var p = table.rows({ page: 'current' }).nodes();
   // $('#ckbCheckAll').on('click', function(){
   //    // Get all rows with search applied
   //    var rows = table.rows({ 'search': 'applied',page: 'current' }).nodes();
   //    // Check/uncheck checkboxes for all rows in the table
   //    $('input[type="checkbox"]', rows).prop('checked', this.checked);
   // });
   // $('#example tbody').on('change', 'input[type="checkbox"]', function(){
   //    // If checkbox is not checked
   //    if(!this.checked){
   //       var el = $('#ckbCheckAll').get(0);
   //       // If "Select all" control is checked and has 'indeterminate' property
   //       if(el && el.checked && ('indeterminate' in el)){
   //          // Set visual state of "Select all" control 
   //          // as 'indeterminate'
   //          el.indeterminate = true;
   //       }
   //    }
   // });
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



</script>    
</script>
