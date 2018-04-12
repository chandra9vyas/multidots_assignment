<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Auth Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <p>
        <?php echo Html::a('Create Auth Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php echo GridView::widget([
        'dataProvider' => $authItmes,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'name',
                // 'contentOptions' =>[''],
                'header' => 'RuleName',
                'content'=>function($data){
                    return $data['name'];
                }
            ],
           // 'name',
            'type',
            'description:ntext',  
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
