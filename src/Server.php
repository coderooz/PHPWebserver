<?php

namespace Coderooz\PhpWebServer;

use Exception;

class Server
{
    protected $sockets = [];
    protected $websites = [];

    public function __construct($configFile)
    {
        $config = json_decode(file_get_contents($configFile), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid configuration file.');
        }

        $this->websites = $config['websites'] ?? [];

        foreach ($this->websites as $website) {
            $host = '127.0.0.1';
            $port = (int) ($website['port']);
            $documentRoot = rtrim($website['documentRoot'] ?? 'htdocs', '/');

            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if (!$socket) {
                throw new Exception('Could not create socket: ' . socket_strerror(socket_last_error()));
            }

            if (!socket_bind($socket, $host, $port)) {
                throw new Exception('Could not bind: ' . $host . ':' . $port . ' - ' . socket_strerror(socket_last_error()));
            }

            socket_listen($socket);
            $this->sockets[$port] = [
                'socket' => $socket,
                'documentRoot' => $documentRoot
            ];
        }
    }

    public function listen($callback)
    {
        if (!is_callable($callback)) {
            throw new Exception('The given argument should be callable.');
        }

        while (true) {
            foreach ($this->sockets as $port => $info) {
                $client = @socket_accept($info['socket']);
                if ($client === false) {
                    continue;
                }

                $requestHeader = socket_read($client, 1024);
                $request = Request::withHeaderString($requestHeader);

                $response = $this->handleRequest($request, $info['documentRoot']);

                socket_write($client, $response, strlen($response));
                socket_close($client);
            }
        }
    }

    protected function handleRequest(Request $request, $documentRoot)
    {
        $uri = $request->uri();
        $filePath = $documentRoot . $uri;

        // Serve index file if directory
        if (is_dir($filePath)) {
            $filePath .= '/index.html';
        }

        // Check for file existence and serve
        if (file_exists($filePath)) {
            $body = file_get_contents($filePath);
            return new Response($body, 200);
        }

        // Handle .htaccess redirections or deny access if needed
        $htaccessPath = $documentRoot . '/.htaccess';
        if (file_exists($htaccessPath)) {
            // Simulate basic .htaccess processing here if necessary
            // This is a simplified example and might not cover all .htaccess rules
        }

        return new Response('Not Found', 404);
    }
}
