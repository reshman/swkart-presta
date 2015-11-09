 <script>
	(function($){
	   $(window).load(function(){
		   /* items_slider */
			$('.items_slider_id_652386531 .items_slider').iosSlider({
				snapToChildren: true,
				desktopClickDrag: true,
				navNextSelector: $('.items_slider_id_652386531 .products_slider_next'),
				navPrevSelector: $('.items_slider_id_652386531 .products_slider_previous'),
				onSliderLoaded: update_height_products_slider,
				onSlideChange: update_height_products_slider,
				onSliderResize: update_height_products_slider

			});
			
			function update_height_products_slider(args) {
				
				/* update height of the first slider */
	
				//alert (setHeight);
				setTimeout(function() {
					var setHeight = $('.items_slider_id_652386531 .products_slider_item:eq(' + (args.currentSlideNumber-1) + ')').outerHeight(true);
					$('.items_slider_id_652386531 .products_slider').stop().animate({ height: setHeight+20 }, 300);
				},0);
				
			}
			
			$(".prodstyle1 .products_slider_item").mouseenter(function(){
				$(this).find('.products_slider_infos').stop().fadeTo(100, 0);
				$(this).find('.products_slider_images img').stop().fadeTo(100, 0.3, function() {
					$(this).parent().parent().parent().find('.products_slider_infos').stop().fadeTo(200, 1);
				});
				//alert("aaaaaaa");
			}).mouseleave(function(){
				$(this).find('.products_slider_images img').stop().fadeTo(100, 1);
				$(this).find('.products_slider_infos').stop().fadeTo(100, 0);
			});
	   })
	})(jQuery);

	</script>
    
    <div class="items_slider_id_652386531 products_slider">  
    
        <div class="items_slider_wrapper">
            <div class="items_slider products_slider">
                <ul class="slider prodstyle1">
         {foreach from=$products_slider item=product name=product}             
	
<li class="products_slider_item">

    <div class="products_slider_content">
        
        <div class="products_slider_images_wrapper">
		
			    
                        
            <div class="products_slider_images"><img width="570" height="750" alt="product_13_img_1" class="attachment-shop_single wp-post-image" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'product_slider')}" style="opacity: 1;"></div>
            
                                
        </div>   
        
        <div class="products_slider_infos" style="opacity: 0; display: block;">
            
            <!-- Show only the first category-->
                       <!-- <div class="products_slider_category"><a rel="tag" href="http://barberry.temashdesign.com/product-category/suits-sportcoats/">Suits &amp; Sportcoats</a></div>-->
            
            <div class="products_slider_title"><a href="{$product.link}">{$product.name|strip_tags|truncate:100:'...'}</a></div>
            
            <div class="products_slider_sep"></div>
      
            {if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))} 
                                            <div class="products_slider_price">

                                                {if ((isset($product.reduction) && $product.reduction)) && $product.price_without_reduction > $product.price && $product.show_price AND !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
                                                    <p class="old-price price">
                                                        <span class="amount">
                                                            {convertPrice price=$product.price_without_reduction} 
                                                        </span>
                                           
                                                    </p>
                                                {/if}
                                               
                                                {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                                                    <p class="special-price price">
                                                        <span class="amount">       
                                                    {if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if} 
                                                </span>
                                            </p>

                                        {/if} 

                                    </div>   
                                {/if} 
            
            
            
           
            
	
         
            
            
            
             <div class="products_slider_button_wrapper">	
            {if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.customizable != 2 && !$PS_CATALOG_MODE}
						
                {if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
								{capture}add=1&amp;id_product={$product.id_product|intval}{if isset($static_token)}&amp;token={$static_token}{/if}{/capture}
								<a class="f_button button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart', true, NULL, $smarty.capture.default, false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}" data-minimal_quantity="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity > 1}{$product.product_attribute_minimal_quantity|intval}{else}{$product.minimal_quantity|intval}{/if}">
									<span>{l s='Add to cart'}</span>
								</a>
							{else}
								<span class="f_button  button ajax_add_to_cart_button btn btn-default disabled">
									<span>{l s='Add to cart'}</span>
								</span>
							{/if}
						{/if}
                       </div>
                            
   
                    
        </div>        
        
    </div>

</li>
	 {/foreach}			
                </ul>
                                       
                <div class="products_slider_previous" style="cursor: pointer;"></div>
                <div class="products_slider_next" style="cursor: pointer;"></div>
                    
            </div>
        </div>
    
    </div>
