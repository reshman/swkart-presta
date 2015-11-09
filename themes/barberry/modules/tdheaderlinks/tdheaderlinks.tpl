<div class="header-switch nav-switch">		
<span class="current">{l s='My Account'  mod='tdheaderlinks'}</span>
<div class="header-dropdown">
<ul>		
<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="{$link->getPageLink('my-account', true)}" title="{l s='My Account' mod='tdheaderlinks'}" >{l s='My Account' mod='tdheaderlinks'}</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="{$wishlist_link}" title="{l s='Wishlist' mod='tdheaderlinks'}">{l s='Wishlist' mod='tdheaderlinks'}</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="{$link->getPageLink($order_process, true)}"  title="{l s='Checkout' mod='tdheaderlinks'}">{l s='Checkout' mod='tdheaderlinks'}</a></li>
    	
    {if $is_logged}
            <li class="menu-item menu-item-type-post_type menu-item-object-page">
		<a class="logout" href="{$link->getPageLink('index', true, NULL, "mylogout")|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Log me out' mod='tdheaderlinks'}">
			{l s='Sign out' mod='tdheaderlinks'}
		</a>
            </li>
	{else}
            <li class="menu-item menu-item-type-post_type menu-item-object-page">
		<a class="login" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Log in to your customer account' mod='tdheaderlinks'}">
			{l s='Sign in' mod='tdheaderlinks'}
		</a>
            </li>
	{/if}
</ul>
</div>
</div>

