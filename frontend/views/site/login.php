<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?php echo Html::encode($this->title); ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php  $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'enableAjaxValidation' => false,
                    'enableClientValidation' => false
                ]); ?>

                <?php  echo $form->field($model, 'email_address')->textInput(); ?>

                <?php  echo $form->field($model, 'password')->passwordInput(); ?>

                <div class="form-group">
                    <?php echo Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']); ?>                              
                </div>
                    
            <?php ActiveForm::end(); ?>
            <div class="form-group">OR</div>
            <?php  $form = ActiveForm::begin(['id' => 'insta-login-form']); ?>
                   <?php echo Html::hiddenInput('instaLogin', 0);?>
                    <div class="form-group">           
                    <?php echo Html::button('Login With Instagram', ['class' => 'btn btn-warning', 'id' => 'instaBtn']); ?>       
                    </div>
            <?php ActiveForm::end(); ?>        
        </div>
    </div>
</div>
<script>
$(document).on("click","#instaBtn",function(){
    $("input[name=instaLogin]").val(1);    
    $("#insta-login-form").submit();
});
</script>
