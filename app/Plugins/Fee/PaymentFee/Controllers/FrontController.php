<?php
/**
 * @package    App\Plugins\Fee\PaymentFee\Controllers
 * @subpackage FrontController
 * @copyright  Copyright (c) 2021 S-Cart opensource.
 * @author     Lanh le <lanhktc@gmail.com>
 */

#App\Plugins\Fee\PaymentFee\Controllers\FrontController.php
namespace App\Plugins\Fee\PaymentFee\Controllers;

use App\Plugins\Fee\PaymentFee\AppConfig;
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
