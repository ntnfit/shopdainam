<?php
/**
 * @package    App\Plugins\Fee\CashFee\Controllers
 * @subpackage FrontController
 * @copyright  Copyright (c) 2021 S-Cart opensource.
 * @author     Lanh le <lanhktc@gmail.com>
 */

#App\Plugins\Fee\CashFee\Controllers\FrontController.php
namespace App\Plugins\Fee\CashFee\Controllers;

use App\Plugins\Fee\CashFee\AppConfig;
use SCart\Core\Front\Controllers\RootFrontController;
class FrontController extends RootFrontController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig;
    }
}
