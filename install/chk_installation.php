<?php
if(!include_once($_SERVER['DOCUMENT_ROOT']."/common/config.php")) echo "Unable to load config.php";
if(mysql_connect($host,$dbuser,$dbpass) && mysql_select_db($dbname))
{
	$config='';
	if($fp = fopen ( "../common/config.php", "w" ))
	{
		fwrite ( $fp, $config );
		fclose ( $fp );
	}
echo $installation = 'yes';
}
else{
	
echo $installation = 'no';
}

?>