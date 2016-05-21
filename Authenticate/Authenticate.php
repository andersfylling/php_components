<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 20/05/16
 * Time: 02:45
 */

namespace php_components;


class Authenticate 
    extends \php_components_essentials\Events
{

    private $user;
    private $credentials;
    private $tokens;

    private $config;
    
    public function __construct(
        array   &$user,
        string  $csrf = '',
        array   $config = [
                    'ajax' => false
                ]
    )
    {
        $this->user = $user;
        $this->config = $config;

        $this->credentials = [
            'username' => '',
            'password' => ''
        ];

        $this->tokens = [
            'csrf' => $csrf
        ];
    }

    /**
     * Log in a user. The user object is only filled with info on success.
     *
     * @event error     emptyField      One of the form fields are empty.
     * @event error     emptyFields     POST was empty.
     * @event error     error           Something went wrong.
     * @event success   match           Successfully matched credentials!
     * @event success   noMatch         Username or password was incorrect.
     * @event success   completed       Last event to be fired, either login success or failure due to typo.
     * @event all       *               Listen for any event. Only called if not the event called has been added.
     *
     * @param callable  $lookup         Callback where the programmer does a database lookup for matches.
     * @param array     $keys_username  username keys that can be found in the POST array.
     * @param array     $keys_password  password keys that can be found in the POST array.
     * @param array     $keys_csrf      csrf keys that can be found in the POST array.
     */
    public function login
    (
        callable    $lookup,
        array       $keys_username      = ['username',  'email',        'login'     ],
        array       $keys_password      = ['password',  'pwd',          'logintoken'],
        array       $keys_csrf          = ['csrf',      'csrftoken',    'token'     ]
    )
    {
        /**
         * Retrieves the values from the POST. Only checks 
         *  values of keys specified in the parameter.
         *
         * @param array $keys
         * @return string or exit() on event trigger.
         */
        $getValue = function ($keys, $field)
        {
            /**
             * Loop through set keys, set $len to -1 if no matching keys were found.
             */
            $len = sizeof($keys);
            while (--$len !== -1 && isset( $_POST[$keys[$len]] ) === false);

            if ($len === -1 && empty($keys) === false)
            {
                $res =
                    'Could not find any matching keys for '
                    . $field
                    . '. Cannot login with missing credential values!';

                $this->triggerEvent('emptyField', [403, $res]);

                return ''; //this will never call if 'emptyField'-event is set.
            }
            else
            {
                return $_POST[$keys[$len]];
            }
        };


        /**
         * Make sure it's an POST request!
         */
        if ($this->config['ajax'] && $_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            $this->triggerEvent('error', [403, 'Must be a post request']);
        }

        /**
         * If post is empty don't waste time on the loop.
         *
         * Don't call an error event as the form might not have been
         *  submitted on this check.
         */
        if ($this->config['ajax'] && empty($_POST) === true)
        {
            $this->triggerEvent('error', [403, 'POST cannot be empty!']);
        }
        else if (empty($_POST) === true)
        {
            return;
        }

        /**
         * Before getting the form values, lets verify the csrf token
         *  if set.
         *
         * Get csrf as csrf | csrftoken | token
         */
        if ($this->tokens['csrf'] !== $getValue($keys_csrf, 'csrf'))
        {
            var_dump($getValue($keys_csrf, 'csrf'));
            $this->triggerEvent('error', [403, 'Wrong csrf token.']);
        }

        /**
         * Get username as username | email | login,
         *  password as password | pwd | logintoken,
         */
        $this->credentials['username']  = $getValue($keys_username, 'username');
        $this->credentials['password']  = $getValue($keys_password, 'password');

        /**
         * Do a callback to let the user find search results from
         *  their database.
         */
        $lookup($this->credentials, function ($err = null, $res = []) {
            /**
             * Handle lookup error.
             */
            if ($err != null)
            {
                $this->triggerEvent('error', [500, $err]);
            }

            $response = [403, 'Wrong username or password.'];
            /**
             * If the result returned was empty, send an response that the request was unsuccessful.
             */
            if (empty($res) === true)
            {
                $response = [403, 'No database matches..']; //This really isn't needed.. But hey.. whatever.
            }
            
            /**
             * successfully found username, now.. how to match password..?
             *
             * TODO: find a better way to match passwords..
             * TODO: Res might also be an multidimensional array..
             */
            else if ($res['password'] === $this->credentials['password']) //this is just awful..
            {
                $response = [200, 'Successfully logged in.'];

                $this->user = $res; //might be multidimensional..
            }

            /**
             * Fire last event "completed".
             */
            $this->triggerEvent('completed', $response);
        });
    } // login method ends here.
}