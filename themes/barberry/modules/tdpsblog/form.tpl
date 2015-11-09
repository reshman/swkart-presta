<script src="{$base_url}modules/tdpsblog/js/jquery.validate.js"></script>
<script>
    $().ready(function() {
        // validate the comment form when it is submitted
        $("#commentform").validate();
    });
</script>
<style>
    #commentform label.error{ display:none!important}
</style>
<div class="comment-respond" id="respond">

    <h3 class="comment-reply-title" id="reply-title"><span>{l s='Leave a Comment' mod='tdpsblog'} </span>{if isset($replytocom) && $replytocom!=0}<small><a href="{tdpsblogClass::postlinks($blogPost.id_tdpsblog,$blogPost.link_rewrite)}" id="cancel-comment-reply-link" rel="nofollow">{l s='Cancel reply' mod='tdpsblog'}</a></small> {/if}</h3>

    <form class="comment-form" id="commentform" method="post" >
        <div id="commentsMsgs">
            {if $confirmation==1}
                <p class="success">{l s='Your comment is awaiting moderation.' mod='tdpsblog'}</p>
            {elseif $confirmation==2}
                <p class="error"> {l s='Please, fill in the required fields!' mod='tdpsblog'} </p>
            {/if}
        </div>
        {if isset($id_customer) && $id_customer!=''}<div style="display:none">{/if}
            <div class="formField comment-form-author"><label for="author">{l s='Your Name' mod='tdpsblog'}<span class="required">*</span></label>
                <input type="text" size="30" class="required-field" value="{if isset($name_customer) && $name_customer!=''}{$name_customer}{/if}" name="author" id="author" required><div class="clear"></div></div><!-- #form-section-author .form-section -->
            <div class="formField comment-form-email"><label for="email">{l s='Your Email' mod='tdpsblog'}<span class="required">*</span></label>
                <input type="email" size="30" value="{if isset($email_customer) && $email_customer!=''}{$email_customer}{/if}" class="required-field" name="email" id="email" required><div class="clear"></div></div><!-- #form-section-email .form-section -->
            {if isset($id_customer) && $id_customer!=''}</div>{/if}
        <div class="formField comment-form-comment"><label for="comment">{l s='Comment' mod='tdpsblog'}<span class="required">*</span></label><textarea class="required-field" rows="8" cols="45" name="comment" id="comment" required></textarea><div class="clear"></div></div><!-- #form-section-comment .form-section -->												

        <p class="form-submit">
            <input type="submit" value="Post Comment" id="submit" name="submit" >
            <input type="hidden" id="comment_post_ID" value="{$blogPost.id_tdpsblog}" name="comment_post_id">
            <input type="hidden" value="{if isset($replytocom)}{$replytocom}{else}0{/if}" id="comment_parent" name="comment_parent">
        </p>
    </form>
</div><!-- #respond -->
