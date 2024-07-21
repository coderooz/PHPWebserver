<?php

namespace Coderooz\PhpWebServer;

class Request
{
    protected $method;
    protected $uri;
    protected $parameters = [];
    protected $headers = [];

    public static function withHeaderString($header)
    {
        $lines = explode("\n", $header);
        list($method, $uri) = explode(' ', array_shift($lines));

        $headers = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, ': ') !== false) {
                list($key, $value) = explode(': ', $line, 2);
                $headers[$key] = $value;
            }
        }

        return new static($method, $uri, $headers);
    }

    public function __construct($method, $uri, $headers = [])
    {
        $this->headers = $headers;
        $this->method = strtoupper($method);
        $parts = explode('?', $uri, 2);
        $this->uri = $parts[0];
        if (isset($parts[1])) {
            parse_str($parts[1], $this->parameters);
        }
    }

    public function method()
    {
        return $this->method;
    }

    public function uri()
    {
        return $this->uri;
    }

    public function header($key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    public function param($key, $default = null)
    {
        return $this->parameters[$key] ?? $default;
    }
}
