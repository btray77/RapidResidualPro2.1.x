<?php
include_once '../session.php';
include_once '../header.php';
include_once('module_config.php');
$targetpage=$mod_url;
if( $id > 0){
$sql="select * from ".$prefix."". $table_name. " where id=$id";
$menus=$db->get_a_line($sql);
}

if(!empty($_REQUEST['action'])){
	$action=$_REQUEST['action'];
}
else {
	$action="Add";
}
switch($_REQUEST['action'])
{
	case 'add':
		$msg=save_records($db,$prefix,$_POST);
		header("location:$targetpage?msg=$msg");
		break;
		
	case 'edit':
		$msg=save_records($db,$prefix,$_POST);
		header("location:$targetpage?msg=$msg");
		break;
			
}
function save_records($db,$prefix,$_POST)
{
	foreach($_POST as $key => $items){
		$$key=addslashes($items);
	}
	if(empty($published)) $published=0;
	if($id > 0)
	{
		$sql="update ".$prefix."menus set menu_name='$name',menu_alias='menu_$menu_alias',published='$published' where id=$id";
		$db->insert($sql);
		$msg='edit';
	}
	else
	{
		$sql="insert ".$prefix."menus set menu_name='$name',menu_alias='menu_$menu_alias',published='$published'";
		$db->insert($sql);
		$msg='add';
	}
	return $msg;
}

?>
<style type="text/css">
<!--
.style1 {color: #FF0000}

-->
</style>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong><?php if($id > 0) echo "Edit"; else echo "Add"; ?> <?php echo $module;?></strong></p>
<div class="buttons">
<a href="<?php echo $mod_url; ?>/" class="add" style="text-transform:capitalize"><?php echo $module;?></a>
</div>
<div class="formborder">
<form method="post" name="menus" id="menus" action="<? echo $_SERVER['PHP_SELF'];?>">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="16%" nowrap="nowrap"><label>Name:<span class="style1">*</span></label></td>
    <td width="84%" nowrap="nowrap">
    <input name="name" type="text" value="<?php echo stripslashes($menus['menu_name']);?>" size="30" maxlength="30" class="required" />
    </td>
  </tr>
  <tr>
    <td width="16%" nowrap="nowrap"><label>Position:<span class="style1">*</span></label></td>
    <td width="84%" nowrap="nowrap">
    menu_<input name="menu_alias" type="text" value="<?php echo stripslashes(str_replace('menu_','',$menus['menu_alias']));?>" size="20" maxlength="20" class="required" />

    </td>
  </tr>
   <tr>
    <td nowrap="nowrap">Published:</td>
    <td nowrap="nowrap">
	<?php if($menus['published']==1) $checked="checked"; else $checked=""; ?>
	<input type="checkbox" name="published" <?php echo $checked;?> value="1" /></td>
  </tr>
	<?php if($id > 0) {?>
    <input type="hidden" name="id" value="<?php echo $id; ?>"  />
    <input type="hidden" name="action" value="edit"  />
     <?php } else { ?>
     <input type="hidden" name="action" value="add"  />
     <?php }?>
  <tr>
    <td nowrap="nowrap"></td>
    <td nowrap="nowrap"><input type="submit" name="submit" value=" Save " /></td>
    </tr>
</table>
</form>
	</div>
 <br />
<br />
</div>

<div class="content-wrap-bottom"></div>
</div>
<!-- ###################### Content Area Close ###################### -->
<?php include_once '../footer.php';?>   