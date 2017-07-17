<?php
/**
 * Created by PhpStorm.
 * User: elad
 * Date: 16 יולי 2017
 * Time: 12:48
 */

namespace Cardcom;


class Setting
{
	static private $user = null;
	static private $password = null;
	static private $terminal = null;
	static private $sendToMail = null;

	const COIN_SHEKEL = 1;
	const COIN_DOLLAR = 2;

	const LANG_HEBREW = "he";
	const LANG_ENGLISH = "en";
	const LANG_RUSSIAN = "ru";
	const LANG_ARABIC = "ar";


	/**
	 * @return array
	 */
	public static function getConfig(): array
	{
		return [
			"user" => self::$user,
			"password" => self::$password,
			"terminal" => self::$terminal,
			"sendToMail" => self::$sendToMail
		];
	}

	/**
	 * @param array $config
	 */
	public static function setConfig(array $config)
	{
		self::$user = (!empty($config["user"])) ? $config["user"] : null;
		self::$password = (!empty($config["password"])) ? $config["password"] : null;
		self::$terminal = (!empty($config["terminal"])) ? $config["terminal"] : null;
		self::$sendToMail = (!empty($config["sendToMail"])) ? $config["sendToMail"] : null;
	}

	/**
	 * @return null
	 */
	public static function getUser()
	{
		return self::$user;
	}

	/**
	 * @param null $user
	 */
	public static function setUser($user)
	{
		self::$user = $user;
	}

	/**
	 * @return null
	 */
	public static function getPassword()
	{
		return self::$password;
	}

	/**
	 * @param null $password
	 */
	public static function setPassword($password)
	{
		self::$password = $password;
	}

	/**
	 * @return null
	 */
	public static function getTerminal()
	{
		return self::$terminal;
	}

	/**
	 * @param null $terminal
	 */
	public static function setTerminal($terminal)
	{
		self::$terminal = $terminal;
	}

	/**
	 * @return null
	 */
	public static function getSendToMail()
	{
		return self::$sendToMail;
	}

	/**
	 * @param null $sendToMail
	 */
	public static function setSendToMail($sendToMail)
	{
		self::$sendToMail = $sendToMail;
	}

}