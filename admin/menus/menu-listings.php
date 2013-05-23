<?php 
include_once '../session.php';
include_once '../header.php';
include_once('module_config.php');
$targetpage=$mod_url;
	
 $sql="SELECT count(m.id)as total FROM ".$prefix."menus_items m where m.menuid=$menu;";
 $total=$db->get_a_line($sql);

$sql="SELECT * ,date_format(m.created_date,'%b, %d %Y') as created_date 
	  FROM ".$prefix."menus_items m where menuid=$menu ORDER BY `order` ASC ";
$rs_menu_items=$db->get_rsltset($sql);

$targetpage="$mod_url/menu-listings.php?menu=$menu";

switch($action)
{
	case 'del':
		if($obj_pri->canDelete($pageurl))
		{
		$msg=delete_content($id,$db,$prefix);
		header("location:$targetpage&msg=$msg");
		}
		else
		{
		 $msg=archive_content($id,0,$db,$prefix);
		 header("location:$targetpage&msg=$msg");
		}		
	break;
	case 'a':
		$msg=archive_content($id,$state,$db,$prefix);
		header("location:$targetpage&msg=$msg");
	break;
	case 'p':
		$msg=publish_menu_items($id,1,$db,$prefix);
		header("location:$targetpage&msg=$msg");
	break;
	case 'up':
		$msg=publish_menu_items($id,0,$db,$prefix);
		header("location:$targetpage&msg=$msg");
	break;
	case 'save':
	    $msg=save_order_menu_items($id,$menu,$order,$db,$prefix);
		header("location:$targetpage&msg=$msg");
	break;	
}
############# Messages ##################
switch($msg)
{
	case 'add':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Menu Item is successfully Added</div>';
	break;
	case 'edit':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Menu Item is successfully Edited</div>';
	break;	
	case 'd':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Menu Item is successfully Deleted</div>';
	break;
	case 'ar':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Menu Item is successfully Unarchived</div>';
	break;
	case 'un':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Menu Item is successfully Archived</div>';
	break;
	case 'p':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Menu Item is successfully Published</div>';
	break;
	case 'up':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Menu Item is successfully Unpublished</div>';
	break;
	case 'o':
		$Message = '<div class="success"><img src="/images/tick.png" align="absmiddle"> Menu Items are successfully Ordered</div>';
	break;
}
############# Operation ##################

function delete_content($id,$db,$prefix)
{
	$db->insert("delete from ".$prefix."menus_items where id ='$id'");
	return $msg='d'; 
}

function publish_menu_items($id,$state,$db,$prefix)
{

	$db->insert("update ".$prefix."menus_items set published='$state' where id ='$id'");
	if($state==1)
		return $msg='p';
	else 
		return $msg='up';
}
function save_order_menu_items($ids,$menu,$orders,$db,$prefix){
	$db->insert("update ".$prefix."menus_items set `order`='0' where menuid=$menu ");
	$i=0;
		foreach($orders as $order)
		{
			$sql="update ".$prefix."menus_items set `order`='$order' where id='$ids[$i] and menuid=$menu';";
			$db->insert($sql);
			$i++;
				
		}
		return $msg='o';
				
}
#####################################################################
?>
<script>
function submit()
{
	document.form.submit();
}
</script>
<?php echo $Message;?>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong>Menu Items</strong></p>
<div class="buttons">
	<a href="add-menu-items.php?menu=<?php echo $menu;?>" class="add">Add <?php echo 'menu items';?></a>
</div>
<div class="buttons">
	<a href="<?php echo $mod_url; ?>/" class="add">Back to <?php echo 'menus';?></a>
</div>

<form action="<?php echo $_SERVER[PHP_SELF]."?menu=$menu"?>" method="post" name="form" id="form">
<div id="grid">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th width="5%" align="center">ID</th>
    <th nowrap="nowrap" style="text-align:left">Name</th>
    <th nowrap="nowrap" style="text-align:left">Type</th>
    <th nowrap="nowrap" style="text-align:center">Orders
		<img src="<?php echo $http_path?>/images/admin/save.png" alt="save" align="absmiddle" onclick="submit()" />
		<input type="hidden" name="action" value="save">
	</th>
    <th  nowrap="nowrap">Created Date</th>
    <th width="3%" align="center" nowrap="nowrap">Status</th>
    <th width="6%" align="left" nowrap="nowrap">
	  <?php if($obj_pri->getRole()=='1'){?>
	Edit/Delete
	<?php } else { ?>
	Edit
	<?php } ?>
	  </th>
  </tr>
  <?php 

if($total['total'] < 1)
{
	echo "<tr><td colspan='8'>No record found</td></tr>";
}else{
  $i=1;
  $x=1;
  
  foreach($rs_menu_items as $menu_items) {?>
  <tr>
    <td align="center" valign="top"><?php echo $menu_items['id'];?></td>
    
    <td align="left" valign="top" nowrap="nowrap" style="text-align:left">
    <a href="<?php echo "$http_path".$menu_items['url'];?>" target="_blank">
    	<?php echo stripslashes($menu_items['name']);?>
    </a>
    
    </td>
    <td valign="top" style="text-align:left" ><?php echo $menu_items['type'];?></td>
   
    <td align="center" valign="middle" nowrap="nowrap">
    <input type="text" value="<?php echo $menu_items['order'];?>" name="order[]" size="2" maxlength="2" style="text-align:center"/>
    <input type="hidden" value="<?php echo $menu_items['id'];?>" name="id[]" />
    </td>
    <td align="left" valign="top" nowrap="nowrap"><?php echo $menu_items['created_date'];?></td>
	<td align="center" valign="top" nowrap="nowrap">
	<?php if($menu_items['published']==1){ ?>
      <a href="<?php echo $mod_url; ?>/menu-listings.php?menu=<?php echo $menu;?>&id=<?php echo $menu_items['id'];?>&action=up" >
      <img src='/images/admin/published.png' border="0" alt="edit"></a>
      <?php } else { ?> 
      <a href="<?php echo $mod_url; ?>/menu-listings.php?menu=<?php echo $menu;?>&id=<?php echo $menu_items['id'];?>&action=p">
      <img src='/images/admin/unpublished.png' border="0" alt="delete">
      </a>
      <?php } ?>	 
	</td>
	<td width="100" align="center">
	      <div class="edit">
	<a href="<?php echo $mod_url; ?>/add-menu-items.php?menu=<?php echo $menu;?>&id=<?php echo $menu_items['id'];?>">
		<img src="/images/editIcon.png" alt="editImage" title="Click to edit this content page" >
	</a> </div>
	<?php if($obj_pri->getRole()=='1'){?>
	<div class="delete" style="float:right;padding-left:10px;">
	<a href="<?php echo $mod_url; ?>/menu-listings.php?menu=<?php echo $menu;?>&id=<?php echo $menu_items['id'];?>&action=del" >
		<img src="/images/crose.png" alt="crose.png" title="Click to edit this content page" Onclick="return confirm('Are you sure! you want to delete this menu item?')">
	</a>
	</div>
	<?php }?>
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
<?php include_once '../footer.php';?>