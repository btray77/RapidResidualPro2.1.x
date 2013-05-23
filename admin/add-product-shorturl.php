<?php
include_once("session.php");
include_once("header.php");

if (isset($_POST['submit']))
{
	// Parse form data
	$url		=  $db->quote($_POST["short_url"]);
	$redirect_url	=  $db->quote($_POST["redirect_url"]);
        $pid		=  $_POST["pid"];
	$id             =  $_POST["id"];

	// Update database
	$set	= " short_url={$url}";
	$set	.= ",redirect_url={$redirect_url}";
        $set	.= ",product_id={$pid}";
        
        
        if($id > 0)
        {
        $SQL = "UPDATE ".$prefix."products_short set $set WHERE id = '$id'";
        $db->insert_data_id($SQL) ;
        $msg = "edit";    
        }
        else {
        $SQL = "INSERT INTO ".$prefix."products_short set $set";
        $db->insert_data_id($SQL) ;
        $msg = "add";
        }
	
	header("Location: product-shorturl.php?pid=$pid&msg=$msg");
        exit();
}
$SQL = "SELECT * FROM ".$prefix."products_short WHERE id = '$id'";
$row=$db->get_a_line($SQL);
@extract($row);
?>


<!-- ###################### Error Message Start ###################### -->
<?php $msg;?>

<!-- ###################### Error Message End ###################### -->


<!-- ###################### Content Area Start ###################### -->
<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner">
<p><strong>Short Url  Management</strong></p>
<div class="buttons"><a	href="product-shorturl.php?pid=<?php echo $pid ?>" >Go Back </a></div>
<div class="formborder"><br />
<br />

<form action="add-product-shorturl.php" method="post">
    <input type="hidden" name="pid" value="<?php echo $pid?>">
    <input type="hidden" name="id" value="<?php echo $id?>">
<table width = "95%" align="center" border="0">
	<tr>
	  <td colspan="2" class="tbtext"><strong>Short Url Settings</strong></td>
    </tr>
	<tr>
	  <td colspan="2" class="titles">&nbsp;</td>
    </tr>
	<tr>
	  <td width="31%"  class="tbtext"><strong>Redirect URL  :</strong></td>
      <td><input name="redirect_url" type="text" class="inputbox" id="redirect_url" value="<?php echo $redirect_url?>" size="75"></td>
	</tr>
	<tr>
	  <td width="31%"  class="tbtext"><strong>Name:</strong></td>
      <td><input name="short_url" class="inputbox" type="text" id="short_url" value="<?php echo $short_url?>"></td>
	</tr>	
	<tr>
	  <td class="tbtext" colspan="2">&nbsp;</td>
    </tr>

	<tr>
		<td colspan="2">
	  	<input type="submit" name="submit" value="Save Short Url" class="inputbox">		</td>
	</tr>
	
</table>
</form>
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>
<?php include_once("footer.php");
?>