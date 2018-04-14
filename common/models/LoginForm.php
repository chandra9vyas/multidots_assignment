<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email_address;
    public $username;
    public $password;
    public $rememberMe = true;
    public $autoLogin = 0; //1 for autologin 

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            //[['username', 'password'], 'required'],
            [['email_address', 'password'], 'required'],
            [['email_address'], 'email','message'=>Yii::t('app','Enter a valid email address.')],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['autoLogin','safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email_address' => Yii::t('app', 'Email Address'),
            'password' => Yii::t('app', 'Password'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        //prd($attribute);
        if (!$this->hasErrors()) {
            $user = $this->getUser();         
            
            if(!empty($user)) {
                // prd($user);              
                if((!$user || !$user->validatePassword($this->password)) && $this->autoLogin==1){
                  
                    $this->addError('The email address and password you entered doesn\'t match.');
                }          
                                        
            }else{
                
                $this->addError('Sorry, Our system doesn\'t recognize that email address.');
            
            }    
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            //return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        	return Yii::$app->user->login($this->getUser(), 3600 * 24 * 30 );
        } else {
            return false;
        }
    }   

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
       
        if ($this->_user === null) {
            $this->_user = User::validLogin($this->email_address);
        }

        return $this->_user;
    }
}
