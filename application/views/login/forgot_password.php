    <div class="container">
    	<div class="center-container col-lg-offset-4 col-lg-4 col-md-5 col-md-offset-1 col-sm-5 col-xs-12">
			<div class="row post login-signup">
				<?php echo form_open('forgot_password'); ?>
				<div class="page-header">
					<h4>Forgot Password</h4>
				</div>
				<?php echo validation_errors(); ?>
				<?php
				if (isset($success_message)) {
					echo '<div class="alert alert-success alert-dismissible" role="alert">
			  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$success_message.'</div>';
				} elseif (isset($fail_message)) {
					echo '<div class="alert alert-danger alert-dismissible" role="alert">
			  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$fail_message.'</div>';
				}
				?>
				<div class="line">
					<h5>Username</h5>
					<?php
					$attributes = array('class' => 'form-control');
					echo form_input('username', '', $attributes);
					?>
				</div>
				<div class="line push-bottom-1">
					<h5>Email</h5>
					<?php
					$attributes = array('class' => 'form-control');
					echo form_input('email', '', $attributes);
					?>
				</div>
				<div class="page-footer push-top-2">
					<span class="forgot"><a href="<?= base_url()."login" ?>">Login / Sign Up</a></span>
					<?php
					$attributes = array('class' => 'btn btn-blue align-right');
					echo form_submit('restore_submit', 'Restore', $attributes);
					?>
				</div>
				<?php echo form_close(); ?>
      		</div><!-- /.login-signup -->

      		<div class="row post login-signup">
				<div class="line">
					All rights reserved © 2015 100 kobo
				</div>
      		</div><!-- /.login-signup -->
      	</div><!-- /.center -->
    </div><!-- /.container -->