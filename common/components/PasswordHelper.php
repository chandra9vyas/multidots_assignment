<?php
namespace common\components;

use Yii;
/**
 * Password Helper Class, 
 * to generate new password using generatePasswordHash method
 * to compare password against hashed password using validatePassword method
 * 
 */
class PasswordHelper
{

	/**
	 * Generates hash using "sha256" for the given password
	 * @param string $password
	 * @return string : Hashed Password
	 */
	public static function generatePasswordHash($password, $salt)
	{
		return hash('sha256', $salt . $password);
	}

	/**
	 * Compates the given password against the Hashed Password
	 * @param string $password
	 * @param string $hashedPassword
	 * @return boolean
	 */
	public static function validatePassword($password, $hashedPassword, $salt)
	{
		if (!is_string($password) || $password === '')
		{
			throw new \yii\base\InvalidParamException('Password must be a string and cannot be empty.');
		}

		if (isset($password, $hashedPassword))
		{
			
			if ($hashedPassword === self::generatePasswordHash($password, $salt))
			{
				return true;
			}
		}
		return false;
	}

}
