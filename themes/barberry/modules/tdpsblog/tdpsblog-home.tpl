	
  {if $blogtdrecentpost}
    <script>
	(function($){
	   $(window).load(function(){
		/* items_slider */
		$('.items_slider_id_1489461241 .items_slider').iosSlider({
			snapToChildren: true,
			desktopClickDrag: true,
			navNextSelector: $('.items_slider_id_1489461241 .items_sliders_nav .big_arrow_right'),
			navPrevSelector: $('.items_slider_id_1489461241 .items_sliders_nav .big_arrow_left'),
			onSliderLoaded: custom_portfolio_UpdateSliderHeight,
			onSlideChange: custom_portfolio_UpdateSliderHeight,
			onSliderResize: custom_portfolio_UpdateSliderHeight
		});
		
		function custom_portfolio_UpdateSliderHeight(args) {
                                
			currentSlide = args.currentSlideNumber;
			
			/* update height of the first slider */

			setTimeout(function() {
			
				var setHeight = $('.items_slider_id_1489461241 .items_slider .blogslider_item:eq(' + (args.currentSlideNumber-1) + ')').outerHeight(true);
				$('.items_slider_id_1489461241 .items_slider').animate({ height: setHeight+50 }, 300);
			},300);			
				
			}		
		})
	})(jQuery);
	</script>

    
    <div class="items_slider_id_1489461241">
    
        <div class="items_sliders_header">
            <div class="items_sliders_title">
                <div class="featured_section_title"><span>{l s='Latest News' mod='tdpsblog'}</span></div>
                <div class='clearfix'></div>
            </div>
            <div class="items_sliders_nav">                        
                <a class='big_arrow_right'></a>
                <a class='big_arrow_left'></a>
                <div class='clearfix'></div>
            </div>
        </div>
    
        <div class="items_slider_wrapper">
            <div class="items_slider blogitems_slider">
                <ul class="slider">
		   {if $blogtdrecentpost}
     {foreach from=$blogtdrecentpost item=recentpost}			                                
                                            
    <li class="blogslider_item">
        <a class="blogslider_item_img" href="{tdpsblogClass::postlinks($recentpost.id_tdpsblog,$recentpost.link_rewrite)}">                                    
            <img width="190" height="190" src="{$base_url}{$recentpost.image_url}" class="attachment-recent_posts_shortcode wp-post-image"  alt="{$recentpost.tdpost_title}" title="{$recentpost.tdpost_title}" />                                    
        </a>
        <div class="blogslider_item_content" style=" ">

            <a class="blogslider_item_title" href="{tdpsblogClass::postlinks($recentpost.id_tdpsblog,$recentpost.link_rewrite)}"><h3>{$recentpost.tdpost_title}</h3></a>	

            <!--<div class="blogslider_item_meta">0 comments</div>-->

            <div class="blogslider_item_excerpt">
                {$recentpost.tdpost_content|html_entity_decode|strip_tags|truncate:200:''}...
            </div>

        </div>

    </li>

                 {/foreach}  

      {else}
          <span>{l s='Not found Blog Post' mod='tdpsblog'}</span>
{/if}
               
                                                
                                    </ul>     
            </div>
        </div>
    
    </div>
        {/if}
    