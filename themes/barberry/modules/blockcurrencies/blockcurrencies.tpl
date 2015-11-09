{*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<!-- Block currencies module -->

{if count($currencies) > 1}
<div class="header-switch nav-switch">	
    <input type="hidden" name="id_currency" id="id_currency" value=""/>
    <input type="hidden" name="SubmitCurrency" value="" />

    {foreach from=$currencies key=k item=f_currency}
            {if $cookie->id_currency == $f_currency.id_currency}<span class="current">{$f_currency.name}</span>{/if}
    {/foreach}

<div class="header-dropdown">		
<form action="{$request_uri}" method="post" enctype="multipart/form-data">
 <ul>
{foreach from=$currencies key=k item=f_currency}                    
<li  class="menu-item menu-item-type-post_type menu-item-object-page"><a class="selected" href="javascript:setCurrency({$f_currency.id_currency});"  title="{$f_currency.name}">{if $cookie->id_currency == $f_currency.id_currency}<b>{$f_currency.name}</b>{else}{$f_currency.name}{/if}</a></li>                                   
{/foreach}
    </ul>

</form>

</div>
</div>
{/if}
