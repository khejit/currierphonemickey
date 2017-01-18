<?php

class CurrierPhoneMickeyAjaxModuleFrontController extends ModuleFrontController {

	public function initContent()
	{
		$this->processPhone();
	}

	public function processPhone(){

		$phone = str_replace(' ', '', trim(Tools::getValue('currier_phone')));
		if(preg_match('/^[+]?([0-9]?)[(|s|-|.]?([0-9]{3})[)|s|-|.]*([0-9]{3})[s|-|.]*([0-9]{1,4})$/', $phone))
		{
			if($this->module->saveOrderPhone($phone)){
				$this->jsonResponse(array("result"=>"ok","message"=>$this->module->frontOkMessage.$phone,"newPhone"=>$phone));
			} else {
				$this->jsonResponse(array("result"=>"error","message"=>$this->module->frontDbErrorMessage));
			}
		}  else {
			$this->jsonResponse(array("result"=>"error","message"=>$this->module->frontWrongPhone));
		}

	}

	public function jsonResponse($data)
	{
		header("Content-type: application/json");
		die(json_encode($data));
	}

}
