<?php 
include_once '../session.php';
include_once '../header.php';
include_once('module_config.php');

 $targetpage=$mod_url;
	$sql="SELECT count(m.id)as total FROM ".$prefix."". $table_name. " m;";
	$total=$db->get_a_line($sql);

$sql="SELECT m.id, m.menu_name,m.menu_alias,m.published,date_format(m.created_date,'%b, %d %Y') as created_date FROM ".$prefix."". $table_name. " m ORDER BY `id` DESC ";
$rs_menu=$db->get_rsltset($sql);

############# Actions ##################

switch($action)
{
	case 'd':
		if($obj_pri->canDelete($pageurl))
		{
		$msg=delete_content($id,$db,$prefix);
		header("location:$targetpage?msg=$msg");
		}
		else
		{
		 $msg=archive_content($id,0,$db,$prefix);
		 header("location:$targetpage?msg=$msg");
		}		
	break;
	case 'a':
		$msg=archive_content($id,$state,$db,$prefix);
		header("location:$targetpage?msg=$msg");
	break;
	case 'p':
		$msg=publish_menu($id,1,$db,$prefix);
		header("location:$targetpage?msg=$msg");
	break;
	case 'up':
		$msg=publish_menu($id,0,$db,$prefix);
		header("location:$targetpage?msg=$msg");
	break;	
}

############# Messages ##################

switch($msg)
{
	case 'add':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Menu is successfully Added</div>';
	break;
	case 'edit':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Menu is successfully Edited</div>';
	break;	
	case 'd':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Menu is successfully Deleted</div>';
	break;
	case 'ar':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Menu is successfully Unarchived</div>';
	break;
	case 'un':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Menu is successfully Archived</div>';
	break;
	case 'p':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Menu is successfully Published</div>';
	break;
	case 'up':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Menu is successfully Unpublished</div>';
	break;
}
############# Operation ##################

function delete_content($id,$db,$prefix)
{
	$db->insert("delete from ".$prefix."menus where id ='$id'");
	$db->insert("delete from ".$prefix."menus_items where menuid ='$id'");
	return $msg='d'; 
}

function publish_menu($id,$state,$db,$prefix)
{

	$db->insert("update ".$prefix."menus set published='$state' where id ='$id'");
	if($state==1)
		return $msg='p';
	else 
		return $msg='up';
}

?>

<?php echo $Message ?>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong><?php echo $name;?></strong></p>


<?php if(!empty($_SESSION['success'])) { ?>
<div class="success">Record is successfully saved.</div>
<?php unset($_SESSION['success']); } ?>
<div class="buttons">
	<a href="<?php echo $mod_url; ?>/add-menus.php" class="add">Add <?php echo 'Menu';?></a>
</div>
<form action="" method="post" name="form" id="form">
<div id="grid">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th width="5%" align="center">Id</th>
    
    <th  align="center" nowrap="nowrap">Menu Name</th>
    <th nowrap="nowrap">Position</th>
    <th nowrap="nowrap" style="text-align:left">Token</th>
    <th nowrap="nowrap">Total Menus Items</th>
    <th  nowrap="nowrap">Created Date</th>
    <th  align="center" nowrap="nowrap">Status</th>
    <th  align="left" nowrap="nowrap">
	<?php if($obj_pri->getRole()=='1'){?>
	Edit/Delete
	<?php } else { ?>
	Edit
	<?php } ?>
	</th>
    </tr>
  
  <?php 
  
if($total['total']<1)
{
	echo "<tr><td colspan='8'>No record found</td></tr>";
}else{
  $i=1;
  $x=1;
 
  foreach($rs_menu as $menu) {
  	
  	?>
  <tr>
    <td align="center" valign="top"><?php echo $i++;?></td>
    <!--<td align="left" valign="top" nowrap="nowrap">
	 <a href="<?php echo $mod_url; ?>/listings&id=<?php echo $menu['id'];?>&action=del">
	<img title="delete" alt="delete" style='border:none;' src="images/delete.gif" /></a>
	<a href="<?php echo $mod_url; ?>/add-menus&id=<?php echo $menu['id'];?>&action=edit">
	<img title="edit" alt="edit" style='border:none;' src="images/page_white_edit.png" /></a> 
	</td>-->
    <td align="left" valign="top" nowrap="nowrap"><?php echo stripslashes($menu['menu_name']);?></td>
    <td align="left" valign="top" nowrap="nowrap"><?php echo stripslashes($menu['menu_alias']);?></td>
    <td align="left" valign="top" nowrap="nowrap">{$<?php echo stripslashes($menu['menu_alias']);?>}</td>
     <td align="left" valign="top" nowrap="nowrap">
     	<a href="<?php echo $mod_url; ?>/menu-listings.php?menu=<?php echo $menu['id'];?>">
     		<?php echo total_menus($menu['id'],$prefix,$db)?>
     	</a>	
     </td>
    <td align="left" valign="top" nowrap="nowrap"><?php echo $menu['created_date'];?></td>
	<td align="center" valign="top" nowrap="nowrap">
	 <?php if($menu['published']==1){ ?>
      <a href="<?php echo $mod_url; ?>/?id=<?php echo $menu['id'];?>&action=up" >
      <img src='/images/admin/published.png' border="0"></a>
      <?php } else { ?> 
      <a href="<?php echo $mod_url; ?>/?id=<?php echo $menu['id'];?>&action=p">
      <img src='/images/admin/unpublished.png' border="0">
      </a>
      <?php } ?>	 </td>
      <td width="100" align="center">
	 <div class="edit">
	<a href="add-menus.php?id=<?php echo $menu['id'] ?>">
	<img src="/images/editIcon.png" alt="editImage" title="Click to edit menu" >
	</a> </div> 
	<?php if($obj_pri->getRole()=='1'){?>
	<div class="delete" style="float:right;padding-left:10px;">
	
	<a href="<?php echo $mod_url; ?>/?id=<?php echo $menu['id'];?>&action=d">
	<img src="/images/crose.png" alt="crose.png" title="Click to delete this menu" Onclick="return confirm('Are you sure! you want to delete this menu?')" >
	</a>
	</div>
	<?php } ?>
      </td>
	</tr>
  <?php
 $x++; }
}
  ?>
</table>
</div>

</form>
<br />
<br />
</div>

<div class="content-wrap-bottom"></div>
</div>
<!-- ###################### Content Area Close ###################### -->
<?php
function total_menus($id,$prefix,$db)
{
	$sql="SELECT count(m.id)as total FROM ".$prefix."menus_items m where menuid=$id;";
	$total=$db->get_a_line($sql);
	return $total['total'];
}

include_once '../footer.php';?>