<h1>{$pagename}</h1>

{$message}

<form name="domain_form" " id="domain_form" method="post"
	action="license.php" enctype="multipart/form-data"
	onsubmit="return formCheck(domain_form);">
<table width="90%" border="0" align="center" cellpadding="2"
	cellspacing="5">
	<tr>
		<td width="22%" align="left" class="tbtext">First Name:&nbsp;*</td>
		<td width="78%" align="left" class="tbtext"><input type="text"
			name="firstname" size="40" class=" required"></td>
	</tr>
	<tr>
		<td class="tbtext" align="left">Last Name:&nbsp;*</td>
		<td class="tbtext" align="left"><input type="text" name="lastname"
			size="40" class=" required"></td>
	</tr>
	<tr>
		<td class="tbtext" align="left">Email:&nbsp;*</td>
		<td class="tbtext" align="left"><input type="text" name="email"
			size="40" class=" required email"></td>
	</tr>
	<tr>
		<td class="tbtext" align="left">Phone:&nbsp;*</td>
		<td class="tbtext" align="left"><input type="text" name="phone"
			size="40" class=" required"></td>
	</tr>
	<tr>
		<td class="tbtext" align="left">No. of Copies:&nbsp;*</td>
		<td class="tbtext" align="left"><input type="text" name="ndomains"
			id="ndomains" maxlength='2' size="10" class="inputbox">&nbsp;<input
			type="button" onclick="addInput()" name="add" value="Add domains" /></td>
	</tr>
	<tr>
		<td class="tbtext" colspan="2" align="left">
		<div id="domains"></div>

		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" class="tbtext">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="buy_licence"
			value="Buy License" class="inputbox"></td>
	</tr>
</table>

</form>

{literal}
<script type="text/javascript"> 
	<!--   
		$(document).ready(function(){$("#domain_form").validate();});

		function addInput(ndomains) {
		    var ndomains= document.getElementById('ndomains').value;
		    if (!isNaN(parseInt(ndomains)) )  {
	
		        if(parseInt(ndomains) <= 9){
		        	document.getElementById('domains').innerHTML='';
		            for(var i=1;i<=ndomains;i++){
			            var id="d"+i;
		                document.getElementById('domains').innerHTML += "<span style='padding-right:90px'>Domain "+i+":</span> <input id='"+i+"'  name='domains[]' type='text' size='40' class='required url'  value='' onblur=\"urlValidate(this.value,'d"+i+"')\"  /><span id='d"+i+"'></span><span style='color:#aaa;font-size:10px;'> http://www.example.com<span><br>";
		                
		            }
		        } else {
		            alert("Sorry ! You cannot add more then 9 domians at the same time");
		        }
		   } else {
		        alert("Value should be an integer and not blank !");
		   }
		}

		function urlValidate(url,id){
		 if(url){
		  xmlhttp=new XMLHttpRequest();
		  xmlhttp.onreadystatechange=function(){
		      if (xmlhttp.readyState==4 && xmlhttp.status==200){
		        document.getElementById(id).innerHTML=xmlhttp.responseText;
		      }
		  }
		  xmlhttp.open("GET","check_url.php?q="+url,true);
		  xmlhttp.send();
		}
		}


	 //-->
</script>
{/literal}
