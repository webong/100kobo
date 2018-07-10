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
      			<div class="page-header">
					<h4><big>Current Balance: <span class="text-muted">N<?= number_format((float)($wallet + $wallet_free + $wallet_topup), 2, '.', '') ?></span></big></h4>
				</div>
				<div class="line">
      				<div class="col-lg-3 no-padding-lr text-right">
      					Free coins
      				</div>
      				<div class="col-lg-9">
      					<span class="text-muted">N<?= number_format((float)($wallet_free), 2, '.', '') ?></span>
      				</div>
      			</div>
      			<hr />
      			<div class="line">
      				<div class="col-lg-3 no-padding-lr text-right">
      					Topup coins
      				</div>
      				<div class="col-lg-9">
      					<span class="text-muted">N<?= number_format((float)($wallet_topup), 2, '.', '') ?></span>
      				</div>
      			</div>
      			<hr />
      			<div class="line">
      				<div class="col-lg-3 no-padding-lr text-right">
      					Earned coins
      				</div>
      				<div class="col-lg-9">
      					<span class="text-muted">N<?= number_format((float)($wallet), 2, '.', '') ?></span>
      				</div>
				</div>
				<hr />
				<div class="line">
      				<div class="col-lg-offset-3 col-lg-9">
      				<?php if ($can_withdraw) : ?>
      					<a href="<?= base_url().'wallet/process' ?>" class="btn btn-primary <?php if($wallet < 1000) echo 'disabled' ?>">Withdraw</a>
      				<?php else : ?>
      					<button type="button" class="btn btn-primary <?php if($wallet < 1000) echo 'disabled' ?>" data-toggle="modal" data-target="#modal-withdraw">Withdraw</button>
      				<?php endif; ?>
      					<a href="<?= base_url() ?>topup" class="btn btn-primary">Top-up</a>
      				<?php if ($is_paid == 0) : ?>
      					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="<?php if ($wallet > 100 || $wallet_topup > 100 || $wallet_free > 100) : ?>#modal-fund-1<?php else : ?>#modal-fund-2<?php endif; ?>">Become a Paid User</button>
      				<?php endif; ?>
      					<p class="push-top-2"><small>You can withdraw only earned coins<br />(if the amount is N1,000.00 or more)</small></p>
      				</div>
				</div>
      		</div><!-- /.post -->

      		<div class="row post">
      			<?php echo form_open('wallet/update_details'); ?>
				<div class="page-header"><h4>Withdraw Details</h4></div>
				<div class="line push-bottom-1">
					<h5><span class="required">* </span>Account Name</h5>
					<input type="text" name="account_name" class="form-control" value="<?php if(isset($user->account_name)) echo $user->account_name ?>" placeholder="Account Name" />
				</div>
				<div class="line push-bottom-1">
					<h5><span class="required">* </span>Bank Name</h5>
					<input type="text" name="bank_name" class="form-control" value="<?php if(isset($user->bank_name)) echo $user->bank_name ?>" placeholder="Bank Name" />
				</div>
				<div class="line push-bottom-1">
					<h5><span class="required">* </span>Account Number</h5>
					<input type="text" name="account_number" class="form-control" value="<?php if(isset($user->account_number)) echo $user->account_number ?>" placeholder="Account Number" />
				</div>
				<div class="line push-bottom-1">
					<h5>Sort Code</h5>
					<input type="text" name="sort_code" class="form-control" value="<?php if(isset($user->sort_code)) echo $user->sort_code ?>" />
				</div>
				<div class="page-footer push-top-2">
					<?php
					$args = array('class' => 'btn btn-blue align-right');
					echo form_submit('withdraw_details', 'Update', $args);
					?>
				</div>
				<?php echo form_close(); ?>
      		</div><!-- /.post -->

      		<div class="row post">
      			<div class="page-header">
					<h4><big>Transaction history</big></h4>
				</div>
				<div class="autoscroll">
					<table class="table">
					    <thead>
				      		<tr>
					        	<th>From</th>
					        	<th>To</th>
					        	<th>Amount</th>
					        	<th>Info</th>
					        	<th>Date/Time</th>
				      		</tr>
					    </thead>
					    <tbody>
					<?php
					if (count($wallet_history) > 0) :
						foreach ($wallet_history as $trans) :
							if ($trans->type < 10) :
					?>
							<tr class="<?= $trans->whom ?>">
								<?php if ($trans->type == 4) : ?>
								<td><?= $trans->user_to ?></td>
								<?php elseif ($trans->user_from == 'admin') : ?>
								<td><strong>100 kobo</strong></td>
								<?php else : ?>
								<td><?= $trans->user_from ?></td>
								<?php endif; ?>

						        <?php if ($trans->user_to == 'admin') : ?>
								<td><strong>100 kobo</strong></td>
								<?php else : ?>
								<td><?= $trans->user_to ?></td>	
								<?php endif; ?>

						        <td><strong>N</strong><?= number_format((float)($trans->amount / 100), 2, '.', ''); ?></td>
						        <td><?php
						        if ($trans->type == 1) echo 'Sign Up';
						        elseif ($trans->type == 2) echo 'Comment';
						        elseif ($trans->type == 3) echo 'Gallery Unlock';
						        elseif ($trans->type == 4) echo 'Top-Up';
						        elseif ($trans->type == 5) echo 'Withdraw';
						        elseif ($trans->type == 6) echo 'Voucher for cash';
						        elseif ($trans->type == 7) echo 'Award';
						        elseif ($trans->type == 8) echo '5 mins active';
						        elseif ($trans->type == 9) echo 'Become a paid user';
						        ?></td>
						        <td><?= $trans->added ?></td>
					      	</tr>
					<?php
							endif;
						endforeach;
					endif;
					?>
						</tbody>
					</table>
				</div>
			</div>
      	</div><!-- /.center -->

      	<div id="modal-withdraw" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
				  	<div class="modal-header">
				    	<button type="button" class="close" data-dismiss="modal">&times;</button>
				    	<h4 class="modal-title">Can not withdraw at this moment</h4>
				  	</div>
				  	<div class="modal-body post">
				    	<p>
				    		In order to withdraw you must fill out <strong>Withdraw Details</strong> on this page
				    	</p>
				  	</div>
				  	<div class="modal-footer">
				  		<button class="btn btn-blue align-right" data-dismiss="modal">OK</button>
				  	</div>
				</div>
			</div>
		</div>

		<div id="modal-fund-1" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<?= form_open('wallet/addpaid'); ?>
				  	<div class="modal-header">
				    	<button type="button" class="close" data-dismiss="modal">&times;</button>
				    	<h4 class="modal-title">Become a Paid User</h4>
				  	</div>
				  	<div class="modal-body post">
				    	<div class="line push-bottom-1">
							<h5>Amount (N)</h5>
							<input type="text" class="form-control" value="100" placeholder="Amount" disabled="disabled" />
						</div>
						<div class="line push-bottom-1" id="calc-topup-holder">
						  	<em class="align-right">Calculated kobo: <strong id="calc-topup">10000</strong></em>
						</div>
				  	</div>
				  	<div class="modal-footer">
				  		<input type="hidden" name="id" value="15446434" />
				  		<?php
						$args = array('class' => 'btn btn-blue align-right');
						echo form_submit('withdraw_details', 'Become a Paid User', $args);
						?>
				  	</div>
				  	<?= form_close() ?>
				</div>
			</div>
		</div>

		<div id="modal-fund-2" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
				  	<div class="modal-header">
				    	<button type="button" class="close" data-dismiss="modal">&times;</button>
				    	<h4 class="modal-title">Not enought funds</h4>
				  	</div>
				  	<div class="modal-body post">
				    	<p>
				    		In order to become a paid user you must fund your wallet with at least <strong>N100</strong>
				    	</p>
				    	<p>
				    		Go to <a href="<?= base_url() ?>topup" class="btn btn-blue">Fund your wallet</a> page.
				    	</p>
				  	</div>
				  	<div class="modal-footer">
				  		<button class="btn btn-blue align-right" data-dismiss="modal">OK</button>
				  	</div>
				</div>
			</div>
		</div>