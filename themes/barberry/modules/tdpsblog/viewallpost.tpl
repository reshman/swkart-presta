{capture name=path}{l s='Blog' mod='tdpsblog'}{/capture}
{include file="$tpl_dir./errors.tpl"}
{if Tools::getValue('tdcatid')&& count($categorynamebyid)}
    <h3 class="page-title">{l s='Category Archives:' mod='tdpsblog'} <span>{$categorynamebyid.category_name}</span></h3>
{/if}
{if $tdblogpost}
    {foreach from=$tdblogpost item=blogPost}                                  
        {$timestamp=$blogPost.tdpost_dete}
        {assign var='tdpostdate' value=date('F d, Y', strtotime ($timestamp))}
        {assign var='date' value=date('d', strtotime ($timestamp))}
        {assign var='monthe' value=date('M', strtotime ($timestamp))}
        {assign var='countpost' value=count(tdpsblogModel::getTotalCommentsByPost($blogPost.id_tdpsblog))}
        <article class="post-821 post type-post status-publish format-standard has-post-thumbnail hentry category-fashion-news tag-beauty tag-summer tag-woocommerce entry clearfix" id="post-821">                                                             
            <div class="blog_list">
                <div class="entry_info">
                    <div class="entry_date">
                        <span>{$date}</span>{$monthe}</div>    
                </div>
                <div class="entry_post">
                    <h2 title="{$blogPost.tdpost_title|stripslashes|html_entity_decode}"><a rel="bookmark" title="{$blogPost.tdpost_title|stripslashes|html_entity_decode}" href="{tdpsblogClass::postlinks($blogPost.id_tdpsblog,$blogPost.link_rewrite)}">{$blogPost.tdpost_title|stripslashes|html_entity_decode}</a></h2>
                    <div class="entry_meta clearfix">
                        <ul>        	
                            <li><span>{l s='Posted in' mod='tdpsblog'}</span> <a rel="category tag" href="{tdpsblogClass::catlinks($blogPost.tdpost_category,$blogPost.cat_rewrite)}">{$blogPost.category_name}</a></li>
                            <li class="date_show"><span>{l s='Posted on' mod='tdpsblog'}</span> {$tdpostdate}
                            </li>
                            <!--<li>Tags: <a rel="tag" href="http://barberry.temashdesign.com/tag/beauty/">Beauty</a>, <a rel="tag" href="http://barberry.temashdesign.com/tag/summer/">Summer</a>, <a rel="tag" href="http://barberry.temashdesign.com/tag/woocommerce/">Woocommerce</a></li>-->
                        </ul>
                    </div>
                    <!-- Standart Post --> 
                    <div class="entry_image single_image">
                        <a href="{tdpsblogClass::postlinks($blogPost.id_tdpsblog,$blogPost.link_rewrite)}">
                            <img width="860" height="450" title="{$blogPost.tdpost_title|stripslashes|html_entity_decode}" alt="{$blogPost.tdpost_title|stripslashes|html_entity_decode}" src="{$base_url}{$blogPost.image_url|html_entity_decode}">
                        </a>
                    </div>
                    <div class="entry-content">
                        <p>{$blogPost.tdpost_content|html_entity_decode|strip_tags|truncate:302:''}<p><a class="more-link" href="{tdpsblogClass::postlinks($blogPost.id_tdpsblog,$blogPost.link_rewrite)}">
                                <span class="moretag">{l s='Read more' mod='tdpsblog'}</span></a></p>
                    </div><!-- .entry-content -->
                    <div class="entry-meta-foot">
                        <ul>
                            <!--<li class="author">By <a href="http://barberry.temashdesign.com/author/admin/">Barberry Stuff</a></li>-->
                            <li class="leave_comm"><span class="comments-link"><a title="{$blogPost.tdpost_title|stripslashes|html_entity_decode}" href="{tdpsblogClass::postlinks($blogPost.id_tdpsblog,$blogPost.link_rewrite)}#respond">{$countpost} {l s='Comments' mod='tdpsblog'}</a></span></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div><!-- .entry-meta-foot -->  
                </div> <!-- .entry-post -->    
                <div class="clearfix"></div>
            </div>
        </article>
    {/foreach}  
{else}
    <span>{l s='No blog post at this time.' mod='tdpsblog'}</span>
{/if}
{include file="$pagination"}
