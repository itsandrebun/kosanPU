<?php

    class Currency{

        public function convert($number){
            $currency_value = number_format($number,0,',','.');
            return $currency_value;
        }
    }
?>