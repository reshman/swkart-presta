
                    
            {if isset($products) && $products}        

        <section class="span3">
        <div id="woocommerce_products-2" class="widget woocommerce widget_products clearfix">
            <h1 class="widget-title">{l s='Featured Products' mod='tdhomefeatured'}</h1>
            <ul class="product_list_widget">
                {foreach from=$products item=product name=products}
         <li>
        <a href="{$product.link|escape:'html':'UTF-8'}" title="">
        <img width="114" height="150" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" class="attachment-shop_thumbnail wp-post-image" alt="" />{$product.name|escape:'html':'UTF-8'}</a>
        
             <div class="star-rating">
                     {hook h='displayProductListReviews' product=$product}
                    </div>	
        
        
           {if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
	 {if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
               <span class="price">
            <del>
                <span class="amount">{convertPrice price=$product.price_without_reduction} </span>
            </del>
            <ins><span class="amount">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span></ins>    
        </span>
          {/if}
 {/if}

         </li>
           {/foreach}
            </ul>
        </div>   

        </section>
            {/if}

