<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?php echo site_url() ?>assets/img/favicon.ico" /> 
<meta name="description" content="<?php echo $description ?>" />
<?php if (isset($no_index)) :
echo '<meta name="robots" content="index, follow" />';
else :
echo '<meta name="robots" content="noindex, nofollow" />';
endif;
?>
<!-- Open Graph data -->
<meta property="og:title" content="<?php if(isset($title)) echo '100 kobo - '.$title; else echo '100 kobo'; ?>">
<meta property="og:url" content="<?php echo $url ?>" />
<meta property="og:type" content="website" />
<meta property="og:description" content="<?php echo $description ?>" />
<meta property="og:site_name" content="<?php echo $site_name ?>" />
<meta property="og:image" content="<?php echo site_url() ?>assets/img/logo.png" />
<!-- Twitter Card -->
<meta name="twitter:card" content=app />
<meta name="twitter:title" content="<?php if(isset($title)) echo '100 kobo - '.$title; else echo '100 kobo'; ?>" />
<meta name="twitter:description" content="<?php echo $description ?>" />
<meta name="twitter:url" content="<?php echo $url ?>" />
<meta name="twitter:image" content="<?php echo site_url() ?>assets/img/logo.png" />

<!-- Compiled and minified CSS -->
<link href="<?= base_url()."assets/css/bootstrap.min.css" ?>" rel="stylesheet" />
<!-- Lightbox -->
<link href="<?= base_url()."assets/css/lightbox.css" ?>" rel="stylesheet">
<!-- Custom style -->
<link href="<?= base_url()."assets/css/style.css" ?>" rel="stylesheet" />
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-101941580-1', 'auto');
  ga('send', 'pageview');

</script>
<title><?php if(isset($title)) echo '100 kobo - '.$title; else echo '100 kobo'; ?></title>
</head>

