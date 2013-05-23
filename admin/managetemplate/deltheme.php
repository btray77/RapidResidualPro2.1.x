<?php
include_once($_SERVER['DOCUMENT_ROOT']."/common/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/common/database.class.php");
$db = new database();
function delete_directory($dirname) {
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . "/" . $file))
                unlink($dirname . "/" . $file);
            else
                delete_directory($dirname . '/' . $file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

#################### Delete Theme ##########################

if (isset($_GET['theme']) and $_GET['action'] == "del"){

    $theme = $root_path."/templates/".$_GET['theme'];
    if (is_dir($theme)) {
        if (delete_directory($theme)) {
            $sql = "delete from " . $prefix . "template where `name`='$_GET[theme]'";
            $db->insert("$sql");
            $msgflag =1;
        } else {
            $msgflag =2;
        }
    } else {
        $msgflag =3;
    }
}
echo "<script>window.location='index.php?msgflag=$msgflag'</script>"
//header("index.php?msgflag=".$msgflag)
?>
