<div class="forms">
{if $error}
	<div class='error'><img src='/images/crose.png' border='0'> {$error} </div>
{/if}	
<p align="right">
	<a href="?page=messages&pid={$pid}">Back To Messages</a>
</p>   
<form id="message" name="message" action="index.php?page=addmessage&pid={$pid}" method="post" enctype="multipart/form-data">
<input type="hidden" name="pid" value="{$pid}">
<table width="95%" align="left" border="0">

  <td colspan="2" align="left" class="logotext">&nbsp;</td>
  </tr>
  <tr>
	<td colspan="2" align="left" class="logotext">
	<b>Message:</b>	</td>
	</tr>
	<tr>
	<td colspan="2" align="left" class="logotext">
	<b>
	<label>
	<textarea name="message" cols="70" rows="10" class="inputbox required alphanum">{if $msgbody}{$msgbody}{/if}</textarea>
	</label>
	</b></td>
	</tr>
	<tr>
	<td colspan="2" align="left" class="logotext">
	<b>Upload File:</b> max size: 5MB</td>
	</tr>
	<tr>
	<td colspan="2" align="left" class="logotext">
	<b>
	<label>
	<input type="hidden" name="max_size" id = "max_size" value="5242880" />
	<input type="file" name="file" id="file" />
	</label>
	</b>(<strong>File Formats:</strong> jpg, png, gif, txt, doc, ppt, xls, pdf)
	</td>
	</tr>
<tr>
  <td class="logotext" align="left">&nbsp;</td>
  <td class="logotext" align="left">&nbsp;</td>
</tr>

<tr>
<td colspan="2" align="left">
	<input type="submit" name="submit" value="Save and Send"  class="inputbox"> 
	<input type="reset" name="Reset" value="Reset" class="inputbox"></td>
</tr>
</table>
</form>
</div>
{literal}
<script type="text/javascript">
        <!--   
        $(document).ready(function(){
         
            
        $("#message").validate();

        }); 
            
        //-->
</script>
{/literal}