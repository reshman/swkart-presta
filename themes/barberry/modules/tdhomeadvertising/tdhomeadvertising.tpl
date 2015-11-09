<div class="main-container col1-layout">                                                              
<div class="banner-static">
<div class="container">
<div class="contain-size">
<div class="row-fluid">

{foreach from=$advertisedata item=slider}
<div class="banner-box banner-box1 span4">
<div class="banner-box-inner">
 <a href="{$slider.image_link}" {if ($slider.new_page==1)} target="_blank" {/if}><img src="{$base_url}{$slider.image_url}" alt="{$slider.image_title}" /></a>
</div>
</div>

    {/foreach}
</div>
</div>
</div>
</div>
</div>
