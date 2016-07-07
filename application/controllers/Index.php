<?php
//默认控制器application/controllers/Index.php
class IndexController extends Yaf_Controller_Abstract {
   public function indexAction() {//默认Action
   	echo "123";
       $this->getView()->assign("content", "Hello World");
   }
}
?>