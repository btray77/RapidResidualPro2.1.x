{if $cntPay gt 0}

{else}

<div class="login-top-message">

	<img align="absmiddle" src="../images/crose.png">&nbsp;You need to complete your profile.

</div>

{/if}



{if $cntPay gt 0}

<!-- Main stats here -->

<div style="text-align:right">

	<a href="affiliate_details.php">View History</a> |

	<a href="affiliate_promotion.php">Sold Products</a>

</div>



<div style="width: 52%;" class="summary">

<table border="0" cellspacing="0" cellpadding="5">

	<tr>

		<th>&nbsp;</th>

		<th>{$month}</th>

		<th>Accumulated</th>

	</tr>

	<tr>

	  <th>Total Views </th>

	  <td>{$row_total_views_today}</td>

	  <td>{$row_total_views_all}</td>

    </tr>

	<tr>

	  <th>Total Unique Views </th>

	  <td>{$row_total_clicks_today}</td>

	  <td>{$row_total_clicks_all}</td>

    </tr>

	<tr>

		<th>Total Referrals</th>

		<td>

			{$totalAffiliatemon}		</td>

		<td>

			{$allAffiliate}		</td>

	</tr>

	<tr>

		<th>Total Products Sold</th>

		<td>

			{$thismonthprod}		</td>

		<td>

			{$thismonthaccprod}		</td>

	</tr>

	<tr>

		<th>Total Earnings</th>

		<td>{$com_mon}</td>

		<td>{$com_all}</td>

	</tr>

</table>

</div>

<!-- Main stats here -->



<div id="grid">

<h1>Referred</h1>

<div>&nbsp;</div>

<table class="notsortable" width="95%" border="0" align="center"

	cellpadding="2" cellspacing="0">

	<thead>

		  <tr>

		  	<th width="4">Id</th>

		  	<th>Name</th>

		    	<th align="left">User name</th>

                        <th align="left">Last Login</th>

		  </tr>

	</thead>

	{if count($data) gt 0} 

	{foreach item=result from=$data} {if $i%2== 0} {$class= "standardRow"} {else} {$class = "alternateRow"} {/if}

	<tr class="{$class}">

	  	<td valign="middle" style="text-align:center;">{$result.id}</td>

	  	<td valign="middle" >{$result.name}</td>

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

{/if}

