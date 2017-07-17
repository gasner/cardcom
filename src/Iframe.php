<?php

/**
 * Created by PhpStorm.
 * User: elad
 * Date: 16 יולי 2017
 * Time: 12:47
 */

namespace Cardcom;

use Exception;
use const null;
use function array_merge;
use function in_array;

/**
 * get iframe for cardcom api 9
 *
 * http://kb.cardcom.co.il/article/AA-00243/0
 *
 * Class Iframe
 * @property string productName
 * @property int maxNumOfPayments
 * @property int minNumOfPayments
 * @property int aPILevel
 * @property boolean hideCreditCardUserId
 * @property string successRedirectUrl
 * @property string errorRedirectUrl
 * @property string indicatorUrl
 * @property string cancelUrl
 * @property int cancelType [0,1,2]
 * @property int sumInStars
 * @property boolean hideCVV
 * @property int creditType
 * @property int returnValue
 * @property int defaultNumOfPayments
 * @property string hideCardOwnerName
 * @property int sapakMutav
 * @property int coinID
 * @property string language
 * @package Cardcom
 */
class Iframe
{

	private $setting = [];
	static private $defaultSetting = [
		"codepage" => '65001',
		"ChargeInfo.APILevel" => "9",
		"ChargeInfo.CoinID" => "1",
		"ChargeInfo.Language" => "he"
	];
	/**
	 * @var Invoice
	 */
	private $invoice = null;


	function __construct()
	{
		$this->setSetting(self::$defaultSetting);
	}


	/**
	 * @return array
	 */
	public static function getDefaultSetting()
	{
		return self::$defaultSetting;
	}

	/**
	 * @param array $settings
	 */
	public static function setDefaultSetting(array $settings)
	{
		foreach ($settings as $key => $setting) {
			self::$defaultSetting[$key] = $setting;
		}

	}

	public function getIframe()
	{

		return $this->postVars($this->getSetting());


	}

	/**
	 * @return mixed
	 */
	public function getInvoice()
	{
		return $this->invoice;
	}

	/**
	 * @param Invoice $invoice
	 * @return $this
	 */
	public function setInvoice(Invoice $invoice)
	{
		$this->invoice = $invoice;
		return $this;
	}


	private function postVars($vars)
	{
		$urlencoded = http_build_query($vars);

		$CR = curl_init();
		curl_setopt($CR, CURLOPT_URL, 'https://secure.Cardcom.co.il/interface/PerformSimpleCharge.aspx');
		curl_setopt($CR, CURLOPT_POST, 1);
		curl_setopt($CR, CURLOPT_FAILONERROR, true);
		curl_setopt($CR, CURLOPT_POSTFIELDS, $urlencoded);
		curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($CR, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($CR, CURLOPT_FAILONERROR, true);
		#actual curl execution perfom
		$r = curl_exec($CR);
		$error = curl_error($CR);
		# some error , send email to developer
		if (!empty($error)) {
			throw new \Exception($error);
		}
		curl_close($CR);
		parse_str($r, $result); # parse result.
		return new IframeResponse($result);

	}

	/**
	 * @param null $goodUrl
	 * @return Iframe
	 */
	public function setGoodUrl($goodUrl)
	{
		$this->setting["ChargeInfo.SuccessRedirectUrl"] = $goodUrl;
		if (empty($this->setting["ChargeInfo.ErrorRedirectUrl"])) {
			$this->setErrorUrl($goodUrl);
		}
		return $this;
	}

	public function setErrorUrl($errorUrl)
	{
		$this->setting["ChargeInfo.ErrorRedirectUrl"] = $errorUrl;
		return $this;
	}

	public function setIndicatorUrl($indicatorUrl)
	{
		$this->setting["ChargeInfo.IndicatorUrl"] = $indicatorUrl;
		return $this;
	}

	/**
	 * @param null $price
	 * @return Iframe
	 */
	public function setPrice($price)
	{
		$this->setting["ChargeInfo.SumToBill"] = $price;
		return $this;
	}

	/**
	 * @param array $settings
	 * @return Iframe
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
		$data = array(
			'username' => Setting::getUser(),
			'terminalnumber' => Setting::getTerminal(),

		);

		$setting = $this->setting;

		foreach ($setting as $key => $value) {
			$data[$key] = $value;
		}

		if ($this->invoice) {
			$settingInvoice = $this->invoice->render();
			$data = array_merge($data, $settingInvoice);
		}
		return $data;
	}

	function __set($name, $value)
	{
		if (in_array($name, ['productName', 'maxNumOfPayments', 'aPILevel', 'minNumOfPayments', 'hideCreditCardUserId', 'successRedirectUrl', 'errorRedirectUrl', 'indicatorUrl', 'cancelUrl', 'cancelType', 'sumInStars', 'hideCVV', 'creditType', 'returnValue', 'defaultNumOfPayments', 'hideCardOwnerName', 'sapakMutav', 'coinID', 'language'])) {
			$this->setting["ChargeInfo." . ucfirst($name)] = $value;
		} else {
			throw new Exception("not valid attribute");
		}

	}

}