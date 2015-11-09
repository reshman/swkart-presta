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

{include file="$tpl_dir./errors.tpl"}

{if !isset($errors) OR !sizeof($errors)}
	
	
<div class="category_header">
			 <div class="page_heading">
             	<h1 class="page-title"> {l s='List of products by supplier:'}&nbsp;{$supplier->name|escape:'html':'UTF-8'}</h1>
             </div>
             
             <div id="breadcrumbs">
{include file="$tpl_dir./breadcrumb.tpl"} 
             </div>
              <div class="clearfix"></div>
          {if !empty($supplier->description)}
		<div class="description_box rte">
			<p>{$supplier->description}</p>
		</div>
	{/if}
            
            <div class="clearfix"></div>

  	
		   <div class="orderby_container">
                <div class="filter_wrapper woocommerce2">
                    <div class="woocommerce-count-wrap">
                        <p class="woocommerce-result-count">
                            
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
	{else}
		<p class="alert alert-warning">{l s='No products for this supplier.'}</p>
	{/if}
{/if}
