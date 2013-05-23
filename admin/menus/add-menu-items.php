<?php
include_once '../session.php';
include_once '../header.php';
include_once('module_config.php');
$targetpage="$mod_url/menu-listings.php?menu=$menu";
if( $id > 0){
$sql="
SELECT i.*,m.menu_name FROM ".$prefix."menus_items i, ".$prefix."menus m where m.id=i.menuid and i.id=$id";
$menus=$db->get_a_line($sql);
}
else {
	$sql="select menu_name from ".$prefix."". $table_name. " where id=$menu";
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
		header("location:$targetpage&msg=$msg");
		break;
		
	case 'edit':
		$msg=save_records($db,$prefix,$_POST);
		header("location:$targetpage&msg=$msg");
		break;
			
}
function save_records($db,$prefix,$_POST)
{

	foreach($_POST as $key => $items){
		$$key= addslashes($items);
		
	}
	//print_r($_POST);
	if(empty($published)) $published=0;
	if(empty($type)) $type=$content_type;
	
		$content_type=$content_type .'>' .$page_content;
		if($id > 0)
		{
			$sql="update ".$prefix."menus_items set 
			name='$name',
			alias='$alias',
			type='$type',
			content='$content_type',
			url='$url',
			nofollow='$nofollow',
			menuid='$_GET[menu]',
			target='$target',
			published='$published'
			where id=$id";
		  //echo "<br>".$sql;
			//exit();
			$db->insert($sql);
			
			$msg='edit';
		}
		else
		{
			$sql="insert ".$prefix."menus_items set 
			name='$name',
			alias='$alias',
			type='$type',
			content='$content_type',
			url='$url',
			nofollow='$nofollow',
			menuid='$_GET[menu]',
			target='$target',
			published='$published'
			";
		//echo $sql;
		//	exit();
			$db->insert($sql);
			$msg='add';
		}
		return $msg;
	 
	
	
	
}
?>
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<script type="text/javascript">
$(document).ready(function(){
	$('#content_type').change(function() {
	  dataString = $("#content_type").val();
	   $('#page-content').html('');
       $("#loading").css('display','block');
	        $.ajax({
		        type: "POST",
		        url: "showpages.php",
		        data: 'content='+dataString,
		        dataType: "html",
		        cacheBoolean:false,
		        success: function(data) {
		            $('#page-content').append(data);
					$("#loading").css('display','none');		
		        },
		        error: function(){
		        	$('#page-content').append("Unable to find file");
			    }
	        });
	        return false;
	});
});

function changeurl(type,content)
{
	type=document.getElementById('content_type').value
	content=document.getElementById('page_content').value
	if(content == 0)
	{	
		alert('Select page name');
		return false;
	}	
	switch(type)
	{
		case 'content':
			 url="/content.php?page=" + content;
			 type='content';
		break;
		case 'blog':
			url="/blog.php?page=" + content;
			type='blog';
		break;
		case 'squeeze':
			url="/squeeze.php?page=" + content;
			type='squeeze';
		break;
		case 'products':
			 url="/products.php?page=" + content;
			 type='product';
		break;
		case 'custom':
			 url="";
			 type='custom';
		break;
		case 'default':
			 url=content;
			 type='default';
		break;
		default:
			url="";
		 	type='custom';
		break;
	}
	document.getElementById('url').value=url;
	document.getElementById('type').value=type;
	
}
function verify()
{
	if(document.getElementById('name').value=='')
	{
		alert('Please: Enter name of menu item');
		return false;
	}
	else if(document.getElementById('page_content').value==0)
	{
		alert('Please: Select page name');
		return false;
	}
	else	
		return true;
}

function changemenufor()
{
	var value = document.getElementById('menufor').value;
	var url= document.getElementById('url').value ;
	url =	url.replace('/member','');
	if(value==2)
		document.getElementById('url').value = '/member' + url; 
	else
		document.getElementById('url').value = '' + url;
	
}
	
</script>

