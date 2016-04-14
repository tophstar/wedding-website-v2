<?php

 namespace Rsvp\Model;

 class Rsvp
 {
    public $idrsvp = 0;

	public $email;
	public $name;
	public $attendingCeremony;
	public $adultsCeremony;
	public $childrenCeremony;
	public $attendingReception;	
	public $adultsReception;
	public $childrenReception;
	public $vegiterianCount;
	public $rsvpComplete;



     public function exchangeArray($data)
     {
        $this->idrsvp     = (!empty($data['idrsvp'])) ? $data['idrsvp'] : 0;
		$this->email  = (!empty($data['email'])) ? $data['email'] : null;
		$this->name  = (!empty($data['name'])) ? $data['name'] : null;
		$this->attendingCeremony  = (!empty($data['attending_ceremony'])) ? $data['attending_ceremony'] : null;
		$this->adultsCeremony  = (!empty($data['adults_ceremony_count'])) ? $data['adults_ceremony_count'] : null;
		$this->childrenCeremony  = (!empty($data['children_ceremony_count'])) ? $data['children_ceremony_count'] : null;
		$this->attendingReception  = (!empty($data['attending_reception'])) ? $data['attending_reception'] : null;
		$this->adultsReception  = (!empty($data['adults_reception_count'])) ? $data['adults_reception_count'] : null;
		$this->childrenReception  = (!empty($data['children_reception_count'])) ? $data['children_reception_count'] : null;
		$this->vegiterianCount  = (!empty($data['vegiterian_count'])) ? $data['vegiterian_count'] : null;
		$this->rsvpComplete = (!empty($data['rsvp_complete'])) ? $data['rsvp_complete'] : null;

     }
 }