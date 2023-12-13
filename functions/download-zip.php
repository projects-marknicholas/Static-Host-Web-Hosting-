<?php
if(!isset($_COOKIE['userid'])){
    header('location: ./');
}
require '../config.php';

if (isset($_GET['repid'])) {
    $sql = "SELECT * FROM files WHERE date_uploaded = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $_GET['repid']);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($res) > 0) {
        while ($result = mysqli_fetch_assoc($res)) {
            if ($result['date_uploaded'] == $_GET['repid']) {

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

                $repository_name = $result['repository_name'];
                $folder = '../r/'.$name.'/'.$repository_name;
                $zipname = $result['repository_name'].'.zip';

                // Create a new zip archive
                $zip = new ZipArchive();
                if ($zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                    die('Failed to create archive!');
                }

                // Add all files in the directory to the archive
                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder));
                foreach ($files as $name => $file) {
                    // Skip directories and files that cannot be read
                    if (!$file->isDir() && $file->isReadable()) {
                        $zip->addFile($file, str_replace($folder . '/', '', $name));
                    }
                }

                $zip->close();

                // Set headers to prompt download
                header('Content-Type: application/zip');
                header('Content-disposition: attachment; filename=' . $zipname);
                header('Content-Length: ' . filesize($zipname));

                // Output the contents of the zip file and delete it
                ob_clean();
                flush();
                readfile($zipname);
                unlink($zipname);

                // Redirect user to repository page
                header('location: repository?repid='.$result['date_uploaded']);
            }
            else{
                header('location: ../logout');
            }
        }
    }
}
else{
    header('location: ../logout');
}
