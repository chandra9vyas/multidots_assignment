<?php
namespace common\models;
use Yii;

use common\components\BaseModel;
use yii\web\IdentityInterface;

use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

use common\components\PasswordHelper;

use common\models\AuthAssignment;


/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $u_id
 * @property string $u_unique_id
 * @property integer $u_role
 * @property string $u_first_name
 * @property string $u_last_name
 * @property string $u_username
 * @property string $u_email
 * @property string $u_image
 * @property string $u_password_hash
 * @property string $u_password_reset_token
 * @property string $u_salt
 * @property string $u_auth_key
 * @property string $u_dob
 * @property string $u_phone
 * @property integer $u_status
 * @property string $u_insta_token
 * @property string $u_created
 * @property string $u_modified
 */
class User extends BaseModel implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    public $password;
    public $confirm_password;
    public $changePassword = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['u_first_name', 'u_last_name','u_email', 'password'], 'required'], 
            [['u_email'], 'unique'], 
            [['u_email'], 'email'], 
            [['u_first_name', 'u_last_name'], 'string', 'max' => 20],
            [['u_username', 'u_salt'], 'string', 'max' => 50],
            [['u_email', 'u_image', 'u_password_hash', 'u_password_reset_token', 'u_insta_token'], 'string', 'max' => 255],
            [['u_auth_key'], 'string', 'max' => 32],
            [['u_phone'], 'string', 'max' => 15],

            // password validation
            [['password','confirm_password'],'checkChangePassword', 'skipOnEmpty' => false],
            ['password','match','pattern' => '/^.*(?=.*\d)(?=.*[a-z])(?=.*[@#$%^&+=]).*$/','message'=>Yii::t('app','Password must have alphanumeric and special characters.')],
          

            [['u_first_name', 'u_last_name', 'u_username', 'u_email', 'u_role', 'u_password_hash',  'u_phone', 'u_created', 'u_modified','u_dob', 'u_salt', 'u_created', 'u_modified','u_image','u_status', 'password', 'confirm_password','u_insta_token','changePassword'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'u_id' => 'U ID',            
            'u_role' => '1= \'admin\', 2=\'user\'',
            'u_first_name' => 'First Name',
            'u_last_name' => 'Last Name',
            'u_username' => 'Username',
            'u_email' => 'Email',
            'u_image' => 'Image',
            'u_password_hash' => 'U Password Hash',
            'u_password_reset_token' => 'U Password Reset Token',
            'u_salt' => 'U Salt',
            'u_auth_key' => 'U Auth Key',
            'u_dob' => 'U Dob',
            'u_phone' => 'U Phone',
            'u_status' => '0 = inactive, 1 = active, 2 = deleted,3=\'blocked\'',
            'u_insta_token' => 'U Insta Token',
            'u_created' => 'U Created',
            'u_modified' => 'U Modified',
        ];
    }

    /*
    * check admin wants to change password of employee
    */
    public function checkChangePassword($attribute, $params){
        //echo 1;exit;
        if($this->changePassword == 1){
            if(empty($this->password))
                $this->addError('password', Yii::t('app','Password cannot be blank.'));
            elseif(isset($this->password) && empty($this->confirm_password))
                $this->addError('confirm_password', Yii::t('app','Confirm Password cannot be blank.'));
        }
    }


    /**
     * @inheritdoc
     * before save  
     */

    public function beforeSave($insert)
    {       
        $this->u_modified = dt();       
       
/*
       if(empty($this->u_unique_id))
            $this->u_unique_id = Yii::$app->security->generateRandomStrings(4); */

        if($this->isNewRecord){
            
            $this->generateSalt();        
            $this->generateAuthKey();
            $this->generatePasswordResetToken();
            $this->u_username = $this->u_first_name.'_'.time();
            $this->u_created = dt();           
        }
        
        return parent::beforeSave($insert);
    }
    
     /**
     * @inheritdoc 
     * after save  
     */
    public function afterSave($insert, $changedAttributes)
    {
        
        if(empty($this->u_unique_id)){
            $this->u_unique_id = str_pad($this->u_id, 4, '0', STR_PAD_LEFT);
            $this->save();
        }

        if(empty($this->u_role))
            $this->u_role = 2; 

        
        if($insert){                  

            $authAssignment = new AuthAssignment;
            $authAssignment->isNewRecord = true; 

            $authAssignment->user_id = $this->u_id;
            $authAssignment->item_name =  ($this->u_role==2) ? 'user' : 'admin';           
            $authAssignment->save(false);

        }     
                
      
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $user = User::find()
                    ->where(['=', 'u_id', $id]) 
                    ->andwhere(['=','u_status',self::STATUS_ACTIVE])  
                    ->one();                    
        return $user;//static::findOne(['u_id' => $id, 'u_status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['u_username' => $username, 'u_status' => self::STATUS_ACTIVE]);
    }
    // find by email
     public static function findByEmail($email)
    {
        return static::findOne(['u_email' => $email, 'u_status' => self::STATUS_ACTIVE]);
    }
    
    // return user first last name
    public static function GetUsername($id)
    {
        $userDetail =  static::findOne($id);
        if(isset($userDetail['u_id'])){
            return $userDetail['name'];
        }
        return "--";
    }
    /**
     * Finds user by username or email
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsernameEmail($username)
    {
        return static::find()->where(['u_username' => $username])->orWhere(['u_email'=>$username])->andWhere(['u_status' => self::STATUS_ACTIVE])->one();
    }

      
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        //echo 1;exit;
        if (!static::isPasswordResetTokenValid($token)) {           
            return null;
        }

        return static::findOne([
            'u_password_reset_token' => $token,
            'u_status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {

        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        $parts = explode('_', $token);        
        $timestamp = (int) end($parts);      
        return $timestamp + $expire >= time();
    }


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->u_auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        //return Yii::$app->security->validatePassword($password, $this->u_password_hash);
        return PasswordHelper::validatePassword($password, $this->u_password_hash, $this->u_salt);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        //$this->u_password_hash = Yii::$app->security->generatePasswordHash($password);
        $this->u_password_hash = PasswordHelper::generatePasswordHash($password, $this->u_salt);
    }
    

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->u_auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password salt
     */
    public function generateSalt()
    {
        $this->u_salt = Yii::$app->security->generateRandomString(8);
    }

   /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->u_password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->u_password_reset_token = null;
    }

    /*
     validate login find by email and role of user
    */
    public function validLogin($email){ 
        
        $user = User::find()
                    ->where(['=', 'u_email', $email]) 
                    ->andwhere(['!=','u_status','2'])                 
                    ->joinWith(['positionRole'])
                    ->one();
                    
        return $user;
    }

    /* set user unique id */
    public function setUniqueId(){

        $lastInsertedId = User::find()  
                         ->select(['u_id'])
                         ->orderBy(['u_id'=> SORT_DESC])  //->asArray()
                         ->one();
        
        if(empty($lastInsertedId->u_id))
            $next = 1;
        else
            $next = $lastInsertedId->u_id + 1;

        $uniqueId = str_pad( $next, 4, '0', STR_PAD_LEFT);

        $checkExists = 0;
        
        While($checkExists < 1){

            $checkExists =  User::find()->where('u_unique_id ="'.$uniqueId .'"')->exists();
            
            if(!$checkExists){
                break;
            }else{
                
                $next++;
                $uniqueId = str_pad( $next, 4, '0', STR_PAD_LEFT);
            }

        }   

        return $uniqueId;
    }

}