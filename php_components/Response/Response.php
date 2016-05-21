<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 21/05/16
 * Time: 06:20
 */

namespace php_components;


class Response implements \php_components_essentials\Http
{
    /**
     * Consult https://developer.mozilla.org/en-US/docs/Web/HTTP/Response_codes
     *  For information about each one.
     *
     * I recommend removing every code that you do not need. I have marked those
     *  I view as common once on the web 23.01.2016
     *
     * I designed the array with two varaibles \ index per line since its a long list
     *
     * @var array $httpCodes
     */
    private $httpCodes = [
        /* Informational Responses */
        'continue'                      => 100,                 'switchingProtocol'             => 101,
        /* Successful Responses */
        'ok'                            => 200, /* Common */    'created'                       => 201,
        'accepted'                      => 202, /* Common */    'nonAuthoritativeInformation'   => 203,
        'noContent'                     => 204, /* Common */    'resetContent'                  => 205,
        'partialContent'                => 206, /* Common */
        /* Redirection Messages */
        'multipleChoice'                => 300,                 'movedPermanently'              => 301, /* Common */
        'found'                         => 302,                 'seeOther'                      => 303,
        'notModified'                   => 304, /* Common */    'useProxy'                      => 305,
        'unused'                        => 306,                 'temporaryRedirect'             => 307, /* Common */
        'permanentRedirect'             => 308, /* Common */
        /* Client Error Responses */
        'badRequest'                    => 400, /* Common */    'unauthorized'                  => 401, /* Common */
        'paymentRequired'               => 402, /* Common */    'forbidden'                     => 403, /* Common */
        'notFound'                      => 404, /* Common */    'methodNotAllowed'              => 405, /* Common */
        'notAcceptable'                 => 406, /* Common */    'proxyAuthenticationRequired'   => 407,
        'requestTimeout'                => 408, /* Common */    'conflict'                      => 409,
        'gone'                          => 410,                 'lengthRequired'                => 411,
        'preconditionFailed'            => 412,                 'payloadTooLarge'               => 413,
        'URITooLong'                    => 414,                 'unsupportedMediaType'          => 415, /* Common */
        'requestedRangeNotSatisfiable'  => 416,                 'expectationFailed'             => 417,
        'imATeapot'                     => 418,                 'misdirectedRequest'            => 421,
        'upgradeRequired'               => 426,                 'preconditionRequired'          => 428,
        'tooManyRequests'               => 429, /* Common */    'requestHeaderFieldsTooLarge'   => 431,
        /* Server Error Responses */
        'internalServerError'           => 500, /* Common */    'notImplemented'                => 501, /* Common */
        'badGateway'                    => 502,                 'serviceUnavailable'            => 503,
        'gatewayTimeout'                => 504,                 'HTTPVersionNotSupported'       => 505,
        'variantAlsoNegotiates'         => 506,                 'variantAlsoNegotiates2'        => 507,
        'networkAuthenticationRequired' => 511,                 'permissionDenied'              => 550,
        /* Custom Responses */
    ];

    /**
     * https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
     */
    private $request_methods = [
        'GET'           => 1,
        'HEAD'          => 1,
        'POST'          => 1,
        'PUT'           => 1,
        'DELETE'        => 1,
        'OPTIONS'       => 0,
        'PATCH'         => 0,
        'TRACE'         => 0,
        'CONNECT'       => 0
    ];

    private $content_types = [
        'pdf'   => 'application/pdf',
        'txt'   => 'text/plain; charset: UTF-8',
        'php'   => 'text/plain; charset: UTF-8',
        'html'  => 'text/html; charset: UTF-8',
        'css'   => 'text/css; charset: UTF-8',
        'json'  => 'application/json; charset: UTF-8',
        'mp4'   => 'video/mp4',
        'gif'   => 'image/gif',
        'png'   => 'image/png',
        'jpg'   => 'image/jpg'
    ];

    public function allowed_request_methods_to_string   ()
    {
        $string = '';

        foreach ($this->request_methods as $key => $value)
        {
            if ($value === 1)
            {
                $string .= $key . ', ';
            }
        }

        return $string;
    }

    public function add_request_method                  (string $method, int $value = 1)
    {
        $method = strtoupper($method);
        return $this->request_methods[$method] = $value === 0 ? 0 : 1;
    }
    public function enable_request_method               (string $method)
    {
        $method = strtoupper($method);
        if (isset($this->request_methods[$method]) === true)
        {
            $this->request_methods[$method] = 1;
            return true;
        }

        return false;
    }
    public function disable_request_method              (string $method)
    {
        $method = strtoupper($method);
        if (isset($this->request_methods[$method]) === true)
        {
            $this->request_methods[$method] = 0;
            return true;
        }

        return false;

    }
    public function remove_request_method               (string $method)
    {
        $method = strtoupper($method);
        if (isset($this->request_methods[$method]) === true)
        {
            unset($this->request_methods[$method]);
            return true;
        }

        return false;
    }
    public function is_request_method_allowed           (string $method)
    {
        $method = strtoupper($method);
        if (isset($this->request_methods[$method]) === true)
        {
            return $this->request_methods[$method];
        }

        return false;
    }
    public function is_request_method_known             (string $method)
    {
        $method = strtoupper($method);
        return (isset($this->request_methods[$method]) === true);
    }

