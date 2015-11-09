
function popWin(url,win,para) {
    var win = window.open(url,win,para);
    win.focus();
}

function setLocation(url){
    window.location.href = url;
}

function setPLocation(url, setFocus){
    if( setFocus ) {
        window.opener.focus();
    }
   // window.opener.location.href = url;
}
function showPopup(){
    html = '<div class="tdtheme-popup-overlay"></div><div class="tdtheme-popup"><div class="tdtheme-popup-content"></div></div>'
    jQuery('body').prepend(html);
    popupOverlay = jQuery('.tdtheme-popup-overlay');
    popupWindow = jQuery('.tdtheme-popup');
    popupOverlay.one('click',function(){
        hidePopup(popupOverlay,popupWindow);
    });
}
function hidePopup(popupOverlay,popupWindow){
    popupOverlay.fadeOut(400);
    popupWindow.fadeOut(400).html('');
}
 function CompareCart(idProduct)
{
   $.ajax({
      url: 'index.php?controller=products-comparison&ajax=1&action=add&id_product=' + idProduct,
     async: true,
                            dataType: "json",
                            success: function(responseData) {
                                        if(responseData==0){   
                                        
                                             showPopup();        
                    productImageSrc = jQuery('.main-image_'+idProduct+' img').attr('src');                    
                    productImage = '<img width="72" src="'+productImageSrc+'" />';                    
                    //productName = jQuery('.main-image_'+idProduct+' img').attr('alt');                    
                    //cartHref = jQuery('#top-cart > a').attr('href');                    
                    popupHtml = productImage + notsuccessfullyaddcompare;
                    popupWindow.find('.tdtheme-popup-content').css('backgroundImage','none').html(popupHtml);                    
                    jQuery('.cont-shop').one('click',function(){
                        hidePopup(popupOverlay,popupWindow);
                    });       

                                
                                    }
                                    else{
                                     showPopup();        
                    productImageSrc = jQuery('.main-image_'+idProduct+' img').attr('src');                    
                    productImage = '<img width="72" src="'+productImageSrc+'" />';                    
                    productName = jQuery('.main-image_'+idProduct+' img').attr('alt');                    
                    //cartHref = jQuery('#top-cart > a').attr('href');                    
                    popupHtml = productImage + '<em>'+productName+'</em> ' + successfullycompareaddsuccess;
                    popupWindow.find('.tdtheme-popup-content').css('backgroundImage','none').html(popupHtml);                    
                    jQuery('.cont-shop').one('click',function(){
                        hidePopup(popupOverlay,popupWindow);
                    });  
                               
                                }
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                               
                            }
  }); 
  
}
$(document).ready(function() {
	// IE6 & IE7 Fixes
	if ($.browser.msie) {
		if ($.browser.version <= 6) {
			$('#column-left + #column-right + #content, #column-left + #content').css('margin-left', '195px');
			
			$('#column-right + #content').css('margin-right', '195px');
		
			$('.box-category ul li a.active + ul').css('display', 'block');	
		}
		
		if ($.browser.version <= 7) {
			$('#menu > ul > li').bind('mouseover', function() {
				$(this).addClass('active');
			});
				
			$('#menu > ul > li').bind('mouseout', function() {
				$(this).removeClass('active');
			});	
		}
	}
	
	$('.success img, .warning img, .attention img, .information img').live('click', function() {
		$(this).parent().fadeOut('slow', function() {
			$(this).remove();
		});
	});	
});



  