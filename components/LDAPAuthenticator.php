<?php

namespace app\components;

use Exception;

class LDAPAuthenticator
{
    private $ldap_host;
    private $ldap_port;
    private $ldap_dn;
    private $ldap_user;
    private $ldap_password;
    private $ldap_connection;

    public function __construct($ldap_host, $ldap_port, $ldap_dn, $ldap_user, $ldap_password)
    {
        $this->ldap_host = $ldap_host;
        $this->ldap_port = $ldap_port;
        $this->ldap_dn = $ldap_dn;
        $this->ldap_user = $ldap_user;
        $this->ldap_password = $ldap_password;
        $this->connect();
    }

    // Establish LDAP connection and bind using admin credentials
    private function connect()
    {
        $this->ldap_connection = ldap_connect($this->ldap_host, $this->ldap_port);
        if (!$this->ldap_connection) {
            throw new Exception('Could not connect to LDAP server.');
        }

        ldap_set_option($this->ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldap_connection, LDAP_OPT_REFERRALS, 0);

        if (!ldap_bind($this->ldap_connection, $this->ldap_user, $this->ldap_password)) {
            throw new Exception('Could not bind to LDAP server.');
        }
    }

    // Authenticate the user
    public function authenticate($username, $password)
    {
        // Use 'uid' instead of 'samaccountname' based on your LDAP configuration
        $search_filter = "(uid=$username)";
        $search = ldap_search($this->ldap_connection, $this->ldap_dn, $search_filter);

        if (!$search) {
            throw new Exception('LDAP search failed.');
        }

        $entries = ldap_get_entries($this->ldap_connection, $search);

        if ($entries['count'] > 0) {
            $user_dn = $entries[0]['dn'];
            if (@ldap_bind($this->ldap_connection, $user_dn, $password)) {
                return 'Authenticated successfully.';
            } else {
                return 'Invalid credentials.';
            }
        } else {
            return 'User not found.';
        }
    }

    // Close the LDAP connection
    public function close()
    {
        ldap_unbind($this->ldap_connection);
    }
}
