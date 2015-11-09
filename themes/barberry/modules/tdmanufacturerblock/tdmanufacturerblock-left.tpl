<div class="widget widget_wp_widget_custom_brands" id="wp_widget_custom_brands-2">
    <h1 class="widget-title">{l s='Brands'}</h1>	
    <ul class="product_list_widget">

{foreach from=$manufacturer item=manuf name=manuf}   
        <li class="show_logo_li">
            <a href="{$link->getmanufacturerLink($manuf.id_manufacturer, $manuf.link_rewrite)}" title="{$manuf.name}">
                <img width="32" height="32" src="{$img_manu_dir}{$manuf.id_manufacturer}-medium_default.jpg" alt="{$manuf.name}" class="attachment-shop_thumbnail wp-post-image">

                <span class="show_logo">{$manuf.name}</span></a>
        </li>
 {/foreach}
    </ul>
		</div>