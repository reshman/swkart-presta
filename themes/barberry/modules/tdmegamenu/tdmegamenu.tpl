 <div class="mobile_navbox">
                <div class="nav tdmenu sf-menu-top" id="navigation">
                     <select class="main-menu-mobile">
                        {$tdMobleMENU}  
</select>
                    {if $tdMENU != ''}
                    <ul id="menu" class="menu sf-menu">
                        
                         {$tdMENU}
                       </ul>
                       {/if}
                    </div> 
                </div>
                
               <div class="clearfix"></div>

  <script type="text/javascript">
   $('#menu').dcMegaMenu({
        rowItems: '6',
        speed: 'first'
    });
</script>
