<?php

    /**
     * Created by PhpStorm.
     * User: ufuk Ozarslan {ozarslan@hublabs.com.tr}
     * Date: 28.10.17 15:43
     * @ BTCTurk.com API Method
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

        private function getPage ($uri = "ticker" , $request = array ( "status" => "off" , "postdata" => null ))
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
            $request[ "status" ] == "on" ? curl_setopt ($ch , CURLOPT_POSTFIELDS , $request[ "postdata" ]) . curl_setopt ($ch , CURLOPT_POST , 1) : "";
            $result = curl_exec ($ch);
            curl_close ($ch);

            return json_decode ($result , true);
        }

        /**
         * (PHP 4, PHP 5)
         * Return BTCTurk Ticker Data
         * @return array
         */

        public function ticker ()
        {
            return self::getPage ();
        }

        /**
         * (PHP 4, PHP 5)
         * Return BTCTurk Order Book
         * @return array
         */

        public function orderbook ($pair = "BTCTRY")
        {
            return self::getPage ("orderbook?pairSymbol=" . $pair);
        }

        /**
         * (PHP 4, PHP 5)
         * Return BTCTurk Trades
         * @return array
         */

        public function trades ($pair = "BTCTRY" , $count = 50)
        {
            return self::getPage ("trades?pairSymbol=" . $pair . "&last=" . $count);
        }

        /**
         * (PHP 4, PHP 5)
         * Return BTCTurk OHCL Data (Daily)
         * @return array
         */

        public function ohcl ($count = 50)
        {
            return self::getPage ("ohlcdata?&last=" . $count);
        }

        /**
         * (PHP 4, PHP 5)
         * Return BTCTurk User Balance Data
         * @return array
         */

        public function balance ()
        {
            return self::getPage ("balance");
        }

        /**
         * (PHP 4, PHP 5)
         * Return BTCTurk User Transactions
         * @return array
         */

        public function transactions ($offset = 0 , $limit = 50 , $sort = "desc")
        {
            return self::getPage ("userTransactions?offset=" . $offset . "&limit=" . $limit . "&sort=" . $sort);
        }

        /**
         * (PHP 4, PHP 5)
         * Return BTCTurk Open Orders
         * @return array
         */

        public function openOrders ($pair = "BTCTRY")
        {
            return self::getPage ("openOrders?pairSymbol=" . $pair);
        }

        /**
         * (PHP 4, PHP 5)
         * Return BTCTurk Cancel Order
         * @return array
         */

        public function cancelOrder ($orderID = 0)
        {
            return self::getPage ("cancelOrder" , array ( "status" => "on" , "postdata" => "id=" . $orderID ));
        }

    }
