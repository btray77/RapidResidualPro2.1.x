{if count($products) gt 0}
		{foreach item=product from=$products}
			<div class="products">
				<a id="{$product.pshort}"></a>
				<h2>{$product.name}</h2>
				<div class="product-date">Date Purchased: {$product.date}</div>
				<div class="product-description">{$product.description}</div>
				<div class="product-image">Transaction ID: {$product.txn_id}</div>
                                
				<div class="product-link">{$product.getprod}</div>
			</div>
		{/foreach}
		<div class="pagers">{$pager}</div>
	{else}
	<div class="products">No product found</div>
	{/if}

