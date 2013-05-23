<?php
include_once("../session.php");
include_once("../header.php");
include_once('class-template.php');

$PATH=$_SERVER[DOCUMENT_ROOT]."/templates/";
$obj_template= new Template_information($PATH);
$dir=$obj_template->ReadFolderDirectory($PATH);


#################### ACTION ##########################

switch($_POST['action']){
		
	case 'add':
	 $msg=asign_template($prefix,$db,$_POST['application'],$_POST[theme]);
	 header("location:assign.php?theme=$theme&msg=$msg");
	 exit();
	break;
				
}
############# OPERATION ####################

function asign_template($prefix,$db,$POST,$theme)
{

 if(count($POST) > 0){		
	foreach($POST as $id)
	{
		$sql="select count(id) as total from ".$prefix."template_assign where `application_id`='$id';";
		$row_total=$db->get_a_line("$sql");
		  
		if($row_total['total']>0)
		{
		$sql="UPDATE ".$prefix."template_assign set `template_id`='$theme' where `application_id`='$id';";
		}
		else {
		$sql="insert ".$prefix."template_assign set `template_id`='$theme',`application_id`='$id'";
		}
		$db->insert("$sql");
		$msg='d';
	}
 }
	return $msg;
}
################## MESSAGE ##################
if($msg == "d")
{
	 $Message ='<div class="success"><img src="../images/tick.png" align="absmiddle"> Template is set as default</div>';
	
}

?>
<?php echo $Message;?>

<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">

<p><strong>Assignement of <?php echo $theme?></strong></p>
<div class="buttons">
<a href="index.php">Go Back!</a>
</div>
<?php
	$sql="SELECT application_id FROM `rrp_template_assign` where template_id='$theme'";
	  $rows=$db->get_rsltset($sql);
	  if(count($rows)>0){
		  foreach($rows as $row)
		  {
		  	if($row['application_id']==1)
		  		$app_id1=1;
		  	else if($row['application_id']==2)
		  	 	$app_id2=2;
		  	else 
		  		$app_id3=3; 	
		  }
	  }
	 
	  
?>
<div class="formborder">
<form name="form" action="assign.php" method="post">
<p><input type="checkbox" value="1" name="application[1]" <?php if($app_id1==1) echo "checked";?>> Blog Management</p> 
<p><input type="checkbox" value="2" name="application[2]" <?php if($app_id2==2) echo "checked";?>> Products Management</p>
<p><input type="checkbox" value="3" name="application[3]" <?php if($app_id3==3) echo "checked";?>> Webpages / Static Content</p>
<input type="hidden" name="theme" value="<?php echo $theme;?>">
<input type="hidden" name="action" value="add">
<p><input type="submit" value="submit" name="submit">
</form>
</div>






</div>
<div class="content-wrap-bottom"></div>
</div>
<?php include_once("../footer.php");?>