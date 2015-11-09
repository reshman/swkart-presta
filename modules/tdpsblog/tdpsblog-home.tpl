{if $blogtdrecentpost}
<div class="mpcth-vc-row-wrap  wpb_animate_when_almost_visible wpb_bottom-to-top wpb_start_animation">
    <div class="wpb_row vc_row-fluid">
	<div class="vc_span12 wpb_column column_container">
	     <div class="wpb_wrapper">
		  <h5 class="mpc-vc-deco-header">
                      <span class="mpcth-color-main-border">{l s='From The Blog' mod='tdpsblog'}</span>
                  </h5>
                  <div class="mpcth-waypoint mpcth-items-slider-wrap mpcth-waypoint-triggered">
                       <div class="mpcth-items-slider-container-wrap">
                            <div class="mpcth-items-slider-container">
                                 <div class="caroufredsel_wrapper">
                                     <div class="mpc-vc-blog-posts-slider mpcth-items-slider mpcth-items-slider-wide">
                                         
                                           {$i=0}
                                            {$j=1}
                                             {foreach from=$blogtdrecentpost item=recentpost} 
                                                         {$timestamp=$recentpost.tdpost_dete}
                                                        {assign var='monthyear' value=date('M Y', strtotime ($timestamp))}
        
                                                        {assign var='date' value=date('d', strtotime ($timestamp))}
                                                             {assign var='countpost' value=count(tdpsblogModel::getTotalCommentsByPost($recentpost.id_tdpsblog))}
                                              {if $i%2==0}   
                                        
                                         <div class="mpcth-slide-wrap">
                                             {/if}
                                             
                                                        
                                             <a class="mpcth-slide mpcth-slide-row-gap" href="{tdpsblogClass::postlinks($recentpost.id_tdpsblog,$recentpost.link_rewrite)}">
                                               
                                                 <img width="600" height="450" class="attachment-mpcth-horizontal-columns-2 wp-post-image" src="{$base_url}{$recentpost.image_url|html_entity_decode}" scale="0">
                                                 <div class="mpcth-slide-wrapper">
                                                     <h4 class="mpcth-slide-title">{$recentpost.tdpost_title}</h4>
                                                     <time datetime="{$date}{'&nbsp;'}{$monthyear}" class="mpcth-slide-time">{$date}{'&nbsp;'}{$monthyear}</time>
                                                     <p class="mpcth-slide-text">{$recentpost.tdpost_content|html_entity_decode|strip_tags|truncate:202:'....'}</p>
                                                     <div class="mpcth-slide-trim"></div>
                                                 </div>
                                             </a>
                                                 {if $j%2==0}
                                           </div>
                                           {/if} 
                                           {$i=$i+1}
                                          {$j=$j+1}
                                          
                                                {/foreach}  
                                            
                                            
                                            
                                         
                                     </div>
                                 </div>
                            </div>
                       </div>
                      <a class="mpcth-items-slider-next mpcth-color-main-color" href="#" style="display: block;">
                          <i class="fa fa-angle-right"></i>
                      </a>
                      <a class="mpcth-items-slider-prev mpcth-color-main-color" href="#" style="display: block;">
                          <i class="fa fa-angle-left"></i>
                      </a>
                  </div>
	     </div> 
	</div> 
    </div>
</div>
 {else}
          <span>{l s='Not found Blog Post' mod='tdpsblog'}</span>
{/if}
               
   
 