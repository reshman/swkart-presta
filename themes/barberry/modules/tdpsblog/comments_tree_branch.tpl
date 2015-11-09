<ul>
    {foreach from=$childcomments item=sumcommers}
        <li id="li-comment-{$sumcommers.id_tdpsblog_comments}" class="children byuser comment-author-admin bypostauthor even thread-even depth-1">
            <div id="comment-{$sumcommers.id_tdpsblog_comments} " >
                <img height="55" width="55" class="avatar avatar-55 photo grav-hashed grav-hijack" src="{$base_url}modules/tdpsblog/img/ad516503a11cd5ca435acc9bb6523536.png" alt="" >            <div class="comment-meta">
                    <h5 class="author">{$sumcommers.comment_author_name} -  <a href=""{tdpsblogClass::postReplyLinks($blogPost.id_tdpsblog,$blogPost.link_rewrite,$tdblogpost.id_tdpsblog_comments)}#respond" class="comment-reply-link">{l s='Reply' mod='tdpsblog'}</a></h5>	
                    <p class="date">
                        {$sumcommers.comment_date}               </p>
                </div>
                <div class="comment-body"><p>{$sumcommers.comments_text}</p>
                </div>
                <div class="clear"></div>
                <!-- .reply -->
            </div><!-- #comment-##  -->
        </li><!-- #comment-## -->
    {/foreach}
</ul>