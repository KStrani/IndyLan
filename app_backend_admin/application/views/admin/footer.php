
		<!-- BEGIN JAVASCRIPT -->

		<script src="<?php echo base_url();?>assets/js/libs/jquery/jquery-migrate-1.2.1.min.js"></script>
		<script src="<?php echo base_url();?>assets/js/libs/bootstrap/bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>assets/js/libs/DataTables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>assets/js/libs/nanoscroller/jquery.nanoscroller.min.js"></script>
		<script src="<?php echo base_url();?>assets/js/core/source/App.js"></script>
		<script src="<?php echo base_url();?>assets/js/core/source/AppNavigation.js"></script>
		<script src="<?php echo base_url();?>assets/js/core/source/AppNavSearch.js"></script>
		<script src="<?php echo base_url();?>assets/js/core/demo/Demo.js"></script>
		<script src="<?php echo base_url();?>assets/js/core/demo/DemoTableDynamic.js"></script>
		<script src="<?php echo base_url();?>assets/js/libs/bootstrap/bootstrap-select.min.js"></script>
		<script src="<?php echo base_url();?>assets/plugin/jquery-validation/jquery.validate.js"></script>
			
		<script src="<?php echo base_url();?>assets/chosen/chosen.jquery.js"></script>
		<script src="<?php echo base_url();?>assets/chosen/chosen.jquery.min.js"></script>
		<script src="<?php echo base_url();?>assets/chosen/chosen.proto.js"></script>
		<script src="<?php echo base_url();?>assets/chosen/chosen.proto.min.js"></script>
		<script src="<?php echo base_url();?>assets/js/custom.js"></script>
		<!-- END JAVASCRIPT -->
		<script type="text/javascript">$(".chosen-select").chosen({width: "100%"});</script>


<script type="text/javascript">
$(".menu_cls").click(function() 
{
	var redirect_url = $(this).attr("data-id");
	$.ajax({
		url:'<?php echo base_url();?>admin_master/unset_session',
		type:'POST',
		dataType : "json",
		success:function(data){
			if(data.status == 1)
			{
				window.location.href = redirect_url;
			}
		},
	}); 
});

$(document).ready(function(){
   $('.selected_language').text('<?php echo $this->session->userdata('support_lang_name') ?>');
   $(document).on('click','.support_lang', function(){
   		var support_lang_id = $(this).attr('data-id');
   		var name = $(this).text();
   		$('.selected_language').text(name);
   		$.ajax({

			    url:"<?php echo base_url() ?>admin_master/change_language",
			    type: "POST",
			    data: {support_lang_id: support_lang_id},
			    success: function (response) {
			        console.log(response);
			        location.reload();
			    },
			    error: function(jqXHR, textStatus, errorThrown) {
			        console.log(textStatus, errorThrown);
			    }
		});
   });
   $(".isa_error").removeClass("hide_eroor").delay(2000).queue(function(){
    $(this).addClass("hide_eroor").dequeue();
    var that = this; setTimeout(function(){ $(that).addClass("hide").dequeue(); }, 2500);
  });

   $(".isa_success").removeClass("hide_eroor").delay(2000).queue(function(){
    $(this).addClass("hide_eroor").dequeue();
    var that = this; setTimeout(function(){ $(that).addClass("hide").dequeue(); }, 2500);
  });
});
</script>
	</body>
</html>
