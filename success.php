<?php
require 'config.php';
if (isset($_GET['success'])) {
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta content="assets/svg/logo.svg" name="og:image">
    <meta name="title" content="Success">
    <meta name="description" content="Great Websites Start with Free Unlimited Hosting">
    <meta name="keywords" content="Static Host, Great Websites Start with Free Unlimited Hosting">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="author" content="Success">
    <meta property="twitter:url" content="https://statichost.rf.gd/">
    <meta property="twitter:title" content="Success">
    <meta property="twitter:description" content="Great Websites Start with Free Unlimited Hosting">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="16x16">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="32x32">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<title>Success</title>
</head>
<body>
	
	<div class="success-mail">
		<div>
			<div>
				<h1>Thanks!</h1>
				<p>The form was submitted successfully</p>
			</div>
			<p>Powered by <img src="assets/svg/logo.svg"> <strong>Static Host</strong></p>
		</div>
	</div>

</body>
</html>
<?php
}
else{
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta content="assets/svg/logo.svg" name="og:image">
    <meta name="title" content="Failed">
    <meta name="description" content="Great Websites Start with Free Unlimited Hosting">
    <meta name="keywords" content="Static Host, Great Websites Start with Free Unlimited Hosting">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="author" content="Failed">
    <meta property="twitter:url" content="https://statichost.rf.gd/">
    <meta property="twitter:title" content="Failed">
    <meta property="twitter:description" content="Great Websites Start with Free Unlimited Hosting">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="16x16">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="32x32">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<title>Failed</title>
</head>
<body>
	
	<div class="success-mail">
		<div>
			<div>
				<h1>404 error!</h1>
				<p>The form wasn't submitted successfully</p>
			</div>
			<p>Powered by <img src="assets/svg/logo.svg"> <strong>Static Host</strong></p>
		</div>
	</div>

</body>
</html>
<?php
}
?>