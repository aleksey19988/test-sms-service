<?php

class Handler
{
    const API_URL = 'https://sms.ru/sms/';

    private string $apiKey;
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Отправка смс на 1 или несколько номеров, указанных через запятую
     *
     * @param string $text Текст сообщения
     * @param array $to Список получателей
     * @return string
     */
    public function sendSms(string $text, array $to): string
    {
        $url = $this->prepareUrlForSendSms($text, $to);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        return $this->getResponseInfo($response);
    }

    private function getResponseInfo(string|bool $response): string
    {
        if ($response) {
            return $response;
        } else {
            return json_encode([
                "success" => false,
                "status" => "ERROR",
            ]);
        }
    }

    private function prepareUrlForSendSms(string $text, array $to): string
    {
        $url = self::API_URL . 'send/';
        $url = $this->setApiToUrl($url);
        $url = $this->setRecipientsToUrl($url, $to);
        $url = $this->setTextToUrl($url, $text);
        $url .= "&json=1";

        return $url;
    }

    /**
     * Добавление в url api-ключа
     *
     * @param string $url
     * @return string
     */
    private function setApiToUrl(string $url): string
    {
        return  "$url?api_id=$this->apiKey";
    }

    /**
     * Добавление в url списка номеров получателей
     *
     * @param string $url
     * @param array $to
     * @return string
     */
    private function setRecipientsToUrl(string $url, array $to): string
    {
        return "$url&to=" . implode(",", $to);
    }

    /**
     * Добавление в url текста сообщения
     *
     * @param string $url
     * @param string $text
     * @return string
     */
    private function setTextToUrl(string $url, string $text): string
    {
        return "$url&msg=" . implode("+", explode(" ", $text));
    }
}