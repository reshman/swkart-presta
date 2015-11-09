        
{if $tdblogallcat}
  <li id="woocommerce_product_categories-2" class="widget woocommerce widget_product_categories">
    <div class="block block-category-nav">
	 <h5 class="widget-title sidebar-widget-title">
	     <span class="mpcth-color-main-border">
                 {l s='Blog Categories' mod='tdpsblog'}
             </span>
	 </h5>
	 <div class="block-content">	
              <ul>
                   {foreach from=$tdblogallcat item=blogcat} 
                           
                          <li class="cat-item cat-item-{$blogcat.id_tdpsblog_category}">
                              <a title="" href="{tdpsblogClass::catlinks($blogcat.id_tdpsblog_category,$blogcat.cat_rewrite)}">
                                 <span class="errow"></span>
                                 {$blogcat.category_name}
                              </a>
                          </li>
                   {/foreach}  
	     </ul>
        </div>   
    </div> 
     </li>              
{/if}   
       
       
{if $blogtdrecentpost}
   <li id="woocommerce_product_categories-2" class="widget woocommerce widget_product_categories">       
    <div class="block block-related">
        <h5 class="widget-title sidebar-widget-title">
            <span class="mpcth-color-main-border">
                {l s='Recent Post' mod='tdpsblog'}
            </span>
        </h5>
    <div class="block-content">
        <!-- p class="block-subtitle">Check items to add to the cart or&nbsp;<a href="#" onclick="selectAllRelated(this); return false;">select all</a></p -->
        <div class="flexslider carousel">
            <ul class="slides">
                       {foreach from=$blogtdrecentpost item=recentpost}
                            <li class="newproductslider-item">
                                <div class="item-inner">
                                    <a class="product-image" title="{$recentpost.tdpost_title}" href="{tdpsblogClass::postlinks($recentpost.id_tdpsblog,$recentpost.link_rewrite)}">
                                        <img title="{$recentpost.tdpost_title}" alt="{$recentpost.tdpost_title}" src=" {$base_url}{$recentpost.image_url}">
                                    </a>
                                
                                    <h2 class="product-name">
                                        <a title="{$recentpost.tdpost_title}" href="{tdpsblogClass::postlinks($recentpost.id_tdpsblog,$recentpost.link_rewrite)}">{$recentpost.tdpost_title}</a>
                                    </h2>
                                
                                    <div class="price-box">
                                         <span id="product-price-1" class="regular-price">
                                               <span class="price">
                                                    {$recentpost.tdpost_dete}  
                                               </span>
                                         </span>
                                    </div>
                                    <!--input type="checkbox" class="checkbox related-checkbox" id="related-checkbox3" name="related_products[]" value="3" /-->
                                
                                </div>
                            </li>
                        {/foreach}
                    </ul>
         </div>
     </div>

  
    <script type="text/javascript">
        $('.block-related .flexslider').flexslider({
        slideshow: true,
        itemWidth: 245,
        itemMargin: 5,
        minItems: 1,
        maxItems: 1,
        slideshowSpeed: 4000,
        animationSpeed: 600,
        controlNav: false,
        move: 1,
        pauseOnAction: true,
        pauseOnHover: true, 
        touch: true,
        animation: "slide"
    });
    </script>

</div>
   </li>                 
{/if}

{if $blogtdrecentcomments}
    <li id="woocommerce_product_categories-2" class="widget woocommerce widget_product_categories">
     <div class="block block-category-nav">
          <h5 class="widget-title sidebar-widget-title">
	      <span class="mpcth-color-main-border">
                    {l s='Recent Comments' mod='tdpsblog'}
              </span>
	  </h5>
          <ul id="recentcomments">
               {foreach from=$blogtdrecentcomments item=blogtdrecentcom}
                        <li class="recentcomments">
                            <span class="comment_author">{$blogtdrecentcom.comment_author_name}</span> <br>
                            <a href="{tdpsblogClass::postlinks($blogtdrecentcom.id_tdpsblog,$blogtdrecentcom.link_rewrite)}">{$blogtdrecentcom.comments_text}</a>
                        </li>
               {/foreach}  
    
          </ul>
     </div>
     </li>
{/if} 