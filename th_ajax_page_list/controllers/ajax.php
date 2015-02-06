<?php
namespace Concrete\Package\ThAjaxPageList\Controller;

use Page;
use Request;
use Area;
use Block;
use Permissions;
use Concrete\Core\Block\View\BlockView;
use Concrete\Core\Block\View\BlockViewTemplate;
use Core;

class Ajax extends \Concrete\Core\Controller\Controller
{
    public function page_list($template)
    {
        // Get the page object
        $c = Page::getByID($_GET['cID']);
        if (!is_object($c) || $c->isError()) {
            die(t('Invalid collection ID.'));
        }
        
        // Set the current page
        $req = Request::getInstance();
        $req->setCurrentPage($c);
        
        // Get the area object
        $a = Area::get($c, $_GET['aHandle']);
        if (!is_object($a)) {
            die(t('Invalid area handle.'));
        }
        
        // Get the block object
        $b = Block::getByID($_GET['bID'], $c, $a);
        if (!is_object($b)) {
            die(t('Invalid block ID.'));
        }
        
        // Check the read permission
        $p = new Permissions($b);
        if (!$p->canRead()) {
            die(t('Access Denied'));
        }
        
        // Override the block template
        $bvt = new BlockViewTemplate($b);
        $bvt->setBlockCustomTemplate($template);
        
        // Return the block view
        $bv = new BlockView($b);
        $bv->start('view');
        $bv->setupRender();
        $bv->startRender();
        
        if (is_array($_GET['parameters'])) {
            $parameters = $_GET['parameters'];
            if ($parameters[0] == 'topic') {
                $bv->controller->action_filter_by_topic($parameters[1]);
            } elseif ($parameters[0] == 'tag') {
                $bv->controller->action_filter_by_tag($parameters[1]);
            } elseif (Core::make("helper/validation/numbers")->integer($parameters[0])) {
                // then we're going to treat this as a year.
                $bv->controller->action_filter_by_date(intval($parameters[0]), intval($parameters[1]));
            }
        }
        
        $bv->setViewTemplate($bvt->getTemplate());
        $scopeItems = $bv->getScopeItems();
        echo $bv->renderViewContents($scopeItems);
    }
}