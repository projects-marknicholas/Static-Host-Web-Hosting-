<?php
if(!isset($_COOKIE['userid'])){
    header('location: ./');
}
require 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="assets/svg/logo.svg" name="og:image">
    <meta name="title" content="Account - Static Host">
    <meta name="description" content="Great Websites Start with Free Unlimited Hosting">
    <meta name="keywords" content="Static Host, Great Websites Start with Free Unlimited Hosting">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="author" content="Account - Static Host">
    <meta property="twitter:url" content="https://statichost.rf.gd/">
    <meta property="twitter:title" content="Account - Static Host">
    <meta property="twitter:description" content="Great Websites Start with Free Unlimited Hosting">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="16x16">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="32x32">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<title>Account</title>
</head>
<body>

<?php
    $current_url = $_SERVER['REQUEST_URI'];
    $parsed_url = parse_url($current_url);
    $path = explode('/', $parsed_url['path'])[2];
?>

<form method="post" action="functions/create-repository" id="modal" enctype="multipart/form-data">
    <div class="modal-bx">
        <div class="mod-int">
            <h1>Create a new repository</h1>
            <p>Consider cloning the repository to your local machine for easy access and version control. Please note that we only allow uploads of files in HTML, CSS, JS, SVG, icon, and font formats. We do not accept folder uploads.</p>
        </div>
        <div class="mod-setup">
            <label for="repository-name">
                <p>Repository name</p>
                <input type="text" name="repository" required pattern="[A-Za-z0-9_]+" autocomplete="off">
            </label>
            <label for="description">
                <p>Description</p>
                <input type="text" name="description" required="" autocomplete="off">
            </label>
            <label for="upload-files">
                <p>Upload files</p>
                <input type="file" name="files[]" required="" multiple="">
            </label>
            <p>Great repository names are short and memorable. Need inspiration? How about <strong>my-website</strong>?</p>
        </div>
        <button type="submit">Create repository</button>
        <button type="button" class="cancel">Cancel</button>
    </div>
</form>

<?php include 'navbar-log.php'?>

<div class="account">
    <div class="left">
        <div>
            <div class="left">
                <h1>Repositories</h1>
            </div>
            <div class="right">
                <a href="#" class="create-new">
                    <img src="assets/svg/repository.svg">
                    New
                </a>
            </div>
        </div>
        <input type="text" id="repository" placeholder="Find a repository..." autocomplete="off">
        <main class="repository-list">
            <?php 
                $sql = "SELECT * FROM repository WHERE repository_id = '".$_COOKIE['userid']."' ORDER BY id DESC";
                $stmt = mysqli_prepare($link, $sql);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($res) > 0) {
                    while ($result = mysqli_fetch_assoc($res)) {
            ?>
                        <div>
                            <a href="repository?repid=<?php echo htmlspecialchars($result['date_created'], ENT_QUOTES) ?>">
                                <?php echo htmlspecialchars($result['repository_name'], ENT_QUOTES) ?>
                            </a>
                        </div>
            <?php
                    }
                }
            ?>
        </main>
    </div>
    <div class="right">
        <div class="package">
            <img src="assets/svg/package.svg" class="svg-package">
            <h1>Get started with Static Host Packages</h1>
            <p>Our web hosting packages offer code-free functionality customization with built-in features. Safely publish, store and share packages alongside your code, without needing server-side programming.</p>
            <h3>Choose a registry</h3>
            <div class="grid-3">
                <div class="grid-item">
                    <h1>Free Form</h1>
                    <p>
                        Creating and embedding custom forms on your website. Receive submissions via email, no server-side scripting needed.
                    </p>
                    <a href="free-form">Use</a>
                </div>
                <div class="grid-item">
                    <h1>Free Comments</h1>
                    <p>
                        A customizable and easy-to-use comment system for your website. No coding knowledge required. Embed on your website for free.
                    </p>
                    <a href="#" onclick="alert('Not available yet')">Use</a>
                </div>
                <div class="grid-item">
                    <h1>Free Analytics</h1>
                    <p>
                        A powerful website analytics tool for tracking visitor behavior. No cost, no setup fees. Easy-to-use dashboard and custom reporting.
                    </p>
                    <a href="#" onclick="alert('This feature is available upon creation of your repository')">Use</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/menu-chunk-07197.js"></script>

</body>
</html>