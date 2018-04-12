<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

function pr($var){
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

function prd($var){
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    exit;
}