<?php

namespace League\OAuth2\Client\Exception;

class IDPException extends \Exception
{
    protected $result;

    public function __construct($result)
    {
        if (is_string($result)) {
            $jsonString = json_decode($result, true);
            if (is_array($jsonString)) {
                $result = $jsonString;
            }
        }
        $this->result = $result;

        $code = isset($result['code']) ? $result['code'] : 0;

        if (isset($result['error'])) {

            // OAuth 2.0 Draft 10 style
            $message = $result['error'];

        } elseif (isset($result['message'])) {

            // cURL style
            $message = $result['message'];

        } else {

            $message = 'Unknown Error.';

        }

        parent::__construct($message, $code);
    }

    public function getType()
    {
        if (isset($this->result['error'])) {

            $message = $this->result['error'];

            if (is_string($message)) {
                // OAuth 2.0 Draft 10 style
                return $message;
            }
        }

        return 'Exception';
    }

    /**
     * To make debugging easier.
     *
     * @return string The string representation of the error.
     */
    public function __toString()
    {
        $str = $this->getType() . ': ';

        if ($this->code != 0) {
            $str .= $this->code . ': ';
        }

        return $str . $this->message;
    }
}
