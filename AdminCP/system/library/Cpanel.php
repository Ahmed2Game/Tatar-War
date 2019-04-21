<?php
/**********************************************************************/

/* cPanel JSON API Class
/* @author: Mohammad Raquib
/* Date: 27th Sept 2017
/* Github URL: https://www.github.com/mohammadraquib/cPanelAPI
/* Copyright © 2017 Mohammad Raquib
/**********************************************************************/

class CPANEL
{
    //Variables
    private $url; //cPanel URL prefixed with either http:// or https:// and suffixed with either port number 2082 or 2083.
    private $username; //cPanel username.
    private $password; //cPanel password.
    private $api_version; //cPanelAPI Version. Default is 2 if no value for version is passed in constructor.

    //Method - Class Constructor
    public function __construct(array $data)
    {
        if (isset($data['url']) && isset($data['username']) && isset($data['password'])) {
            if (strpos($data['url'], 'http://') || strpos($data['url'], 'https://') && strpos($data['url'], ':2082') || strpos($data['url'], ':2083')) {
                $this->url = $data['url'];
                $this->username = $data['username'];
                $this->password = $data['password'];
                $this->api_version = isset($data['api_version']) ? $data['api_version'] : 2;
            } else {
                throw new Exception('cPanel URL should be prefixed with either http:// or https:// and suffixed with either port number 2082 or 2083.');
            }
        } else {
            throw new Exception('Constructor variables aren\'t valid. Please see the MohdRaquib\cPanelAPI\cPanelAPI class\' documentation for more information.');
        }
    }

    //Method - Make A HTTP Request
    private function __request($url, $post = false, array $headers = NULL, $decode_json = true)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($post) ? http_build_query($post) : $post);
        }
        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $data = curl_exec($ch);
        if ($decode_json) {
            return json_decode($data);
        } else {
            return $data;
        }
    }

    //Method - Query String Builder
    private function buildQueryString($module, $function, array $params)
    {
        $param[] = 'cpanel_jsonapi_func=' . $function;
        $param[] = 'cpanel_jsonapi_module=' . $module;
        $param[] = 'cpanel_jsonapi_version=' . $this->api_version;
        foreach ($params as $key => $value) {
            $param[] = $key . '=' . urlencode($value);
        }
        return implode('&', $param);
    }

    //Method - Make Request URL
    private function makeRequestURL($url, $query_string)
    {
        return trim($url, '/') . '/json-api/cpanel?' . $query_string;
    }

    //Method - Parse HTTP Host
    private function parseHost($url)
    {
        return parse_url($url)['host'];
    }

    //Method - Encrypt Username and Password
    private function encryptCredentials()
    {
        return base64_encode($this->username . ':' . $this->password);
    }

    //Method - Make HTTP Header for cPanelAPI Request
    public function makeHeaders()
    {
        $host = self::parseHost($this->url);
        $credentials = self::encryptCredentials();
        return ['Host: ' . $host, 'Authorization: Basic ' . $credentials];
    }

    //Method - Make cPanel API HTTP Request
    public function makeRequest($method, $function, array $params)
    {
        $url = self::makeRequestURL($this->url, self::buildQueryString($method, $function, $params));
        $headers = self::makeHeaders();
        return self::__request($url, false, $headers);
    }
}