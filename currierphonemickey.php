<?php

if (!defined('_PS_VERSION_'))
	exit;

class CurrierPhoneMickey extends Module {

	public function __construct() {
		$this->name = 'currierphonemickey';
		$this->tab = 'phone';
		$this->version = '1.0';
		$this->author = 'khejit';
		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Currier Phone');
		$this->description = $this->l('Makes phone field mandatory for currier shipping option.');
		$this->frontOkMessage = $this->l('Contact number for currier is: ');
		$this->frontDbErrorMessage = $this->l('There \'s an error. Please try again.');
		$this->frontWrongPhone = $this->l('Write correct phone number!');
		$this->frontNoPhone = $this->l('Write phone number for a currier!');

	}

	public function install()
	{
		if (!parent::install()
		    || !$this->registerHook('displayAdminOrderTabOrder')
		    || !$this->registerHook('displayAdminOrderContentOrder')
		    || !$this->registerHook('displayCarrierList')
		)
			return false;
		$this->createSql();

		return true;
	}

	public function uninstall() {
		parent::uninstall();
	}

	public function hookDisplayAdminOrderTabOrder($params)
	{
		$customer_id = $params['customer']->id;
		$sql = 'SELECT * FROM '._DB_PREFIX_.$this->name.' WHERE customer = '.$customer_id;
		$row= Db::getInstance()->getRow($sql);
		$phone = $row['phone'];

		if($phone){
			return $this->display(__FILE__, '/views/templates/hook/tab_order.tpl');
		}
		return false;
	}

	public function hookDisplayAdminOrderContentOrder($params)
	{
		$customer_id = $params['customer']->id;
		$sql = 'SELECT * FROM '._DB_PREFIX_.$this->name.' WHERE customer = '.$customer_id;
		$row= Db::getInstance()->getRow($sql);
		$phone = $row['phone'];

		if($phone){
			$this->smarty->assign(
				array(
					'order' => $params['order'],
					'phone' => $phone
				)
			);
			return $this->display(__FILE__, '/views/templates/hook/content_order.tpl');
		}

		return false;
	}

	public function hookDisplayCarrierList ($params) {

		//d(unserialize('a:2:{i:28;s:4:"true";i:29;s:4:"true";}'));

		$cart = $params['cart'];

		if(!$this->phoneExists($cart)){

			$this->smarty->assign(
				array(
					'ajax_url'=>$this->context->link->getModuleLink('currierphonemickey','ajax',array(),Configuration::get('PS_SSL_ENABLED')?true:false),
					"module_dir" => _MODULE_DIR_.$this->name."/",
					"wrong_phone" => $this->frontWrongPhone,
					"no_phone" => $this->frontNoPhone,
				)
			);
			return $this->display(__FILE__, '/views/templates/hook/carriersList.tpl');

		};

		return false;

	}

	public function saveOrderPhone($phone){
		//$orderId = (int)Tools::getValue("id_order");
		$customer = (int)$this->context->customer->id;
		$phone = pSQL($phone);
		$shop_id = (int)$this->context->shop->id;
		if(preg_match("/^[+]?([0-9]?)[(|s|-|.]?([0-9]{3})[)|s|-|.]*([0-9]{3})[s|-|.]*([0-9]{1,4})$/", $phone)){

			if($this->currierPhoneTableExists($customer)){
				Db::getInstance()->update(
					$this->name,
					array(
						"id_shop" => $shop_id,
						//"customer"=> $customer,
						"phone"   => $phone
					),
					'customer="'.$customer.'"');
			} else {
				Db::getInstance()->insert($this->name, array(
					"id_shop" => $shop_id,
					"customer"=> $customer,
					"phone"   => $phone
				));
			}

			return true;
		}
		return false;
	}

	protected function currierPhoneTableExists($customer){
		$sql = 'SELECT * FROM '._DB_PREFIX_.$this->name.' WHERE customer = '.$customer;
		$row = Db::getInstance()->getRow($sql);
		return $row;
	}

	protected function phoneExists($cart){

		//$id_customer = $cart->id_customer;
		//$customer = new Customer($id_customer);
		//if ($customer->is_guest)
		//	return false;

		$address = new Address($cart->id_address_delivery);

		if($address->phone_mobile)
			return true;

		return false;
	}

	public function createSql(){
		$sql = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_.$this->name."` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `id_shop` int(11) NOT NULL,
            `customer` int(11) NOT NULL,
            `phone` varchar(25) DEFAULT NULL,
            PRIMARY KEY (`id`) )";

		Db::getInstance()->execute($sql);
	}

}