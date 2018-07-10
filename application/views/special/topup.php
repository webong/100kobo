
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
			<?php if ($payment === 1) : ?>
				<?php echo form_open('topup/add'); ?>
					<div class="page-header"><h4>Fund your wallet</h4></div>
					<div class="line push-bottom-1">
						<h5>Amount to Fund (N)</h5>
						<input type="text" name="amount" id="calc-amount-topup" class="form-control" placeholder="Amount" />
					</div>
					<div class="line push-bottom-1" id="calc-topup-holder">
						  <em class="align-right">Calculated kobo: <strong id="calc-topup">0</strong></em>
					</div>

					<div class="page-footer push-top-2">
						<?php
						$args = array('class' => 'btn btn-blue align-right');
						echo form_submit('withdraw_details', 'Fund', $args);
						?>
					</div>

				<?php echo form_close(); ?>
			<?php else : ?>
				<!-- live -->
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<div class="page-header"><h4>Fund your wallet</h4></div>
					<div class="line push-bottom-1">
						<h5>Amount to Fund (N)</h5>
						<input type="text" name="amount" id="calc-amount-topup" class="form-control" placeholder="Amount" />
					</div>

					<div class="page-footer push-top-2">
						<input type="hidden" name="cmd" value="_xclick">
						<input type="hidden" name="business" value="5GL5QFPZAZLDA">
						<input type="hidden" name="lc" value="US">
						<input type="hidden" name="item_name" value="buy-kobo">
						<input type="hidden" name="amount" value="0.00" id="pp_amount">
						<input type="hidden" name="currency_code" value="USD">
						<input type="hidden" name="button_subtype" value="services">
						<input type="hidden" name="custom" value="<?php echo $user ?>">
						<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHosted">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</div>
				</form>
				<?php /*
				<!-- sandbox -->
				<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<div class="page-header"><h4>Fund your wallet</h4></div>
					<div class="line push-bottom-1">
						<p>...</p>
					</div>

					<div class="page-footer push-top-2">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="3U5XF9GF985E6">
						<input type="hidden" name="custom" value="<?php echo $user ?>">
						<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</div>
				</form>
				*/ ?>
			<?php endif; ?>
      		</div><!-- /.post -->
      	</div><!-- /.center -->