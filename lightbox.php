<?php
error_reporting(E_ERROR);
include_once "include.php";
include_once "common/placeholder.class.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Videos</title>
</head>
<body>
<?php
if(!empty($_GET['video']) && is_numeric($_GET['video'])){
    echo $token = 	$common->getmedia('video',(int) $_GET['video'] ,$db,$prefix);
}
else if(!empty($_GET['audio']) ){
    echo $token =	$common->getmedia('audio',$temp[2],$db,$prefix);
}
?>
</body>
</html>