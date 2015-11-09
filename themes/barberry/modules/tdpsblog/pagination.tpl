<div class="toolbar bottom">
            	<div class="pagintaion">
	<ul class="page-numbers">
{if $num_pages>1}
            {$psblogdisplypage}
      {/if}       
</ul>

</div>    
{if $num_pages>1}
   <div class="pageinfo"> {l s='Page' mod='tdpsblog'} {$current_page} {l s='of' mod='tdpsblog'} {$num_pages}</div>
 {/if}
   <div class="clear"></div>
            </div>