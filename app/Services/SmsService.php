<?php

namespace App\Services;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

/**
 * Class SmsService
 *
 * @package App\Services
 */
class SmsService
{
    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $to;

    /**
     * @var string
     */
    private $endPoint = 'https://myprovider.sms';

    /**
     * @return \App\Services\SmsService
     */
    public function reset() : SmsService
    {
        $this->message = null;
        $this->to = null;
        return $this;
    }

    /**
     * @param string $string
     * @return \App\Services\SmsService
     * @throws \Exception
     */
    public function setMessage(string $string): SmsService
    {
        $string = htmlspecialchars(strip_tags($string), ENT_XML1 | ENT_QUOTES, 'UTF-8');
        if (empty($string)) {
            throw new \Exception("SMS_SERVICE_EMPTY_MESSAGE");
        }

        $this->message = $string;
        return $this;
    }

    /**
     * @param $to
     * @return \App\Services\SmsService
     * @throws \Exception
     */
    public function setTo($to) : SmsService
    {
        if (strlen($to) < 10 || strlen($to) > 11) {
            throw new \Exception("SMS_SERVICE_INVALID_PHONE");
        }
        if (strlen($to) == 10) {
            $to = "0$to";
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
            throw new \Exception("SMS_SERVICE_NO_PHONE");
        }
        if (empty($this->message)) {
            throw new \Exception("SMS_SERVICE_NO_MESSAGE");
        }

        $client = new \GuzzleHttp\Client();
        $headers = ['content-type' => 'text/xml; charset=UTF-8'];

        /**
         * @todo: service implementation will be needed
         */
        $data ='';

        $request_data = ['headers' => $headers, 'body' => $data];

        try {
            //$response = $client->post($this->endPoint, $request_data);
            //$response_data = $response->getBody()->read(1024);

            $this->to = null;
            $this->message = null;

            return true;
        } catch (ClientException $e) {
            $exception_message = $e->getResponse()->getStatusCode() . ': ' . $e->getResponse()->getReasonPhrase();
            throw new \Exception("SMS_SERVICE_EXCEPTION_$exception_message");
        } catch (ConnectException $e) {
            throw new \Exception("SMS_SERVICE_NO_RESPONSE");
        }
    }

    /**
     * @return null|string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getEndPoint(): string
    {
        return $this->endPoint;
    }
}
