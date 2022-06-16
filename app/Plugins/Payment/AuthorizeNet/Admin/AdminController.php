<?php
#App\Plugins\Payment\AuthorizeNet\Admin\AdminController.php

namespace App\Plugins\Payment\AuthorizeNet\Admin;

use SCart\Core\Admin\Controllers\RootAdminController;
use App\Plugins\Payment\AuthorizeNet\AppConfig;

class AdminController extends RootAdminController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig;
    }
    public function index()
    {
        return view($this->plugin->pathPlugin.'::Admin',
            [
                
            ]
        );
    }
}
