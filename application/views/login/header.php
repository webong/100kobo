<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?php echo site_url() ?>assets/img/favicon.ico" />
<meta name="description" content="<?php echo $description ?>" />
<meta name="robots" content="index, follow" />
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
				    <li<?php if(isset($title) && $title == '') echo ' class="active"' ?>><a href="<?= base_url() ?>"><span class="glyphicon glyphicon-home"></span><span>Home</span></a></li>
				    <li<?php if(isset($title) && $title == 'User Guide') echo ' class="active"' ?>><a href="<?= base_url() ?>user-guide"><span class="glyphicon glyphicon-book"></span><span>User Guide</span></a></li>
				    <li class="logout"><a href="#" data-toggle="modal" data-target="#modal-login"><span class="glyphicon glyphicon-lock"></span><span>Login</span></a></li>
				    <li class="logout"><a href="#" data-toggle="modal" data-target="#modal-signup"><span class="glyphicon glyphicon-edit"></span><span>Sign Up</span></a></li>
			  	</ul>
			</div><!--/.nav-collapse -->
		</div>
    </nav>

    <div id="modal-login" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<?php
				echo form_open('login');
				?>
			  	<div class="modal-header">
			    	<button type="button" class="close" data-dismiss="modal">&times;</button>
			    	<h4 class="modal-title"><span class="modal-glyphicon glyphicon glyphicon-lock"></span>Login</h4>
			  	</div>
			  	<div class="modal-body post">
			    	<div class="line">
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
			  	</div>
			  	<div class="modal-footer">
			  		<span class="forgot"><a href="#" data-toggle="modal" data-target="#modal-forgot" onclick="$('#modal-login').modal('hide')">Forgot Password?</a></span>
			  		<?php
			  		$attributes = array('class' => 'btn btn-blue align-right');
					echo form_submit('login_submit', 'Login', $attributes);
			  		?>
			  	</div>
			  	<?php echo form_close() ?>
			</div>
		</div>
	</div>

	<div id="modal-signup" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<?php
				echo form_open('signup');
				?>
			  	<div class="modal-header">
			    	<button type="button" class="close" data-dismiss="modal">&times;</button>
			    	<h4 class="modal-title"><span class="modal-glyphicon glyphicon glyphicon-edit"></span>Sign Up</h4>
			  	</div>
			  	<div class="modal-body post">
					<div class="line">
						<h5>Username</h5>
						<?php
						$attributes = array('class' => 'form-control');
						echo form_input('username', '', $attributes);
						?>
					</div>
					<div class="line">
						<h5>Email</h5>
						<?php
						echo form_input('email', '', $attributes);
						?>
					</div>
					<div class="line">
						<h5>Email (again)</h5>
						<?php
						echo form_input('cemail', '', $attributes);
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
			  	</div>
			  	<div class="modal-footer">
			  		<?php
			  		$attributes = array('class' => 'btn btn-blue align-right');
					echo form_submit('signup_submit', 'Sign Up', $attributes);
			  		?>
			  	</div>
			  	<?php echo form_close() ?>
			</div>
		</div>
	</div>

	<div id="modal-forgot" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<?php
				echo form_open('forgot_password');
				?>
			  	<div class="modal-header">
			    	<button type="button" class="close" data-dismiss="modal">&times;</button>
			    	<h4 class="modal-title"><span class="modal-glyphicon glyphicon glyphicon-question-sign"></span>Forgot Password</h4>
			  	</div>
			  	<div class="modal-body post">
			    	<div class="line">
						<h5>Username</h5>
						<?php
						$attributes = array('class' => 'form-control');
						echo form_input('username', '', $attributes);
						?>
					</div>
					<div class="line push-bottom-1">
						<h5>Email</h5>
						<?php
						$attributes = array('class' => 'form-control');
						echo form_input('email', '', $attributes);
						?>
					</div>
			  	</div>
			  	<div class="modal-footer">
			  		<?php
			  		$attributes = array('class' => 'btn btn-blue align-right');
					echo form_submit('restore_submit', 'Restore', $attributes);
			  		?>
			  	</div>
			  	<?php echo form_close() ?>
			</div>
		</div>
	</div>

    <div class="container">