<?php
#App\Plugins\Fee\CashFee\Admin\AdminController.php

namespace App\Plugins\Fee\CashFee\Admin;

use App\Plugins\Fee\CashFee\Admin\Models\AdminCashFee;
use SCart\Core\Admin\Controllers\RootAdminController;
use App\Plugins\Fee\CashFee\AppConfig;
use SCart\Core\Admin\Models\AdminConfig;
use Validator;
class AdminController extends RootAdminController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig;
    }
    
    public function postCreate() {
        $methods = json_decode(sc_config($this->plugin->configKey.'_config_method'), true);
        $data = request()->all();
        $methods[$data['new_range']] = (float)($data['new_amount']);
        AdminConfig::where('key', $this->plugin->configKey.'_config_method')->update(['value' => json_encode($methods)]);
        return response()->json(['error' => 0, 'msg' => 'Success']);
    }

    public function removeRange() {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => sc_language_render('admin.method_not_allow')]);
        }
        $methods = json_decode(sc_config($this->plugin->configKey.'_config_method'), true);
        $key = request('key');
        unset($methods[$key]);
        AdminConfig::where('key', $this->plugin->configKey.'_config_method')->update(['value' => json_encode($methods)]);
        return response()->json(['error' => 0, 'msg' => 'Success']);
    }
}
