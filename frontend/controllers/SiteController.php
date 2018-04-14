<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\components\BaseController;
use common\components\InstaApi;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\LoginForm;
use common\models\User;

/**
 * Site controller
 */
class SiteController extends BaseController // Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['logout', 'signup'],
				'rules' => [
					[
						'actions' => ['signup'],
						'allow' => true,
						'roles' => ['?'],
					],
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$obj_insta = new InstaApi();
		$model = Yii::$app->user->identity;
		if(isset($model->u_insta_token) && !empty($model->u_insta_token)){
			$result = $obj_insta->getOwnerDetail($model->u_insta_token);
				if(isset($result['data']['counts']) && !empty($result['data']['counts'])){ 
					//update delete - in any access user change his detail and we are already logged in
					$model->u_first_name = $result['data']['full_name'];
					$model->u_insta_id = $result['data']['id'];					
					$model->u_username = $result['data']['username'];
					$model->u_image = $result['data']['profile_picture'];
					$model->u_bio = $result['data']['bio'];
					$model->u_website = $result['data']['website'];
				    $model->u_media = $result['data']['counts']['media'];
					$model->u_follows = $result['data']['counts']['follows'];
					$model->u_followed_by = $result['data']['counts']['followed_by'];
					$model->save();
				}
			
		}      

		$params = [];
		$params['model'] = $model;
		return $this->render('index',$params);
	}

	/**
	 * Logs in a user.
	 *
	 * @return mixed
	 */

	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		$obj_insta = new InstaApi();
		
		//instagram login       
		if(Yii::$app->request->post('instaLogin') == 1){
			
			$data = $obj_insta->authInstagram();                      
		}
		// callback from instagram 
		if(isset($_GET['code'])){                 
		   // Set access token
			$obj_insta->setAccess_token($_GET['code']);
			// Get user details
			$result = $obj_insta->getUserDetails();
			/* insta login */
			if(isset($result['access_token']) && isset($result['user']['id'])){
				//check user exists or not - if not then save 
				$userModel = User::validLogin('',$result['user']['id']);
				if(empty($userModel)){
					$userModel = new User();                   
				}
				$userModel->u_first_name = $result['user']['full_name'];
				$userModel->u_insta_id = $result['user']['id'];
				$userModel->u_insta_token = $result['access_token'];
				$userModel->u_username = $result['user']['username'];
				$userModel->u_image = $result['user']['profile_picture'];
				$userModel->u_bio = $result['user']['bio'];
				$userModel->u_website = $result['user']['website'];
				$userModel->u_isBusiness = $result['user']['is_business'];
				$userModel->password = "test@". Yii::$app->security->generateRandomString(4);
				$userModel->scenario = 'instalogin';

				if($userModel->validate()) {
								
					if($userModel->save()){                   
						
						//set pasword
						$userModel->setPassword($model->password);                   
						$userModel->removePasswordResetToken();
						$userModel->save();
						//autologin
						Yii::$app->user->login( $userModel, 3600 * 24 * 30 );
						//prd(Yii::$app->user->identity);
						return $this->redirect(['index']);
					
					}else{
						displayMessage(0,'SAVE_ERROR');
						return $this->redirect(['login']);
					}
						 
				}else{
					$error = \yii\widgets\ActiveForm::validate($userModel);
					 prd($error);
					displayMessage(0,'VALIDATE_ERROR');
					return $this->redirect(['login']);                  
				}

				pr($userModel);
				 prd($result);

			}else{
				displayMessage(0,'',$result['code'].' - '.$result['error_message']);
				return $this->redirect(['login']);   
			}
		   
		}else{

			if ($model->load(Yii::$app->request->post()) && $model->login()) {

				return $this->goBack();
			} else {
				return $this->render('login', [
					'model' => $model,
				]);
			}
		}          
	}

	// instalogin
	public function actionInstalogin(){

	}

	/**
	 * Logs out the current user.
	 *
	 * @return mixed
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

  
	/**
	 * Signs user up.
	 *
	 * @return mixed
	 */
	public function actionSignup()
	{
		$model = new User();

		if(Yii::$app->request->isPost){
			
			//echo Yii::getAlias('@userImg');exit;
			$data = Yii::$app->request->post('User');            
			$model->attributes = $data; 
				  
			if($model->validate()) {
								
				if($model->save()){                   
					
					//set pasword
					$model->setPassword($model->password);                   
					$model->removePasswordResetToken();
					$model->save();
					//autologin
					Yii::$app->user->login( $model, 3600 * 24 * 30 );
					//prd(Yii::$app->user->identity);
					return $this->redirect(['index']);
				  
				
				}else{
					 displayMessage(0,'SAVE_ERROR');
				}
					 
			}else{
				 /*$error = \yii\widgets\ActiveForm::validate($model);
				 prd($error);
*/                 displayMessage(0,'VALIDATE_ERROR');
			} 
		}
	   

		return $this->render('signup', [
			'model' => $model,
		]);
	} 
}