<?php
if(!isset($_COOKIE['userid'])){
    header('location: ./');
}
require 'config.php';
if (isset($_GET['repository'])) {
    $user = mysqli_real_escape_string($link, $_GET['user']);
    $repository = mysqli_real_escape_string($link, $_GET['repository']);

    // Use prepared statements
    $sql = "SELECT * FROM repository WHERE repository_name = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $repository);
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
            else{
                header('location: logout');
            }
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta content="assets/svg/logo.svg" name="og:image">
    <meta name="title" content="Analytics - Static Host">
    <meta name="description" content="Great Websites Start with Free Unlimited Hosting">
    <meta name="keywords" content="Static Host, Great Websites Start with Free Unlimited Hosting">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="author" content="Analytics - Static Host">
    <meta property="twitter:url" content="https://statichost.rf.gd/">
    <meta property="twitter:title" content="Analytics - Static Host">
    <meta property="twitter:description" content="Great Websites Start with Free Unlimited Hosting">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="16x16">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="32x32">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<title>Analytics - Static Host</title>
</head>
<body>

<?php include 'navbar-log.php'?>

<div class="analytics">
    <div class="analytics-nav">
        <a href="#" onclick="history.back()">Back to your repository</a>
        <h1><?php echo $result['repository_name']?>'s Overview</h1>
    </div>
    <div class="grid-3">
        <div class="grid-ana">
            <p>Total page views</p>
            <h1>
                <?php
                    $csql = 'SELECT SUM(view) FROM views WHERE repository_name = ?';
                    $cstmt = mysqli_prepare($link, $csql);
                    mysqli_stmt_bind_param($cstmt, 's', $result['repository_name']);
                    mysqli_stmt_execute($cstmt);
                    $cres = mysqli_stmt_get_result($cstmt);
                    if (mysqli_num_rows($cres) > 0) {
                        while($echo = mysqli_fetch_assoc($cres)){
                            if($echo['SUM(view)'] == ""){
                                echo "0";
                            }
                            else{
                                echo $echo['SUM(view)'];
                            }
                        }
                    }
                ?>
            </h1>
        </div>
        <div class="grid-ana">
            <p>Unique page views</p>
            <h1>
                <?php
                    $csql = 'SELECT COUNT(view) FROM views WHERE repository_name = ?';
                    $cstmt = mysqli_prepare($link, $csql);
                    mysqli_stmt_bind_param($cstmt, 's', $result['repository_name']);
                    mysqli_stmt_execute($cstmt);
                    $cres = mysqli_stmt_get_result($cstmt);
                    if (mysqli_num_rows($cres) > 0) {
                        while($echo = mysqli_fetch_assoc($cres)){
                            echo $echo['COUNT(view)'];
                        }
                    }
                ?>
            </h1>
        </div>
        <div class="grid-ana">
            <p>Files count</p>
            <h1>
                <?php
                    $ssql = "SELECT * FROM files WHERE date_uploaded = '".$result['date_created']."' ORDER BY id DESC";
                    $sstmt = mysqli_prepare($link, $ssql);
                    mysqli_stmt_execute($sstmt);
                    $sres = mysqli_stmt_get_result($sstmt);

                    $repository_name = "";
                    $file_name = "";
                    $count = 0;
                    if (mysqli_num_rows($sres) > 0) {
                        while ($row = mysqli_fetch_assoc($sres)) {
                            $file_name = $row["file_name"];
                            $direct = dirname("r/".$name."/".$result['repository_name']."/".$file_name);
                            $count += count(glob($direct . '/*.html'));
                        }
                    }
                    echo $count;
                ?>
            </h1>
        </div>
        <?php
            $url = "";
            if (strpos($url, "index.html") === false) {
                $url = "http://www.statichost.rf.gd/r/".$name."/".$repository."/index.html";
            }
            else{
                $url = "http://www.statichost.rf.gd/r/".$name."/".$repository."/";
            }
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                die("Invalid URL format");
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $html = curl_exec($ch);
            curl_close($ch);

            $start = microtime(true);
            $dom = new DOMDocument();
            $dom->loadHTML($html);
            $end = microtime(true);
            $load_time = $end - $start;

            $domxpath = new DOMXPath($dom);
            $assets = $domxpath->query('//img | //script | //link | //iframe');

            $total_size = 0;
            $total_requests = 0;

            foreach ($assets as $asset) {
                $asset_url = $asset->getAttribute('src') ?: $asset->getAttribute('href');
                if (!empty($asset_url)) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $asset_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HEADER, true);
                    curl_setopt($ch, CURLOPT_NOBODY, true);
                    $headers = curl_exec($ch);
                    curl_close($ch);
                    if (preg_match('/Content-Length: (\d+)/', $headers, $matches)) {
                        $total_size += $matches[1];
                    }
                    $total_requests++;
                }
            }

            $page_size = $total_size / 1024;
            $requests = $total_requests;

            $score = 100 - ($load_time * 10) - ($page_size * 0.1) - ($requests * 0.2);
            $score = max(0, $score);
            $grade = ($score >= 90) ? 'A' : (($score >= 80) ? 'B' : (($score >= 70) ? 'C' : (($score >= 60) ? 'D' : (($score >= 50) ? 'E' : 'F'))));
        ?>
        <div class="grid-ana">
            <p>Web Performance Grade</p>
            <h1>
                <?php echo $grade?>
            </h1>
        </div>
        <div class="grid-ana">
            <p>Page Size (KB)</p>
            <h1>
                <?php echo $page_size?>
            </h1>
        </div>
        <div class="grid-ana">
            <p>Load Time</p>
            <h1 style="font-size: 1.5em; margin-top: 20px;">
                <?php echo $load_time?>
            </h1>
        </div>
        <div class="grid-ana">
            <p>Requests</p>
            <h1>
                <?php echo $requests?>
            </h1>
        </div>
        <div class="grid-ana">
            <p>Live</p>
            <h1 style="font-size: 1.5em; margin-top: 20px;">
                True
            </h1>
        </div>
    </div>
</div>

<div class="analytics-table">
    <p>Recent User Demographics</p>
    <table>
        <tr>
            <th>Country</th>
            <th>Country Code</th>
            <th>Region</th>
            <th>Region Name</th>
            <th>City</th>
            <th>Zip</th>
            <th>Timezone</th>
            <th>Internet</th>
        </tr>
        <?php
            $ssql = "SELECT ipaddress FROM views WHERE ipaddress = '".$_SERVER['REMOTE_ADDR']."' ORDER BY id DESC";
            $sstmt = mysqli_prepare($link, $ssql);
            mysqli_stmt_execute($sstmt);
            $sres = mysqli_stmt_get_result($sstmt);
            if (mysqli_num_rows($sres) > 0) {
                while ($row = mysqli_fetch_assoc($sres)) {

                $url = "http://ip-api.com/json/".$row['ipaddress'];
                $content = file_get_contents($url);
                $json = json_decode($content);
                if ($json->status == "fail"){
                    $return = "Invalid IP";
                }
                else{
                $return = "
                        <tr>
                            <td>".$json->country."</td>
                            <td>".$json->countryCode."</td>
                            <td>".$json->region."</td>
                            <td>".$json->regionName."</td>
                            <td>".$json->city."</td>
                            <td>".$json->zip."</td>
                            <td>".$json->timezone."</td>
                            <td>".$json->isp."</td>
                        </tr>
                ";   
                echo $return;
                }
            }
        }
        ?>
    </table>
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