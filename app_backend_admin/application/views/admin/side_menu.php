<?php 
$admin_data = $this->session->userdata('logged_in');
$type = $admin_data[0]['type'];
?>
			<div id="menubar" class="menubar-inverse ">
				<div class="menubar-scroll-panel">

					<!-- BEGIN MAIN MENU -->
					<ul id="main-menu" class="gui-controls">

						<!-- BEGIN ADD CATEGORY -->
						<li>
							<a href="javascript:void(0);" data-id="<?php echo base_url();?>admin_master/category_list" class="<?php if($active_class=='category') { echo 'active';}?>  menu_cls">
								<div class="gui-icon"><i class="fa fa-file-text"></i></div>
								<span class="title">Category</span>
							</a>
						</li><!--end /menu-li -->
						<!-- END ADD CATEGORY -->
						
							<!-- BEGIN ADD SUB CATEGORY -->
						<li>
							<a href="javascript:void(0);"  data-id="<?php echo base_url();?>admin_master/subcategory_list" class="<?php if($active_class=='subcategory') { echo 'active';}?> menu_cls">
								<div class="gui-icon"><i class="fa fa-files-o"></i></div>
								<span class="title">Sub Category</span>
							</a>
						</li><!--end /menu-li -->
						<!-- END ADD SUB CATEGORY -->

						<li>
							<a href="javascript:void(0);" data-id="<?php echo base_url();?>admin_master/words_list" class="<?php if($active_class=='word') { echo 'active';}?> menu_cls">
								<div class="gui-icon"><i class="fa fa-font"></i></div>
								<span class="title">Vocabulary Mode</span>
							</a>
						</li>

						<li>
							<a href="javascript:void(0);" data-id="<?php echo base_url();?>admin_master/aural_list" class="<?php if($active_class=='aural') { echo 'active';}?> menu_cls">
								<div class="gui-icon"><i class="fa fa-font"></i></div>
								<span class="title">Aural Comprehension Mode</span>
							</a>
						</li>

						<li>
							<a href="javascript:void(0);"  data-id="<?php echo base_url();?>admin_master/grammar_list" class="<?php if($active_class=='grammar') { echo 'active';}?> menu_cls">
								<div class="gui-icon"><i class="fa fa-font"></i></div>
								<span class="title">Grammar Mode</span>
							</a>
						</li>


						<li>
							<a  href="#" data-id="<?php echo base_url();?>admin_master/culture_list" class="<?php if($active_class=='culture') { echo 'active';}?> menu_cls">
								<div class="gui-icon"><i class="fa fa-paragraph "></i></div>
								<span class="title">Culture Mode</span>
							</a>
						</li>


						<li>
							<a href="javascript:void(0);" data-id="<?php echo base_url();?>admin_master/phrases_list" class="<?php if($active_class=='phrase') { echo 'active';}?> menu_cls">
								<div class="gui-icon"><i class="fa fa-comment-o"></i></div>
								<span class="title">Phrase Mode</span>
							</a>
						</li>

						<li>
							<a href="javascript:void(0);" data-id="<?php echo base_url();?>admin_master/dialogue_list" class="<?php if($active_class=='dialogue') { echo 'active';}?> menu_cls">
								<div class="gui-icon"><i class="fa fa-weixin"></i></div>
								<span class="title">Dialogue Mode</span>
							</a>
						</li>

						
						<!-- BEGIN ADD WORDS --> 

						<li>
							<a href="javascript:void(0);" data-id="<?php echo base_url();?>admin_master/upload_word_images" class="<?php if($active_class=='image') { echo 'active';}?> menu_cls">
								<div class="gui-icon"><i class="fa fa-file-image-o"></i></div>
								<span class="title">Upload images</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);" data-id="<?php echo base_url();?>admin_master/upload_word_audio" class="<?php if($active_class=='audio') { echo 'active';}?> menu_cls">
								<div class="gui-icon"><i class="fa fa-file-audio-o"></i></div>
								<span class="title">Upload Audio</span>
							</a>
						</li>

							<li>
							<a href="javascript:void(0);" data-id="<?php echo base_url();?>admin_master/type_list" class="<?php if($active_class=='type') { echo 'active';}?> menu_cls">
								<div class="gui-icon"><i class="fa fa-list-alt"></i></div>
								<span class="title">Exercise Type</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);" data-id="<?php echo base_url();?>admin_master/user_list" class="<?php if($active_class=='user') { echo 'active';}?> menu_cls">
								<div class="gui-icon"><i class="fa fa-users"></i></div>
								<span class="title">User List</span>
							</a>
						</li>
						<?php
						if($type == '0')
						{ ?>
							<li>
								<a href="javascript:void(0);" data-id="<?php echo base_url();?>admin_master/portal_users" class="<?php if($active_class=='portal_user') { echo 'active';}?> menu_cls">
									<div class="gui-icon"><i class="fa fa-users"></i></div>
									<span class="title">Portal Users</span>
								</a>
							</li>
						<?php }
						?>
						<li>
							<a href="javascript:void(0);" data-id="<?php echo base_url();?>admin_master/missing_word_image_audio" class="<?php if($active_class=='missing') { echo 'active';}?> menu_cls">
								<div class="gui-icon"><i class="fa fa-list-alt"></i></div>
								<span class="title">Missing Audio/Images</span>
							</a>
						</li>




						<!--end /menu-li -->
						

						
						

						

						
						
						<!-- END ADD WORDS -->

					</ul><!--end .main-menu -->
					<!-- END MAIN MENU -->

					<!-- <div class="menubar-foot-panel">
						<small class="no-linebreak hidden-folded">
							<span class="opacity-75">Copyright &copy; 2017</span> <strong>SFI</strong>
						</small>
					</div> -->
				</div><!--end .menubar-scroll-panel-->
			</div><!--end #menubar-->
			<!-- END MENUBAR -->

		</div><!--end #base-->
		<!-- END BASE -->
