{*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if $wishlists|count == 1}
    <div class="product-actions"> 
			  
                    <div class="action wishlist">
                        
<div class="yith-wcwl-add-to-wishlist add-to-wishlist-301">
		    <div style="display:block" class="yith-wcwl-add-button show">

	        
                        <a class="add_to_wishlist" href="#" onclick="WishlistCart('wishlist_block_list', 'add', '{$id_product|intval}', false, 1); return false;">
        {l s="Wishlist" mod='blockwishlist'}</a>
	    </div>

	    <div style="clear:both"></div>
	    <div class="yith-wcwl-wishlistaddresponse"></div>
	
</div>

<div class="clear"></div> 
                    </div>
             
             
                    <div class="action compare">
                         <div class="woocommerce product compare-button"><a href="#" class="compare" onclick="CompareCart({$id_product|intval}); return false;"> {l s="Compare" mod='blockwishlist'}</a></div>                
                    </div> 
                           
 
             	<div class="clearfix"></div>    
            </div>

{else}
	{foreach name=wl from=$wishlists item=wishlist}
		{if $smarty.foreach.wl.first}
		<p class="buttons_bottom_block">
			<a class="btn btn-default" tabindex="0" data-toggle="popover" data-trigger="focus" title="Wishlist" data-placement="bottom" id="wishlist_button">{l s='Add to wishlist' mod='blockwishlist'}</a></p>
				<div hidden id="popover-content">
					<table class="table" border="1">
						<tbody>
		{/if}
							<tr title="{$wishlist.name}" value="{$wishlist.id_wishlist}" onclick="WishlistCart('wishlist_block_list', 'add', '{$id_product|intval}', $('idCombination').val(), document.getElementById('quantity_wanted').value, '{$wishlist.id_wishlist}');">
								<td>
									{l s='Add to %s'|sprintf:$wishlist.name mod='blockwishlist'}
								</td>
							</tr>
		{if $smarty.foreach.wl.last}
					</tbody>
				</table>
			</div>
		{/if}
	{foreachelse}
		<a href="#" id="wishlist_button_nopop" onclick="WishlistCart('wishlist_block_list', 'add', '{$id_product|intval}', $('#idCombination').val(), document.getElementById('quantity_wanted').value); return false;" rel="nofollow"  title="{l s='Add to my wishlist' mod='blockwishlist'}">
			{l s='Add to wishlist' mod='blockwishlist'}
		</a>
	{/foreach}
{/if}
