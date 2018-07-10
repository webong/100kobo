      	<div class="center-container col-lg-6 col-md-6 col-xs-12">
      		<?php if($this->session->flashdata('message_f')) { ?>
			<div class="alert alert-danger alert-dismissible" role="alert">
		  		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  		<?php echo $this->session->flashdata('message_f'); ?>
		  	</div>
		  	<?php } elseif($this->session->flashdata('message_i')) { ?>
			<div class="alert alert-info alert-dismissible" role="alert">
		  		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  		<?php echo $this->session->flashdata('message_i'); ?>
		  	</div>
		  	<?php } elseif ($this->session->flashdata('message_s')) { ?>
		  	<div class="alert alert-success alert-dismissible" role="alert">
		  		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  		<?php echo $this->session->flashdata('message_s'); ?>
		  	</div>
		  	<?php } ?>
		  	
			<div class="row post">
				<div class="page-header"><h2 class="page-temp-header">Your Content in our Services</h2></div>
				<div class="line">
					<p>Some of our Services allow you to upload, submit, store, send or receive content. You retain ownership of any intellectual property rights that you hold in that content. In short, what belongs to you stays yours.</p>

					<p>When you upload, submit, store, send or receive content to or through our Services, you give 100Kobo (and those we work with) a worldwide license to
					use, host, store, reproduce, modify, create derivative works so that your content works better with our Services. But will not be publicly displayed
					without your knowledge to carry out such exercise. When you set a PRICE TAG on some of your content with 100kobo, you also
					give 100kobo the license to communicate, publish, publicly perform, publicly display and distribute such content.</p>

					<p>The rights you grant in this license are for the limited purpose of operating, promoting, and improving our Services, and to develop new ones.
					This license continues even if you stop using our Services (for example, for some photos or business listings you have added to 100Kobo) our services
					still has the license to access such information as required by the admin.</p>
				</div>
      		</div><!-- /.post -->
      	</div><!-- /.center -->