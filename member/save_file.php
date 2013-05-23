<?php
$getfile = explode(".", $_GET['file']);
$file_ext = $getfile[1];
switch ($file_ext) {
	  case "pdf":
	  $ctype="application/pdf";
	  break;
	  case "doc":
	  $ctype="application/msword";
	  break;
	  case "xls":
	  $ctype="application/vnd.ms-excel"; 
	  break;
	  case "ppt": 
	  $ctype="application/vnd.ms-powerpoint"; 
	  break;
	  case "txt": 
	  $ctype="text/plain"; 
	  break;	  
	  case "gif": 
	  $ctype="image/gif"; 
	  break;
	  case "png": 
	  $ctype="image/png"; 
	  break;
	  case "jpeg":
	  case "jpg": 
	  $ctype="image/jpg"; 
	  break;
	  default: 
	  $ctype="application/force-download";
} 
header('Content-type: ' . $ctype);
header('Content-Disposition: attachment; filename="'.$_GET['file'].'"');
readfile('../document/'.$_GET['file']);
?>
