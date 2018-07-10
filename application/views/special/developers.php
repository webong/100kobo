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
				<div class="page-header"><h2 class="page-temp-header">Developers</h2></div>
				<div class="line">
					<p>Writing the 100kobo program was a joy. This website and its functionalities
					has everything the users need for informative, interesting and entertaining
					experience.</p>
					<p>We turned the program writing and coding style of the website to fit the
					users needs. We have added extensive code walkthroughs to make the website
					more useful to every user.</p>
					<p>The tour will help every user get a sense of the rich coverage we have put
					altogether to come up with a more exiting and entertaining user experience.
					If you are evaluating the website, please feel free to contact us through
					the admin portal.</p>
				</div>
      		</div><!-- /.post -->
      	</div><!-- /.center -->