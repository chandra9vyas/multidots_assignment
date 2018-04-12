<?php

namespace common\components;
use common\components\Translation;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use common\models\DbLog;
use yii\helpers\Html;


class BaseModel extends ActiveRecord
{
	public $status_arr = array('Inactive','Active');  
	

	/* validate data - html purifiier */
    public function beforeValidate()
    { 
      
        $valueChecked = [];
        foreach( $this->attributes as $key => $val){     
        	 
            if(($this->getTableSchema()->columns[$key]->type == 'string'  ||  $this->getTableSchema()->columns[$key]->type == 'text') ){           
            	if(!is_array( $val ))
            		$valueChecked[$key] = trim($val);
            }
        }           
       
        $this->attributes = self::filterPostRequest( $valueChecked );
        return parent::beforeValidate();        
    }
	
    /* after find */
    public function afterfind()
    {
    	//Html::decode($userName);
    	foreach( $this->attributes as $key => $val){
    	
    		if((($this->getTableSchema()->columns[$key]->type == 'string'  ||  $this->getTableSchema()->columns[$key]->type == 'text')) && is_string($val) ){
    			//prd($this->attributes);//[$key] = Html::decode($val);
    			$this->setAttribute($key, Html::decode($val));
    		}
    	}
    	return parent::afterfind();
    }
    /* validate post data*/
	public function filterPostRequest($arr = array()){

		if( isset($arr) && !empty($arr)){
		 	
		 	foreach($arr as $key=>$val){
				
				if(is_array($val)){						
				
					$arr[$key] = self::filterPostRequest( $val );

				}else{

					$arr[$key] = \yii\helpers\HtmlPurifier::process($val);				
					
				}				
			}
		}
		return $arr;
	}

	//validation check max length
	public function checkMaxLength( $attribute, $params){		
		if(!empty($attribute)){
			$response = self::checkNumberLength($this->$attribute, $params);
			if(!empty($response))
				$this->addError($attribute, $response);			
		}
	}
	// check number field length
	public function checkNumberLength ($number=0, $max=10){
	
		$response ='';
		$val = explode('.', $number);
		
		if(isset($val[0]) && !empty($val[0]))
			$number = $val[0];
			
		if(strlen($number) > $max)
			$response = Yii::t('app','Max Length should be').' '.$max.' '.Yii::t('app','digits before decimal.');

		return $response;
	}
	
}