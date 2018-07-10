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
				<div class="page-header"><h2 class="page-temp-header">Our Mission</h2></div>
				<div class="line">
					<p>Our mission is to enhance your creativity, make your work count and useful to others.</p>
					<p>Our goal: To bring back the existence of the Nigerian kobo and make it
					useful again.</p>
					<p>We have focused on providing the best user interface possible. Whether we
					are designing a new feel to the look of the homepage, we are passionate to
					ensure that they will ultimately serve our users, rather than our own personal
					goal as a team.</p>
				</div>
      		</div><!-- /.post -->
      	</div><!-- /.center -->