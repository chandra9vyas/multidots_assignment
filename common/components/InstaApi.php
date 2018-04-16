<?php
namespace common\components;

use yii\helpers\Json;
use yii\helpers\Html;

ini_set('max_execution_time', 300); //300 seconds = 5 minutes

class InstaApi{

	public $token_array;

	public function authInstagram(){

		$url = "https://api.instagram.com/oauth/authorize/?client_id=".\Yii::$app->params['_INSTAGRAM_CLIENT_ID']."&redirect_uri=".\Yii::$app->params['_INSTAGRAM_REDIRECT_URL']."&response_type=code";       
		header('location: '.$url);
	}

	/* set access token by login with instagram */
	public function setAccess_token($code){
		
		$this->token_array = array("client_id"=>\Yii::$app->params['_INSTAGRAM_CLIENT_ID'],
				"client_secret"=>\Yii::$app->params['_INSTAGRAM_CLIENT_SECRET'],
				"grant_type"=>'authorization_code',
				"redirect_uri"=>\Yii::$app->params['_INSTAGRAM_REDIRECT_URL'],
				"code"=>$code);

	}

	/* get othe user details */
	public function getUserDetails(){

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,"https://api.instagram.com/oauth/access_token");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_TIMEOUT,60);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->token_array);   
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false); 

		$responseData = curl_exec ($ch);

		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close ($ch);  
		
		return Json::decode($responseData,true);
	}
	
	/* get user's followers, following and media count*/
	public function getOwnerDetail($accessToken=NULL){       
		$ch = curl_init();
		$url = "https://api.instagram.com/v1/users/self/?access_token=".$accessToken;
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_TIMEOUT,60);       
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false); 

		$responseData = curl_exec ($ch);

		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close ($ch);       
		return Json::decode($responseData,true);
	}

	/*get insta feeds*/
	public function getFeeds($accessToken=NULL,$count=10,$maxId=NULL,$minID=NULL){  

		$url = "https://api.instagram.com/v1/users/self/media/recent/?access_token=".$accessToken."&count=".$count;
		
		if(isset( $maxId ) && !empty( $maxId )){
		
			$url = $url.'&max_id='.trim($maxId);		
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_TIMEOUT,60);       
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false); 
		$responseData = curl_exec ($ch);

		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close ($ch);    
		
		return Json::decode($responseData,true);
	}
	
}