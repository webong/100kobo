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
				<div class="page-header"><h2 class="page-temp-header">Privacy Policy</h2></div>
				<div class="line">
					<h3>Welcome to 100Kobo!</h3>
					<p>When you use 100Kobo services, you trust us with your information. This Privacy Policy is meant to help you understand what data we collect, why we collect it, and what we do with it. This is important;
					 we hope you will take time to read it carefully. And remember, you can find controls to manage your information, protect your privacy and security by clicking settings on the website.</p>
					<p>We have tried to keep it as simple as possible, there are different ways you can use our services – to upload photos and share information, to communicate with other users, to create new content and generate income.</p>
				</div>
				<div class="line">
					<p>When you share information with us, for example by creating a 100Kobo Account, we can make those services even better; to show you more relevant ads/photos of others like the ones you may have uploaded. Also to help you connect with people and to make income generation from the website quicker and easier.</p>
					<p>As you use our services, we want you to be clear how we are using information and the ways in which you can protect your privacy with us. Your privacy matters to 100Kobo so whether you are new to 100Kobo or a long-time user, please do take the time to get to know our practices; and
					if you have any questions you can contact us through the admin portal.</p>
					<h4>How we use information we collect:</h4>
					<p>We use the information we collect from all of our services to provide, maintain, protect and improve them, to develop new ones, and to protect 100Kobo and its users.</p>
					<p>100Kobo privacy policies explain how we treat your personal data and protect your privacy when you use our Services. By using our Services, you agree that 100kobo can use such data in accordance with our privacy policies.</p>
					<p>We respond to notices of alleged copyright infringement and terminate accounts of repeat infringers according to the law.</p>
					<p>We also use this information to offer you tailored contents from our developers that should be up and running on the website.</p>
				</div>
      		</div><!-- /.post -->
      	</div><!-- /.center -->