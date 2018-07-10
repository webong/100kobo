      	<div class="center-container col-lg-6 col-md-6 col-xs-12">
      		<?php echo form_open('settings/update_password'); ?>
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
				<div class="page-header"><h4>Security</h4></div>
				<div class="line">
					<h5>Current password</h5>
					<input type="password" name="current" class="form-control" placeholder="Old password" />
				</div>
				<div class="line">
					<h5>New password</h5>
					<input type="password" name="new" class="form-control" placeholder="New password" />
				</div>
				<div class="line push-bottom-1">
					<h5>Again new password</h5>
					<input type="password" name="new2" class="form-control" placeholder="New password (again)" />
				</div>
				<div class="page-footer push-top-2">
					<?php
					$args = array('class' => 'btn btn-blue align-right');
					echo form_submit('password_u', 'Update', $args);
					?>
				</div>
      		</div><!-- /.post -->
      		<?php echo form_close(); ?>

      		<div class="row post">
      			<?php echo form_open('settings/update_account'); ?>
				<div class="page-header"><h4>Account</h4></div>
				<div class="line push-bottom-1">
					<h5>First Name</h5>
					<input type="text" name="first_name" class="form-control" value="<?php if(isset($user->first_name)) echo $user->first_name ?>" placeholder="First Name" />
				</div>
				<div class="line push-bottom-1">
					<h5>Surname</h5>
					<input type="text" name="surname" class="form-control" value="<?php if(isset($user->surname)) echo $user->surname ?>" placeholder="Surname" />
				</div>
				<div class="line push-bottom-1">
					<h5>Phone number</h5>
					<input type="text" name="phone" class="form-control" value="<?php if(isset($user->phone)) echo $user->phone ?>" placeholder="Phone Number" />
				</div>
				<div class="page-footer push-top-2">
					<?php
					$args = array('class' => 'btn btn-blue align-right');
					echo form_submit('about_u', 'Update', $args);
					?>
				</div>
				<?php echo form_close(); ?>
      		</div><!-- /.post -->

      		<div class="row post">
      			<?php echo form_open('settings/update_about'); ?>
				<div class="page-header"><h4>Profile</h4></div>
				<div class="line push-bottom-1">
					<h5>About me</h5>
					<textarea name="about" class="form-control" maxlength="160"><?php if(isset($user->about)) echo $user->about ?></textarea>
				</div>
				<div class="line push-bottom-1">
					<h5>Profile picture</h5>
					<?php if ($gallery) : ?>
					<div class="gallery-profile">
						<?php foreach ($gallery as $image) : ?>
						<label>
					    	<input type="radio" name="profile" value="<?= $image->image ?>" <?php if ($user->image == $image->image) echo 'checked' ?> />
					    	<img src="<?= base_url().'images/'.$image->image ?>" />
				  		</label>
						<!-- <input type="radio" name="profile_image" value="<?= $image->id ?>" style="background-image:url(<?= base_url().'images/'.$image->image ?>);" /> -->
						<?php endforeach; ?>
					</div>
					<?php else : ?>
					<p>No images uploaded</p>
					<?php endif; ?>
					<div class="row col-lg-12"><a href="#" class="btn btn-blue align-left" data-toggle="modal" data-target="#modal-profileimg">Quick upload</a></div>
				</div>
				<div class="page-footer push-top-2">
					<?php
					$args = array('class' => 'btn btn-blue align-right');
					echo form_submit('about_u', 'Update', $args);
					?>
				</div>
				<?php echo form_close(); ?>
      		</div><!-- /.post -->

      		<div class="row post">
      			<?php echo form_open('settings/update_gallery'); ?>
				<div class="page-header"><h4>Gallery</h4></div>
				<div class="line push-bottom-1">
					<h5>Current price: <strong>N<?= $gallery_price ?></strong></h5>
				</div>
				<div class="line push-bottom-1">
					<h5>New price:</h5>
					<?php /* <input type="text" name="g_price" class="form-control" placeholder="<?= $gallery_price * 100 ?>" /> */ ?>
					<select name="g_price" class="form-control">
						<option>...</option>
						<option value="100">N1.00</option>
						<option value="200">N2.00</option>
						<option value="300">N3.00</option>
						<option value="400">N4.00</option>
						<option value="500">N5.00</option>
						<option value="600">N6.00</option>
						<option value="700">N7.00</option>
						<option value="800">N8.00</option>
						<option value="900">N9.00</option>
						<option value="1000">N10.00</option>
					</select>
				</div>
				<div class="page-footer push-top-2">
					<?php
					$args = array('class' => 'btn btn-blue align-right');
					echo form_submit('gallery_u', 'Update', $args);
					?>
				</div>
				<?php echo form_close(); ?>
      		</div><!-- /.post -->
      	</div><!-- /.center -->