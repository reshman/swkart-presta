<script type="text/javascript">
    
    
var wishlistlink="{$link->getModuleLink('blockwishlist', 'mywishlist')}";
var successfullyaddedwishlist= '{l s='was successfully added to your wishlist.'}<div class="clear"><a class=" btn button cont-shop left wishlist-btn-left"><span><span>{l s='Continue Here'}</span></span></a><a href="'+wishlistlink+'" class="btn button right wishlist-btn-right"><span><span>{l s='WISHLIST'}</span></span></a></div>';
var successfullydeletedwishlist='{l s='was successfully deleted to your wishlist.'}<div class="clear"><a class="btn button cont-shop left wishlist-btn-left"><span><span>{l s='Continue Here'}</span></span></a><a href="'+wishlistlink+'" class="btn button right wishlist-btn-right"><span><span>{l s='WISHLIST'}</span></span></a></div>';
var needtologinforwishlist= '{l s='You must be logged in to manage your wishlist!'}<div class="clear"><a class="btn button cont-shop left wishlist-btn-left"><span><span>{l s='Continue Here'}</span></span></a><a href="'+wishlistlink+'" class="btn button right wishlist-btn-right"><span><span>{l s='WISHLIST'}</span></span></a></div>';

var comparelink="{$link->getPageLink('products-comparison')}";
var notsuccessfullyaddcompare = '{l s='Allready added max value products!'}<div class="clear"><a class="btn button cont-shop left compare-btn-left"><span><span>{l s='Continue Here'}</span></span></a><a href="'+comparelink+'" class="btn button right compare-btn-right"><span><span>{l s='Compare List'}</span><span></a></div>';
var successfullycompareaddsuccess = '{l s='was successfully added to your compare list.'}<div class="clear"><a class="btn button cont-shop left compare-btn-left"><span><span>{l s='Continue Here'}</span></span></a><a href="'+comparelink+'" class="btn button right compare-btn-right"><span><span>{l s='Compare List'}</span></span></a></div>';
     
var someerrmsg = '{l s='Something went wrong'}';
var menuTitle = '{l s='Menu'}';

{$themesdev.td_custom_js|html_entity_decode}

	function successMessage(idProduct)
		{
                    showPopup();        
                    productImageSrc = jQuery('.main-image_'+idProduct+' img').attr('src');                    
                    productImage = '<img width="72" src="'+productImageSrc+'" alt="" />';                    
                    productName = jQuery('.main-image_'+idProduct+' img').attr('alt');                    
                    cartHref = jQuery('#top-cart > a').attr('href');                    
                    popupHtml = productImage + '<span>'+productName+'</span> ' + successfullyAdded2;
                    popupWindow.find('.tdtheme-popup-content').css('backgroundImage','none').html(popupHtml);                    
                    jQuery('.cont-shop').one('click',function(){
                        hidePopup(popupOverlay,popupWindow);
                    });                    
		}
                fading_effects = 1;
</script>

	<style>
html { background-color:#ffffff;}
</style>    
<!-- 
================================================== -->
            <style>
                .wishlist_table .add_to_cart, .yith-wcwl-add-button > a.button.alt { border-radius: 16px; -moz-border-radius: 16px; -webkit-border-radius: 16px; }            </style>
           
<style>
/*========== Body ==========*/
 {if $themesdev.td_enabody_bg=='enable'}
    body{
 {if $themesdev.td_enabody_bg=='enable'} 
    {if $themesdev.td_body_bg_custom!=''} 
        background: url("{$themesdev.td_body_bg_custom}") {$themesdev.td_bgrepeat} {$themesdev.td_bgattachment} {$themesdev.td_bgposition} {$themesdev.td_body_bg_color}!important;   
    {else}
        background:url("{$themesdev.td_body_bg}") {$themesdev.td_bgrepeat} {$themesdev.td_bgattachment} {$themesdev.td_bgposition} {$themesdev.td_body_bg_color}!important;   
    {/if}
 {else}
    background:none repeat scroll 0 0 {$themesdev.td_body_bg_color}!important;   
 {/if}  
 {if $themesdev.td_body_font_color!=''}
    color: {$themesdev.td_body_font_color};
 {/if}
 }
      {/if}
.dark #header .logo, .fullslider #header.dark .logo {
background:url({$logo_url}) no-repeat;background-size:{$themesdev.td_logowidth} {$themesdev.td_logoheight};
}
				
.light #header .logo, .fullslider #header.light .logo {
background:url({$logo_url}) no-repeat; background-size:{$themesdev.td_logowidth} {$themesdev.td_logoheight};
}

@media only screen and (-webkit-min-device-pixel-ratio: 2), 
only screen and (min-device-pixel-ratio: 2)
{
.dark #header .logo, .fullslider #header.dark .logo {
background:url({$logo_url}) no-repeat;background-size:{$themesdev.td_logowidth} {$themesdev.td_logoheight};
}				
				
.light #header .logo, .fullslider #header.light .logo {
background:url(
<?php if ( {$logo_url}) no-repeat;background-size:{$themesdev.td_logowidth} {$themesdev.td_logoheight};
}
}
     
</style>