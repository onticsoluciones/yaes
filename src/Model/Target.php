<?php

namespace Ontic\Yaes\Model;

class Target
{
    /** @var string */
    private $host;
    /** @var int */
    private $port;
    /** @var string */
    private $basePath;

    /**
     * @param string $host
     * @param string $port
     * @param string $basePath
     */
    function __construct($host, $port, $basePath)
    {
        $this->host = $host;
        $this->port = $port;
        $this->basePath = $basePath;

        if(strlen($basePath) > 0 && $basePath[count($basePath) -1] !== '/')
        {
            $this->basePath .= '/';
        }
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }
}