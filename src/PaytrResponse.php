<?php

/**
 * @author Gizem Sever <gizemsever68@gmail.com>
 */

namespace Gizemsever\LaravelPaytr;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;

class PaytrResponse
{
    private $response;
    private $content;

    public function __construct($response)
    {
        $this->response = $response;
        $this->content = $response;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    public function isSuccess(): bool
    {
        return ($this->content['status'] === 'success');
    }

    public function getMessage()
    {
        return isset($this->content['reason']) ? $this->content['reason'] : null;
    }

    public function getToken()
    {
        return isset($this->content['token']) ? $this->content['token'] : null;
    }
}