    public function content_types_to_string             ()
    {
        $string = '';

        foreach ($this->content_types as $key => $value) 
        {
            $string .= $value . ', ';
        }

        return $string;
    }
    public function is_content_type_allowed             ()
    {
        return true; //TODO: fix?
    }
    public function get_detailed_content_type           (string $trivial = 'txt')
    {
        $content = 'txt';

        if (isset($this->content_types[$trivial]) === true) 
        {
            $content = $this->content_types[$trivial];
        } 
        else 
        {
            $content = $this->content_types[$content];
        }

        return $content;
    }


    public function __construct ()
    {

    }




/*
    protected function content_isAllowed ($type) {
        $result = '';   //returns the not allowed content type

        /**
         * First remove anything besides the content type
         *  eg. text/plain; charset: UTF-8 => text/plain
         *
         * Then flip the array for simpler
         */
    /*
        $arr = array_map(function ($type) {
            return explode(';', $type)[0];
        }, $this->content_type_arr);
        $arr = array_flip($arr);

        // Test if the type is allowed
        if (is_string($type) === TRUE) {
            if (isset($this->content_upload_type_arr[$type]) === FALSE) {
                $result = $type;
            }
        } else if (is_array($type) === TRUE) {
            foreach ($type as $t) {
                if (isset($this->content_upload_type_arr[$t['type']]) === FALSE) {
                    $result = $t['type'];
                    break;
                }
            }
        }

        //return the results, TRUE or content type
        if ($result === '') {
            return TRUE;
        } else {
            return $result;
        }
    }
    */

    /**
     * Private headers
     */
    private function setResponseCode ($code = 200) 
    {
        if ($code !== 200) 
        {
            if (is_int($code) === TRUE && in_array($code, $this->httpCodes)) 
            {
                //$code is valid
            } 
            else if (is_string($code) && isset($this->httpCodes[$code]) === TRUE) 
            {
                //key name was used, set code to its value
                $code = $this->httpCodes[$code];
            } 
            else 
            {
                $code = 200; //fallback code
            }
        }

        http_response_code($code);
    }

    private function addHeaders (array $headers)
    {
        $i = sizeof($headers);
        while (--$i !== -1)
        {
            header($headers[$i]);
        }
    }

    private function setHeaders (string $type = '', int $length = 0)
    {
        header('Access-Control-Allow-Methods: ' . $this->allowed_request_methods_to_string());
        header('Cache-Control: public, max-age=0, no-cache');
        header('Access-Control-Allow-Credentials: true');

        if ($type !== '')
        {
            header('Content-Type: ' . $this->get_detailed_content_type($type));

            if ($length !== 0)
            {
                header('Content-Length: ' . $length);
            }
        }
    }

    /**
     * Public methods
     */

    /**
     * Method that only sends success codes.
     *
     * @param int       $code
     */
    public function success(int $code = 200)
    {
        $this->setResponseCode($code);
        exit;
    }

    /**
     * Method that sends a string as response
     *
     * @param string    $res
     * @param int       $code
     */
    public function send(string $res = '', int $code = 200)
    {
        $type = 'txt';

        /**
         * Convert response to a string type.
         */
        $res = (string)$res;

        /**
         * Set headers.
         */
        $this->setResponseCode($code);
        $this->setHeaders($type, strlen($res));

        /**
         * Send string data and exit.
         */
        echo $res;
        exit;
    }


    public function json(array $res = [], int $code = 200)
    {
        $type = 'json';

        /**
         * Convert response to a json type.
         */
        $res = json_encode($res);

        /**
         * Set headers.
         */
        $this->setResponseCode($code);
        $this->setHeaders($type, strlen($res));

        /**
         * Send json data and exit.
         */
        echo $res;
        exit;
    }

    public function failure(int $code = 500, array $headers = [])
    {
        $this->setResponseCode($code);
        $this->addHeaders($headers);
        $this->setHeaders();

        /**
         * Exit
         */
        exit;
    }

    /**
     * Sends a text file (.txt extension)
     *
     * @param string    $file   Requires full path to file (or relative, base is set by .htaccess)
     * @param int       $code
     */
    public function text(string $file = '', int $code = 200)
    {
        $type = 'txt';

        /**
         * Checks if file exists
         */
        if ($file === '' || file_exists($file) === false) 
        {
            $code = 404;
            $type = null;
        }

        /**
         * Set headers
         */
        $this->setResponseCode($code);
        $this->setHeaders($type);

        /**
         * Check if the file exists and require it if so
         *  otherwise set a error header
         */
        if ($code !== 404) 
        {
            require $file;
        }

        /**
         * exit script
         */
        exit;
    }

    public function html(string $file = '', int $code = 200)
    {
        $type = 'html';

        /**
         * Checks if file exists
         */
        if ($file === '' || file_exists($file) === false) 
        {
            $code = 404;
            $type = null;
        }

        /**
         * Set headers
         */
        $this->setResponseCode($code);
        $this->setHeaders($type);

        /**
         * Check if the file exists and require it if so
         *  otherwise set a error header
         */
        if ($code !== 404) 
        {
            require $file;
        }

        /**
         * exit script
         */
        exit;
    }
}