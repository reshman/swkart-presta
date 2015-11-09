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
{include file="$tpl_dir./errors.tpl"}
{if isset($category)}
    {if $category->id AND $category->active}
        <div class="category_header">
			 <div class="page_heading">
             	<h1 class="page-title">  {strip}
                        {$category->name|escape:'html':'UTF-8'}
                        {if isset($categoryNameComplement)}
                            {$categoryNameComplement|escape:'html':'UTF-8'}
                        {/if}
                    {/strip}</h1>
             </div>
             
             <div id="breadcrumbs">
{include file="$tpl_dir./breadcrumb.tpl"} 
             </div>
             
          
            
            <div class="clearfix"></div>

            
                        
                        
                                 <div class="grid_slider">
                    
                                                <div class="product-category-description right dark">
                           
                                 <h1> {$category->name|escape:'html':'UTF-8'}</h1>
                        {if $scenes}
                        <div class="content_scene">
                            <!-- Scenes -->
                            {include file="$tpl_dir./scenes.tpl" scenes=$scenes}
                            {if $category->description}
                                <div class="cat_desc rte">
                                    {if Tools::strlen($category->description) > 350}
                                        <div id="category_description_short">{$description_short}</div>
                                        <div id="category_description_full" class="unvisible">{$category->description}</div>
                                        <a href="{$link->getCategoryLink($category->id_category, $category->link_rewrite)|escape:'html':'UTF-8'}" class="lnk_more">{l s='More'}</a>
                                    {else}
                                        <div>{$category->description}</div>
                                    {/if}
                                </div>
                            {/if}
                        </div>
                    {else}
                        {if $category->description}
                           
                            {if Tools::strlen($category->description) > 350}
                                <div id="category_description_short" class="rte">{$description_short}</div>
                                <div id="category_description_full" class="unvisible rte">{$category->description}</div>
                                <a href="{$link->getCategoryLink($category->id_category, $category->link_rewrite)|escape:'html':'UTF-8'}" class="lnk_more">{l s='More'}</a>
                            {else}
                                {$category->description}
                            {/if}  
                        {/if}
                    {/if}                            
                                                </div>
                                           {if $category->id_image} 
                        <img class="cat-banner" src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category_default')|escape:'html':'UTF-8'}" style="right center no-repeat; background-size:cover; min-height:{$categorySize.height}px;">
                    {/if}  
                       
                    </div>
                    
            {if $scenes || $category->description || $category->id_image}
          
                {if isset($subcategories)}
                    {if (isset($display_subcategories) && $display_subcategories eq 1) || !isset($display_subcategories) }
                        <!-- Subcategories -->
                        <div id="subcategories">
                            <p class="subcategory-heading">{l s='Subcategories'}</p>
                            <ul class="clearfix">
                                {foreach from=$subcategories item=subcategory}
                                    <li>
                                        <div class="subcategory-image">
                                            <a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}" title="{$subcategory.name|escape:'html':'UTF-8'}" class="img">
                                                {if $subcategory.id_image}
                                                    <img class="replace-2x" src="{$link->getCatImageLink($subcategory.link_rewrite, $subcategory.id_image, 'medium_default')|escape:'html':'UTF-8'}" alt="" width="{$mediumSize.width}" height="{$mediumSize.height}" />
                                                {else}
                                                    <img class="replace-2x" src="{$img_cat_dir}default-medium_default.jpg" alt="" width="{$mediumSize.width}" height="{$mediumSize.height}" />
                                                {/if}
                                            </a>
                                        </div>
                                        <h5><a class="subcategory-name" href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}">{$subcategory.name|truncate:25:'...'|escape:'html':'UTF-8'|truncate:350}</a></h5>
                                            {if $subcategory.description}
                                            <div class="cat_desc">{$subcategory.description}</div>
                                        {/if}
                                    </li>
                                {/foreach}
                            </ul>
                        </div>

                    {/if}
                {/if}       
            {/if}		
		   <div class="orderby_container">
                <div class="filter_wrapper woocommerce2">
                    <div class="woocommerce-count-wrap">
                        <p class="woocommerce-result-count">
                            {include file="./category-count.tpl"}  
                        </p>
                    </div>
                    <div class="orderby_bg">            
                        <div id="toggle_sidebar"></div>
                  {include file="./product-sort.tpl"}
                            {include file="./nbr-product-page.tpl"}  
                   
                        
                    </div>
                </div>
                <div class="clearfix"></div>
            </div> 
    
  
        	</div>

        {if $products}
            <div class="row-fluid {$themesdev.td_perrow}" id="prodrow">
                <div class="span12">
                    {include file="./product-list.tpl" products=$products}
                </div>
            </div>

            {include file="./pagination.tpl"}
        {/if}
    {/if}
{/if}