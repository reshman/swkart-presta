{if $themesdev.td_hslider =='contentslider'}

<script type="text/javascript">
	(function($){
	   $(window).load(function(){
				
				jQuery('.iosSlider').iosSlider({
					snapToChildren: true,
					desktopClickDrag: true,
					keyboardControls: true,
					autoSlide: true,
                    autoSlideTimer: 5000,
					navNextSelector: jQuery('.next'),
					navPrevSelector: jQuery('.prev'),
					navSlideSelector: jQuery('#selectors .item'),
					onSlideChange: slideChange,
					onSlideComplete: slideContentComplete,
					onSliderLoaded: slideContentLoaded,				
				});
				
	
			function slideChange(args) {
				
				currentSlide = args.currentSlideNumber;		
				
				jQuery('#selectors .item').removeClass('selected');
				jQuery('#selectors .item:eq(' + (currentSlide - 1) + ')').addClass('selected');

				var obj = args.currentSlideObject;
                var slideNumber = obj.attr('data-img');
				jQuery('#header, #selectors').removeClass();
				jQuery('#header').addClass(slideNumber);
				jQuery('#selectors').addClass(slideNumber);

				
		function checkWidth() {
		var $window = $(window);
        var windowsize = $window.width();
        if (windowsize < 978) {
            //if the window is greater than 440px wide then turn on jScrollPane..
            jQuery('#header').removeClass();
			jQuery('#page_wrapper').removeClass('fullslider');


        }

		else {
            //if the window is greater than 440px wide then turn on jScrollPane..

			jQuery('#header').removeClass();
            jQuery('#header').addClass(slideNumber);
			jQuery('#page_wrapper').addClass('fullslider');
        }

    }
    // Execute on load
    checkWidth();

    // Bind event listener
    $(window).resize(checkWidth);
			
		
			}		
				function slideContentComplete(args) {
					
					if(!args.slideChanged) return false;					
					jQuery(args.sliderObject).find('.center .iostitle, .center .iostext, .right .iostitle, .left .iostitle, .right .iostext, .left .iostext').attr('style', '');					
					jQuery(args.currentSlideObject).find('.right .iostitle').animate({
						marginRight: '30px',
						opacity: '1'
					}, 400, 'easeOutQuint');
					jQuery(args.currentSlideObject).find('.left .iostitle').animate({
						marginLeft: '30px',
						opacity: '1'
					}, 400, 'easeOutQuint');
					jQuery(args.currentSlideObject).find('.center .iostitle').animate({
						opacity: '1'
					}, 400, 'easeOutQuint');										
					jQuery(args.currentSlideObject).find('.right .iostext').delay(300).animate({
						marginRight: '30px',
						opacity: '1'
					}, 400, 'easeOutQuint');
					jQuery(args.currentSlideObject).find('.left .iostext').delay(300).animate({
						marginLeft: '30px',
						opacity: '1'
					}, 400, 'easeOutQuint');
					jQuery(args.currentSlideObject).find('.center .iostext').delay(300).animate({
						opacity: '1'
					}, 400, 'easeOutQuint');					

				}
				
				function slideContentLoaded(args) {
					
					jQuery(args.sliderObject).find('.center .iostitle, .center .iostext, .right .iostitle, .left .iostitle, .right .iostext, .left .iostext').attr('style', '');					
					jQuery(args.currentSlideObject).find('.right .iostitle').animate({
						marginRight: '30px',
						opacity: '1'
					}, 400, 'easeOutQuint');
					jQuery(args.currentSlideObject).find('.left .iostitle').animate({
						marginLeft: '30px',
						opacity: '1'
					}, 400, 'easeOutQuint');
					jQuery(args.currentSlideObject).find('.center .iostitle').animate({
						opacity: '1'
					}, 400, 'easeOutQuint');										
					jQuery(args.currentSlideObject).find('.right .iostext').delay(300).animate({
						marginRight: '30px',
						opacity: '1'
					}, 400, 'easeOutQuint');
					jQuery(args.currentSlideObject).find('.left .iostext').delay(300).animate({
						marginLeft: '30px',
						opacity: '1'
					}, 400, 'easeOutQuint');
					jQuery(args.currentSlideObject).find('.center .iostext').delay(300).animate({
						opacity: '1'
					}, 400, 'easeOutQuint');				
																		
					
					slideChange(args);
					
				}
				
		// Optimalisation: Store the references outside the event handler:

		})
	})(jQuery);
			
</script>


<div class="slidercontainer full-slider">
<div class="iosSlider">

  
	<!-- slider -->
	<div class="slider">
            {$i=0}
            {foreach from=$tdcontentslider item=slider}
             
            <div style="background: url('{$base_url}{$slider.image_url}') repeat scroll center center transparent; position: absolute; top: 0px; transform: matrix(1, 0, 0, 1, 5396, 0); width: 1349px;" data-img="{if $i%2==0}light{else}dark{/if}" class="item">
		<img width="1600" height="600" alt="" src="{$base_url}{$slider.image_url}">
                    <div class="caption {if $i%2==0}light{else}dark{/if} left">
                    <div class="container navcont">
                        <h1 class="iostitle" style=""><a href="{$slider.slider_link}">{$slider.image_title}</a></h1>
                        <div class="clearfix"></div>
                        <div class="iostext" style="">{$slider.slider_content|htmlspecialchars}</div>
                    </div>
                </div>
            </div>      
                       {$i=$i+1}
            {/foreach}
 			           
 
           
 			 
             
</div>            

                    
    <div class="selectorsBlock">

        <div id="selectors" class="dark">
        	<div class="prev" style="cursor: pointer;"></div>
                                   
    
                <div class="item first" style="cursor: pointer;"></div>
                                   
    
                <div class="item selected" style="cursor: pointer;"></div>
                                   
    
                <div class="item" style="cursor: pointer;"></div>
                                   
    
                <div class="item" style="cursor: pointer;"></div>
                                <div class="next" style="cursor: pointer;"></div>
        </div>
        
     

    </div>
</div>

</div>
            {/if}