<body>
	<nav class="navbar navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				    <span class="sr-only">Toggle navigation</span>
				    <span class="icon-bar"></span>
				    <span class="icon-bar"></span>
				    <span class="icon-bar"></span>
		  		</button>
			</div>
			<div class="navbar-brand"><img src="<?= base_url() ?>assets/img/logo-big.png" alt="100 kobo" /></div>
			<div id="navbar" class="collapse navbar-collapse">
			  	<ul class="nav navbar-nav">
				    <li<?php if($title == 'Home') echo ' class="active"' ?>><a href="<?= base_url() ?>"><span class="glyphicon glyphicon-home"></span><span>Home</span></a></li>
				    <?php if (isset($updated)) : ?><li><a href="#" data-toggle="modal" data-target="#modal-main"><span class="glyphicon glyphicon-pencil"></span><span>Write post</span></a></li><?php endif; ?>
				    <li class="<?php if($title == 'Settings' || $title == 'Wallet') echo 'active ' ?>dropdown pull-right pull-left-md">
	                  	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="<?php if ($user->image) echo base_url().'images/'.$user->image; else echo base_url().'assets/img/no-profile.png' ?>" class="nav-profile nav-profile-md" alt="User profile" /><span class="nav-name"><?php if ($user->first_name) echo $user->first_name.' '.$user->surname; else echo $user->username; ?></span><img src="<?php if ($user->image) echo base_url().'images/'.$user->image; else echo base_url().'assets/img/no-profile.png' ?>" class="nav-profile" /></a>
	                  	<ul class="dropdown-menu pull-righ">
                      		<li><a href="<?= base_url()."user/".$user->username ?>"><span class="glyphicon glyphicon-user"></span><span>My profile</span></a></li>
                      		<li class="divider"></li>
                      		<li><a href="<?= base_url()."settings/" ?>"><span class="glyphicon glyphicon-cog"></span><span>Settings</span></a></li>
                      		<li><a href="<?= base_url()."wallet/" ?>"><span class="glyphicon glyphicon-briefcase"></span><span>My Wallet</span></a></li>
	                      	<li class="divider"></li>
	                      	<li><a href="<?= base_url()."logout/" ?>"><span class="glyphicon glyphicon-lock"></span><span>Log Out</span></a></li>
	                  	</ul>
	              	</li>
	              	<?php if ($access == 3) : ?>
				    <li class="<?php if ($title == 'Reports' || $title == 'User Management' || $title == 'Voucher For Cash / Become Paid User' || $title == 'Withdraw requests') echo 'active ' ?>dropdown pull-right pull-left-md">
	                  	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="nav-name">Dashboard</span></a>
	                  	<ul class="dropdown-menu pull-righ">
	                  		<li><a href="<?= base_url()."reports/" ?>"><span class="glyphicon glyphicon-hand-right"></span><span>Reports</span></a></li>
	                  		<li><a href="<?= base_url()."users/" ?>"><span class="glyphicon glyphicon-user"></span><span>User Management</span></a></li>
	                  		<li><a href="<?= base_url()."voucher/" ?>"><span class="glyphicon glyphicon-scissors"></span><span>Vouchers</span></a></li>
	                  		<li><a href="<?= base_url()."withdraw/" ?>"><span class="glyphicon glyphicon-piggy-bank"></span><span>Withdraw Requests</span></a></li>
	                  	</ul>
	                </li>
				    <?php endif; ?>
			  	</ul>
			</div><!--/.nav-collapse -->
		</div>
    </nav>

    <div id="modal-main" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<?php
				$args = array();
				if ($access == 1) {
					$args['onsubmit'] = 'return validate(this)';
				}
				echo form_open_multipart('post', $args);
				?>
			  	<div class="modal-header">
			    	<button type="button" class="close" data-dismiss="modal">&times;</button>
			    	<h4 class="modal-title">Write post</h4>
			  	</div>
			  	<div class="modal-body post">
			    	<div class="line">
						<h5>Message</h5>
						<span id="textarea_feedback" class="counter align-right"></span>
						<textarea name="message" id="textarea" class="form-control" maxlength="160" placeholder="Message..."></textarea>
					</div>
					<div class="line push-top-2 push-bottom-1">
						<h5>Upload image (optional)</h5>
						<input type="file" name="file" size="20" accept="image/*" />
					</div>
					<?php if ($access > 1) : ?>
					<div class="line push-top-2 push-bottom-1">
						<!-- <p class="text-small-alert">You are posting to Trending zone</p> -->
						<h5 class="inline">Zone</h5>
						<div class="inline">
							<input type="radio" name="zone" value="t" checked /> Trending<br />
							<input type="radio" name="zone" value="f" /> Free
						</div>
					</div>
					<?php endif; ?>
			  	</div>
			  	<div class="modal-footer">
			  		<?php
			  		$args = array('class' => 'btn btn-blue align-right');
			  		echo form_submit('submit', 'Post', $args);
			  		?>
			  	</div>
			  	<?= form_close() ?>
			</div>
		</div>
	</div>

	<div id="modal-edit" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<?php
				echo form_open_multipart('edit');
				?>
			  	<div class="modal-header">
			    	<button type="button" class="close" data-dismiss="modal">&times;</button>
			    	<h4 class="modal-title">Edit post</h4>
			  	</div>
			  	<div class="modal-body post">
			    	<div class="line">
						<h5>Message</h5>
						<span id="textarea_feedback" class="counter align-right"></span>
						<textarea name="message" id="textarea_edit" class="form-control" maxlength="160" placeholder="Message..."></textarea>
						<div id="image_edit">
							<p><strong>Image attached: </strong><span></span></p>
							<img id="image_edit_img" src="" />
							<p><button type="button" id="remove_image" class="btn btn-xs btn-danger m-t">Remove Image</button></p>
						</div>
					</div>
					<div class="line push-top-2 push-bottom-1">
						<div id="upload_image">
							<h5>Upload image (optional)</h5>
							<input type="file" id="file_edit" name="file" size="20" accept="image/*" />
							<input type="hidden" name="post_id" id="post_id" value="0" />
						</div>
					</div>
			  	</div>
			  	<div class="modal-footer">
			  		<?php
			  		$args = array('class' => 'btn btn-blue align-right');
			  		echo form_submit('submit', 'Post', $args);
			  		?>
			  	</div>
			  	<?= form_close() ?>
			</div>
		</div>
	</div>

	<div id="modal-paid-user" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
			    	<button type="button" class="close" data-dismiss="modal">&times;</button>
			    	<h4 class="modal-title">Error</h4>
			  	</div>
			  	<?php
                echo form_open('voucher/add');
                ?>
			  	<div class="modal-body post">
			    	<div class="line push-bottom-1">
						<p>You have to be <span class="label label-primary no-float">Paid User</span> in order to post and comment in trending zone.</p>
						<p>Fill out fields below and become paid user in a few clicks.
					</div>
					<hr />
					<div class="line push-bottom-1">
					    <h5>Voucher Type</h5>
					    <select class="form-control popup_voucher" name="v_type">
					          <option>...</option>
					          <option value="mtn">MTN</option>
					          <option value="glo">Glo</option>
					    </select>
					    <?php echo form_hidden('product', 'paid'); ?>
					</div>
					<div class="line push-bottom-1 n-kobo-popup">
					    <h5>Amount</h5>
					    <input type="text" name="amount" class="form-control" value="10000" readonly="" />
					    <strong>kobo</strong>
					    <div class="line push-bottom-1">
                        	<em class="align-right">Naira Equivalent: <strong>N100.00</strong></em>
                    	</div>
					</div>
					<div class="line push-bottom-1">
					    <h5>Your Voucher</h5>
					    <input type="text" name="voucher" class="form-control" placeholder="Your voucher here" />
					</div>
			  	</div>
			  	<div class="modal-footer">
				    <?php
				    $args = array('class' => 'btn btn-primary align-right');
				    echo form_submit('voucher_u', 'Send', $args);
				    ?>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>

	<div id="modal-paid-user-2" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
			    	<button type="button" class="close" data-dismiss="modal">&times;</button>
			    	<h4 class="modal-title">Become a paid user</h4>
			  	</div>
			  	<?php
                echo form_open('voucher/add');
                ?>
			  	<div class="modal-body post">
			    	<div class="line push-bottom-1">
						<p>Fill out fields below and become paid user in a few clicks.
					</div>
					<hr />
					<div class="line push-bottom-1">
					    <h5>Voucher Type</h5>
					    <select class="form-control popup_voucher" name="v_type">
					          <option>...</option>
					          <option value="mtn">MTN</option>
					          <option value="glo">Glo</option>
					    </select>
					    <?php echo form_hidden('product', 'paid'); ?>
					</div>
					<div class="line push-bottom-1 n-kobo-popup">
					    <h5>Amount</h5>
					    <input type="text" name="amount" class="form-control" value="10000" readonly="" />
					    <strong>kobo</strong>
					    <div class="line push-bottom-1">
                        	<em class="align-right">Naira Equivalent: <strong>N100.00</strong></em>
                    	</div>
					</div>
					<div class="line push-bottom-1">
					    <h5>Your Voucher</h5>
					    <input type="text" name="voucher" class="form-control" placeholder="Your voucher here" />
					</div>
			  	</div>
			  	<div class="modal-footer">
				    <?php
				    $args = array('class' => 'btn btn-primary align-right');
				    echo form_submit('voucher_u', 'Send', $args);
				    ?>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>

    <div class="container">