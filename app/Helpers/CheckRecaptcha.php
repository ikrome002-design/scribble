<?php

namespace App\Helpers;

class CheckRecaptcha
{
    public function verifyRecaptcha($reCaptchaToken)
    {


        try {
            $data = array(
                'secret' => env('GOOGLE_RECAPTCHA_SECRET_KEY'),
                'response' => $reCaptchaToken
            );
            $verify = curl_init();
            curl_setopt(
                $verify,
                CURLOPT_URL,
                "https://www.google.com/recaptcha/api/siteverify"
            );
            curl_setopt($verify, CURLOPT_POST, true);
            curl_setopt(
                $verify,
                CURLOPT_POSTFIELDS,
                http_build_query($data)
            );
            curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($verify);
            curl_close($verify);
            return json_decode($response);
        } catch (\Exception $e) {
            return false;
        }
    }
}
