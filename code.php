<?php
if(!isset($_COOKIE['userid'])){
    header('location: ./');
}
require 'config.php';
if (isset($_GET['file_id'])) {
    $file_id = mysqli_real_escape_string($link, $_GET['file_id']);

    // Use prepared statements
    $sql = "SELECT * FROM files WHERE file_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $file_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) > 0) {
        while ($result = mysqli_fetch_assoc($res)) {

            $name = "";
            $ssql = "SELECT * FROM users WHERE userid = ?";
            $sstmt = mysqli_prepare($link, $ssql);
            mysqli_stmt_bind_param($sstmt, "s", $_COOKIE['userid']);
            mysqli_stmt_execute($sstmt);
            $sres = mysqli_stmt_get_result($sstmt);
            if (mysqli_num_rows($sres) > 0) {
                while ($row = mysqli_fetch_assoc($sres)) {
                    $name = strtolower(str_replace(' ', '', $row['firstname'].$row['lastname']));
                }
            }
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="assets/svg/logo.svg" name="og:image">
    <meta name="title" content="<?php echo $result['repository_name']?> - Static Host">
    <meta name="description" content="Great Websites Start with Free Unlimited Hosting">
    <meta name="keywords" content="Static Host, Great Websites Start with Free Unlimited Hosting">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="author" content="<?php echo $result['repository_name']?> - Static Host">
    <meta property="twitter:url" content="https://statichost.rf.gd/repository?repid=12345">
    <meta property="twitter:title" content="<?php echo $result['repository_name']?> - Static Host">
    <meta property="twitter:description" content="Great Websites Start with Free Unlimited Hosting">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="16x16">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="32x32">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
	<title><?php echo $result['repository_name']?> - Static Host</title>
</head>
<body>

<?php include 'navbar-log.php'?>

<div class="repo-path">
    <div class="left">
        <img src="assets/svg/directory.svg">
        <a href="account"><?php echo $name?></a> / <a href="repository?repid=<?php echo $result['date_uploaded']?>"><?php echo $result['repository_name']?></a> / <a href=""><?php echo $result['file_name']?></a> <span>Live</span>
    </div>
    <div class="right"></div>
</div>

<div class="code-view">
    <div class="nav">
        <a href="edit-code?file_id=<?php echo $result['file_id']?>">Edit</a>
    </div>
    <?php
        $html_file = "r/".$name."/".$result['repository_name']."/".$result['file_name'];
        if (!file_exists($html_file)) {
          echo "File does not exist.";
          exit();
        }
        if (!$html_handle = fopen($html_file, "r")) {
          echo "Cannot open file.";
          exit();
        }
        $html_contents = fread($html_handle, filesize($html_file));
        fclose($html_handle);
        echo '<pre><code class="html">' . htmlspecialchars($html_contents) . '</code></pre>';
    ?>
</div><br>

<script src="assets/js/menu-chunk-07197.js"></script>

</body>
</html>
<?php
        }
    }
    mysqli_stmt_close($stmt);
}

?>