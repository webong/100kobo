      	<div class="center-container col-lg-12 col-md-12 col-xs-12">
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
				<div class="page-header">
					<h4>
					<?php
					if ($role == -1) echo 'All Users';
					elseif ($role == 0) echo 'Banned Users';
					elseif ($role == 1) echo 'Regular Users';
					elseif ($role == 2) echo 'Paid Users';
					elseif ($role == 3) echo 'Administrators';
					?>
					</h4>
				</div>
				<?php
				if ($users) :
				?>
				<div class="autoscroll">
					<table class="table">
					    <thead>
				      		<tr>
					        	<th class="text-center">Username</th>
					        	<th class="text-left">Email</th>
					        	<th class="text-left">First Name</th>
					        	<th class="text-left">Surname</th>
					        	<th class="text-left">Joined</th>
					        	<th class="text-center">Access</th>
					        	<th class="text-center">Confirmed</th>
					        	<th></th>
				      		</tr>
					    </thead>
					    <tbody>
					<?php
						foreach ($users as $user) :
							echo form_open('voucher/process');
					?>
							<tr class="text-center">
								<td><a href="<?= base_url().'user/'.$user->username ?>"><?= $user->username ?></a></td>
								<td class="text-left"><?= $user->email ?></td>
						        <td class="text-left"><?= $user->first_name ?></td>
						        <td class="text-left"><?= $user->surname ?></td>
						        <td class="text-left"><?= $user->added ?></td>
						        <td>
						        	<?php
						        	if ($user->role == '0') echo '<a href="'.base_url().'users/regular"><span class="label label-danger">Banned User</span></a>';
						        	if ($user->role == '1') echo '<a href="'.base_url().'users/regular"><span class="label label-default">Regular User</span></a>';
						        	elseif ($user->role == '2') echo '<a href="'.base_url().'users/paid"><span class="label label-info">Paid User</span></a>';
						        	elseif ($user->role == '3') echo '<a href="'.base_url().'users/admins"><span class="label label-primary">Administrator</span></a>';
						        	?>
						        </td>
						        <td>
						        	<?php
						        	if ($user->confirmed == '1') echo '<span class="label label-success">Yes</span>';
						        	else echo '<span class="label label-danger">No</span>';
						        	?>
						        </td>
				      		</tr>
				    <?php
				    		echo form_close();
				    	endforeach;
				    ?>
				    	</tbody>
				    </table>
				</div>
				<?php 
				else :
				?>
				<div class="line">
					<div class="text-center">No users in database</div>
				</div>
				<?php
				endif;
				?>
      		</div><!-- /.post -->
      		<div class="row post">
      			<div class="pull-right">
      				<a href="<?= base_url() ?>users/regular"><span class="label label-default no-float">Regular Users</span></a>
					<a href="<?= base_url() ?>users/paid"><span class="label label-info no-float">Paid Users</span></a>
					<a href="<?= base_url() ?>users/admins"><span class="label label-primary no-float">Administrators</span></a>
					<a href="<?= base_url() ?>users/banned"><span class="label label-danger no-float">Banned Users</span></a>
					<a href="<?= base_url() ?>users"><span class="label label-default no-float">Show All</span></a>
				</div>
      		</div><!-- /.post -->
      	</div><!-- /.center -->

      	<div class="center-container col-lg-4 col-md-4 col-xs-12">
      		<div class="row post">
      			<div class="page-header"><h4>Add/Remove Administrator</h4></div>
      			<?php echo form_open('users/add_remove_admin'); ?>
				<div class="line push-bottom-1">
					<h5>Username: </h5>
					<input type="text" name="username" class="form-control" placeholder="Username" />
				</div>
				<div class="line push-bottom-1">
					<h5>What do to?</h5>
					<select name="action" class="form-control">
						<option value="add">Make Admin</option>
						<option value="remove">Remove Admin</option>
					</select>
				</div>
				<div class="page-footer push-top-2">
					<?php
					$args = array('class' => 'btn btn-blue align-right');
					echo form_submit('submit', 'Submit', $args);
					?>
				</div>
				<?php echo form_close(); ?>
      		</div><!-- /.post -->
      	</div><!-- /.center -->

      	<div class="center-container col-lg-4 col-md-4 col-xs-12">
      		<div class="row post">
      			<div class="page-header"><h4>Ban/Unban User</h4></div>
      			<?php echo form_open('users/ban_unban_user'); ?>
				<div class="line push-bottom-1">
					<h5>Username: </h5>
					<input type="text" name="username" class="form-control" placeholder="Username" />
				</div>
				<div class="line push-bottom-1">
					<h5>What do to?</h5>
					<select name="action" class="form-control">
						<option value="ban">Ban User</option>
						<option value="unban">Unban User</option>
					</select>
				</div>
				<div class="page-footer push-top-2">
					<?php
					$args = array('class' => 'btn btn-blue align-right');
					echo form_submit('submit', 'Submit', $args);
					?>
				</div>
				<?php echo form_close(); ?>
      		</div><!-- /.post -->
      	</div><!-- /.center -->