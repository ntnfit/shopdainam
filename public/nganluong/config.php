<?php
use App\Plugins\Payment\Nganluong\AppConfig;
define('URL_API','https://www.nganluong.vn/checkout.api.nganluong.post.php'); // Đường dẫn gọi api
define('RECEIVER',sc_config('NL_RECEIVER')); // Email tài khoản ngân lượng
define('MERCHANT_ID', sc_config('MERCHANT_ID')); // Mã merchant kết nối
define('MERCHANT_PASS', sc_config('MERCHANT_PASS')); // Mật khẩu kết nôi
?>
