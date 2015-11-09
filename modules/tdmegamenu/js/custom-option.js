        jQuery(document).ready(function($) { 
         
         jQuery("#selected_menu_type").change(function () {
                            var selected_menu_type=$(this).val();
                            switch (selected_menu_type){
                            case "cat":
                            $("#category_block").css("display","block");
                            $("#cms-block").css("display","none");
                            $("#menuf-block").css("display","none");
                            $("#supplier-block").css("display","none");
                            $("#custom-box").css("display","none");
                            $("#custom_links_block").css("display","none");
                           $("#menu_links_block").css("display","none");
                           $("#description").css("display","none");
                            break; 
                          case "cms":
                            $("#category_block").css("display","none");
                            $("#cms-block").css("display","block");
                            $("#menuf-block").css("display","none");
                            $("#supplier-block").css("display","none");
                            $("#custom-box").css("display","none");
                           $("#custom_links_block").css("display","none");
                           $("#menu_links_block").css("display","none");
                           $("#description").css("display","none");
                            break; 
                            case "manu":
                            $("#category_block").css("display","none");
                            $("#cms-block").css("display","none");
                            $("#menuf-block").css("display","block");
                            $("#supplier-block").css("display","none");
                            $("#custom-box").css("display","none");
                            $("#custom_links_block").css("display","none");
                           $("#menu_links_block").css("display","none");
                           $("#description").css("display","none");
                            break; 
                            case "sup":
                            $("#category_block").css("display","none");
                            $("#cms-block").css("display","none");
                            $("#menuf-block").css("display","none");
                            $("#supplier-block").css("display","block");
                            $("#custom-box").css("display","none");
                           $("#custom_links_block").css("display","none");
                           $("#menu_links_block").css("display","none");
                           $("#description").css("display","none");
                            break; 
                           case "custom":
                            $("#category_block").css("display","none");
                            $("#cms-block").css("display","none");
                            $("#menuf-block").css("display","none");
                            $("#supplier-block").css("display","none");
                            $("#custom-box").css("display","block");
                            $("#custom_links_block").css("display","none");
                           $("#menu_links_block").css("display","none");
                           $("#description").css("display","none");
                            break; 
                            default:
                            $("#category_block").css("display","none");
                            $("#cms-block").css("display","none");
                            $("#menuf-block").css("display","none");
                            $("#supplier-block").css("display","none");
                            $("#custom-box").css("display","none");
                            $("#description").css("display","none");
                            }
                           return false;
                        });
                          $("select#custom-block-section").change(function(){
                            var custome_url=$(this).val();
                            switch(custome_url) {
                            case "cus_links":
                            $("#custom_links_block").css("display","block");
                            $("#description").css("display","none");
                              $("#menu_links_block").css("display","block");
                            break; 
                          case "cus_html":
                            $("#custom_links_block").css("display","none");
                            $("#description").css("display","block");
                            $("#menu_links_block").css("display","block");
                            break; 
                            }
                        });
            });
            
           