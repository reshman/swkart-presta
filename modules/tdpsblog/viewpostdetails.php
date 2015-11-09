<?php
require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');

$psblogobject = new tdpsblogClass();
$confirmation='';
$context = Context::getContext();
if (Tools::isSubmit('submit')) {
        $author=$psblogobject->inputData(Tools::getValue('author')); 
        $email=$psblogobject->inputData(Tools::getValue('email'));
        $comment=$psblogobject->inputData(Tools::getValue('comment'));
    if((filter_var(Tools::getValue('email'), FILTER_VALIDATE_EMAIL)) && (isset($comment) && !empty($comment)) && (isset($author) && !empty($author))) {
           $psblogobject->insertComments($author,$email,$comment); 
        $confirmation=1;     
    }else{
       $confirmation=2;
        }
 
}

               global $smarty;
		$id_shop = $context->shop->id;
		$id_lang = $context->language->id;
                $id_tdpost = Tools::getValue('tdpost');
                if(Tools::getValue('replytocom')){
                    $replytocom =Tools::getValue('replytocom');
                }else{
                    $replytocom =0;
                }
                

             

              $numofrepost= Configuration::get('td_numofrepost')?Configuration::get('td_numofrepost'):5;
              $numofcomments= Configuration::get('td_numofcomments')?Configuration::get('td_numofcomments'):5;     
             
              $postcomments = tdpsblogModel::getComments($id_tdpost); 
              $posttotalcomments = count(tdpsblogModel::getTotalCommentsByPost($id_tdpost)); 
              $postcateogyr = tdpsblogModel::getPostCategory(); 
            //print_r($postcateogyr);

               // print_r($postcomments);
                
            $tdblogpspost =$psblogobject->gettdBlogPostByID($id_tdpost,$id_lang,$id_shop);
            $data = array();
            foreach ($tdblogpspost as $tdblogps):
                    $data[] = $tdblogps;
            endforeach;
                 
   if (file_exists(_PS_THEME_DIR_.'modules/tdpsblog/form.tpl'))
      $smarty->assign('comments_form', _PS_THEME_DIR_.'modules/tdpsblog/form.tpl');
    else
      $smarty->assign('comments_form', _PS_MODULE_DIR_.'tdpsblog/views/templates/front/form.tpl');
                        
          if (file_exists(_PS_THEME_DIR_.'modules/tdpsblog/comments.tpl'))
      $smarty->assign('comments_tmp', _PS_THEME_DIR_.'modules/tdpsblog/comments.tpl');
    else
      $smarty->assign('comments_tmp', _PS_MODULE_DIR_.'tdpsblog/views/templates/front/comments.tpl');               
            

       if (file_exists(_PS_THEME_DIR_.'modules/tdpsblog/comments_tree_branch.tpl'))
      $smarty->assign('comments_tree_branch', _PS_THEME_DIR_.'modules/tdpsblog/comments_tree_branch.tpl');
    else
      $smarty->assign('comments_tree_branch', _PS_MODULE_DIR_.'tdpsblog/views/templates/front/comments_tree_branch.tpl');               
      

          
if($context->customer->isLogged()){
$context->smarty->assign(array(
	'id_customer' => (int)$context->customer->id,
        'email_customer' => $context->customer->email,
        'name_customer' => $context->customer->firstname.' '.$context->customer->lastname
));
  }

    
            //print_r($tdblogpspost); 
            $smarty->assign(array(
                'default_lang' => (int)$context->language->id,
                'id_lang' => (int) $context->language->id,
                'postdata' => $data,
                'postcomments' => $postcomments,
                'tdblogdetailslinks' => $context->link->getModuleLink('tdpsblog', 'details'),
                'tdblogcatpostlinks' => $context->link->getModuleLink('tdpsblog', 'default'),
                'base_url' => __PS_BASE_URI__,
                'replytocom' => $replytocom,
                'confirmation'=>$confirmation,
                'totalcommentsbypost' => $posttotalcomments
                
            ));
           // return $this->display(__FILE__, 'tdpsblog.tpl');