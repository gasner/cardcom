<?php
/**
 * Created by PhpStorm.
 * User: elad
 * Date: 16 יולי 2017
 * Time: 13:13
 */

namespace Cardcom;


class IframeResponse
{
	private $response;

	function __construct($response)
	{
		$this->response = $response;
	}

	/**
	 * @return bool
	 */
	public function isSuccess()
	{
		return $this->response['ResponseCode'] == '0';
	}

	/**
	 * @return string
	 */
	public function getError()
	{
		return $this->response['Description'];
	}

	/**
	 * This code must be stored on the database order
	 * @return string
	 */
	public function getLowProfileCode()
	{
		return $this->response['LowProfileCode'];
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->response['url'];
	}

	/**
	 * @return string
	 */
	public function getPayPalUrl()
	{
		return $this->response['PayPalUrl'];
	}
}