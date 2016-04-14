<?php

 namespace Rsvp\Model;

 class Auth
 {
     public $authToken;

     public function exchangeArray($data)
     {
         $this->authToken     = (!empty($data['auth_token'])) ? $data['auth_token'] : null;
     }
 }