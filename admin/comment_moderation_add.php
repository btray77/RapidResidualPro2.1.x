<?php
include_once("session.php");
include_once("header.php");


$tbl_name=$prefix."comments";		//your table name
$pageid=$_REQUEST['pageid'];
$filename = $_REQUEST['filename'];
$type=$_REQUEST['type'];

if(isset($_REQUEST['cid']))
{
	$cid=$_REQUEST['cid'];
	$sql='select display_name,comment,display_url,checked,type from '.$prefix.'comments where id='.$cid;
	$comments = $db->get_rsltset($sql);
	$checked=$comments[0]['checked'];
		if($checked==0)
		{
		$sql = "update ".$prefix."comments set checked='1' where id=$cid";
		$db->insert($sql);
		}
}
else $cid=0;
/*************************************************************************/
if ($_GET['msg'] == 'req'){
	$Message = '<div class="top-message"><img src="../images/crose.png" align="absmiddle">Please Fill all the required fields!</div>';
}
/**************************************************************************/	
if (isset($_REQUEST['submit']))
{	
	if(!empty($_POST["comments"])) 
	{
		foreach($_POST as $key=>$items)
			$$key=addslashes($items);
			
		if($cid > 0)
		{
			 $sql="update ".$prefix."comments set 
				comment='".$comments."',
				display_url='".$display_url."'
			 where id=$cid
			 ;";
		}
		else 
		{
			/* If type is time release content then add new field i.e. filename */
			if(isset($filename) && $type == 'trcontent'){ 
				$sql="insert ".$prefix."comments set 
				 comment='".$comments."',
				 display_name='".$name."',
				 page='".$pageid."',
				 filename = '".$filename."',
				 type='".$type."',
				 display_url='".$display_url."',
				 checked='1',
				 date=now()	 
				 ;";
			}else{
				$sql="insert ".$prefix."comments set 
				 comment='".$comments."',
				 display_name='".$name."',
				 page='".$pageid."',
				 type='".$type."',
				 display_url='".$display_url."',
				 checked='1',
				 date=now()	 
				 ;";
				
			}	
		}
	
	$db->insert("$sql");
	if(isset($filename) && $type == 'trcontent'){
		header('location:comment_moderation.php?pageid='.$pageid.'&filename='.$filename.'&type='.$type.'&msg=a');
	}else{
		header('location:comment_moderation.php?pageid='.$pageid.'&type='.$type.'&msg=a');
	}	
	}
	else
	header('location:'.$_SERVER[HTTP_REFERER]."&msg=req");	
	exit();
}
/*********************************************************************************/



include_once('../html/admin/comment_moderation_add.html');

include_once("footer.php");
?>