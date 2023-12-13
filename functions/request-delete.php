<?php
if (!isset($_COOKIE["userid"])) {
    header("location: ./");
}
require "../config.php";

if (isset($_GET["repository_name"])) {
    $sql = "SELECT * FROM repository WHERE repository_name = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $_GET["repository_name"]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) > 0) {
        while ($result = mysqli_fetch_assoc($res)) {

            $dsql = "DELETE FROM files WHERE date_uploaded = ?";
            $dstmt = mysqli_prepare($link, $dsql);
            mysqli_stmt_bind_param($dstmt, "s", $result['date_created']);
            mysqli_stmt_execute($dstmt);

            $name = "";
            $sssql = "SELECT * FROM users WHERE userid = ?";
            $ssstmt = mysqli_prepare($link, $sssql);
            mysqli_stmt_bind_param($ssstmt, "s", $_COOKIE["userid"]);
            mysqli_stmt_execute($ssstmt);
            $ssres = mysqli_stmt_get_result($ssstmt);
            if (mysqli_num_rows($ssres) > 0) {
                while ($rows = mysqli_fetch_assoc($ssres)) {
                    $name = strtolower(
                        str_replace(
                            " ",
                            "",
                            $rows["firstname"] . $rows["lastname"]
                        )
                    );
                }
            }

            $user_folder = "../r/$name/";
            $repository_name = mysqli_real_escape_string(
                $link,
                $_GET["repository_name"]
            );

            // Delete old files
            $old_files = glob($user_folder . $repository_name . "/*"); // get all files in the repository folder
            foreach ($old_files as $file) {
                if (is_file($file)) {
                    unlink($file); // delete the file
                }
            }

            // Delete repository folder
            $repository_folder = $user_folder . $repository_name;
            if (is_dir($repository_folder)) {
                $success = rrmdir($repository_folder);
                if (!$success) {
                    echo "Failed to delete repository folder";
                }
            }

            $drsql = "DELETE FROM repository WHERE repository_name = ? AND repository_id = ?";
            $drstmt = mysqli_prepare($link, $drsql);
            mysqli_stmt_bind_param($drstmt, "ss", $result['repository_name'], $result['repository_id']);
            mysqli_stmt_execute($drstmt);

            $drssql = "DELETE FROM views WHERE user_folder = ? AND repository_name = ?";
            $drsstmt = mysqli_prepare($link, $drssql);
            mysqli_stmt_bind_param($drsstmt, "ss", $user_folder, $repository_name);
            mysqli_stmt_execute($drsstmt);

            header("location: ../account");
        }
    } else {
        echo "Repository not found";
    }
} else {
    header("location: ../logout");
}

function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir."/".$object))
                    rrmdir($dir."/".$object);
                else
                    unlink($dir."/".$object);
            }
        }
        rmdir($dir);
        return true;
    }
    return false;
}
?>
