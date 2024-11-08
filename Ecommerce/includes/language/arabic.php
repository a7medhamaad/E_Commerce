<?php
   function lang($pharse){
    static $lang = array(
        'message'=>'مرحبا'
    );
    return $lang[$pharse];
   }