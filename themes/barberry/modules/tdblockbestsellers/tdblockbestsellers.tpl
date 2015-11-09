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
 <div class="widget woocommerce widget_products" id="woocommerce_products-3">
     <h1 class="widget-title">{l s='Top Sellers' mod='tdblockbestsellers'}</h1>
     <ul class="product_list_widget">
          {foreach from=$best_sellers item=product name=myLoop}
         <li>
            
    <a title="{$product.legend|escape:'htmlall':'UTF-8'}" href="{$product.link}">
    <img width="114" height="150" alt="product_13_img_1" class="attachment-shop_thumbnail wp-post-image"
         src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}">
   {$product.name|strip_tags|truncate:40:'...'}</a>
   {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE} 
	<span class="price">
            <del>
                <span class="amount">{convertPrice price=$product.price_without_reduction} </span>
            </del>
            <ins><span class="amount">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span></ins>    
        </span>
 {/if}
             
             </li>
  {/foreach}
     </ul>
 </div>
  {/if}