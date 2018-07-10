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
				<div class="page-header"><h2 class="page-temp-header">Terms and Conditions</h2></div>
				<div class="line">
					<p>Thank you for using our products and services. The Services are provided by 100Kobo Enterprise!</p>
					<p>By using our Services, you are agreeing to these terms. Please read them carefully.</p>

					<p>Our Services are very diverse, so sometimes additional terms or product requirements (including age requirements) may apply. Additional terms will be available with the relevant Services, and those additional terms become part of your agreement with us if you use those Services.</p>

					<h3>Using our Services:</h3>

					<p>Don’t misuse our Services. For example, don’t interfere with our Services
					or try to access them using a method other than the interface and the
					instructions that we provide. You may use our Services only as permitted
					by law and regulations. We may suspend or stop providing our Services to
					you if you do not comply with our terms or policies or if we are
					investigating suspected misconduct.</p>

					<p>Using our Services does not give you ownership of any intellectual
					property rights in our Services or the content you access. You may not
					use content from our Services unless you obtain permission from its
					owner or are otherwise permitted by law. These terms do not grant you
					the right to use any branding or logos used on our website.</p>

					<p>Our Services display some content that is not from 100Kobo; this content
					is the sole responsibility of the entity that makes it available. We may
					review content to determine whether it is illegal or violates our
					policies, and we may remove or refuse to display content that we
					reasonably believe violates our policies or the law. But that does not
					necessarily mean that we review content, so please do not assume that we
					do.</p>

					<p>In connection with your use of the Services, we may send you service
					announcements, administrative messages, and other information.
					You may not be able to opt out of some of those messages we send to you.</p>

					<p>Some of our Services are available on mobile devices. Do not use such
					Services in a way that distracts you and prevents you from obeying
					traffic or safety laws.</p>
				</div>
				<div class="line">
					<h3>Your 100Kobo Account:</h3>

					<p>You may need to create your own 100Kobo Account in order to use some of
					our Services.<br />
					If you are using a 100Kobo Account assigned to you by an administrator,
					you may have to create a new password in other to gain full privacy of
					that account.<br />
					To protect your 100Kobo Account, keep your password confidential. You are
					responsible for the activity that happens on or through your 100Kobo
					Account. Try not to reuse your 100Kobo Account password on third-party
					applications.<br />
					If you learn of any unauthorized use of your password or 100Kobo Account
					then you must change that password as it may have been compromised.<p>
				</div>
      		</div><!-- /.post -->
      	</div><!-- /.center -->