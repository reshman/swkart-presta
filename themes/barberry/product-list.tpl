{if isset($products) && $products}
	{*define numbers of product per line in other page for desktop*}
	{if $page_name !='index' && $page_name !='product'}
		{assign var='nbItemsPerLine' value=3}
		{assign var='nbItemsPerLineTablet' value=2}
		{assign var='nbItemsPerLineMobile' value=3}
	{else}
		{assign var='nbItemsPerLine' value=4}
		{assign var='nbItemsPerLineTablet' value=3}
		{assign var='nbItemsPerLineMobile' value=2}
	{/if}
	{*define numbers of product per line in other page for tablet*}
	{assign var='nbLi' value=$products|@count}
	{math equation="nbLi/nbItemsPerLine" nbLi=$nbLi nbItemsPerLine=$nbItemsPerLine assign=nbLines}
	{math equation="nbLi/nbItemsPerLineTablet" nbLi=$nbLi nbItemsPerLineTablet=$nbItemsPerLineTablet assign=nbLinesTablet}
	<!-- Products list -->
        
      
        
        
	<ul id="products" {if isset($id) && $id} id="{$id}"{/if} class="product_list grid row isotope{if isset($class) && $class} {$class}{/if}">
	{foreach from=$products item=product name=products}
		{math equation="(total%perLine)" total=$smarty.foreach.products.total perLine=$nbItemsPerLine assign=totModulo}
		{math equation="(total%perLineT)" total=$smarty.foreach.products.total perLineT=$nbItemsPerLineTablet assign=totModuloTablet}
		{math equation="(total%perLineT)" total=$smarty.foreach.products.total perLineT=$nbItemsPerLineMobile assign=totModuloMobile}
		{if $totModulo == 0}{assign var='totModulo' value=$nbItemsPerLine}{/if}
		{if $totModuloTablet == 0}{assign var='totModuloTablet' value=$nbItemsPerLineTablet}{/if}
		{if $totModuloMobile == 0}{assign var='totModuloMobile' value=$nbItemsPerLineMobile}{/if}
		<li class="product_item productanim1   isotope-item ajax_block_product {if $page_name == 'index' || $page_name == 'product'} col-xs-12 col-sm-4 col-md-3{else} col-xs-12 col-sm-6 col-md-4{/if}{if $smarty.foreach.products.iteration%$nbItemsPerLine == 0} last-in-line{elseif $smarty.foreach.products.iteration%$nbItemsPerLine == 1} first-in-line{/if}{if $smarty.foreach.products.iteration > ($smarty.foreach.products.total - $totModulo)} last-line{/if}{if $smarty.foreach.products.iteration%$nbItemsPerLineTablet == 0} last-item-of-tablet-line{elseif $smarty.foreach.products.iteration%$nbItemsPerLineTablet == 1} first-item-of-tablet-line{/if}{if $smarty.foreach.products.iteration%$nbItemsPerLineMobile == 0} last-item-of-mobile-line{elseif $smarty.foreach.products.iteration%$nbItemsPerLineMobile == 1} first-item-of-mobile-line{/if}{if $smarty.foreach.products.iteration > ($smarty.foreach.products.total - $totModuloMobile)} last-mobile-line{/if}">
		

			<div class="image_container">
        	    {if (!$PS_CATALOG_MODE && $PS_STOCK_MANAGEMENT && ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
               {if isset($product.available_for_order) && $product.available_for_order && !isset($restricted_country_mode)}
                  
                       {if ($product.allow_oosp || $product.quantity > 0)}
                         
                       {elseif (isset($product.quantity_all_versions) && $product.quantity_all_versions > 0)}
                        
                       {else}
                           <div class="outstock">
                               <link itemprop="availability" href="http://schema.org/OutOfStock" />{l s='Out of stock'}
                           </div>
                       {/if}
                
               {/if}
           {/if}
                       
          <div class="star-rating">
      {hook h='displayProductListReviews' product=$product}
         </div>	      
	 {if isset($product.new) && $product.new == 1}
           <div class="pro_new"  href="{$product.link|escape:'html':'UTF-8'}">
                {l s='New!'}
            </div>
        {/if}
       
        {if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
       <div class="onsale" href="{$product.link|escape:'html':'UTF-8'}">
           {l s='Sale!'}
       </div>
       {/if}	
       
 
        
                <a href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" class="prodimglink main-image_{$product.id_product}">

                    <div class="loop_product front"><img width="400" height="526" alt="{$product.name|escape:'html':'UTF-8'}" class="attachment-shop_catalog wp-post-image" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image"></div>
                                        
					<div class="loop_products back"><img width="400" height="526" alt="product_4_img_2" class="attachment-shop_catalog" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default' ,$product.id_product)|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image"></div>                    
                                        
                </a>
                
                <div class="clearfix"></div>

				 
          <div class="product_button_cont">
                                    <div class="product_button">
                                        	{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.customizable != 2 && !$PS_CATALOG_MODE}
							{if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
								{capture}add=1&amp;id_product={$product.id_product|intval}{if isset($static_token)}&amp;token={$static_token}{/if}{/capture}
								<a class="button add_to_cart_button product_type_simple ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart', true, NULL, $smarty.capture.default, false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}" data-minimal_quantity="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity > 1}{$product.product_attribute_minimal_quantity|intval}{else}{$product.minimal_quantity|intval}{/if}">
									<span>{l s='Add to cart'}</span>
								</a>
							{else}
							
							{/if}
						{/if}
                                        
                                    </div>
                                </div>                   
                                             

            </div>
            
            <div class="clearfix"></div>
            
            <div class="product_details">
                          
           <p class="category"> <a href="{$link->getCategoryLink({$product.id_category_default} , {$product.category})}" rel="tag">{$product.category}</a></p>                         
             
                        
            <h4><a href="{$product.link}">{$product.name|escape:html:'UTF-8'|truncate:50:'...'}</a></h4>
            
            
	<div  class="star-rating">{hook h='displayProductListReviews' product=$product}</div>
        
        
        
        



{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
<span itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="price">
{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
<del>	
{hook h="displayProductPriceBlock" product=$product type="old_price"}
<span class="amount">
{displayWtPrice p=$product.price_without_reduction}
</span>
{hook h="displayProductPriceBlock" id_product=$product.id_product type="old_price"}
</del>
{/if}
<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
<ins>
<span itemprop="price" class="amount">
{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
</span>
</ins>  
{hook h="displayProductPriceBlock" product=$product type="price"}
{hook h="displayProductPriceBlock" product=$product type="unit_price"}
{/if}
</span>
{/if}
        
        
                
        
            <div class="clearfix"></div>
            
	            
                <div class="product-actions"> 
                  
                    <div class="action wishlist">
                                        <div class="yith-wcwl-add-button ">
                                                <a onclick="WishlistCart('wishlist_block_list', 'add', '{$product.id_product|intval}', $('#idCombination').val(), 1); return false;" class="add_to_wishlist" >{l s='Wishlist' mod='tdhomefeatured'}</a>
                                     <div style="clear:both"></div>
                                        </div>
                                    </div>
                 
                 
                            <div class="action compare">
                                        
                                            <a onclick="CompareCart({$product.id_product|intval}); return false;" title="{l s='Add to Compare' mod='tdhomefeatured'}" class="compare">{l s='Compare' mod='tdhomefeatured'}</a>
                                                     
                                    </div>
     
                    <div class="clearfix"></div>               
                       
                </div>               
     
 
           </div>


		</li>
	{/foreach}
	</ul>
        
   
        
        
{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef comparedProductsIds=$compared_products}
{/if}
