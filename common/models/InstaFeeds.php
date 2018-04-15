<?php

namespace common\models;
use Yii;


/**
 * This is the model class for table "{{%insta_feeds}}".
 *
 * @property integer $ifd_id
 * @property integer $ifd_u_id
 * @property integer $ifd_feed_id
 * @property integer $ifd_type
 * @property string $ifd_url
 * @property string $ifd_link
 * @property string $ifd_caption
 * @property string $ifd_created
 */
class InstaFeeds extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%insta_feeds}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['ifd_u_id', 'ifd_feed_id', 'ifd_type', 'ifd_url', 'ifd_link', 'ifd_caption', 'ifd_created'], 'required'],
            [['ifd_u_id', 'ifd_type'], 'integer'],
            [['ifd_caption'], 'string'],
            [['ifd_u_id', 'ifd_feed_id', 'ifd_type', 'ifd_url', 'ifd_link', 'ifd_caption', 'ifd_created'], 'safe'],
            [['ifd_url', 'ifd_link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ifd_id' => 'Ifd ID',
            'ifd_u_id' => 'Ifd U ID',
            'ifd_feed_id' => 'Ifd Feed ID',
            'ifd_type' => '0=\'image\',1=\'video\',2=\'carousel\'',
            'ifd_url' => 'Ifd Url',
            'ifd_link' => 'Ifd Link',
            'ifd_caption' => 'Ifd Caption',
            'ifd_created' => 'Ifd Created',
        ];
    }

    /* save feeds */
    public function saveFeeds($request=array()){

        if(isset($request) && !empty($request) && isset(Yii::$app->user->identity->u_id) && !empty(Yii::$app->user->identity->u_id)){
           // prd($request);
            foreach ($request as $key => $value) {
                # code...
                $isExists = InstaFeeds::find()->where('ifd_u_id='.Yii::$app->user->identity->u_id.' AND ifd_feed_id LIKE "'.$value['id'].'"')->exists();

                if(!$isExists){
                    $model = new InstaFeeds();
                    $model->ifd_u_id = Yii::$app->user->identity->u_id;
                    $model->ifd_feed_id = $value['id'];
                    if($value['type']=='image'){
                        $model->ifd_type = 0;
                        $model->ifd_url = $value['images']['low_resolution']['url'];
                    }else if($value['type']=='video'){
                        $model->ifd_type = 1;
                        $model->ifd_url = $value['videos']['low_resolution']['url'];
                    }else if($value['type']=='carousel'){
                        $model->ifd_type = 2;
                        $model->ifd_url = $value['carousel_media'][0]['images']['low_resolution']['url'];
                    }

                    $model->ifd_link = $value['link'];
                    $model->ifd_caption = isset($value['caption']) ? $value['caption']['text'] : '';

                    $model->ifd_created =dt();

                    $model->save(false);
                }               
            }           
        }
    }
}
