<?php
/**
 * Created by PhpStorm.
 * User: elad
 * Date: 18 יולי 2017
 * Time: 16:55
 */

namespace Cardcom;

/**
 *
 * http://kb.cardcom.co.il/article/AA-00241/0
 *
 * Class DealResponse
 * @package Cardcom
 */
class DealResponse
{


	private $response;
	private $strResponse;

	private function __construct($response)
	{
		$this->strResponse = $response;
		parse_str($response, $this->response);
	}

	static public function getDealResponse($dealCode)
	{
		$urlencoded = http_build_query([
			'username' => Setting::getUser(),
			'terminalnumber' => Setting::getTerminal(),
			"lowprofilecode" => $dealCode
		]);

		$CR = curl_init();
		curl_setopt($CR, CURLOPT_URL, 'https://secure.cardcom.co.il/Interface/BillGoldGetLowProfileIndicator.aspx');
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
//		parse_str($r, $result); # parse result.
		return new DealResponse($r);
	}

	/**
	 * @return mixed
	 */
	public function getResponse()
	{
		return $this->response;
	}

	/**
	 * @return mixed
	 */
	public function getStrResponse()
	{
		return $this->strResponse;
	}

}