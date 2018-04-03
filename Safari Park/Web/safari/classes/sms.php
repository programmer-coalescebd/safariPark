<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 6:06 PM
 */

(strpos($_SERVER["REQUEST_URI"], "classes") !== false) ? exit('Direct access not allowed') : '';

class sms
{
    function __construct($sender_api, $sender_secret, $sernder_number)
    {
        $this->sender_api = $sender_api;
        $this->sender_secret = $sender_secret;
        $this->sernder_number = $sernder_number;
    }

    public function send($reception, $pincode)
    {

        $url = 'https://partners.ring.to/api/users/' . $this->sender_api . '/messages';

        $ch = curl_init($url);
        $payload = "from=%2B1" . $this->sernder_number . "&to=%2B1" . $reception . "&text=" . $pincode;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded; charset=utf-8', 'Authorization:Bearer ' . $this->sender_secret));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $decision = json_decode($result);
        $decision = $decision["0"]->status;
        //->status
        return $decision;
    }
}