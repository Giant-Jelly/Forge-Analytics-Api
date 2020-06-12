<?php


namespace App\ApiError;


class ApiError
{
    /**
     * @var string
     */
    private $message;
    /**
     * @var int
     */
    private $errorCode;
    /**
     * @var string
     */
    private $url;

    public function __construct(string $message, int $errorCode, string $url = 'https://httpstatusdogs.com/')
    {
        $this->message = $message;
        $this->errorCode = $errorCode;
        $this->url = $url . $errorCode;
    }

    public function getError()
    {
        return [
            'message' => $this->message,
            'errorCode' => $this->errorCode,
            'url' => $this->url
        ];
    }
}