<!DOCTYPE html>
<html lang="{$lang_iso}">
<!--[if lt IE 7 ]><html class="ie ie6" lang="{$lang_iso}"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="{$lang_iso}"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="{$lang_iso}"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><!--<![endif]-->
    <head>
        <meta charset="utf-8" />
        <title>{$meta_title|escape:'html':'UTF-8'}</title>
        {if isset($meta_description) AND $meta_description}
            <meta name="description" content="{$meta_description|escape:'html':'UTF-8'}" />
        {/if}
        {if isset($meta_keywords) AND $meta_keywords}
            <meta name="keywords" content="{$meta_keywords|escape:'html':'UTF-8'}" />
        {/if}
        <meta name="generator" content="PrestaShop" />
        <meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow" />
        <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" /> 
        <meta name="apple-mobile-web-app-capable" content="yes" /> 
        <link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
        <link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />
        <!-- Basic Page Needs
        ================================================== -->

        <link href="https//fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700" rel="stylesheet" type="text/css" media="all" />

        {if isset($css_files)}
            {foreach from=$css_files key=css_uri item=media}
                <link rel="stylesheet" href="{$css_uri|escape:'html':'UTF-8'}" type="text/css" media="{$media|escape:'html':'UTF-8'}" />
            {/foreach}
        {/if}
        {if isset($js_defer) && !$js_defer && isset($js_files) && isset($js_def)}
            {$js_def}
            {foreach from=$js_files item=js_uri}
                <script type="text/javascript" src="{$js_uri|escape:'html':'UTF-8'}"></script>
            {/foreach}
        {/if}
        <!-- ******************************************************************** -->
        <!-- Custom CSS Styles -->
        <script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false&#038;ver=4.2.1'></script>
        {include file="$tpl_dir./custom_options.tpl"}
        {$HOOK_HEADER}
    </head>
    <body{if isset($page_name)} id="{$page_name|escape:'html':'UTF-8'}"{/if} class="{if isset($page_name)}{$page_name|escape:'html':'UTF-8'}{/if}{if isset($body_classes) && $body_classes|@count} {implode value=$body_classes separator=' '}{/if}{if $hide_left_column} hide-left-column{/if}{if $hide_right_column} hide-right-column{/if}{if isset($content_only) && $content_only} content_only{/if} lang_{$lang_iso} home page page-id-44 page-template page-template-page-full-width-php woocommerce woocommerce-page gecko">

        {if !isset($content_only) || !$content_only}
            <div class="wrapper_header {$themesdev.td_contentcol} {$themesdev.td_headerstyle}">
                <div id="page_wrapper" class="{if ($themesdev.td_slider_type=="fullslider") && ($page_name=='index')  && ($themesdev.td_hslider=="contentslider")} fullslider_tb {/if} " >
                    <div id="header_topbar">
                        <div class="container">
                            <div class="row-fluid">
                                <div class="span8 info">
                                    <div class="topbarmenu">
                                        {$themesdev.td_htoplinks|html_entity_decode}
                                    </div>                        </div>
                                <div class="span4 social">
                                    <ul id="social-icons">
                                        {if $themesdev.td_facebook_url !=''}<li class="facebook"><a  title="{l s='Facebook'}" href="{$themesdev.td_facebook_url}" target="_blank"></a></li>{/if}
                                        {if $themesdev.td_twitter_url !=''}<li class="twitter"><a  title="{l s='Twitter'}" href="{$themesdev.td_twitter_url}" target="_blank"></a></li>{/if}
                                        {if $themesdev.td_google_url !=''}<li class="googleplus"><a  title="{l s='Google Plus'}" href="{$themesdev.td_googleplus}" target="_blank"></a></li>{/if}
                                        {if $themesdev.td_pinteres_url !=''}<li class="pinterest"><a  title="{l s='Pinterest'}" href="{$themesdev.td_pinteres_url}" target="_blank"></a></li>{/if}
                                        {if $themesdev.td_youtube_url !=''}<li class="youtube"><a  title="{l s='Youtube'}" href="{$themesdev.td_youtube_url}" target="_blank"></a></li>{/if}

                                    </ul>            
                                </div>
                            </div>
                        </div>
                    </div>  
                             {if $themesdev.td_stickymenu=="enable"}          
                    <div id="sticky-menu" class="clearfix">
                        <div class="container clearfix">
                            <div id="navigations" class="nav tdmenu sf-menu-top">
                                <select class="main-menu-mobile hasCustomSelect" style="width: 196px; position: absolute; opacity: 0; height: 52px; font-size: 14px;">
                                    {$tdMobleMENU}  
                                </select>
                                <span class="customSelect mobile_menu_select main-menu-mobile" style="width: 194px; display: inline-block;"><span class="customSelectInner" style="width: 192px; display: inline-block;">Navigation</span></span>
                                {if $tdMENU != ''}
                                    <ul class="menu sf-menu" id="smenu">
                                        {$tdMENU}
                                    </ul> 
                                    <script type="text/javascript">
                                        $('#smenu').dcMegaMenu({
                                            rowItems: '6',
                                            speed: 'first'
                                        });
                                    </script>
                                {/if}
                            </div>
                        </div>
                    </div>
                        {/if}
                    <div id="header">
                        <div class="container">
                            <div class="header_box">
                                <div class="header_container">
                                    {$themesdev.td_cus_info|html_entity_decode}
                                    <div class="rightnav">
                                        {hook h="displayNav"}
                                    </div>
                                    <!-- /Block currencies module -->
                                {if isset($HOOK_TOP)}{$HOOK_TOP}{/if}
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div> 
                {if $page_name =='index'}   
             
                                {if $themesdev.td_slider_type=="standardslider"}
                                      <div class="container">
            <div class="row">
                 {/if} 
                    <div class="fullwidth  {if $themesdev.td_slider_type=="standardslider"}span12{/if}">
                               <div class="container">
            <div class="row">
                <div class="span12">    
                            </div>
            </div>
        </div>
                        <article class="post-44 page type-page status-publish hentry">              
                            <div class="entry-content">
                                <div class="content_wrapper four_side shopproductlist">
                                   
                                    {hook h="displayTopColumn"}
                                    <div style="margin-top:0px;margin-bottom:25px" class="divider"></div>
                                {/if}  

                                {if $page_name!='index' && $page_name!='contact' && $page_name!='sitemap' && $page_name!='stores'  && $page_name!='product'}               
                                 <div class="container headerline"></div>
                                    <hr class="paddingbottom30">
                                {/if}    
                                <div class="clearfix"></div> 
                                <div class="columns-container container">
                                    <div id="columns" class="row {if $HOOK_LEFT_COLUMN}side_left{elseif $HOOK_RIGHT_COLUMN}side_right{/if}">  

                                        <div id="primary" class="{if $HOOK_LEFT_COLUMN}side_left span9 sidebar{elseif $HOOK_RIGHT_COLUMN}side_right span9 sidebar{else}span12{/if}">
                                            <div id="center_column">
                                                {if $page_name =='index'}
                                                    {$themesdev.td_sc_banner|html_entity_decode}
                                                {/if}     
                                            {/if} 