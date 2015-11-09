{if $page_name=="module-tdpsblog-default" || $page_name=="module-tdpsblog-details"}
{if $tdblogallcat}
    <div class="widget widget_categories" id="categories-2">
        <h1 class="widget-title">{l s='Categories' mod='tdpsblog'}</h1>		
        <ul>
             {foreach from=$tdblogallcat item=blogcat} 

                <li class="cat-item cat-item-{$blogcat.id_tdpsblog_category}"><a title="{$blogcat.category_name|escape:html:'UTF-8'}" href="{tdpsblogClass::catlinks($blogcat.id_tdpsblog_category,$blogcat.cat_rewrite)}">{$blogcat.category_name|escape:html:'UTF-8'}</a>
                </li>
            {/foreach} 
	
        </ul>
</div>
   
{/if}   
{if $blogtdrecentpost}
    <div class="widget posts-widget" id="posts_widget-2">
        <h1 class="widget-title">{l s='Recent Posts' mod='tdpsblog'}</h1>
        <ul class="clearfix">
               {foreach from=$blogtdrecentpost item=recentpost}
                {$rtimestamp=$recentpost.tdpost_dete}
                {assign var='tdrecentpostdate' value=date('d M', strtotime ($rtimestamp))}
                 {assign var='countpost' value=count(tdpsblogModel::getTotalCommentsByPost($recentpost.id_tdpsblog))}
			<li class="clearfix">
            
            
                <a href="{tdpsblogClass::postlinks($recentpost.id_tdpsblog,$recentpost.link_rewrite)}" class="post_image">
                    <img width="80" height="80" alt="{$recentpost.tdpost_title}" src="{$base_url}{$recentpost.image_url|html_entity_decode}"><div class="item-overlay"></div></a>
     



                <div class="post_block">

                    <div class="post_title"><a title="{$recentpost.tdpost_title}" href="{tdpsblogClass::postlinks($recentpost.id_tdpsblog,$recentpost.link_rewrite)}">{$recentpost.tdpost_title}</a></div>

                    <div class="post_meta">

                       {$tdrecentpostdate} <a href="{tdpsblogClass::postlinks($recentpost.id_tdpsblog,$recentpost.link_rewrite)}#respond">{$countpost} {l s='Comments' mod='tdpsblog'}</a>

                    </div>

                </div>

            </li>
    
            {/foreach} 
        </ul>        
		

			<div class="clear"></div>
            
        </div>

{/if} 
{if $blogtdrecentcomments}
    <div class="widget widget_recent_comments" id="recent-comments-3">
        <h1 class="widget-title">{l s='Recent Comments' mod='tdpsblog'}</h1>
        <ul id="recentcomments">
            <li class="recentcomments">
                <span class="comment-author-link">{$blogtdrecentcom.comment_author_name}</span> {l s='on' mod='tdpsblog'} 
                <a href="{tdpsblogClass::postlinks($blogtdrecentcom.id_tdpsblog,$blogtdrecentcom.link_rewrite)}">{$blogtdrecentcom.comments_text}</a>
            </li>
        </ul>
    </div>
{/if} 
{/if}