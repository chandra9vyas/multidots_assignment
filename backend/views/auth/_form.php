<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper; // load classes

/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>
<!-- Main content -->
<section class="content"> 

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(
            [
                'id'=>'auth-form',
                'options'=>['enctype'=>'multipart/form-data'],
                'enableClientValidation'=>true,
            ]
        ); 
    ?>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 dashboard-white-contend">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="">
                        <?php echo  $form->field($model, 'name')->textInput(['maxlength' => true,])->hint('Hint: FrontendControllerAction') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="">
                        <?php $dataList = ArrayHelper::map($authRoles, 'name', 'name');

                            echo $form->field($modelAuthChild, 'parent')->dropDownList($dataList, ['prompt'=>'Select Parent','multiple'=>true,'size'=>'4']);
                        ?>
                       
                    </div>
                </div>
                <div class="row">
                    <div class="">
                        <?php echo $form->field($model, 'description')->textarea(['rows' => 4]) ?>
                    </div>
                </div> 
                 <div class="row">
                    <div class="">
                        <?php echo $form->field($model, 'data')->textarea(['rows' => 4]) ?>
                    </div>
                </div> 
                <div class="row">
                    <div class="">
                        <div class="form-group">
                            <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        </div>
                     </div>
                </div>     
        </div>
    </div>    
    <?php ActiveForm::end(); ?>

</div>
</section>
