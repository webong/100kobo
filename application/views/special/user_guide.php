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
				<div class="page-header"><h2 class="page-temp-header">100 kobo User Guide</h2></div>
				<div class="line">
					<p><strong>You are here!</strong></p>
					<p>Signing up with 100 kobo is the first step to take while using our services, after which you can begin to enjoy our numerous services as follows;</p>
					<p>You get 1000 kobo when you sign up with us, free cash.</p>
				</div>
				<div class="line">
					<h3>Free zone and Trending zone</h3>
					<p>In the free zone users of the website can post comments and other users can also make comments on posts and this is totally free.</p>
				</div>
				<div class="line">
					<h3>Trending Zone</h3>
					<p>In trending zone every comment counts! In the trending zone, only paid users can post and make money when other users comment on such posts. It is advisable to make your posts or comments attractive by adding photos to them thereby getting more attention.</p>
				</div>
				<div class="line">
					<h3>Voucher For Cash</h3>
					<p>In this section of 100 kobo, a user can liquidate recharge cards to cash and withdraw from their local bank accounts with a 19% off. Try it</p>
				</div>
				<div class="line">
					<h3>Back Stage Photos</h3>
					<p>Here you can set your gallery price and also make money when other users unlock it at any set price of your choice.</p>
				</div>
				<div class="line">
					<h3>Top up</h3>
					<p>You can top up your units when the free 1000 kobo is exhausted.</p>
				</div>
				<div class="line">
					<p>We trust that you will find this exciting as you get started.<br />Thank you.</p>
				</div>
      		</div><!-- /.post -->
      	</div><!-- /.center -->