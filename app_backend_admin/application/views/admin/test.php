
		<!-- BEGIN BASE-->
		<div id="base">
			<!-- BEGIN CONTENT-->
			<div id="content">
				<!-- BEGIN SECTION -->
				<section>
					<!-- SECTION-HEADER -->
					<div class="section-header">
						<ol class="breadcrumb">
							<li><a href="index.html">Home</a></li>
							<li class="active">Add Category List</li>
						</ol>
					</div>
					<!-- END SECTION-HEADER -->
					
					<!-- SECTION-BODY -->
					<div class="section-body">
						<!-- BEGIN CARD -->
						<div class="card">
							<!-- BEGIN CARD-BODY -->
							<div class="card-body">
								<!-- BEGIN HORIZONTAL FORM - SIZES -->
								<form class="form-horizontal">
									<div class="form-group">
										<div class="col-md-2 col-sm-4 col-xs-12 mrgt10">
											<select class="selectpicker" data-live-search="true">
												<option value="">Select Exercise Mode</option>
												<option value="30">30</option>
												<option value="40">40</option>
												<option value="50">50</option>
												<option value="60">60</option>
												<option value="70">70</option>
											</select>
										</div>
										<div class="col-md-3 col-sm-6 col-xs-12 mrgt10 text-center">
											<div class="input-group">
												<label class="input-group-btn">
													<span class="btn btn-primary">
														Choose Excel File <input type="file" style="display: none;" multiple>
													</span>
												</label>
												<input type="text" class="form-control" readonly>
											</div>
										</div>
										<div class="col-md-2 col-sm-2 col-xs-12 mrgt10">
											<div class="btn btn-primary btn-raised">Upload</div>
										</div>
									</div>
								</form>
								<!-- END HORIZONTAL FORM - SIZES -->
								
								<!-- BEGIN ROW -->
								<div class="row">
									<h4 class="col-md-2 col-sm-3 mrgt15">Category List</h4>
									<div class="col-md-10 col-sm-9 mrgt10">
										<a href="addcategory.html" class="btn btn-primary btn-sm btn-raised pull-right">Add New</a>
										<input type="submit" class="btn btn-primary btn-sm btn-raised pull-right mrgr10" value="Delete Selected" />
									</div>
									<div class="clearfix"></div>
									<!-- BEGIN DATATABLE -->
									<div class="col-lg-12 category mrgt15">
										<div class="table-responsive no-margin">
										<table id="datatable2" class="table order-column hover">
											<thead>
												<tr>
													<th></th>
													<th>Name</th>
													<th>Position</th>
													<th>Office</th>
													<th>Salary</th>
												</tr>
											</thead>

											<tbody>
												<tr>
													<td>1</td>
													<td><img src="img/tiger.png" class="img-responsive" alt=""></td>
													<td>Animal</td>
													<td>Animal</td>
													<td>Animal</td>
													
												</tr>

											
											</tbody>
										</table>
										<table id="datatable2" class="table hover hidden">
											<thead>
												<tr>
													<th>Id</th>
													<th>Image</th>
													<th>Category Name</th>
													<th>Category Name</th>
													<th>Category Name</th>
													<th>Category Name</th>
													<th class="action">Action</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1</td>
													<td><img src="img/tiger.png" class="img-responsive" alt=""></td>
													<td>Animal</td>
													<td>Animal</td>
													<td>Animal</td>
													<td>Animal</td>
													<td><i class="fa fa-pencil action"></i><i class="fa fa-trash-o action"></i></td>
												</tr>
												<tr>
													<td>2</td>
													<td><img src="img/crow.png" class="img-responsive" alt=""></td>
													<td>Bird</td>
													<td>Bird</td>
													<td>Bird</td>
													<td>Bird</td>
													<td><i class="fa fa-pencil action"></i><i class="fa fa-trash-o action"></i></td>
												</tr>
												<tr>
													<td>3</td>
													<td><img src="img/fish.png" class="img-responsive" alt=""></td>
													<td>Fish</td>
													<td>Fish</td>
													<td>Fish</td>
													<td>Fish</td>
													<td><i class="fa fa-pencil action"></i><i class="fa fa-trash-o action"></i></td>
												</tr>
												<tr>
													<td>4</td>
													<td><img src="img/goat.png" class="img-responsive" alt=""></td>
													<td>Animal</td>
													<td>Animal</td>
													<td>Animal</td>
													<td>Animal</td>
													<td><i class="fa fa-pencil action"></i><i class="fa fa-trash-o action"></i></td>
												</tr>
												<tr>
													<td>5</td>
													<td><img src="img/hen.png" class="img-responsive" alt=""></td>
													<td>Bird</td>
													<td>Bird</td>
													<td>Bird</td>
													<td>Bird</td>
													<td><i class="fa fa-pencil action"></i><i class="fa fa-trash-o action"></i></td>
												</tr>
											</tbody>
										</table>
										</div>
									</div>
									<!-- END DATATABLE -->
								</div>
								<!-- END ROW -->
							</div>
							<!-- END CARD-BODY -->
						</div>
						<!-- END CARD -->
					</div>
					<!-- END SECTION-BODY -->
				</section>
				<!-- END SECTION -->
			</div>
			<!-- END CONTENT -->

