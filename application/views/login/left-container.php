		<!--
		<div class="left-container col-lg-3 col-md-3 col-xs-12">
		 	<div class="left-bg">
				<div class="row">
      				<div class="page-header">
      					<h3>Login</h3>
      				</div>
					<?php
					echo form_open('login');
					?>
					<div class="col-xs-12 post-mini no-borders">
					    <div class="line push-bottom-1">
				          	<h5>Username</h5>
							<?php
							$attributes = array('class' => 'form-control');
							echo form_input('username', $this->input->post('username'), $attributes);
							?>
					    </div>
					    <div class="line push-bottom-1">
				          	<h5>Password</h5>
							<?php echo form_password('password', '', $attributes); ?>
					    </div>
					    <div class="page-footer push-top-2">
				         	<?php
					  		$attributes = array('class' => 'btn btn-blue align-right');
							echo form_submit('login_submit', 'Login', $attributes);
					  		?>
					    </div>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
		-->
		<div class="col-md-3 col-lg-1_5">
      		<div class="row"></div>
      	</div><!-- /.left -->