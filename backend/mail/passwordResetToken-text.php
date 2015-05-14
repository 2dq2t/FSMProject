<?php

/* @var $this yii\web\View */
/* @var $user backend\models\Employee */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Hello <?= $user->full_name ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
