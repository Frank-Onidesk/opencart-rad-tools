<?php
namespace Opencart\Admin\Controller\Extension;

use \Opencart\System\Engine\Controller;

/**
 * @property \Opencart\System\Engine\Model $model_user_user_group
 * @property \Opencart\System\Engine\Model $model_setting_extension
 * @property \Opencart\System\Engine\Model $model_setting_setting
 * @property \Opencart\System\Engine\Model $model_setting_event
 * @property \Opencart\System\Library\DB $db
 * @property \Opencart\System\Library\Loader $load
 * @property \Opencart\System\Library\Response $response
 * @property \Opencart\System\Library\Request $request
 * @property \Opencart\System\Library\Language $language
 * @property \Opencart\System\Library\Document $document
 * @property \Opencart\System\Library\Url $url
 * @property \Opencart\System\Library\Session $session
 * @property \Opencart\System\Library\Cache $cache
 * @property \Opencart\System\Library\Config $config
 * @property \Opencart\System\Library\Customer $customer
 * @property \Opencart\System\Library\Currency $currency
 * @property \Opencart\System\Library\Weight $weight
 * @property \Opencart\System\Library\Length $length
 * @property \Opencart\System\Library\Cart\Cart $cart
 * @property \Opencart\System\Library\Cart\Customer $cart_customer
 * @property \Opencart\System\Library\Cart\Currency $cart_currency
 * @property \Opencart\System\Library\Cart\Tax $tax
 * @property \Opencart\System\Library\Cart\Shipping $shipping
 * @property \Opencart\System\Library\Cart\Total $total
 * @property \Opencart\System\Library\Mail\Mail $mail
 * @property \Opencart\System\Library\User $user
 * @property \Opencart\System\Library\Log $log
 */

class Myproject extends Controller {

// index method
public function index(): void
{
        $this->load->language('extension/myproject');
}

// install method
    public function install(): void {
        // Add installation logic here
        $this->load->model('setting/extension');
        $this->model_setting_extension->install();
    }

// uninstall method
    public function uninstall(): void {
        // Add uninstallation logic here
        $this->load->model('setting/extension');
        $this->model_setting_extension->uninstall();
    }

}