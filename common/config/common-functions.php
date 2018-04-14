<?php
/* print function - without exit*/
function pr($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

/* print function - with exit*/
function prd($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
	exit;
}
function appMessageCode($msgCode){
	
	$appCode = array();
	$appCode['OK'] = 200;
	$appCode['BAD_REQUEST'] = 400;
	$appCode['UNAUTHORIZE_ACCESS'] = 401;
	$appCode['SOMETHING_WRONG'] = 403;
	$appCode['NOT_FOUND'] = 404;
	$appCode['VALIDATION_ERR'] = 500;
	$appCode['MULTIPLE_USER'] = 420;//send this code when user is login from multiple device

	$msg = $appCode[$msgCode];

	return $msg;

}
/* flash message - 0 : error , 1: success*/
function processMessage($isError, $msgCode){

	if($isError == 0)
	{       
		
		$pFailure = array();        
		
		$pFailure['LOGIN_FAILED'] = 'Failed to login, please try again.'; 		
		$pFailure['TOKEN_EXPIRE'] = 'Your password change request has expired.Please try again.';		
		$pFailure['VALIDATE_ERROR'] = 'Please check again, fields are not properly filled out.';
		$pFailure['NO_IMAGE'] = 'Image Not Found.';
		$pFailure['NO_FILE_FOUND'] = 'File Not Found.';
		$pFailure['UNAUTHORIZE_ACCESS'] = 'Unauthorize access.';
		$pFailure['SAVE_ERROR'] = 'ERROR IN SAVE.'; 	
		
		
		$pMessgae = $pFailure[$msgCode];

	}
	else 
	{
		$pSuccess = array();        		

		$pSuccess['LOGIN_SUCCESS'] = 'You have been successfully logged in.'; 		
		
		$pMessgae = $pSuccess[$msgCode];
		
	}
	
	return $pMessgae;

}
/* display flash message */
function displayMessage($pType, $pCode, $message = ''){
	
	if(isset($message) && !empty( $message )){
		 Yii::$app->session->setFlash($pType == 1 ? 'success' : 'error', $message);
	}else{
		 Yii::$app->session->setFlash($pType == 1 ? 'success' : 'error', processMessage($pType, $pCode));
	}   
}

/* function to view images */  
function viewImg($imgName, $dirUpload, $getThumb = 0, $thumbPrefix, $isFile = 0){

	$response = array();

	$dirStorage = Yii::getAlias('@adminUploads').'/'.$dirUpload.'/';
	$imageUrl = '@web/uploads/'.$dirUpload;
	

	if($getThumb == 1)
	{
		$imagePath = $imageUrl.'/'.$thumbPrefix.'_'.$imgName;
		$filename = $dirStorage.$thumbPrefix.'_'.$imgName;
	}
	else
	{
		$imagePath = $imageUrl.'/'.$imgName;
		$filename = $dirStorage.$imgName;
	}
	//prd($filename);

	//extension
	$ext = pathinfo($imgName, PATHINFO_EXTENSION);
		 
	
	if(file_exists($filename) && isset($imgName) && !empty($imgName))
	{
		$response['title'] = processMessage(1, 'IMAGE_FOUND');
		$response['image'] = $imagePath;  		
		$response['filePath'] = $imageUrl.'/'.$imgName;  		
		$response['success'] = 1; 
	}
	else
	{
		$response['title'] = processMessage(0, 'NO_IMAGE');		
		
		if($dirUpload == 'user'){

			$response['image'] =  '@web/images/profile_default.png';
			$response['filePath'] =  '@web/images/profile_default.png';


		}else{

			$response['image'] =  '@web/images/no-image-available.jpg';
			$response['filePath'] =  '@web/images/no-image-available.jpg';
		}	

		$response['success'] = 0; 

	}
	//prd($response);

	return $response;
}
// date time in UTC
function dt($format = '',$date=''){
	if(empty($format)){
		$format = 'Y-m-d H:i:s';
	}
	
	if(empty($date)){
		$Date_time = new \DateTime();
		return $Date_time->format($format);
	}
	
	else{
		try {
			$Date_time = new \DateTime($format,strtotime($date));
			return $Date_time->format($format);
		} catch (Exception $e) {
			return date($format,strtotime($date));
			
		}
		
	}
}
	

?>