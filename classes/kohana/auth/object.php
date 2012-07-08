<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Object Auth driver.
 * [!!] this Auth driver does not support roles nor autologin.
 * @author     Jakub Westfalewski <jwest@jwest.pl>
 */
class Kohana_Auth_Object extends Auth {

    // User list
    protected $_users;

    /**
     * Constructor loads the user list into the class.
     */
    public function __construct($config = array())
    {
        parent::__construct($config);

        // Load user list
        $this->_users = Arr::get($config, 'users', array());
    }

    /**
     * Logs a user in.
     *
     * @param   string   username
     * @param   string   password
     * @param   boolean  enable autologin (not supported)
     * @return  boolean
     */
    protected function _login($username, $password, $remember)
    {
        if (is_string($password))
        {
            // Create a hashed password
            $password = $this->hash($password);
        }

        $user = Object::factory( 'system' )->find_where( 'name', 'user-' . $username );
        
        if ( $user !== NULL AND $user->value === $password)
        {
            // Complete the login
            return $this->complete_login($username);
        }

        // Login failed
        return FALSE;
    }

    /**
     * Forces a user to be logged in, without specifying a password.
     *
     * @param   mixed    username
     * @return  boolean
     */
    public function force_login($username)
    {
        // Complete the login
        return $this->complete_login($username);
    }

    /**
     * Get the stored password for a username.
     *
     * @param   mixed   username
     * @return  string
     */
    public function password($username)
    {
        $user = Object::factory( 'system' )->find_where( 'name', 'user-' . $username );

        if ( $user !== NULL )
        {
            return $user->value;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Compare password with original (plain text). Works for current (logged in) user
     *
     * @param   string  $password
     * @return  boolean
     */
    public function check_password($password)
    {
        $username = $this->get_user();

        if ($username === FALSE)
        {
            return FALSE;
        }

        return ($password === $this->password($username));
    }

} // End Auth File