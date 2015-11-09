{*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if $best_sellers|@count > 0}
<div class="block box-up-sell">
    <div class="block-title"><strong><span>{l s='Top Sellers' mod='tdblockbestsellers'}</span></strong></div>
    <div class="block-content">
        <ul id="box_upsell">
            
            <li class="item">
                <div class="row">
                    {foreach from=$best_sellers item=product name=myLoop}
                    <div class="col-xs-6">
                        <div class="product-image-area">
                            <a class="product-image" title="{$product.legend|escape:'htmlall':'UTF-8'}" href="{$product.link}"><img alt="{$product.legend|escape:'htmlall':'UTF-8'}" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}"></a>
                          
                            <div class="clearer"></div>
                        </div>
                        <div class="details-area">
                             <h2 class="product-name"><a href="{$product.link}">{$product.name|strip_tags|truncate:10:'...'}</a></h2>
                            <div class="price-box">
                                    {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                                        <p class="special-price">
                                            <span class="price">       
                                        {if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if} 
                                    </span>
                                </p>
                            {/if} 
                            {if ((isset($product.reduction) && $product.reduction)) && $product.price_without_reduction > $product.price && $product.show_price AND !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
                                        <p class="old-price">
                                            <span class="price">
                                                {convertPrice price=$product.price_without_reduction} 
                                            </span>
                                            {if $product.specific_prices.reduction_type == 'percentage'}
                                                <span class="price-percent-reduction">-{$product.specific_prices.reduction * 100}%</span>
                                            {/if}
                                        </p>
                                    {/if}

                        </div>

                        </div>
                    </div>
                    {/foreach}
                </div>

            </li>
           
        </ul>
    </div>
</div>
 {/if}
 <script type="text/javascript">// <![CDATA[
    jQuery(function ($) {
            var img_owl = $(".image-slider .owl-carousel");
            img_owl .owlCarousel({
                    autoPlay: 10000,
                    itemsCustom: [ [0, 1], [320, 1], [480, 1], [768, 1], [992, 1], [1170, 1] ],     responsiveRefreshRate: 50,
                    pagination: false,
                    navigation: false,
            });
            $(".img-slider-navigation .btn-next").click(function(){
                    img_owl .trigger('owl.next');
            })
            $(".img-slider-navigation .btn-prev").click(function(){
                    img_owl .trigger('owl.prev');
            })
    });
// ]]></script>
