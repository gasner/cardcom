<?php
/**
 * Created by PhpStorm.
 * User: elad
 * Date: 17 יולי 2017
 * Time: 10:14
 */

namespace Cardcom;


use const null;
use const true;

/**
 * Class Invoice
 * @property string custAddresLine1
 * @property string custAddresLine2
 * @property string custCity
 * @property string custLinePH
 * @property string custMobilePH
 * @property string compID
 * @property string comments
 * @property string coinID
 * @property string extIsVatFree
 * @property string manualInvoiceNumber
 * @property string isAutoCreateUpdateAccount
 * @property string accountForeignKey
 * @property string date
 * @property string departmentId
 * @property string siteUniqueId
 * @property string invoiceType
 * @package Cardcom
 */
class Invoice
{

	static private $defaultSetting = [
		"InvoiceHead.SendByEmail" => true,
		"InvoiceHead.Language" => "he",
	];

	private $products = [];

	private $setting;


	function __construct($customerName, $email = null)
	{
		$this->setSetting(self::$defaultSetting);
		$this->setting["InvoiceHead.CustName"] = $customerName;
		$this->setting["InvoiceHead.Email"] = $email;
	}


	public function render()
	{
		$setting = $this->setting;

		for ($i = 0; $i < count($this->products); $i++) {

			/** @var InvoiceProduct $product */
			$product = $this->products[$i];
			$productSetting = $product->getSetting();
			foreach ($productSetting as $key => $value) {
				$setting["InvoiceLines" . ($i + 1) . ".$key"] = $value;
			}
		}
		return $setting;
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
	 * set free parameter
	 *
	 * @param $name
	 * @param $value
	 * @return $this
	 */
	public function setParameter($name, $value)
	{
		$this->setting[$name] = $value;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getSetting(): array
	{
		return $this->setting;
	}

	function __set($name, $value)
	{
		if (in_array($name, ['custAddresLine1', 'custAddresLine2', 'custCity', 'custLinePH', 'custMobilePH', 'compID', 'comments', 'coinID', 'extIsVatFree', 'manualInvoiceNumber', 'isAutoCreateUpdateAccount', 'accountForeignKey', 'date', 'departmentId', 'siteUniqueId'])) {
			$this->setting["InvoiceHead." . ucfirst($name)] = $value;
		} else {
			$this->setParameter($name, $value);
		}

	}

	/**
	 * @return array
	 */
	public function getProducts()
	{
		return $this->products;
	}

	/**
	 * @param array $products
	 */
	public function setProducts(array $products)
	{
		$this->products = $products;
	}


}