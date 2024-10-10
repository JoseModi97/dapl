<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Login';
?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-5">

            <div style="color:#999;">
                all passwords are <strong>password</strong> all users.<br>
                <div>
                    ou=mathematicians,dc=example,dc=com
                    <ol>
                        <li>riemann</li>
                        <li>gauss</li>
                        <li>euler</li>
                        <li>euclid</li>
                    </ol>
                </div>
                <div>
                    ou=scientists,dc=example,dc=com
                    <ol>
                        <li>einstein</li>
                        <li>newton</li>
                        <li>galieleo</li>
                        <li>tesla</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>