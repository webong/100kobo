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
				<div class="page-header"><h4>Withdraw Requests (New)</h4></div>
				<?php
				if ($withdraws_nonsolved) :
				?>
				<div class="autoscroll">
					<table class="table">
					    <thead>
				      		<tr>
					        	<th class="text-center">User</th>
					        	<th class="text-center">Amount</th>
					        	<th class="text-center">Bank Details</th>
					        	<th class="text-center">Date/Time</th>
					        	<th class="text-center">Mark As</th>
					        	<th></th>
				      		</tr>
					    </thead>
					    <tbody>
					<?php
						foreach ($withdraws_nonsolved as $withdraw) :
							echo form_open('withdraw/process');
							echo form_hidden('id', $withdraw->id);
							echo form_hidden('user_id', $withdraw->user_id);
					?>
							<tr class="text-center">
								<td><a href="<?= base_url().'user/'.$withdraw->username ?>"><?= $withdraw->username ?></a></td>
						        <td>N<?= $withdraw->amount / 100 ?></td>
						        <td>
						        	<b>Bank Name:</b> <?= $withdraw->bank_name ?><br />
						        	<b>Account Name:</b> <?= $withdraw->account_name ?><br />
						        	<b>Account Number:</b> <?= $withdraw->account_number ?><br />
						        </td>
						        <td><?= $withdraw->added ?></td>
						        <td>
						        	<select name="mark_as">
						        		<option>...</option>
						        		<option value="approved">Approved</option>
						        		<option value="declined">Declined</option>
						        	</select>
						        </td>
						        <td>
						        <?php
						        $args = array('class' => 'btn btn-blue btn-xs align-right');
						  		echo form_submit('submit', 'Resolve', $args);
						        ?>
						        </td>
				      		</tr>
				    <?php
				    		echo form_close();
				    	endforeach;
				    ?>
				    	<tbody>
				    </table>
				</div>
				<?php 
				else :
				?>
				<div class="line">
					<div class="text-center">No new withdraw requests</div>
				</div>
				<?php
				endif;
				?>
      		</div><!-- /.post -->

      		<div class="row post">
				<div class="page-header"><h4>Withdraw Requests (solved)</h4></div>
				<?php
				if ($withdraws_solved) :
				?>
				<div class="autoscroll">
					<table class="table">
					    <thead>
				      		<tr>
					        	<th class="text-center">User</th>
					        	<th class="text-center">Amount</th>
					        	<th class="text-center">Bank Details</th>
					        	<th class="text-center">Date/Time requested</th>
					        	<th class="text-center">Date/Time resolved</th>
					        	<th class="text-center">Status</th>
				      		</tr>
					    </thead>
					    <tbody>
					<?php
						foreach ($withdraws_solved as $withdraw) :
					?>
							<tr class="text-center">
								<td><a href="<?= base_url().'user/'.$withdraw->username ?>"><?= $withdraw->username ?></a></td>
						        <td>N<?= $withdraw->amount / 100 ?></td>
						        <td>
						        	<b>Bank Name:</b> <?= $withdraw->bank_name ?><br />
						        	<b>Account Name:</b> <?= $withdraw->account_name ?><br />
						        	<b>Account Number:</b> <?= $withdraw->account_number ?><br />
						        </td>
						        <td><?= $withdraw->added ?></td>
						        <td><?= $withdraw->solved_date ?></td>
						        <td>
						        	<?php
						        	if ($withdraw->status == 1) echo '<span class="label label-success">Approved</span>';
						        	elseif ($withdraw->status == 2) echo '<span class="label label-danger">Declined</span>';
						        	?>
						        </td>
				      		</tr>
				    <?php
				    		
				    	endforeach;
				    ?>
				    	<tbody>
				    </table>
				</div>
				<?php 
				else :
				?>
				<div class="line">
					<div class="text-center">No past withdraw requests</div>
				</div>
				<?php
				endif;
				?>
      		</div><!-- /.post -->
      	</div><!-- /.center -->