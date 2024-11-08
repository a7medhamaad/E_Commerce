<?php
   function lang($pharse){
    static $lang = array(
        'HOME_ADMIN'=>'HOME',
       
    );
    return $lang[$pharse];
   }