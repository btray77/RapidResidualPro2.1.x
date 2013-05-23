
	{if count($products) gt 0}
		{foreach item=product from=$products}
			<div class="products">
				<h3>{$product.name}</h3>
				<div class="product-discription">{$product.image}{$product.discription}</div>
				
				<div class="product-price">{$product.price}</div>
				<div class="product-link">{$product.commission}</div>
				<div class="product-link">{$product.affiliate_link}</div>
				<div class="product-link">{$product.affiliate_link_alertpay}</div>
				<div class="product-link">{$product.clickbank_link}</div>
				<div class="product-link">{$product.affiliate_tools}</div>
				<div class="product-link">{$product.affiliate_tools_alertpay}</div>
				<div class="product-link">{$product.saleslink}</div>
				
			</div>
		{/foreach}
		<div class="pagers">{$pager}</div>
	{else}
	<div class="products">No product found in market place</div>
	{/if}