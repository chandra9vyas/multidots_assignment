<?php

namespace common\components;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper; // load classes
use yii\helpers\Json;

use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;


// use yii\web\Request

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class BaseController extends Controller
{

	/**
	 * @var string the default layout for the controller view. Defaults to 'column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = 'main';

	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $MENU = array();
	
	
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $title = '';
	public $user_id = '';
	public $grid_title = '';

	public $breadcrumbs = array();
	// AJAX RESPONSE FORMAT, 
	public $ajaxResponse = ['isloggedout' => 0, 'hasaccess' => 1, 'error' => 0, 'message' => [], 'data' => ""];
	

	public function init()
	{
		parent::init();
	}

	// TO CHECK THE ACCESS FOR THE REQUEST URL OR DATA
	public function beforeAction($action)
	{
		
		
		
		$controllerId = $action->controller->id;
		$actionId = $action->id;
		
		/* for error page */
		if ($action->id == 'error') {
            $this->layout = 'error';
        }

        if (\Yii::$app->request->isAjax)
        {
        	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        }
        
        $front_back ="Frontend";
        
        
		$operation = $front_back.ucfirst(strtolower($controllerId)) . ucfirst(strtolower($actionId));
	

		if (!\Yii::$app->user->can($operation))
		{

			if (\Yii::$app->request->isAjax)
			{
				
				if (!\Yii::$app->user->isGuest)
				{
					$this->ajaxResponse['hasaccess'] = 0;
				}
				else
				{
					$this->ajaxResponse['isloggedout'] = 1;
				}
				//print(json_encode($this->ajaxResponse));
			}
			else
			{		
			
				if(Yii::$app->user->isGuest) {

					\Yii::$app->user->loginUrl = ['site/login','return'=>\Yii::$app->request->url];
					
					return $this->redirect(\Yii::$app->user->loginUrl)->send();
					
				}else{			

					//prd($operation);
					
					if($operation!="FrontendSiteLogout"){
						//displayMessage(0,'UNAUTHORIZE_ACCESS');							
						return $this->redirect(['site/index'])->send();		
					}else{
						$this->goHome();
					}
				}
			}
			
		}
		else
		{ 
			
			if (\Yii::$app->request->isAjax)
			{
				
			}
			else
			{
				
			}
		}  		
		
		return  parent::beforeAction($action);
	}
	
	/*
	 * Image Thumbnail creation function
	*/
	public function createThumb($width, $height, $destination, $newimgpath){

		//Resizing and Preserving Aspect Ratio
		$thumb = Image::getImagine()->open($destination)->thumbnail(new Box($width, $height))->save($newimgpath , ['quality' => 90]);
		// only thumb
		//$thumb = Image::thumbnail( $destination, $width, $height)->save(Yii::getAlias($newimgpath), ['quality' => 80]);
		
		if(!$thumb)
			echo "error";
		
		
	}

	/* function to upload file using model and its col name*/
	public function uploadFile($objModel, $col, $dirUpload, $thumb = 0, $thumbWidth, $thumbHeight, $thumbPrefix, $oldImg=NULL){

        if(!empty($objModel) && !empty($col) && !empty($dirUpload)){
            
            $path = Yii::getAlias('@adminUploads').'/'.$dirUpload.'/'; 
            $file = UploadedFile::getInstance($objModel, $col);
            if(isset(  $file->name ) && !empty(  $file->name )){
	            $ext = end((explode(".", $file->name)));
	            $file_name = 'ls_'.Yii::$app->security->generateRandomString(5).".{$ext}";
	            $file_path = $path . $file_name;
	           
	            if($file->saveAs($file_path)){
	               
	                if($thumb == 1){ // create thumb
	                	$destination =  $file_path;                	
	                	$newPath = $path.$thumbPrefix.'_'.$file_name;                 	
	                	$thumb_img = $this->createThumb($thumbWidth, $thumbHeight, $destination, $newPath);
	                }

	                if($oldImg!=NULL){ // unlink old image                	
	                    @unlink($path.$oldImg);
	                    @unlink($path.$thumbPrefix.'_'.$oldImg);
	                } 
	                
	                return $file_name;
	            }         
	         }   
                     
        }
        return false;
    }  
}