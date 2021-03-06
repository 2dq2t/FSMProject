<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;

    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'FLoginUserName'),
            'password' => Yii::t('app', 'FLoginPassword'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getCustomer();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app','Login_Error_Msg'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getCustomer());
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return Customer|null
     */
    public function getCustomer()
    {
        if ($this->_user === false) {
            $this->_user = Customer::findByUsername($this->username);
        }

        return $this->_user;
    }
}
