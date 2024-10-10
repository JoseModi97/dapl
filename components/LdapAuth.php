<?php

namespace app\components;

use Yii;
use yii\base\Component;
use app\components\LDAPAuthenticator;
use yii\base\Exception;
use yii\web\ServerErrorHttpException;

class LdapAuth extends Component
{
    public $ldap_host;
    public $ldap_port;
    public $ldap_dn;
    public $ldap_user;
    public $ldap_password;

    private $authenticator;

    public function init()
    {
        parent::init();
        try {
            // Attempt to create the LDAPAuthenticator instance
            $this->authenticator = new LDAPAuthenticator(
                $this->ldap_host,
                $this->ldap_port,
                $this->ldap_dn,
                $this->ldap_user,
                $this->ldap_password
            );
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            if (YII_ENV_DEV) {
                $message .= ' File: ' . $th->getFile() . ' Line: ' . $th->getLine();
            }
            throw new ServerErrorHttpException($message, 500);
        }
    }

    public function authenticate($username, $password)
    {
        try {
            return $this->authenticator->authenticate($username, $password);
        } catch (Exception $e) {
            // Handle authentication errors
            Yii::error("LDAP Authentication Failed: " . $e->getMessage(), __METHOD__);
            throw new Exception("Authentication failed: " . $e->getMessage());
        }
    }

    public function close()
    {
        // Ensure the connection is closed safely
        if ($this->authenticator) {
            $this->authenticator->close();
        }
    }
}
