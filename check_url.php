<?php
include_once "common/config.php";
include_once "include.php";

function is_valid_url($url) {
    $resURL = curl_init();
    curl_setopt($resURL, CURLOPT_URL, $url);
    curl_setopt($resURL, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($resURL, CURLOPT_HEADERFUNCTION, 'curlHeaderCallback');
    curl_setopt($resURL, CURLOPT_FAILONERROR, 1);
    curl_exec($resURL);
    $intReturnCode = curl_getinfo($resURL, CURLINFO_HTTP_CODE);
    curl_close($resURL);
    if ($intReturnCode != 200 && $intReturnCode != 302 && $intReturnCode != 304) {
        return "<font color='red'>&nbsp;Invalid URl !</font>";
    } else {
        return "<font color='green'>&nbsp;Ok</font>";
    }
}

    $sql = "select * from " . $prefix . "license_domains where domain='".$_GET['q']."'";

    $res = $db->get_a_line($sql);
    
    if($res!=false)
        echo "<label for=1 generated=true class=error>This domain is already exist.</label>";
    else
        echo is_valid_url($_GET['q']);
?>
