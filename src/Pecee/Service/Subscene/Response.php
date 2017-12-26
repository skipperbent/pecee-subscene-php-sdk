<?php

namespace Pecee\Service\Subscene;

use Pecee\Service\Subscene;

class Response extends Subscene implements \JsonSerializable
{
    /**
     * Response array
     *
     * @var array
     */
    protected $response;

    /**
     * Constructor
     *
     * @param array $response
     */
    public function __construct($response)
    {
        $this->response = $response;
        parent::__construct();
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Return response as array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
