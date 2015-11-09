
{if $products}

<div class="widget woocommerce widget_products" id="woocommerce_products-3">
     <h1 class="widget-title">{l s='Special Products' mod='tdblockspecials'}</h1>
     <ul class="product_list_widget">
         {foreach from=$products item=product name=homebestsellerProducts} 
         <li>
            
    <a title="{$product.name|escape:html:'UTF-8'}" href="{$product.link}">
    <img width="114" height="150" alt="product_13_img_1" class="attachment-shop_thumbnail wp-post-image"
         src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}">
   {$product.name|strip_tags|truncate:40:'...'}</a>
   {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE} 
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
     {/if}