<p><strong><?php if($id > 0) echo "Edit"; else echo "Add"; ?> <?php echo $module;?> items</strong></p>
<div class="buttons">
<a href="<?php echo $mod_url; ?>/menu-listings.php?menu=<?php echo $menu?>" class="add" style="text-transform:capitalize"><?php echo $module;?></a>
</div>
<div class="formborder">
<form method="post" name="menus" id="menus" action="<?php echo $_SERVER['PHP_SELF']."?menu=$menu";?>" onsubmit="return verify();">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="16%" nowrap="nowrap"><label>Name:</label></td>
    <td width="84%" nowrap="nowrap">
    <input name="name" id="name" type="text" value="<?php echo $menus['name'];?>" size="30" maxlength="30" class="required" />    </td>
  </tr>
  <tr>
    <td width="16%" nowrap="nowrap"><label>Alias:</label></td>
    <td width="84%" nowrap="nowrap"><input name="alias" type="text" value="<?php echo $menus['alias'];?>" size="20" maxlength="20" class="required" />    </td>
  </tr>
   <tr>
     <td nowrap="nowrap">Url Type:</td>
     <td nowrap="nowrap">
     
     <select name="content_type" id="content_type" >
       <option value="custom" 	<?php if($menus['type']!="custom") 	 echo "selected";?>>Custom</option>
       <option value="content" 	<?php if($menus['type']=="content")  echo "selected";?>>Custom Content</option>
       <option value="blog" 	<?php if($menus['type']=="blog") 	 echo "selected";?>>Blogs</option>
       <option value="squeeze" 	<?php if($menus['type']=="squeeze")  echo "selected";?>>Squeeze Pages</option>
       <option value="default" 	<?php if($menus['type']=="default")  echo "selected";?>>Default Pages</option>
       <!--  <option value="products" <?php if($menus['type']=="products") echo "selected";?>>Products</option> -->
     </select>
     
     </td>
   </tr>
  
   <tr id="pages">
     <td nowrap="nowrap">Page: </td>
     <td nowrap="nowrap" id="page-content">
      
     <?php
  //   echo '<pre>';
  //   print_r($menus);
  //   echo '</pre>';
 if($menus['type']!='custom'){
   if ($menus['type']=="content" ||  $menus['type']=="blog"){ 
		$sql="select filename,pagename from ". $prefix ."pages where type='". $menus['type'] . "' and published=1;";
		$data_row=$db->get_rsltset($sql);
	   	 }
	else if($menus['content']=="products"){
		$sql="select product_name as pagename,pshort as filename   from ". $prefix ."products where published=1;";
		$data_row=$db->get_rsltset($sql);
		}
	else if($menus['content']=="squeeze"){
		$sql="select name as pagename, name as filename  from ". $prefix ."squeeze_pages where published=1;";
		$data_row=$db->get_rsltset($sql);
		 }
		
	
	if(count($data_row)>0){ $temp= explode(">",$menus['content']);  $pagename = $temp[1]; 
		?>
	<select name="page_content" id="page_content" onchange="changeurl()">';
		<option value="0">Select</option>
		<?php
		
			foreach($data_row as $row)	{ 
				if($pagename==$row['filename']) 
					$selected="selected";
				else 
					$selected="";					
				
				  ?>
		     <option value=<?php echo $row['filename'] ?> <?php echo $selected;?> ><?php echo $row['pagename'] ?></option>';
		<?php } ?>
	 </select>		 
	<?php  } ?>
<?php  } ?>	 
   </td>
   </tr>
   <tr>
     <td nowrap="nowrap">Menu For:</td>
     <td nowrap="nowrap">
     <select onchange="changemenufor()" name="menufor" id="menufor">
     	<option value="1">Public</option>
     	<option value="2">Register User</option>
     </select>
    
   </tr>
   <tr>
     <td nowrap="nowrap">Link:</td>
     <td nowrap="nowrap">
     <textarea rows="1" cols="40"  name="url" id="url"><?php echo $menus['url'];?></textarea><br><small>/page.php?a=1</small>
    
   </tr>
   <tr>
     <td nowrap="nowrap">Display in:</td>
     <td nowrap="nowrap">
     <input name="menu_name" disabled="disabled" type="text" value="<?php echo $menus['menu_name'];?>" size="20" maxlength="20" class="required" /></td>
   </tr>
   <tr>
     <td nowrap="nowrap">On Click, Open in:</td>
     <td nowrap="nowrap">
     
	 <select name="target" id="target" >
	 <option value="0" <?php if($menus['target']=='0') echo 'selected="selected"';?>>Parent Window with Browser Navigation</option>
	 <option value="1" <?php if($menus['target']=='1') echo 'selected="selected"';?>>New Window with Browser Navigation</option>
	 </select>	 </td>
   </tr>
   <tr>
     <td nowrap="nowrap">Nofollow:</td>
     <td nowrap="nowrap">
     	 <input name="nofollow" type="radio" value="0" <?php if($menus['nofollow']==0) echo 'checked';?>>  No
         <input name="nofollow" type="radio" value="1" <?php if($menus['nofollow']==1) echo 'checked';?>> Yes
     </td>
   </tr>
   <tr>
    <td nowrap="nowrap">Published:</td>
    <td nowrap="nowrap">
	<?php if($menus['published']==1) $checked="checked"; else $checked=""; ?>
	<input type="checkbox" name="published" <?php echo $checked;?> value="1" /></td>
  </tr>
  	<input type="hidden" name="type" id="type">
     <input type="hidden" name="action" value="edit"  />
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