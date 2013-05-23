<?php
error_reporting(1);

if(!include_once($_SERVER['DOCUMENT_ROOT']."/common/config.php")) echo "Unable to load config.php";
if(mysql_connect($host,$dbuser,$dbpass) && mysql_select_db($dbname))
{
	$installation = 'yes';
}
else{
	
	$installation = 'no';
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

	<meta name="description" content="">

	<meta name="keywords" content="">

	<title>Rapid Residual Pro</title>

	<link type="text/css" rel="stylesheet" href="css/template.css" />

    <link rel="stylesheet" type="text/css" href="../common/newLayout/core.css"/>

    <script src="../common/newLayout/jquery/jquery.js" type="text/javascript" charset="utf-8"></script>

    <script src="../scripts/validate.js" type="text/javascript" charset="utf-8"></script>    

    <script type="text/javascript"> 

        /* Validating forms */

		$(document).ready(function(){

            $("#form1").validate();

        });

    </script>

</head>

<body>

	<div id="wrap">

	    <div id="header">

			<div class="wrap">

				<h1><a href="#none">Rapid Residual Pro</a></h1>

			</div><!-- end of wrap -->

		</div><!-- end of header -->