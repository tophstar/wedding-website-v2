<?php

namespace Rsvp\Model;

 use Zend\Db\TableGateway\TableGateway;

 class RsvpTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }

     public function getRsvp($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }


     public function fetchByEmail($email)
     {
         $rowset = $this->tableGateway->select(array('email' => $email));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $email");
         }
         return $row;
     }


     public function saveRsvp(Rsvp $rsvp)
     {

         $data = array(
             'idrsvp' => $rsvp->idrsvp,
             'email' => $rsvp->email,
             'name' => $rsvp->name,
             'attending_ceremony' => $rsvp->attendingCeremony,
             'adults_ceremony_count' => $rsvp->adultsCeremony,
             'children_ceremony_count' => $rsvp->childrenCeremony,
             'attending_reception' => $rsvp->attendingReception,
             'adults_reception_count' => $rsvp->adultsReception,
             'children_reception_count' => $rsvp->childrenReception,
             'vegiterian_count' => $rsvp->vegiterianCount,
             'rsvp_complete' => $rsvp->rsvpComplete,
             'rsvp_comments' => $rsvp->rsvpComments
         );

         $id = (int) $rsvp->idrsvp;

         if ($id == 0) {    
             $this->tableGateway->insert($data);
         } else {

             $this->tableGateway->update($data, array('idrsvp' => $id));
         }
     }



     public function deleteRsvp($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
 }