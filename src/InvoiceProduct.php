<?php
/**
 * Created by PhpStorm.
 * User: elad
 * Date: 17 יולי 2017
 * Time: 10:47
 */

namespace Cardcom;

/**
 *
 * http://kb.cardcom.co.il/article/AA-00244/0
 *
 * Class InvoiceProduct
 * @package Cardcom
 */

class InvoiceProduct
{
	static private $defaultSetting = [
		"IsPriceIncludeVAT" => true,
	];

	private $products = [];

	private $setting;


	function __construct($description, $price, $quantity)
	{
		$this->setSetting(self::$defaultSetting);
		$this->setting["Description"] = $description;
		$this->setting["Price"] = $price;
		$this->setting["Quantity"] = $quantity;
	}

	/**
	 * @param array $settings
	 * @return $this
	 */
	public function setSetting(array $settings)
	{
		foreach ($settings as $key => $setting) {

			$this->setting[$key] = $setting;
		}
		return $this;
	}

	/**
	 * @return array
	 */
	public function getSetting()
	{
		return $this->setting;
	}

	public function setProductID($id)
	{
		$this->setting["ProductID"] = $id;
	}

	public function setIsVatFree($bool)
	{
		$this->setting["IsVatFree"] = $bool;
	}

}