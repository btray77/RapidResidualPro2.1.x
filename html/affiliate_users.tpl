<div style="text-align:right"><a href="affiliate_home.php">[ Back ]</a></div>
<div>&nbsp;</div>
<div id="grid">
<table class="notsortable" width="95%" border="0" align="center"
	cellpadding="2" cellspacing="0">
	<thead>
		  <tr>
		  	<th width="4">Id</th>
		  	<th>Name</th>
		  	<th>Email</th>
		  	<th align="left">User name</th>
		    <th align="left">Last Login</th>
		  </tr>
	</thead>
	{if count($data) gt 0} 
	{foreach item=result from=$data} {if $i%2== 0} {$class= "standardRow"} {else} {$class = "alternateRow"} {/if}
	<tr class="{$class}">
	  	<td valign="middle" style="text-align:center;">{$result.id}</td>
	  	<td valign="middle" >{$result.name}</td>
	  	<td valign="middle" >{$result.email}</td>
	    <td valign="middle" style="text-align:left;">{$result.username}</td>
        <td valign="middle" style="text-align:left;">{$result.DAYS}</td>
	  </tr>
 	 {/foreach} {else}
	<tr>
		<td colspan='9' style='text-align: center'>Sorry no record found</td>
	</tr>
	{/if}

</table>
</div>
<div class="pagers">{$pager}</div>
<div><a href="#top" style="text-align: center;">Move to top</a></div>