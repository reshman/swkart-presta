   {capture name=path}{l s='Blog' mod='tdpsblog'}{/capture}
	{include file="$tpl_dir./errors.tpl"}
      <div class="mpcth-products-info">                
    <div class="clear"></div>
             {foreach from=$postdata item=blogPost} 
      
            <article id="post-{$blogPost.tdpost_title}" class="article-single">
                <h2 class="article-title">{$blogPost.tdpost_title|stripslashes|html_entity_decode}</h2>
                <div class="article-image">
                    <img alt="2" class="attachment-700x700 wp-post-image" src="{$base_url}{$blogPost.image_url|html_entity_decode}"> 
                </div>
                        		<div class="article-meta">
                    <span class="meta-prep meta-prep-author">{l s='Posted on' mod='tdpsblog'}</span> <a rel="bookmark" title="{$blogPost.tdpost_title|stripslashes|html_entity_decode}" href="{tdpsblogClass::postlinks($blogPost.id_tdpsblog,$blogPost.link_rewrite)}"><span class="entry-date">{$blogPost.tdpost_dete}</span></a> / 
                                        {l s='This entry was posted in ' mod='tdpsblog'}<a title="" href="{tdpsblogClass::catlinks($blogPost.tdpost_category,$blogPost.cat_rewrite)}">{$blogPost.category_name}</a>. {l s='Bookmark the' mod='tdpsblog'} <a rel="bookmark" title="{$blogPost.tdpost_title|stripslashes|html_entity_decode}" href="{tdpsblogClass::postlinks($blogPost.id_tdpsblog,$blogPost.link_rewrite)}">{l s='permalink' mod='tdpsblog'}</a>.        		</div>   
                                     
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

   
</div><!----mpcth-products-info--->


        
			

