<?php
include_once("session.php");
include_once("header.php");
include_once("../common/constant-contact.class.php");
$ccListOBJ = new CC_List(); 
$allLists = $ccListOBJ->getLists();

if (isset($_POST['submit']))
{
	// Parse form data
	$posturl		= $db->quote($_POST["posturl"]);
	$rspname2		= $db->quote($_POST["rspname2"]);
	// Update database
	$set	= " posturl={$posturl}";
	$set	.= ", aweber_meta=''";
	$set	.= ", rspname2={$rspname2}";
	$set	.= ", rspname='Constant-Contact'";
        
        if($id > 0)
        {
            $pid = $db->insert_data_id("update ".$prefix."responders set $set where id = $id") ;
            $msg = "edit";
        }
        else
        {
	$pid = $db->insert_data_id("insert into ".$prefix."responders set $set") ;
        $msg = "add";
        }
	header("Location: constant-contact.php?msg=$msg");
}
if($id > 0){
$sql="select * from ".$prefix."responders where id=$id";
$row=$db->get_a_line($sql);
extract($row);
}
?>

<!-- ###################### Error Message Start ###################### -->
<?php echo $msg ?>

<!-- ###################### Error Message End ###################### -->

<!-- ###################### Content Area Start ###################### -->

<div class="content-wrap">
<div class="content-wrap-top"></div>
<div class="content-wrap-inner"><p><strong>Constant Contact Autoresponder</strong></p>

<div class="buttons">
    <a href="constant-contact.php">Go back</a>
    </div>

<div class="formborder">
<form action="" method="post">
    <input type="hidden" name="id" value="<?php echo $id ?>">
<table width="95%" align="center" border="0">
	<tr>
	  <td colspan="2" class="tbtext"><strong>Constant Contact Settings</strong></td>
    </tr>
	<tr>
	  <td colspan="2" class="tbtext">&nbsp;</td>
    </tr>
	<tr>
	  <td width="31%"  class="tbtext"><strong>Responder Name :</strong></td>
      <td><input name="rspname2" class="inputbox" type="text" id="rspname2" value="<?php echo $rspname2;?>">
      <div class="tool">	
		<a href="" class="tooltip" title="Responder Name: What you want to call this autoresponder to remember it here. ">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div>
      </td>
	</tr>
	<tr>
	  <td width="31%"  class="tbtext"><strong>Constant Contact ListId:</strong></td>
      <td>
          <select name="posturl" id="posturl">
       <?php
	foreach ($allLists as $k=>$item) {
	      $selected='';
		if ($item['id'] == $posturl) {
			$selected="selected";
		}
                ?>
              <option value="<?php echo $item['id']?>" <?php echo $selected; ?> ><?php echo $item['title']?></option>
	
        <?php } ?>
         
          </select>
      <div class="tool">	
		<a href="" class="tooltip" title="Constant Contact List Name:The ListID of this autoresponder in Constant Contact ">
			<img src="../images/toolTip.png" alt="help"/>
		</a>
		</div>
      </td>
	</tr>
	
	<tr>
	  <td class="tbtext" colspan="2">&nbsp;</td>
    </tr>

	<tr>
		<td colspan="2">
	  	<input type="submit" name="submit" value="Add Autoresponder" class="inputbox">		</td>
	</tr>
	<tr>
	  <td colspan="2">&nbsp;</td>
    </tr>
	
	
	<tr>
		<td class="tbtext" colspan="2" align="left">* These fields can be found inside the code for your signup form supplied by your autoresponder service. </td>
	</tr>
	

	
</table>
</form>
</div>
</div>
<div class="content-wrap-bottom"></div>
</div>


<?php include_once("footer.php");?>
