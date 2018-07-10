    <div class="container">
    	<div class="center-container col-lg-offset-2 col-lg-4 col-md-5 col-md-offset-1 col-sm-5 col-xs-12">
			<div class="row post login-signup">
				<?php echo form_open('login'); ?>
				<div class="page-header">
					<h4>Login</h4>
				</div>
				<div class="line">
					<h5>Username</h5>
					<?php
					$attributes = array('class' => 'form-control');
					echo form_input('username', '', $attributes);
					?>
				</div>
				<div class="line push-bottom-1">
					<h5>Password</h5>
					<?php echo form_password('password', '', $attributes); ?>
				</div>
				<div class="page-footer push-top-2">
					<span class="forgot"><a href="<?= base_url()."forgot-password" ?>">Forgot Password?</a></span>
					<?php
					$attributes = array('class' => 'btn btn-blue align-right');
					echo form_submit('login_submit', 'Login', $attributes);
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

      	<div class="center-container col-lg-4 col-lg-offset-0 col-md-5 col-md-offset-0 col-sm-offset-2 col-sm-5 col-xs-12">
			<div class="row post login-signup">
				<?php echo form_open('signup'); ?>
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
				<div class="page-header">
					<h4>Sign Up</h4>
				</div>
				<div class="line">
					<h5>Surename</h5>
					<?php
					$attributes = array('class' => 'form-control');
					echo form_input('surename', $this->input->post('surename'), $attributes);
					?>
				</div>
				<div class="line">
					<h5>Email</h5>
					<?php
					echo form_input('email', $this->input->post('email'), $attributes);
					?>
				</div>
				<div class="line">
					<h5>Username</h5>
					<?php
					echo form_input('username', $this->input->post('username'), $attributes);
					?>
				</div>
				<div class="line">
					<h5>Password</h5>
					<?php echo form_password('password', '', $attributes); ?>
				</div>
				<div class="line push-bottom-1">
					<h5>Password again</h5>
					<?php echo form_password('cpassword', '', $attributes); ?>
				</div>
				<div class="page-footer push-top-2">
					<?php
					$attributes = array('class' => 'btn btn-blue align-right');
					echo form_submit('signup_submit', 'Sign Up', $attributes);
					?>
				</div>
				<?php echo form_close(); ?>
      		</div><!-- /.login-signup -->
      	</div><!-- /.center -->
    </div><!-- /.container -->