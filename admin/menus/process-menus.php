<?php 
include_once('module_config.php');
if(!session_start()){
	session_start();
}

if(count($_POST) > 0)
{
	foreach($_POST as $key => $items){
		$$key=trim($items);
	}
	
	$published = $published=="on" ? "1": "0";

	if($id > 0)	
	{
		$sql="update $table_name set `title`='$name', `pageid`='$webpage',`type`='$type', `published`=$published where `id`='$id'";
	}
	else
	{
		$sql="insert $table_name set `title`='$name', `pageid`='$webpage',`type`='$type', `created_date`=NOW(), `published`=$published;";
	}
	
	$exec = mysql_query($sql);
	
	if($exec){
		
		$_SESSION['success']=1;
		echo "<script>window.location.href='".$mod_url."/listings'</script>";
		exit();
	}
	else{
		if(mysql_errno()=="1062")
		$_SESSION['error']=2;
		else
		$_SESSION['error']=1;
		echo "<script>window.location.href='".$mod_url."/listings'</script>";
		exit();
	}
	
}
else
{
	$_SESSION['error']=1;
	echo "<script>window.location.href='".$mod_url."/listings'</script>";
	exit();
}

?>