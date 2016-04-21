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
	public $rsvpComments;


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
		$this->rsvpComments = (!empty($data['rsvp_comments'])) ? $data['rsvp_comments'] : null;
     }

     public function exchangeObject($data)
     {
        $this->idrsvp     = (!empty($data['idrsvp'])) ? $data['idrsvp'] : 0;
		$this->email  = (!empty($data['email'])) ? $data['email'] : null;
		$this->name  = (!empty($data['name'])) ? $data['name'] : null;
		$this->attendingCeremony  = (!empty($data['attendingCeremony'])) ? $data['attendingCeremony'] : null;
		$this->adultsCeremony  = (!empty($data['adultsCeremony'])) ? $data['adultsCeremony'] : null;
		$this->childrenCeremony  = (!empty($data['childrenCeremony'])) ? $data['childrenCeremony'] : null;
		$this->attendingReception  = (!empty($data['attendingReception'])) ? $data['attendingReception'] : null;
		$this->adultsReception  = (!empty($data['adultsReception'])) ? $data['adultsReception'] : null;
		$this->childrenReception  = (!empty($data['childrenReception'])) ? $data['childrenReception'] : null;
		$this->vegiterianCount  = (!empty($data['vegiterianCount'])) ? $data['vegiterianCount'] : null;
		$this->rsvpComplete = (!empty($data['rsvpComplete'])) ? $data['rsvpComplete'] : null;
		$this->rsvpComments = (!empty($data['rsvpComments'])) ? $data['rsvpComments'] : null;
     }
 }