<?php 
namespace Concrete\Package\ThAjaxPageList;

use Route;

class Controller extends \Concrete\Core\Package\Package
{
    protected $pkgHandle = 'th_ajax_page_list';
    protected $appVersionRequired = '5.7.3';
    protected $pkgVersion = '0.1';
    
    public function getPackageDescription()
    {
        return t("Add custom templates to Page List for Ajax pagination.");
    }
    
    public function getPackageName()
    {
        return t("Ajax Page List");
    }
    
    public function on_start()
    {
        Route::register('/ajax/page_list/{template}', '\Concrete\Package\ThAjaxPageList\Controller\Ajax::page_list');
    }

}