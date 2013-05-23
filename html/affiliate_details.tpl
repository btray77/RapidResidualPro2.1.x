<!-- Main stats here -->

<div style="text-align:right"><a href="affiliate_home.php">[ Back ]</a></div>
<h4>Summary Report of {$mid} for {$month} {$year}</h4>

<div style="width: 50%;" class="summary">

<div class="form">
<form method="get" action="" name="">Stats For 
<select name="month">
	<option value="01" {if $month eq '01'} selected="selected" {/if}>Jan</option>
	<option value="02" {if $month eq '02'} selected="selected" {/if}>Feb</option>
	<option value="03" {if $month eq '03'} selected="selected" {/if}>March</option>
	<option value="04" {if $month eq '04'} selected="selected" {/if}>April</option>
	<option value="05" {if $month eq '05'} selected="selected" {/if}>May</option>
	<option value="06" {if $month eq '06'} selected="selected" {/if}>June</option>
	<option value="07" {if $month eq '07'} selected="selected" {/if}>July</option>
	<option value="08" {if $month eq '08'} selected="selected" {/if}>Aug</option>
	<option value="09" {if $month eq '09'} selected="selected" {/if}>Sep</option>
	<option value="10" {if $month eq '10'} selected="selected" {/if}>Oct</option>
	<option value="11" {if $month eq '11'} selected="selected" {/if}>Nov</option>
	<option value="12" {if $month eq '12'} selected="selected" {/if}>Dec</option>
</select> 
<select name="year">
{foreach item=ear from=$yyear}
	<option value="{$ear}" {if $ear eq $year} selected="selected" {/if}> {$ear} </option>
{/foreach}
</select> <input type="hidden"  name="mid" value="{$mid}">
		  <input type="submit" name="search" value="search">
</form>


</div>
<table border="0" cellspacing="0" cellpadding="5">
	<tr>
		<th>&nbsp;</th>
		<th>{$month}</th>
		<th>Accumulated</th>
	</tr>
	<tr>
	  <th nowrap>Total Views </th>
	  <td>{$row_total_views_today}</td>
	  <td nowrap>{$row_total_views_all}</td>
    </tr>
	<tr>
	  <th nowrap>Total Unique Views </th>
	  <td>{$row_total_clicks_today}</td>
	  <td nowrap>{$row_total_clicks_all}</td>
    </tr>
	<tr>
		<th nowrap>Total Referrals</th>
		<td>
			{$totalAffiliatemon}		</td>
		<td nowrap>
			{$allAffiliate}		</td>
	</tr>
	<tr>
		<th nowrap>Total Products Sold</th>
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

<div id="grid" style="width: 90%;">
<table class="notsortable" border="0" align="center" cellpadding="2"
	cellspacing="0">

	<tr>

		<th style="text-align: center">Date</th>
		<th style="text-align: center">Sales</th>
		<th style="text-align: center">Earned</th>
                <th style="text-align: center">Views</th>
                <th style="text-align: center">Clicks</th>    
                <th style="text-align: center">Conversion</th>
                <th style="text-align: center">EPC</th>
	</tr>

	{if count($dayy) > 0}
		{foreach item=day from=$dayy}
	<tr class="">
		<td valign="middle" style="text-align: center;">{$day.date}</td>
		<td valign="middle" style="text-align: center">{$day.sales}</td>
		<td valign="middle" style="text-align: right">{$day.earned}</td>
                <td valign="middle" style="text-align: right">{$day.impression}</td>
                <td valign="middle" style="text-align: right">{$day.views}</td>
                <td valign="middle" style="text-align: right">{$day.conversion}</td>
                <td valign="middle" style="text-align: right">{$day.epc}</td>   
	</tr>
		{/foreach}
	{else}
	<tr><td colspan='8' style='text-align:center'>Sorry no record forund</td></tr>
	
	{/if}
</table>

<div><a href="#top" style="text-align: center;">Move to top</a></div>

</div>
