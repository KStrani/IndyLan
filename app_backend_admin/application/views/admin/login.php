<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Indylan Admin-Login</title>

		<!-- BEGIN META -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="your,keywords">
		<meta name="description" content="Short explanation about this website">
		<!-- END META -->
		<link rel="icon" href="<?php echo base_url();?>assets/img/logo.png" type="image/png" sizes="16x16" />
		<!-- BEGIN STYLESHEETS -->
		<link href='http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900' rel='stylesheet' type='text/css'/>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/materialadmin.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/font-awesome.min.css" />
		<!-- END STYLESHEETS -->

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="js/libs/utils/html5shiv.js"></script>
		<script type="text/javascript" src="js/libs/utils/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="menubar-hoverable header-fixed ">

		<!-- BEGIN LOGIN SECTION -->
		<section class="section-account">
			<div class="card contain-sm style-transparent">
				<div class="card-body">
					<div class="row">
						<div class="col-sm-4 col-sm-offset-4 text-center">
							<img src="<?php echo base_url();?>assets/img/logo.png" class="img-responsive logo" alt="Lango">
							<!-- <h4>Lango Admin Panel</h4> -->
						</div>
						<div class="clearfix"></div>
						<div class="col-sm-6 col-sm-offset-3">
							<br/>
							<!-- <span class="text-lg text-bold text-primary">LOGIN</span> -->
							<br/><br/>
							<?php 
if(isset($error)){ ?>
<div style="color:red"> <?php echo $error; ?></div>
<?php } ?>

							<?php //print_r($exercise_mode); 
$attributes = array('action' => base_url().'admin_master/login');
echo form_open_multipart('admin_master/login', $attributes);
?>
								<div class="form-group">
									<input type="text" class="form-control" id="username" name="email" placeholder="Email Address">
									<?php echo form_error('email', '<div style="color:red;">', '</div>'); ?>
								</div>
								<div class="form-group">
									<input type="password" class="form-control" id="password" name="password" placeholder="Password">
									<?php echo form_error('password', '<div style="color:red;">', '</div>'); ?>
									<!-- <p class="help-block"><a href="forgotpassword.html">Forgotten?</a></p> -->
								</div>
								<br/>
								<div class="row">
									<div class="col-xs-6 text-left">
										
									</div><!--end .col -->
									<div class="col-xs-6 text-right">
										<button class="btn btn-primary btn-raised" type="submit">Login</button>
									</div><!--end .col -->
								</div><!--end .row -->
							</form>
						</div><!--end .col -->
					</div><!--end .row -->
				</div><!--end .card-body -->
			</div><!--end .card -->
		</section>
		<!-- END LOGIN SECTION -->


			</body>
		</html>
