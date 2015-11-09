/**
 * Prints out the inline javascript needed for the colorpicker and choosing
 * the tabs in the panel.
 */
jQuery(document).ready(function($) {
       	$(".td-radio-box-image").click(function(){
            $(this).parent().parent().find(".td-radio-box-image").removeClass("add-radio-image");
            $(this).addClass("add-radio-image");
	});
        $("#lefttablinks li a").click(function(evt){
            var optiongroup = $(this).attr("href");
             $(".td-tab-panel").hide();
            $("#lefttablinks li").removeClass("active");
            $(this).parent().addClass("active");								
            $(optiongroup).fadeIn("fast");
            return false;				
	});
        $(".td-radio-tile-img").click(function(){
            $(this).parent().parent().find(".td-radio-tile-img").removeClass("td-radio-tile-selected");
            $(this).addClass("td-radio-tile-selected");
	});
        $(".td-tab-panel").hide();
        $(".td-tab-panel:first").fadeIn(); 
        $("#lefttablinks li:first").addClass("active");
        $(".td-radio-image-label").hide();
	$(".td-radio-box-image").show();
	$(".td-radio-box-image-radio").hide();;
	$(".td-radio-tile-img").show();
	$(".td-radio-tile-radio").hide();
        styleSelect = {
		init: function () {
		$(".td-select-box").each(function () {
			$(this).prepend("<span>" + $(this).find(".select option:selected").text() + "</span>");
		});
		$(".select").live("change", function () {
			$(this).prev("span").replaceWith("<span>" + $(this).find("option:selected").text() + "</span>");
		});
		$(".select").bind($.browser.msie ? "click" : "change", function(event) {
			$(this).prev("span").replaceWith("<span>" + $(this).find("option:selected").text() + "</span>");
		}); 
		}
	};
        styleSelect.init()
        $('.cb-enable').click(function(){
		var parent = $(this).parents('.switch');
		$('.cb-disable',parent).removeClass('selected');
		$(this).addClass('selected');
		$('.checkbox',parent).attr('checked', true);
	});
	$('.cb-disable').click(function(){
		var parent = $(this).parents('.switch');
		$('.cb-enable',parent).removeClass('selected');
		$(this).addClass('selected');
		$('.checkbox',parent).attr('checked', false);
	});
        $('.colorSelector').each(function(){
            var Othis = this; 
            var initialColor = $(Othis).next('input').attr('value');
            $(this).ColorPicker({
            color: initialColor,
            onShow: function (colpkr) {
            $(colpkr).fadeIn(500);
            return false;
            },
            onHide: function (colpkr) {
            $(colpkr).fadeOut(500);
            return false;
            },
            onChange: function (hsb, hex, rgb) {
            $(Othis).children('div').css('backgroundColor', '#' + hex);
            $(Othis).next('input').attr('value','#' + hex);
            }
            });
        }); 
});	

    
        
    