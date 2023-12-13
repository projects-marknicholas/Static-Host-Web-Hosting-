<?php
if(!isset($_COOKIE['userid'])){
    header('location: ./');
}
require 'config.php';
if (isset($_GET['repid'])) {
    $repository_id = mysqli_real_escape_string($link, $_GET['repid']);

    // Use prepared statements
    $sql = "SELECT * FROM repository WHERE date_created = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $repository_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $name = "";
    if (mysqli_num_rows($res) > 0) {
        while ($result = mysqli_fetch_assoc($res)) {

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
    <meta property="twitter:url" content="https://statichost.rf.gd/repository?repid=<?php echo $result['date_created']?>">
    <meta property="twitter:title" content="<?php echo $result['repository_name']?> - Static Host">
    <meta property="twitter:description" content="Great Websites Start with Free Unlimited Hosting">
    <link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="16x16">
    <link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="32x32">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <title><?php echo $result['repository_name']?> - Static Host</title>
</head>
<body>

<form method="post" action="functions/update-repository?repository_name=<?php echo $result['repository_name']?>" id="modal" enctype="multipart/form-data">
    <div class="modal-bx">
        <div class="mod-int">
            <h1>Update this repository</h1>
            <p>Consider cloning the repository to your local machine for easy access and version control. Please note that we only allow uploads of files in HTML, CSS, JS, SVG, icon, and font formats. We do not accept folder uploads.</p>
        </div>
        <div class="mod-setup">
            <label for="upload-files">
                <p>Upload files</p>
                <input type="file" name="files[]" required="" multiple="">
            </label>
            <p>Great repository names are short and memorable. Need inspiration? How about <strong>my-website</strong>?</p>
        </div>
        <button type="submit">Update repository</button>
        <button type="button" class="cancel">Cancel</button>
    </div>
</form>

<?php include 'navbar-log.php'?>

<div class="repo-path">
    <div class="left">
        <img src="assets/svg/directory.svg">
        <a href="account"><?php echo $name;?></a> / <a href=""><?php echo $result['repository_name']?></a> <span>Live</span>
    </div>
    <div class="right">
        <a href="#" class="create-new">Update Files</a>
        <a href="functions/download-zip?repid=<?php echo $result['date_created']?>">Download Zip</a>
    </div>
</div>
<div class="repo-dir">
    <div class="left">
        <?php 
            $ssql = "SELECT * FROM files WHERE date_uploaded = '".$result['date_created']."' ORDER BY id DESC";
            $sstmt = mysqli_prepare($link, $ssql);
            mysqli_stmt_execute($sstmt);
            $sres = mysqli_stmt_get_result($sstmt);

            $repository_name = "";
            $file_name = "";
            if (mysqli_num_rows($sres) > 0) {
                while ($row = mysqli_fetch_assoc($sres)) {
                    $repository_name = $row["repository_name"];
                    $file_name = $row["file_name"];
        ?>
        <div class="dir-file">
            <div>
                <img src="assets/svg/file.svg">
                <a href="code?file_id=<?php echo $row['file_id']?>"><?php echo $row['file_name']?></a>
            </div>
        </div>
        <?php
                }
            }
        ?>
    </div>
    <div class="right">
        <h1>About</h1>
        <i><?php echo $result['description']?></i>
        <h1>Danger Zone</h1>
        <a href="functions/request-delete?repository_name=<?php echo $result['repository_name']?>" class="delete">Request Repository Deletion</a>
        <h1>Link</h1>
        <a href="#" onclick="window.open('r/<?php echo $name?>/<?php echo $result['repository_name']?>/')" class="link">www.statichost.rf.gd/r/<?php echo $name?>/<?php echo $result['repository_name']?>/</a><br><br>
        <h1>Analytics</h1>
        <p>  
            <?php
                $direct = dirname("r/".$name."/".$repository_name."/".$file_name);
                $count = count(glob($direct . '/*.html'));
                if ($count > 0) {
                    echo "Page Count: ".$count;
                }
                else{
                    echo "Page Count: 0";
                }
            ?>
        </p>
        <p>
            <?php
                $csql = 'SELECT COUNT(view) FROM views WHERE repository_name = ?';
                $cstmt = mysqli_prepare($link, $csql);
                mysqli_stmt_bind_param($cstmt, 's', $result['repository_name']);
                mysqli_stmt_execute($cstmt);
                $cres = mysqli_stmt_get_result($cstmt);
                if (mysqli_num_rows($cres) > 0) {
                    while($echo = mysqli_fetch_assoc($cres)){
                        echo "Unique Page Views: ".$echo['COUNT(view)'];
                    }
                }
            ?>
        </p>
        <p>
            <?php
                $csql = 'SELECT SUM(view) FROM views WHERE repository_name = ?';
                $cstmt = mysqli_prepare($link, $csql);
                mysqli_stmt_bind_param($cstmt, 's', $result['repository_name']);
                mysqli_stmt_execute($cstmt);
                $cres = mysqli_stmt_get_result($cstmt);
                if (mysqli_num_rows($cres) > 0) {
                    while($echo = mysqli_fetch_assoc($cres)){
                        echo "Total Page Views: ".$echo['SUM(view)'];
                    }
                }
            ?>
        </p>
        <p>View more: 
            <a href="analytics?user=<?php echo $name?>&repository=<?php echo $result['repository_name']?>">view</a>
        </p>
    </div>
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