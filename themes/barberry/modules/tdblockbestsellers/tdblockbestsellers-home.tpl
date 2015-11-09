 <div class="clearcol"></div>  
    <script>
	(function($){
		$(window).load(function(){
		/* items_slider */
		$('.items_slider_id_bestsellers .items_slider').iosSlider({
			snapToChildren: true,
			desktopClickDrag: true,
			navNextSelector: $('.items_slider_id_bestsellers .items_sliders_nav .big_arrow_right'),
			navPrevSelector: $('.items_slider_id_bestsellers .items_sliders_nav .big_arrow_left'),
			onSliderLoaded: custom_best_sellers_UpdateSliderHeight,
			onSlideChange: custom_best_sellers_UpdateSliderHeight,
			onSliderResize: custom_best_sellers_UpdateSliderHeight
		});
		
		function custom_best_sellers_UpdateSliderHeight(args) {
                                
			currentSlide = args.currentSlideNumber;
			
			/* update height of the first slider */

				setTimeout(function() {
																				 					
					var setHeight = $('.items_slider_id_bestsellers .items_slider .product_item:eq(' + (args.currentSlideNumber-1) + ')').outerHeight(true);
					$('.items_slider_id_bestsellers .items_slider').animate({ height: setHeight+20 }, 300);
				},300);
				
			}
		
		})
	})(jQuery);
	</script>
   
    <div class="woocommerce items_slider_id_bestsellers {$themesdev.td_perrowhome}">
    
        <div class="items_sliders_header">
            <div class="items_sliders_title">
                <div class="featured_section_title"><span>{l s='Best Sellers'  mod='tdblockbestsellers'}</span></div>
                <div class="clearfix"></div>
            </div>
            <div class="items_sliders_nav">                        
                <a class='big_arrow_right'></a>
                <a class='big_arrow_left'></a>
                <div class='clearfix'></div>
            </div>
        </div>

        <div class="items_slider_wrapper">
             {if $best_sellers && $best_sellers|@count > 0}
            <div class="items_slider">
                <ul class="slider">
                                                    
                                            
                            
	
	
{foreach from=$best_sellers item=product name=myLoop}
  <li class="product_item productanim1 ">
                            <div class="image_container">
                                                    {if (!$PS_CATALOG_MODE && $PS_STOCK_MANAGEMENT && ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
               {if isset($product.available_for_order) && $product.available_for_order && !isset($restricted_country_mode)}
                  
                       {if ($product.allow_oosp || $product.quantity > 0)}
                         
                       {elseif (isset($product.quantity_all_versions) && $product.quantity_all_versions > 0)}
                         
                       {else}
                            <div class="outstock">
                                                <link itemprop="availability" href="http://schema.org/OutOfStock" />{l s='Out of stock'  mod='tdblockbestsellers'}
                           </div>
                       {/if}
               {/if}
           {/if}
                                <div class="star-rating" >
                                    {hook h='displayProductListReviews' product=$product}
                                </div>	
                                {if isset($product.new) && $product.new == 1}
                                    <div class="pro_new">
                                        {l s='New!' mod='tdblockbestsellers'}
                                    </div>
                                {/if}
                                {if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
                                    <div class="onsale">
                                        {l s='Sale!' mod='tdblockbestsellers'}
                                    </div>
                                {/if}
             
                                <a class="prodimglink  main-image_{$product.id_product}" href="{$product.link}">

                                    <div class="loop_product front"><img width="400" height="526" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}" class="attachment-shop_catalog wp-post-image" alt="{$product.name|escape:html:'UTF-8'}" /></div>

                                    <div class="loop_products back"><img width="400" height="526" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default',$product.id_product)}" class="attachment-shop_catalog" alt="{$product.name|escape:html:'UTF-8'}" /></div>                    

                                </a>
                                <div class="clearfix"></div>
                                      <div class="product_button_cont">
                                    <div class="product_button">
                                        	{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.customizable != 2 && !$PS_CATALOG_MODE}
							{if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
								{capture}add=1&amp;id_product={$product.id_product|intval}{if isset($static_token)}&amp;token={$static_token}{/if}{/capture}
								<a class="button add_to_cart_button product_type_simple ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart', true, NULL, $smarty.capture.default, false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'  mod='tdblockbestsellers'}" data-id-product="{$product.id_product|intval}" data-minimal_quantity="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity > 1}{$product.product_attribute_minimal_quantity|intval}{else}{$product.minimal_quantity|intval}{/if}">
									<span>{l s='Add to cart'  mod='tdblockbestsellers'}</span>
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
                                <span class="price">
                                    {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE} 
                                        <del>
                                            <span class="amount">{convertPrice price=$product.price_without_reduction} </span>
                                        </del>
                                        <ins><span class="amount">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span></ins>
                                        {/if}
                                </span>
                                <div class="clearfix"></div>
                                <div class="product-actions"> 
                                    <div class="action wishlist">
                                        <div class="yith-wcwl-add-button ">
                                                <a onclick="WishlistCart('wishlist_block_list', 'add', '{$product.id_product|intval}', $('#idCombination').val(), 1); return false;" class="add_to_wishlist" >{l s='Wishlist' mod='tdblockbestsellers'}</a>
                                     <div style="clear:both"></div>
                                        </div>
                                    </div>
                                    <div class="action compare">
                                        
                                            <a onclick="CompareCart({$product.id_product|intval}); return false;" title="{l s='Add to Compare' mod='tdblockbestsellers'}" class="compare">{l s='Compare' mod='tdblockbestsellers'}</a>
                                                     
                                    </div> 
                                    <div class="clearfix"></div>               
                                </div>               
                            </div>
                        </li>
                {/foreach}
                                                
                                    </ul>     
            </div>
       
       {else}
		<p>{l s='No best sellers at this time' mod='blockbestsellers'}</p>
	{/if}
        </div>
    
    </div>
    