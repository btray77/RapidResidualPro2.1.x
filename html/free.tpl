{literal}
<style type="text/css">
<!--
.style1 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
{/literal}
{$warning}
<form name="login" id="signup_form" action="free.php" method="post" enctype="multipart/form-data" >
<input type="hidden" name="pshort" value='{$pshort}'>
<input type="hidden" name="rand" value='{$rand}'>
<input type="hidden" name="c" value='{$c}'>
<input type="hidden" name="ref" value='{$ref}'>
{$message}
<br>
{$hidelink1}
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="5">
<tr>
	<td width="31%" align="right" nowrap="NOWRAP" class="logotext">First Name:&nbsp;<span  style="color:red">*</span></td>
	<td width="69%" align="left" class="logotext"><input type="text" name="firstname" size="30" class="inputbox required alphanum" value="{$firstname}"></td>
</tr>
<tr>
	<td align="right" nowrap="NOWRAP" class="logotext">Last Name:&nbsp;<span  style="color:red">*</span></td>
	<td class="logotext" align="left"><input type="text" name="lastname" size="30" class="inputbox required alphanum" value="{$lastname}"></td>
</tr>
<tr>
	<td align="right" nowrap="NOWRAP" class="logotext">Email:&nbsp;<span  style="color:red">*</span></td>
	<td class="logotext" align="left"><input type="text" name="email" size="30" class="inputbox required email" value="{$email}"></td>
</tr>
<tr>
	<td align="right" nowrap="NOWRAP" class="logotext">User Name:&nbsp;<span  style="color:red">*</span></td>
	<td class="logotext" align="left"><input type="text" name="username" size="30" class="inputbox required" value="{$username}"></td>
</tr>
<tr>
	<td align="right" nowrap="NOWRAP" class="logotext">Password:&nbsp;<span  style="color:red">*</span></td>
	<td class="logotext" align="left"><input type="password" name="password" size="30" class="inputbox required " ></td>
</tr>
<tr>
        <td nowrap="nowrap">&nbsp;</td>
	<td class="tbtext" align="left" >
            <img src="captcha/CaptchaSecurityImages.php?width=160&height=55&characters=5" />
      </td>
</tr>
<tr>
  <td align="right" nowrap="nowrap" class="tbtext">&nbsp;Security Code: <span class="style1">*</span> </td>
  <td colspan="2" align="left" class="tbtext">
<input type="text" name="captchastring" id="captchastring" size="30" class="inputbox required alphanum" tabindex="5"/></td>
</tr>
<tr>
	<td nowrap="nowrap">&nbsp;</td>
	<td  align="left" class="cmdtext"><input type="submit" name="Submit" value="Signup" class="inputbox"></td>
</tr>
</table>
{$hidelink2}
</form>
{literal}
<script language="JavaScript" src="http://j.maxmind.com/app/geoip.js"></script>
<script type="text/javascript">
        <!--   
        $(document).ready(function(){
      
        
        $.validator.addMethod( "alphanum", function(value, element) {
            return this.optional(element) || /^[a-z0-9\-\s\@\#\$\%\^\*\(\)\-\+\=\_~\`\<\>\[\]\{\}\,\.\:\;]+$/i.test(value);
                }, "Error:This symbols are not allowed"
		);
        $.validator.addMethod( "username", function(value, element) {
            return this.optional(element) || /^[a-z0-9\-\_]+$/i.test(value);
                }, "Error:Only Alphanumeric and - and _ is allowed"
		);    
        $("#signup_form").validate();
        
    
        }); 
            
        //-->
</script>
{/literal}