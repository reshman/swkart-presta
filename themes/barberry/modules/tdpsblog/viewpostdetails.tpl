   {capture name=path}{l s='Blog' mod='tdpsblog'}{/capture}
	{include file="$tpl_dir./errors.tpl"}
     {foreach from=$postdata item=blogPost} 
         {$timestamp=$blogPost.tdpost_dete}
         {assign var='tdpostdate' value=date('F d, Y', strtotime ($timestamp))}
         {assign var='countpost' value=count(tdpsblogModel::getTotalCommentsByPost($blogPost.id_tdpsblog))}
         
            <article id="post-{$blogPost.tdpost_title|strip_tags}" class="article-single">
                <h2 class="article-title">{$blogPost.tdpost_title|stripslashes|html_entity_decode}</h2>
                <div class="article-image">
                    <img  alt="{$blogPost.tdpost_title|strip_tags}" class="attachment-700x700 wp-post-image" src="{$base_url}{$blogPost.image_url|html_entity_decode}"> 
                </div>
                        		<div class="article-meta">
                    <span class="meta-prep meta-prep-author">{l s='Posted on' mod='tdpsblog'}</span> <a rel="bookmark" title="{$blogPost.tdpost_title|strip_tags}" href="{tdpsblogClass::postlinks($blogPost.id_tdpsblog,$blogPost.link_rewrite)}"><span class="entry-date">{$tdpostdate}</span></a> / 
                                        {l s='This entry was posted in ' mod='tdpsblog'}<a title="" href="{tdpsblogClass::catlinks($blogPost.tdpost_category,$blogPost.cat_rewrite)}">{$blogPost.category_name}</a>. {l s='Bookmark the' mod='tdpsblog'} <a rel="bookmark" title="{$blogPost.tdpost_title|stripslashes|html_entity_decode}" href="{tdpsblogClass::catlinks($blogPost.tdpost_category,$blogPost.cat_rewrite)}">{l s='permalink' mod='tdpsblog'}</a>.        		</div>   
                                     
                <div class="article-description">
            {$blogPost.tdpost_content|html_entity_decode}
		 </div>
                <div class="clear"></div>
            </article>
   {/foreach}   
 <div id="comments">
           {include file="$comments_tmp" node=$postcomments}
          {include file="$comments_form"}
</div>