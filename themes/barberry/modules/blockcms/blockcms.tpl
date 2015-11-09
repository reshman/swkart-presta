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

{if $block == 1}
    <section class="span3">
    <!-- Block CMS module -->
    {foreach from=$cms_titles key=cms_key item=cms_title}
        <section id="informations_block_left_{$cms_key}" class="block informations_block_left">
            <p class="title_block">
                <a href="{$cms_title.category_link|escape:'html':'UTF-8'}">
            {if !empty($cms_title.name)}{$cms_title.name}{else}{$cms_title.category_name}{/if}
        </a>
    </p>
    <div class="block_content list-block">
        <ul>
            {foreach from=$cms_title.categories item=cms_page}
                {if isset($cms_page.link)}
                    <li class="bullet">
                        <a href="{$cms_page.link|escape:'html':'UTF-8'}" title="{$cms_page.name|escape:'html':'UTF-8'}">
                            {$cms_page.name|escape:'html':'UTF-8'}
                        </a>
                    </li>
                {/if}
            {/foreach}
            {foreach from=$cms_title.cms item=cms_page}
                {if isset($cms_page.link)}
                    <li>
                        <a href="{$cms_page.link|escape:'html':'UTF-8'}" title="{$cms_page.meta_title|escape:'html':'UTF-8'}">
                            {$cms_page.meta_title|escape:'html':'UTF-8'}
                        </a>
                    </li>
                {/if}
            {/foreach}
            {if $cms_title.display_store}
                <li>
                    <a href="{$link->getPageLink('stores')|escape:'html':'UTF-8'}" title="{l s='Our stores' mod='blockcms'}">
                        {l s='Our stores' mod='blockcms'}
                    </a>
                </li>
            {/if}
        </ul>
    </div>
</section>
{/foreach}
<!-- /Block CMS module -->
</section>
{else}



    <section class="span3">

        <div id="barberry_social-3" class="widget barberry_social clearfix">
            <h1 class="widget-title">{l s='Barberry Social'}</h1>
            <div class="social_widget">
                {if $themesdev.td_facebook_url !=''}<a class="facebook" title="{l s='Facebook'}" href="{$themesdev.td_facebook_url}" target="_blank"></a>{/if}
                {if $themesdev.td_twitter_url !=''}<a class="twitter" title="{l s='Twitter'}" href="{$themesdev.td_twitter_url}" target="_blank"></a>{/if}
                {if $themesdev.td_google_url !=''}<a class="googleplus" title="{l s='Google Plus'}" href="{$themesdev.td_googleplus}" target="_blank"></a>{/if}
                {if $themesdev.td_pinteres_url !=''}<a class="pinterest" title="{l s='Pinterest'}" href="{$themesdev.td_pinteres_url}" target="_blank"></a>{/if}
                {if $themesdev.td_youtube_url !=''}<a class="youtube" title="{l s='Youtube'}" href="{$themesdev.td_youtube_url}" target="_blank"></a>{/if}

            </div>
        </div>

        <div id="nav_menu-5" class="widget widget_nav_menu clearfix">
            <div class="menu-customer-service-container">
                <ul id="menu-customer-service">
                    {if $show_price_drop && !$PS_CATALOG_MODE}
                        <li class="item  menu-item menu-item-type-custom menu-item-object-custom menu-item-897">
                            <a href="{$link->getPageLink('prices-drop')|escape:'html':'UTF-8'}" title="{l s='Specials' mod='blockcms'}">
                                {l s='Specials' mod='blockcms'}
                            </a>
                        </li>
                    {/if}
                    {if $show_new_products}
                        <li class="item  menu-item menu-item-type-custom menu-item-object-custom menu-item-897">
                            <a href="{$link->getPageLink('new-products')|escape:'html':'UTF-8'}" title="{l s='New products' mod='blockcms'}">
                                {l s='New products' mod='blockcms'}
                            </a>
                        </li>
                    {/if}
                    {if $show_best_sales && !$PS_CATALOG_MODE}
                        <li class="item  menu-item menu-item-type-custom menu-item-object-custom menu-item-897">
                            <a href="{$link->getPageLink('best-sales')|escape:'html':'UTF-8'}" title="{l s='Top sellers' mod='blockcms'}">
                                {l s='Top sellers' mod='blockcms'}
                            </a>
                        </li>
                    {/if}
                    {if $display_stores_footer}
                        <li class="item  menu-item menu-item-type-custom menu-item-object-custom menu-item-897">
                            <a href="{$link->getPageLink('stores')|escape:'html':'UTF-8'}" title="{l s='Our stores' mod='blockcms'}">
                                {l s='Our stores' mod='blockcms'}
                            </a>
                        </li>
                    {/if}
                    {if $show_contact}
                        <li class="item  menu-item menu-item-type-custom menu-item-object-custom menu-item-897">
                            <a href="{$link->getPageLink($contact_url, true)|escape:'html':'UTF-8'}" title="{l s='Contact us' mod='blockcms'}">
                                {l s='Contact us' mod='blockcms'}
                            </a>
                        </li>
                    {/if}
                    {foreach from=$cmslinks item=cmslink}
                        {if $cmslink.meta_title != ''}
                            <li class="item menu-item menu-item-type-custom menu-item-object-custom menu-item-897">
                                <a href="{$cmslink.link|escape:'html':'UTF-8'}" title="{$cmslink.meta_title|escape:'html':'UTF-8'}">
                                    {$cmslink.meta_title|escape:'html':'UTF-8'}
                                </a>
                            </li>
                        {/if}
                    {/foreach}
                    {if $show_sitemap}
                        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-897">
                            <a href="{$link->getPageLink('sitemap')|escape:'html':'UTF-8'}" title="{l s='Sitemap' mod='blockcms'}">
                                {l s='Sitemap' mod='blockcms'}
                            </a>
                        </li>
                    {/if}
                </ul>
            </div>
        </div>    
    </section>
{/if}
