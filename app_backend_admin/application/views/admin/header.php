<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Indylan</title>

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
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/libs/DataTables/jquery.dataTables.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap-select.min.css" />

		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/chosen/chosen.css" />
		<!-- <link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/chosen/chosen.min.css" /> -->


		<!-- END STYLESHEETS -->
		<script src="<?php echo base_url();?>assets/js/libs/jquery/jquery-1.11.2.min.js"></script>
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="js/libs/utils/html5shiv.js"></script>
		<script type="text/javascript" src="js/libs/utils/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="menubar-hoverable header-fixed menubar-pin ">

		<!-- BEGIN HEADER-->
		<header id="header" >
			<div class="headerbar">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="headerbar-left">
					<ul class="header-nav header-nav-options">
						<li class="header-nav-brand" >
							<div class="brand-holder">
								<a href="#">
									<span class="text-lg text-bold text-primary">
										<img src="<?php echo base_url();?>assets/img/logo.png" class="img-responsive logo" alt="Lango" style="width: 70px;">
									</span>
								</a>
							</div>
						</li>
						<li>
							<a class="btn btn-icon-toggle menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
								<i class="fa fa-bars"></i>
							</a>
						</li>
					</ul>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="headerbar-right">
					<?php /*<ul class="header-nav header-nav-options">
						<li>
							<!-- Search form -->
							<span class="text-lg text-bold text-primary">
										<img src="<?php echo base_url();?>assets/img/flag_ENGLISH.jpg" alt="Finnish_flag" style="height: 39px;
    margin-right: -21px;
    margin-top: 0;
    width: 75px;">
							</span>
						</li>

					</ul> */ ?><!--end .header-nav-options -->
					<ul class="header-nav header-nav-profile">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle ink-reaction" data-toggle="dropdown" aria-expanded="false">
                                <img src="http://blue.alphademo.in/development/cityquiz/assets/img/language.png" alt="">
                                <span class="profile-info selected_language" id="lang">
                                   
                                    <?php echo $current_support_lang; ?>                                    
                                </span>
                            </a>
                            <ul class="dropdown-menu animation-dock">
                                                                


                                <?php foreach ($master_lang as  $value) { ?>
                                	<li><a class="support_lang" data-id="<?php echo $value['source_language_id']; ?>" href="javascript:void(0);">
  								<?php echo ucfirst($value['language_name']) ?></a></li>

                               <?php } ?>
                                
                                
                                
                            	
                            </ul><!--end .dropdown-menu -->
                        </li><!--end .dropdown -->
                    </ul>
					<ul class="header-nav header-nav-profile">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle ink-reaction" data-toggle="dropdown">
								<img src="<?php echo base_url();?>assets/img/avatar-2.png" alt="" />
								<span class="profile-info">
									<?php echo ucfirst($userefirst_name) .' '.ucfirst($userelast_name); ?>
									
								</span>
							</a>
							<ul class="dropdown-menu animation-dock">
								<!-- <li><a href="#">My Profile</a></li> -->
								<!-- <li class="divider"></li> -->
								<li><a href="<?php echo base_url();?>admin_master/edit_profile"><i class="fa fa-user"> </i>  Edit Profile</a></li>

								<li><a href="<?php echo base_url();?>admin_master/logout"><i class="fa fa-power-off text-danger"></i> Logout</a></li>
							</ul><!--end .dropdown-menu -->
						</li><!--end .dropdown -->
					</ul><!--end .header-nav-profile -->
				</div><!--end #header-navbar-collapse -->
			</div>
		</header>
		<!-- END HEADER-->
