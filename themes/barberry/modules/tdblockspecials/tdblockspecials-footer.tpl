



{$themesdev.td_about_us|html_entity_decode}                    
                    
{if $products}
<section class="span3">
    <div id="woocommerce_top_rated_products-2" class="widget woocommerce widget_top_rated_products clearfix">
        <h1 class="widget-title">{l s='Special Products' mod='tdblockspecials'}</h1>
        <ul class="product_list_widget">
            {foreach from=$products item=product name=homebestsellerProducts} 
            <li>
                <a href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}">
                    <img width="114" height="150" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}" 
                         class="attachment-shop_thumbnail wp-post-image" alt="{$product.name|escape:html:'UTF-8'}" />{$product.name|escape:html:'UTF-8'}</a>
      
                         <div class="star-rating">
                     {hook h='displayProductListReviews' product=$product}
                    </div>	
                
               
                
       {if ((isset($product.reduction) && $product.reduction)) && $product.price_without_reduction > $product.price && $product.show_price AND !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
	<span class="price">
            <del>
                <span class="amount">{convertPrice price=$product.price_without_reduction} </span>
            </del>
            <ins><span class="amount">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span></ins>    
        </span>
 {/if}
 
 
 
 
            </li>
     {/foreach}
        </ul>
    </div>  
</section>
{/if}

