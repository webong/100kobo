<!DOCTYPE html>
<html>
<head>
<title>Home</title>
</head>

<body>
	<h1>Welcome!</h1>
	<?= form_open('fund/add') ?>
		<input type="email" name="email" />
		<input type="text" name="amount" />
		<button type="submit">Fund!</button>
	<?= form_close() ?>
</body>
</html>