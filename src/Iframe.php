<?php

/**
 * Created by PhpStorm.
 * User: elad
 * Date: 16 יולי 2017
 * Time: 12:47
 */

namespace Cardcom;


class Iframe
{

	private $setting = [];
	static private $defaultSetting = [
		"codepage" => '65001',
		"ChargeInfo.APILevel" => "9",
		"ChargeInfo.CoinID" => "1",
		"ChargeInfo.Language" => "he"
	];
	private $goodUrl;


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
		$data = array(
			'username' => Setting::getUser(),
//			'password' => Setting::getPassword(),
			'terminalnumber' => Setting::getTerminal(),
//			"NotificationGoodMail" => Setting::getSendToMail(),
//			"NotificationErrorMail" => Setting::getSendToMail(),
//			"NotificationFailMail" => Setting::getSendToMail(),

		);

//		if(Setting::getPassword()){
//			$data["pa"]
//		}

		$setting = $this->setting;

		foreach ($setting as $key => $value) {
			$data[$key] = $value;
		}

		return $this->postVars($data);


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
	//$vars['ChargeInfo.ErrorRedirectUrl'] = "https://secure.Cardcom.co.il/DealWasUnSuccessful.aspx?customVar=1234"; // Error Page
//$vars['ChargeInfo.IndicatorUrl'] = "http://www.yoursite.com/NotifyURL"; // Indicator Url \ Notify URL

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
	public function getSetting(): array
	{
		return $this->setting;
	}

}