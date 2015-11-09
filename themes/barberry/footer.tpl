{if !isset($content_only) || !$content_only}

</div>
</div>
{if isset($left_column_size) && !empty($left_column_size)}
    <div class="span3 rsidebar">
        <div class="aside_sidecolumn">
            {$HOOK_LEFT_COLUMN}
        </div>
    </div>
{elseif isset($right_column_size) && !empty($right_column_size)}
    <div class="span3 rsidebar">
        <div class="aside_sidecolumn">
            {$HOOK_RIGHT_COLUMN}
        </div><!--/right_column-->
    </div>
{/if}             
</div>
</div>
<div class="clearfix"></div>
<div class="container">
</div>
{if $page_name =='index'}
    
</div>
</div><!-- .entry-content -->
</article><!-- #post-786 -->
<div class="clearfix"></div>
           {if $themesdev.td_slider_type=="standardslider"}
                      </div>
</div>
                 {/if} 
</div>
{/if}
</div>
<footer id="copyright">
    <div class="container">
        <div class="footer_container">
            <div class="container widget_area">
                <div class="row">
                    {$HOOK_FOOTER}
                </div>
            </div>
        </div>
        <div class="footer_copyright">
            <div class="container">
                <div class="row"> 
                    <div class="span6 copytxt">
                        {$themesdev.td_copyright|html_entity_decode}
                    </div>            
                    <div class="span6 cards">
                       
                         <a href="{$themesdev.td_paymlink1|html_entity_decode}"><img src="{$themesdev.td_paymenticon|html_entity_decode}" alt="" /></a>
                    </div>
                </div>
            </div>
        </div>
    </div>	
</footer>
<div class="clearfix"></div>

{if $themesdev.td_backto_top=='enable'}
<a class="go-top"></a>
{/if}
</div>
<div id="review_form_wrapper_overlay">
    <div id="review_form_wrapper_overlay_close"><i class="fa fa-times"></i></div>
</div>
 
        <script type="text/javascript">
						var $ = jQuery.noConflict();
						
                         $(window).load(function() {
							 
															 							 
													
								$(function(){
									$('#toggle_sidebar').toggle(function(){
										$(".rsidebar").addClass("removeside");
										$("#primary").removeClass().addClass("span12 fullwidth");
																				$("#prodrow").removeClass().addClass("row-fluid four_side");
										$("#shop_categories").removeClass().addClass("four_side");
																				$('#products, #products_cat').isotope( 'reloadItems' ).isotope();
									}, function(){

										$(".rsidebar").removeClass("removeside");
										$("#primary").removeClass().addClass("span9 sidebar");
																				$("#prodrow").removeClass().addClass("row-fluid three_side");
										$("#shop_categories").removeClass().addClass("three_side");
																				$('#products, #products_cat').isotope( 'reloadItems' ).isotope();
									});
									
								});
											
							 
						 							 

							
                            // cache container
                            var $container = $('#products, #products_cat');
							
                            // initialize isotope
                            $container.isotope({
                                itemSelector : '.product_item, .product-category',
                                animationEngine : 'best-available',
								layoutMode : 'fitRows',

								animationOptions: {
                        	     	easing: 'easeInOutQuad',
                        	     	queue: false
                        	   	}

                            });							

                        });
                        </script>
                         {/if} 
{include file="$tpl_dir./global.tpl"}
</body>
</html>