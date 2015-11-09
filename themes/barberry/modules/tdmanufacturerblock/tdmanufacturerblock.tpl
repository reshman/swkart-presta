

<div class="clearcol"></div>
<script>
	(function($){
	   $(window).load(function(){
		/* items_slider */
		$('.items_slider_id_507765054 .items_slider').iosSlider({
			snapToChildren: true,
			desktopClickDrag: true,
			navNextSelector: $('.items_slider_id_507765054 .items_sliders_nav .big_arrow_right'),
			navPrevSelector: $('.items_slider_id_507765054 .items_sliders_nav .big_arrow_left'),
			onSliderLoaded: custom_portfolio_UpdateSliderHeight,
			onSlideChange: custom_portfolio_UpdateSliderHeight,
			onSliderResize: custom_portfolio_UpdateSliderHeight
		});
		
		function custom_portfolio_UpdateSliderHeight(args) {
                                
			currentSlide = args.currentSlideNumber;
			
			/* update height of the first slider */

			setTimeout(function() {
			
				var setHeight = $('.items_slider_id_507765054 .items_slider .brand-item:eq(' + (args.currentSlideNumber-1) + ')').outerHeight(true);
				$('.items_slider_id_507765054 .items_slider').animate({ height: setHeight+50 }, 300);
			},300);			
				
			}		
		})
	})(jQuery);
	</script>

        
        
        
        
        

<script>
	(function($){
	   $(window).load(function(){
		/* items_slider */
		$('.items_slider_id_1326501762 .items_slider').iosSlider({
			snapToChildren: true,
			desktopClickDrag: true,
			navNextSelector: $('.items_slider_id_1326501762 .items_sliders_nav .big_arrow_right'),
			navPrevSelector: $('.items_slider_id_1326501762 .items_sliders_nav .big_arrow_left'),
			onSliderLoaded: custom_portfolio_UpdateSliderHeight,
			onSlideChange: custom_portfolio_UpdateSliderHeight,
			onSliderResize: custom_portfolio_UpdateSliderHeight
		});
		
		function custom_portfolio_UpdateSliderHeight(args) {
                                
			currentSlide = args.currentSlideNumber;
			
			/* update height of the first slider */

			setTimeout(function() {
			
				var setHeight = $('.items_slider_id_1326501762 .items_slider .brand-item:eq(' + (args.currentSlideNumber-1) + ')').outerHeight(true);
				$('.items_slider_id_1326501762 .items_slider').animate({ height: setHeight+50 }, 300);
			},300);			
				
			}		
		})
	})(jQuery);
	</script>
                 
    
    
    <div class="items_slider_id_1326501762">
    
        <div class="items_sliders_header">
            <div class="items_sliders_title">
                <div class="featured_section_title"><span>{l s='Brands' mod='tdmanufacturerblock'}</span></div>
                <div class='clearfix'></div>
            </div>
            <div class="items_sliders_nav">                        
                <a class='big_arrow_right'></a>
                <a class='big_arrow_left'></a>
                <div class='clearfix'></div>
            </div>
        </div>
    
        <div class="items_slider_wrapper">
            <div class="items_slider branditems_slider">
                <ul class="slider">
                
                
      {foreach from=$manufacturer item=manuf name=manuf}      	
    <li class="brand-item">
    <a title="{$manuf.name}" href="{$link->getmanufacturerLink($manuf.id_manufacturer, $manuf.link_rewrite)}">

    <img class="attachment-shop_thumbnail wp-post-image" src="{$img_manu_dir}{$manuf.id_manufacturer}-medium_default.jpg" alt="{$manuf.name}" width="32" height="32" /></a>
    </li>
    {/foreach}

                               

                </ul>     
            </div>
        </div>
    
    </div>