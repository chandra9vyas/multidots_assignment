<?php

namespace app\models;

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
            [['ifd_u_id', 'ifd_feed_id', 'ifd_type'], 'integer'],
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
}
