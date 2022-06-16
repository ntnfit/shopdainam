<?php
#App\Plugins\Fee\CashFee\AppConfig.php
namespace App\Plugins\Fee\CashFee;

use SCart\Core\Admin\Models\AdminConfig;
use SCart\Core\Front\Models\Languages;
use SCart\Core\Front\Models\ShopOrderTotal;
use App\Plugins\ConfigDefault;
class AppConfig extends ConfigDefault
{
    public function __construct()
    {
    	$config = file_get_contents(__DIR__.'/config.json');
    	$config = json_decode($config, true);
    	$this->configGroup = $config['configGroup'];
    	$this->configCode = $config['configCode'];
    	$this->configKey = $config['configKey'];
        $this->pathPlugin = $this->configGroup . '/' . $this->configCode . '/' . $this->configKey;
        $this->title = trans($this->pathPlugin.'::lang.title');
        $this->image = $this->pathPlugin.'/'.$config['image'];
        $this->version = $config['version'];
        $this->auth = $config['auth'];
        $this->link = $config['link'];
    }

    public function install()
    {
        $return = ['error' => 0, 'msg' => ''];
        $check = AdminConfig::where('key', $this->configKey)->first();
        if ($check) {
            $return = ['error' => 1, 'msg' => 'Module exist'];
        } else {
            $process = AdminConfig::insert([
                [
                    'group' => $this->configGroup,
                    'code' => $this->configCode,
                    'key' => $this->configKey,
                    'sort' => 0, // Sort extensions in group
                    'value' => self::ON, //1- Enable extension; 0 - Disable
                    'detail' => $this->configKey.'.title',
                ],
                [
                    'group' => '',
                    'code' => $this->configKey.'_config',
                    'key' => $this->configKey.'_config_method',
                    'sort' => 0, // Sort extensions in group
                    'value' => '{"0":0}',
                    'detail' => $this->configKey.'.config_method',
                ]
            ]
            );
            Languages::insert([
                ['code' => $this->configKey.'.title', 'text' => 'Cash collection fee', 'position' => 'plugin__'.$this->configKey, 'location' => 'en'],
                ['code' => $this->configKey.'.title', 'text' => 'Phí thu hộ tiền mặt', 'position' => 'plugin__'.$this->configKey, 'location' => 'vi'],
                ['code' => $this->configKey.'.amount_fee', 'text' => 'Amount', 'position' => 'plugin__'.$this->configKey, 'location' => 'en'],
                ['code' => $this->configKey.'.amount_fee', 'text' => 'Phí', 'position' => 'plugin__'.$this->configKey, 'location' => 'vi'],
                ['code' => $this->configKey.'.amount_total', 'text' => 'Collected value(>=)', 'position' => 'plugin__'.$this->configKey, 'location' => 'en'],
                ['code' => $this->configKey.'.amount_total', 'text' => 'Giá trị thu hộ (>=)', 'position' => 'plugin__'.$this->configKey, 'location' => 'vi'],
                ['code' => $this->configKey.'.config_method', 'text' => 'Configuration', 'position' => 'plugin__'.$this->configKey, 'location' => 'en'],
                ['code' => $this->configKey.'.config_method', 'text' => 'Cấu hình', 'position' => 'plugin__'.$this->configKey, 'location' => 'vi'],
            ]
            );
            if (!$process) {
                $return = ['error' => 1, 'msg' => 'Error when install'];
            }
        }
        return $return;
    }

    public function uninstall()
    {
        $return = ['error' => 0, 'msg' => ''];
        //Please delete all values inserted in the installation step
        $process = (new AdminConfig)
            ->where('key', $this->configKey)
            ->orWhere('code', $this->configKey.'_config')
            ->delete();
        Languages::where('position', 'plugin__'.$this->configKey)->delete();

        if (!$process) {
            $return = ['error' => 1, 'msg' => sc_language_render('plugin.plugin_action.action_error', ['action' => 'Uninstall'])];
        }
        return $return;
    }

    public function enable()
    {
        $return = ['error' => 0, 'msg' => ''];
        $process = (new AdminConfig)->where('key', $this->configKey)->update(['value' => self::ON]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => 'Error enable'];
        }
        return $return;
    }
    public function disable()
    {
        $return = ['error' => 0, 'msg' => ''];
        $process = (new AdminConfig)->where('key', $this->configKey)->update(['value' => self::OFF]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => 'Error disable'];
        }
        return $return;
    }

    public function config()
    {
        $valueConfig = json_decode(sc_config($this->configKey.'_config_method'), true);
        $htmlRender ='<tr><td><input name="new_range" type="number" class="form-control"></td><td><input name="new_amount"  type="number" class="form-control"></td></tr>';
        return view($this->pathPlugin.'::Admin')->with(
            [
                'code'       => $this->configCode,
                'key'        => $this->configKey,
                'title'      => $this->title,
                'pathPlugin' => $this->pathPlugin,
                'configKey' => $this->configKey,
                'valueConfig'    => $valueConfig,
                'htmlRender'    => $htmlRender,
            ]);
    }


    public function getData()
    {
        $amountTotal = ShopOrderTotal::getAmountTotalWithoutShipping();
        $methodConfig = json_decode(sc_config($this->configKey.'_config_method'), true);
        $paymentMethod = session('paymentMethod');
        $fee = 0;
        if ($paymentMethod == 'Cash') {
            ksort($methodConfig);
            if (count($methodConfig)) {
                foreach ($methodConfig as $range => $value) {
                    if ($amountTotal >= $range) {
                        $fee = $value;
                    }
                }
            }
        }
        $arrData = [
            'title'      => $this->title,
            'code'       => $this->configCode,
            'key'        => $this->configKey,
            'image'      => $this->image,
            'permission' => self::ALLOW,
            'value'      => $fee,
            'version'    => $this->version,
            'auth'       => $this->auth,
            'link'       => $this->link,
            'pathPlugin' => $this->pathPlugin,
        ];
        return $arrData;
    }

}
