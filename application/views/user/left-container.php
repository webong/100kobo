						<div class="left-container col-lg-3 col-md-3 col-xs-12">
									<div class="left-bg">
						<div class="row profile-image center">
										<div class="header-avatar" style="background-image: url('<?php if ($user->image) echo base_url().'images/'.$user->image; else echo base_url().'assets/img/no-profile.png' ?>"></div>
							<a href="<?= base_url()."user/".$user->username ?>"><h4 class="profile"><?php if ($user->first_name) echo $user->first_name.' '.$user->surname; else echo $user->username; ?></h4></a>
							<p><?php if($user->about) echo $user->about ?></p>
										<?php if($show_report) echo '<p><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal-report">Report User</button></p>'; ?>
								</div>
								<div class="row gallery">
										<?php
												if ($g_access) :
														if ($gallery) :
																foreach($gallery as $image) :
																		// echo '<a href="'.base_url().'user/'.$user->username.'/#'.$user->username.'-'.$image->id.'"><img src="'.base_url().'images/'.$image->image.'" /></a>';
																		echo '<a href="'.base_url().'images/'.$image->image.'" data-title="'.$image->text.'" data-lightbox="gallery"><img src="'.base_url().'images/'.$image->image.'" /></a>';
																endforeach;
														else :
														?>
														<!-- <div class="text-center">No items in gallery</div> -->
														<?php
														endif;
												endif;
										?>
								</div>

								<div class="row">
							<div class="page-header">
								<h3>Voucher for Cash</h3>
							</div>
															<?php
															echo form_open('voucher/add');
															?>
															<div class="col-xs-12 post-mini no-borders">
																		<div class="line push-bottom-1">
																					<h5>Voucher Type</h5>
																					<select class="form-control" name="v_type" id="voucher">
																								<option>...</option>
																								<option value="mtn">MTN</option>
																								<option value="glo">Glo</option>
																					</select>
																		</div>
																		<div class="line voucher-option push-bottom-1">
																					<h5>Product Type</h5>
																					<select class="form-control" name="product" id="product">
																								<option value="vfc">Voucher for Cash</option>
																								<?php
																								if ($user->group == 1)
																								echo '<option value="paid">Become a Paid User</option>';
																								elseif ($user->group > 1)
																								echo '<option value="topu">Top-up</option>';
																								?>
																					</select>
																		</div>
																		<div class="line voucher-option push-bottom-1 n-kobo">
																					<h5>Amount</h5>
																					<strong class="n">N</strong>
																					<input type="text" name="amount" class="form-control" id="calc-amount" placeholder="0.00" />
																					<strong class="kobo">kobo</strong>
																		</div>
																		<div class="line voucher-option push-bottom-1" id="calc-naira-holder">
																					<em class="align-right"> Naira Equivalent: <strong>N100.00</strong></em>
																		</div>
																		<div class="line voucher-option push-bottom-1" id="calc-cash-holder">
																					<em class="align-right">Calculated cash: <strong id="calc-cash">0.00</strong></em>
																		</div>
																		<div class="line voucher-option push-bottom-1" id="topup-info-holder">
																					<em class="align-right">Minimum: <strong>N100.00</strong></em>
																		</div>
																		<div class="line voucher-option push-bottom-1">
																					<h5>Your Voucher</h5>
																					<input type="text" name="voucher" class="form-control" placeholder="Your voucher here" />
																		</div>
																		<div class="page-footer voucher-option push-top-2">
																					<?php
																					$args = array('class' => 'btn btn-primary align-right');
																					echo form_submit('voucher_u', 'Send', $args);
																					?>
																		</div>
															</div>
															<?php echo form_close(); ?>
								</div>
									</div><!-- /.left-bg -->

									<div class="left-gray">
												<div class="col-xs-12">
															<ul>
																		<li><a href="<?= base_url().'privacy-policy' ?>">Privacy Policy</a></li>
																		<li><a href="<?= base_url().'terms-and-conditions' ?>">Terms and Conditions</a></li>
																		<li><a href="<?= base_url().'your-content-in-our-services' ?>">Your Content in our Services</a></li>
																		<li><a href="<?= base_url().'our-mission' ?>">Our Mission</a></li>
																		<li><a href="<?= base_url().'developers' ?>">Developers</a></li>
															</ul>
															<span class="copyright"><a href="<?= base_url() ?>">100kobo</a> © 2016 - 2018</span>
												</div>
									</div>
				</div><!-- /.left -->

				<div id="modal-report" class="modal fade" role="dialog">
							<div class="modal-dialog">
									<!-- Modal content-->
									<div class="modal-content">
											<?php
											echo form_open('report');
											echo form_hidden('user_id', $user->id);
											?>
											<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h4 class="modal-title">Report <?= $user->username ?></h4>
											</div>
											<div class="modal-body post">
													<div class="push-bottom-1">
															<h5>Reason</h5>
															<textarea name="message" class="form-control" placeholder="Message..."></textarea>
													</div>
											</div>
											<div class="modal-footer">
													<?php
													$args = array('class' => 'btn btn-blue align-right');
													echo form_submit('submit', 'Report', $args);
													?>
											</div>
											<?= form_close() ?>
									</div>
							</div>
				</div><!-- /.report -->
