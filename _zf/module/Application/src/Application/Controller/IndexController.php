<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function getTestAction() {
	    if ($this->getRequest()->isXmlHttpRequest()) {
	        if ($this->getRequest()-isPost()) {

	            $this->_helper->layout()->disableLayout();
	            $this->_helper->viewRenderer->setNoRender(true);
	            echo '{test:"test"}';
	            exit;
	        }
	    }
	    else {
	        // ... Do normal controller logic here (To catch non ajax calls to the script)
	    }
	}

}
