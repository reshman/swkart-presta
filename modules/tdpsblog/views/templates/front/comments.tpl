{if $totalcommentsbypost>0}
    <h3 id="comments-title">{$totalcommentsbypost} {l s='Response to' mod='tdpsblog'} <em>{$blogPost.tdpost_title|stripslashes|html_entity_decode}</em></h3>
    <ol class="comments-list">
        {foreach from=$postcomments item=tdblogpost}
            <li id="li-comment-{$tdblogpost.id_tdpsblog_comments}" class="comment byuser comment-author-admin bypostauthor even thread-even depth-1">
                <div id="comment-{$tdblogpost.id_tdpsblog_comments}">
                    <img height="55" width="55" class="avatar avatar-55 photo grav-hashed grav-hijack" src="{$base_url}modules/tdpsblog/img/ad516503a11cd5ca435acc9bb6523536.png" alt="">            
                    <div class="comment-meta">
                        <h5 class="author">{$tdblogpost.comment_author_name} -  <a  href="{tdpsblogClass::postReplyLinks($blogPost.id_tdpsblog,$blogPost.link_rewrite,$tdblogpost.id_tdpsblog_comments)}#respond" class="comment-reply-link">{l s='Reply' mod='tdpsblog'}</a></h5>	
                        <p class="date">
                            {$tdblogpost.comment_date}               </p>
                    </div>
                    <div class="comment-body"><p>{$tdblogpost.comments_text}</p>
                    </div>
                    <div class="clear"></div>
                    <!-- .reply -->
                </div><!-- #comment-##  -->

                {if isset($tdblogpost.sub_comments) && is_array($tdblogpost.sub_comments)}
                    {assign var='childcomments' value=$tdblogpost.sub_comments}
                    {include file="$comments_tree_branch" node=$childcomments}
                {/if}
            </li><!-- #comment-## -->
        {/foreach}
    </ol>
{/if}