{$error}

<div style="clear:both">

<form name="form1" id="form1" method="post" action="{$action}">

  	<label for="textfield">Name:<span style="color:red" > *</span> <br>

  	</label>

     <input type="text" name="name" tabindex="1" size="30" id="Name" value="{$name}" class="required" >

	<br>

    <label for="label">Email: <span style="color:red">*</span><br>

  </label>

    <input type="text" name="email" id="email" size="30" value="{$email}" class="required email" tabindex="2">

    <br>

    <label for="label2">Subject: <span style="color:red">*</span><br>

  </label>

    <input type="text" name="subject" id="subject" size="30" value="{$subject}" class="required" tabindex="3">

    <br>

    <label for="textarea">Message: <span style="color:red">*</span><br>

  </label>

    <textarea name="message" cols="50" rows="5" id="message" class="required" tabindex="4">{$message}</textarea>

    <br>

    <br>

    <img src="/captcha/CaptchaSecurityImages.php?width=160&height=55&characters=5" />

    <br>

    <label for="label2">Security Code:<span style="color:red">*</span><br>

  </label>

   <input type="text" name="captchastring" size="30" class="required" tabindex="5"/>

    <br>

    <input type="hidden" name="url" value="{$action}">

    <input type="submit" name="submit_contact" value="Submit" tabindex="6">

    <input type="reset" name="reset" value="reset" tabindex="7">

</form>

</div>

{literal}

<script type="text/javascript"> 

	<!--   

	

	$(document).ready(function(){$("#form1").validate();}); //-->

</script>

{/literal}



