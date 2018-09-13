<?php

namespace App\Services;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

/**
 * Class PushNotificationService
 *
 * @package App\Services
 */
class PushNotificationService
{
    /**
     * @var string
     */
    private $endPoint = 'https://myprovider.push';

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $to;

    /**
     * @return \App\Services\PushNotificationService
     */
    public function reset() : PushNotificationService
    {
        $this->title = null;
        $this->message = null;
        $this->to = null;
        return $this;
    }

    /**
     * @param $message
     * @return PushNotificationService
     * @throws \Exception
     */
    public function setMessage($message) : PushNotificationService
    {
        if (empty($message)) {
            throw new \Exception("PUSH_SERVICE_NO_MESSAGE");
        }
        $this->message = $message;
        return $this;
    }

    /**
     * @param $title
     * @return \App\Services\PushNotificationService
     * @throws \Exception
     */
    public function setTitle($title) : PushNotificationService
    {
        if (empty($title)) {
            throw new \Exception("PUSH_SERVICE_NO_TITLE");
        }
        $this->title = $title;
        return $this;
    }

    /**
     * @param $to
     * @return \App\Services\PushNotificationService
     * @throws \Exception
     */
    public function setTo($to) : PushNotificationService
    {
        if (empty($to)) {
            throw new \Exception("PUSH_SERVICE_NO_TOKEN");
        }
        $this->to = $to;
        return $this;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function send()
    {
        if (empty($this->to)) {
            throw new \Exception("PUSH_SERVICE_NO_TOKEN");
        }
        if (empty($this->title)) {
            throw new \Exception("PUSH_SERVICE_NO_TITLE");
        }
        if (empty($this->message)) {
            throw new \Exception("PUSH_SERVICE_NO_MESSAGE");
        }

        $client = new \GuzzleHttp\Client();

        /**
         * @todo: service implementation will be needed
         */
        $data = '';

        $request_data = [
            'headers' => [
                'accept' => 'application/json',
                'accept-encoding' => 'gzip, deflate',
                'content-type' => 'application/json',
            ],
            'body' => json_encode($data),
        ];

        try {
            //$response = $client->post($this->endPoint, $request_data);
            //$response_data = $response->getBody()->read(1024);

            $this->title = null;
            $this->message = null;
            $this->to = null;
            return true;
        } catch (ClientException $e) {
            $exception_message = $e->getResponse()->getStatusCode() . ': ' . $e->getResponse()->getReasonPhrase();
            throw new \Exception("SMS_SERVICE_$exception_message");
        } catch (ConnectException $e) {
            throw new \Exception("SMS_SERVICE_NO_RESPONSE");
        }
    }

    /**
     * @return string
     */
    public function getEndPoint(): string
    {
        return $this->endPoint;
    }

    /**
     * @return string|null
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }
}
