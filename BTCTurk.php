<?php

    /**
     * Created by PhpStorm.
     * User: ufukOzarslan
     * Date: 28.10.17
     * Time: 15:43
     */
    class BTCTurk
    {

        var $apiUri = "https://www.btcturk.com/api/";
        var $apiKey = "";
        var $apiSecretKey = "";

        private function createSignature ()
        {
            return base64_encode (hash_hmac ('sha256' , $this->apiKey . time () , base64_decode ($this->apiSecretKey) , true));
        }

        private function getPage ($uri = "ticker")
        {
            $ch = null;
            $ch = curl_init ($this->apiUri . $uri);
            curl_setopt ($ch , CURLOPT_RETURNTRANSFER , true);
            curl_setopt ($ch , CURLOPT_HTTPHEADER , array (
                'X-PCK: ' . $this->apiKey ,
                'X-Stamp: ' . time () ,
                'X-Signature: ' . self::createSignature ()
            ));
            curl_setopt ($ch , CURLOPT_SSL_VERIFYPEER , false);
            $result = curl_exec ($ch);
            curl_close ($ch);

            return json_decode ($result , true);
        }

        /**
         * (PHP 4, PHP 5)
         * Return BTCTurk Ticker Data
         * @return string
         */

        public function ticker ()
        {
            return self::getPage ();
        }


        /**
         * (PHP 4, PHP 5)
         * Return BTCTurk User Balance Data
         * @return string
         */

        public function balance ()
        {
            return self::getPage ("balance");
        }

    }
