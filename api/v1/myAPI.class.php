<?php
require_once 'API.class.php';
class MyAPI extends API
{
    protected $User;

    public function __construct($request, $origin) {
        parent::__construct($request);

        // Abstracted out for example
        $APIKey = new APIKey();
        $User = new User();

        if (!array_key_exists('apiKey', $this->request)) {
            throw new Exception('No API Key provided');
        } else if (!$APIKey->verifyKey($this->request['apiKey'], $origin)) {
            throw new Exception('Invalid API Key');
        } else if (array_key_exists('token', $this->request) &&
            !$User->get('token', $this->request['token'])) {
            throw new Exception('Invalid User Token');
        }
        $this->User = $User;
    }

    /**
     * Example of an Endpoint
     */
     protected function example() {
        if ($this->method == 'GET') {
            return "Your name is " . $this->User->name;
        } else {
            return "Only accepts GET requests";
        }
     }
}


class APIKey
{
    function verifyKey($key, $source) {
        $json = file_get_contents('apikey.json');
        $keyset = json_decode($json);
        # Check apikey
        if (($keyset->source == $source) && ($keyset->apikey == $key) ) {
            #echo "key: $key, source: $source\n";
            return true;
        }
        return false;
    }
}

class User
{
    function get($what, $data) {
        $json = file_get_contents('apikey.json');
        $keyset = json_decode($json);
        # Check token
        if (($what == 'token') && ($data == $keyset->token)) {
            #echo "User: $what, $data\n";
            $this->name = 'Theo';
            return True;
        }
        return false;
    }

}