<?php
if(!isset($_COOKIE['userid'])){
    header('location: ./');
}
require 'config.php';

$sql = "SELECT firstname, lastname, userid, username FROM users WHERE userid = '".$_COOKIE['userid']."'";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) > 0) {
    while ($result = mysqli_fetch_assoc($res)) {
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="assets/svg/logo.svg" name="og:image">
    <meta name="title" content="Free Form - Static Host">
    <meta name="description" content="Great Websites Start with Free Unlimited Hosting">
    <meta name="keywords" content="Static Host, Great Websites Start with Free Unlimited Hosting">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="author" content="Free Form - Static Host">
    <meta property="twitter:url" content="https://statichost.rf.gd/">
    <meta property="twitter:title" content="Free Form - Static Host">
    <meta property="twitter:description" content="Great Websites Start with Free Unlimited Hosting">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="16x16">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="32x32">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
	<title>Free Form - Static Host</title>
</head>
<body>

<?php include 'navbar-log.php'?>

<div class="package-nav">
    <div>
        <a href="account">back</a>
        <h1><?php echo $result['firstname']?> <?php echo $result['lastname']?>'s Form</h1>
    </div>
</div>
<div class="package-integrate">
    <h2>Your form's endpoint is:</h2>
    <div class="form-endpoint">
        <div>
            <input type="text" readonly="" id="endpoint" value="../../../form?formid=<?php echo $result['userid']?>">
        </div>
        <button onclick="copyEndpoint()">Copy</button>
    </div>
    <p>
        Place this URL in the action attribute of your form. Also, make sure your form uses method="POST". Finally, ensure that each input has a name attribute.
    </p>

    <h2>Integrate with your usecase</h2>
    <p>Check out the code snippets below for more example:</p>
    <div class="sample-code">
        <pre><code class="html">&lt;form method="post" action="../../../form?formid=<?php echo $result['userid']?>"&gt;
    &lt;input type="text" name="name"&gt;
    &lt;input type="text" name="subject"&gt;
    &lt;input type="email" name="email"&gt;
    &lt;textarea name="name" rows="5" cols="5"&gt;&lt;/textarea&gt;
&lt;/form&gt;</code></pre>
    </div>

    <h2>Target Email</h2>
    <div class="form-endpoint">
        <div>
            <input type="text" readonly="" id="target-email" value="<?php echo $result['username']?>">
        </div>
        <button class="disabled">Save</button>
    </div>
    <p>Where to send submissions.</p>

    <h2>Redirect URL</h2>
    <div class="form-endpoint">
        <div>
            <input type="link" name="redirect-url" readonly="" value="Not available yet">
        </div>
        <button class="disabled">Save</button>
    </div>
</div><br>

<script src="assets/js/menu-chunk-07197.js"></script>

</body>
</html>
<?php
    }
}
?>