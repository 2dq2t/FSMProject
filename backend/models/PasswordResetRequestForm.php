<?php
namespace backend\models;

use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\backend\models\Employee',
                'filter' => ['status' => Employee::STATUS_ACTIVE],
                'message' => \Yii::t('app', 'There is no user with such email.')
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user Employee */
        $user = Employee::findOne([
            'status' => Employee::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            if (!Employee::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                return \Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->params['applicationName']])
                    ->setTo($this->email)
                    ->setSubject(\Yii::t('app', 'Password reset for ') . \Yii::$app->name)
                    ->send();
            }
        }

        return false;
    }
}
