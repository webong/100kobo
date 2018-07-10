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
				<div class="page-header"><h4>Vouchers (unresolved)</h4></div>
				<?php
				if ($voucher_requests) :
				?>
				<div class="autoscroll">
					<table class="table">
					    <thead>
				      		<tr>
					        	<th class="text-center">User</th>
					        	<th class="text-center">Product Type</th>
					        	<th class="text-center">Voucher Type</th>
					        	<th class="text-center">Amount</th>
					        	<th class="text-center">Voucher</th>
					        	<th class="text-center">Date/Time</th>
					        	<th class="text-center">Status</th>
					        	<th class="text-center">Mark As</th>
					        	<th></th>
				      		</tr>
					    </thead>
					    <tbody>
					<?php
						foreach ($voucher_requests as $request) :
							echo form_open('voucher/process');

							echo form_hidden('id', $request->id);
							echo form_hidden('user_id', $request->user_id);
							echo form_hidden('amount', $request->amount);
							echo form_hidden('p_type', $request->product_type);
					?>
							<tr class="text-center">
								<td><a href="<?= base_url().'user/'.$request->username ?>"><?= $request->username ?></a></td>
								<td>
									<?php
									if ($request->product_type == 'paid') echo 'Paid user';
									elseif($request->product_type == 'topu') echo 'Top-up voucher';
									else echo 'Voucher for cash';
									?>
								</td>
								<td><?= $request->voucher_type ?></td>
						        <td>N<?= $request->amount / 100 ?></td>
						        <td><?= $request->voucher ?></td>
						        <td><?= $request->added ?></td>
						        <td>
						        	<?php
						        	if ($request->status == 'pending') echo '<span class="label label-info">Pending</span>';
						        	else echo '<span class="label label-danger">'.$request->status.'</span>';
						        	?>
						        </td>
						        <td>
						        	<select name="mark_as">
						        		<option>...</option>
						        		<option value="valid">Validated</option>
						        		<option value="wpin">Wrong PIN</option>
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
					<div class="text-center">No unresolved vouchers</div>
				</div>
				<?php
				endif;
				?>
      		</div><!-- /.post -->

      		<div class="row post">
				<div class="page-header"><h4>Vouchers (validated)</h4></div>
				<?php
				if ($voucher_requests_validated) :
				?>
				<div class="autoscroll">
					<table class="table">
					    <thead>
				      		<tr>
					        	<th class="text-center">User</th>
					        	<th class="text-center">Product Type</th>
					        	<th class="text-center">Voucher Type</th>
					        	<th class="text-center">Amount</th>
					        	<th class="text-center">Voucher</th>
					        	<th class="text-center">Date/Time</th>
					        	<th class="text-center">Status</th>
					        	<th class="text-center">Mark As</th>
					        	<th></th>
				      		</tr>
					    </thead>
					    <tbody>
					<?php
						foreach ($voucher_requests_validated as $request) :
							echo form_open('voucher/process');

							echo form_hidden('id', $request->id);
							echo form_hidden('user_id', $request->user_id);
							echo form_hidden('amount', $request->amount);
							echo form_hidden('p_type', $request->product_type);
					?>
							<tr class="text-center">
								<td><a href="<?= base_url().'user/'.$request->username ?>"><?= $request->username ?></a></td>
								<td>
									<?php
									if ($request->product_type == 'paid') echo 'Paid user';
									elseif($request->product_type == 'topu') echo 'Top-up voucher';
									else echo 'Voucher for cash';
									?>
								</td>
								<td><?= $request->voucher_type ?></td>
						        <td>N<?= $request->amount / 100 ?></td>
						        <td><?= $request->voucher ?></td>
						        <td><?= $request->added ?></td>
						        <td>
						        	<?php
						        	if ($request->status == 'valid') echo '<span class="label label-info">Validated</span>';
						        	?>
						        </td>
						        <td>
						        	<select name="mark_as">
						        		<option>...</option>
						        		<option value="paid">Paid</option>
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
					<div class="text-center">No validated vouchers</div>
				</div>
				<?php
				endif;
				?>
      		</div><!-- /.post -->

      		<div class="row post">
				<div class="page-header"><h4>Vouchers (paid)</h4></div>
				<?php
				if ($voucher_requests_paid) :
				?>
				<div class="autoscroll">
					<table class="table">
					    <thead>
				      		<tr>
					        	<th class="text-center">User</th>
					        	<th class="text-center">Product Type</th>
					        	<th class="text-center">Voucher Type</th>
					        	<th class="text-center">Amount</th>
					        	<th class="text-center">Voucher</th>
					        	<th class="text-center">Date/Time</th>
					        	<th class="text-center">Status</th>
				      		</tr>
					    </thead>
					    <tbody>
					<?php
						foreach ($voucher_requests_paid as $request) :
					?>
							<tr class="text-center">
								<td><a href="<?= base_url().'user/'.$request->username ?>"><?= $request->username ?></a></td>
								<td>
									<?php
									if ($request->product_type == 'paid') echo 'Paid user';
									elseif($request->product_type == 'topu') echo 'Top-up voucher';
									else echo 'Voucher for cash';
									?>
								</td>
								<td><?= $request->voucher_type ?></td>
						        <td>N<?= $request->amount / 100 ?></td>
						        <td><?= $request->voucher ?></td>
						        <td><?= $request->added ?></td>
						        <td>
						        	<?php
						        	if ($request->status == 'paid') echo '<span class="label label-success">Paid</span>';
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
					<div class="text-center">No paid vouchers</div>
				</div>
				<?php
				endif;
				?>
      		</div><!-- /.post -->

      		<div class="row post">
				<div class="page-header"><h4>Vouchers (declined)</h4></div>
				<?php
				if ($voucher_requests_declined) :
				?>
				<div class="autoscroll">
					<table class="table">
					    <thead>
				      		<tr>
					        	<th class="text-center">User</th>
					        	<th class="text-center">Product Type</th>
					        	<th class="text-center">Voucher Type</th>
					        	<th class="text-center">Amount</th>
					        	<th class="text-center">Voucher</th>
					        	<th class="text-center">Date/Time</th>
					        	<th class="text-center">Status</th>
				      		</tr>
					    </thead>
					    <tbody>
					<?php
						foreach ($voucher_requests_declined as $request) :
					?>
							<tr class="text-center">
								<td><a href="<?= base_url().'user/'.$request->username ?>"><?= $request->username ?></a></td>
								<td>
									<?php
									if ($request->product_type == 'paid') echo 'Paid user';
									elseif($request->product_type == 'topu') echo 'Top-up voucher';
									else echo 'Voucher for cash';
									?>
								</td>
								<td><?= $request->voucher_type ?></td>
						        <td>N<?= $request->amount / 100 ?></td>
						        <td><?= $request->voucher ?></td>
						        <td><?= $request->added ?></td>
						        <td>
						        	<?php
						        	if ($request->status == 'wpin') echo '<span class="label label-danger">Wrong PIN</span>';
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
					<div class="text-center">No declined vouchers</div>
				</div>
				<?php
				endif;
				?>
      		</div><!-- /.post -->
      	</div><!-- /.center -->