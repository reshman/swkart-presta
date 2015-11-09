{capture name=path}{l s='Blog' mod='tdpsblog'}{/capture}
{include file="$tpl_dir./errors.tpl"}
       

                                                  
<div class="mpcth-products-info">

{if Tools::getValue('tdcatid')&& count($categorynamebyid)}
    <h3 class="page-title">{l s='Category Archives:' mod='tdpsblog'} <span>{$categorynamebyid.category_name}</span></h3>
{/if}
{if $tdblogpost}
         {foreach from=$tdblogpost item=blogPost}                                  
        <article id="post-{$blogPost.id_tdpsblog}" class="article ">
            <div class="post-{$blogPost.id_tdpsblog} type-post status-publish format-standard hentry category-blanco-category category-demo-category instock">
               <h3 class="article-title"><a href="{tdpsblogClass::postlinks($blogPost.id_tdpsblog,$blogPost.link_rewrite)}" title="{$blogPost.tdpost_title|stripslashes|html_entity_decode}">{$blogPost.tdpost_title|stripslashes|html_entity_decode}</a></h3>
               <a href="{tdpsblogClass::postlinks($blogPost.id_tdpsblog,$blogPost.link_rewrite)}" class="article-image"><img src="{$base_url}{$blogPost.image_url|html_entity_decode}" /></a> <!-- 888/292 -->   
            	                                   
               <div class="entry-utility">
                    <span class="meta-prep meta-prep-author">{l s='Posted on' mod='tdpsblog'}</span> 
                    <a href="{tdpsblogClass::postlinks($blogPost.id_tdpsblog,$blogPost.link_rewrite)}" title="{$blogPost.tdpost_dete}" rel="bookmark"><span class="entry-date">{$blogPost.tdpost_dete}</span></a> /
        
        				        					<span class="cat-links">
        						<span class="entry-utility-prep entry-utility-prep-cat-links">{l s='Posted in' mod='tdpsblog'}</span> <a href="{tdpsblogClass::catlinks($blogPost.tdpost_category,$blogPost.cat_rewrite)}" title="" >{$blogPost.category_name}</a>      					</span>
        					<span class="meta-sep">|</span>
        				        	<span class="comments-link"><a href="{tdpsblogClass::postlinks($blogPost.id_tdpsblog,$blogPost.link_rewrite)}#respond" title="{$blogPost.tdpost_title|stripslashes|html_entity_decode}">{l s='Leave a comment' mod='tdpsblog'}</a></span>
        	</div><!-- .entry-utility -->  
            	            	            			<div class="entry-content">
            				<p>{$blogPost.tdpost_content|html_entity_decode|strip_tags|truncate:302:''}<a href="{tdpsblogClass::postlinks($blogPost.id_tdpsblog,$blogPost.link_rewrite)}" class="more-link"><br />
                                                <button class="button active fl-r" name="SubmitCreate" id="SubmitCreate" type="button"><span><span>{l s='READ MORE' mod='tdpsblog'}</span></span></button>
                                                <br /></a></p>
            				            			</div><!-- .entry-content -->
            	              
                <div class="clear"></div>
	     </div>
        </article>   

	  {/foreach}  
      {else}
          <span>{l s='No blog post at this time.' mod='tdpsblog'}</span>
{/if}
{include file="$pagination"}

      </div> 

   

   
   
  	
