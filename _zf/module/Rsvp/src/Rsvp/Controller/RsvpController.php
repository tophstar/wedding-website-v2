<?php

 namespace Rsvp\Controller;

 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;
 use Zend\View\Model\JsonModel;
 use Rsvp\Model\Rsvp;

 class RsvpController extends AbstractActionController
 {
	 protected $rsvpTable;
     protected $authTable;

     protected function validateRSVPRequest($request){

        if($request->isXmlHttpRequest()) {
            return true;
        }

     }

     protected function validateAccessToken($data){




                //@TODO Verify ttl is still valid





        if(!isset($data->accessToken) || empty($data->accessToken) || !isset($data->ttl) || empty($data->ttl) || !isset($data->created) || empty($data->created)) {
            return new JsonModel($result);
        }



        $auths = $this->getAuthTable()->fetchAll();
        $authToken = '';
        foreach ($auths as $auth) {
            $authToken = $auth->authToken;
        }

        $accessToken = $data->ttl.'|'.$data->created.'|'.$authToken;


        if(!password_verify($accessToken, $data->accessToken)) {
            return false;
        }

        return true;
     }





     protected function updateRsvp($data, $updateParam){
        $rsvp = $this->getRsvpTable()->fetchByEmail($data->email);
        $rsvp->$updateParam = $data->$updateParam;

        $dataArray = json_decode(json_encode($rsvp), True);

        $this->getRsvpTable()->saveRsvp($rsvp);
     }






     public function indexAction()
     {
     	 return new ViewModel(array(
             'rsvps' => $this->getRsvpTable()->fetchAll(),
         ));
     }

     public function getRsvpTable()
     {
         if (!$this->rsvpTable) {
             $sm = $this->getServiceLocator();
             $this->rsvpTable = $sm->get('Rsvp\Model\RsvpTable');
         }
         return $this->rsvpTable;
     }

     public function getAuthTable()
     {
         if (!$this->authTable) {
             $sm = $this->getServiceLocator();
             $this->authTable = $sm->get('Rsvp\Model\AuthTable');
         }
         return $this->authTable;
     }

    public function getAccessTokenAction() {

        $request = $this->getRequest();

        $result = array('status' => 'error', 'message' => 'There was some error. Try again.', 'isXmlHTTP' => $request->isXmlHttpRequest());

        if($request->isXmlHttpRequest()){

            $data = \Zend\Json\Json::decode($request->getContent());
            
            if(isset($data->token) && !empty($data->token)){

                $auths = $this->getAuthTable()->fetchAll();
                $created = time();
                $ttl = '500';


                foreach ($auths as $auth){
                    if($auth->authToken == $data->token){
                        $result['status'] = 'success';
                        $result['message'] = '';
                        
                        //Adding ttl and created will make a unique token tied to thoese values and will ensure this token only lasts for a set amount of time
                        $accessToken = $ttl.'|'.$created.'|'.$data->token;

                        //@TODO Hash with TTL appended?
                        $result['accessToken'] = password_hash($accessToken, PASSWORD_BCRYPT);
                        
                        $result['ttl'] = $ttl;
                        $result['created'] = $created;


                    }
                }
            }
        }
        
        return new JsonModel($result);
    }

    public function emailRevistCheckAction() {

        $request = $this->getRequest();

        $result = array('status' => 'error', 'message' => 'There was some error. Try again.', 'isXmlHTTP' => $request->isXmlHttpRequest());

        if(!$this->validateRSVPRequest($request)){
            return new JsonModel($result);
        }

        $data = \Zend\Json\Json::decode($request->getContent());


        if(!isset($data->email) || empty($data->email)){
            return new JsonModel($result);
        }

        if(!$this->validateAccessToken($data)){
            return new JsonModel($result);
        }



        $rsvps = $this->getRsvpTable()->fetchAll();
        foreach ($rsvps as $rsvp) {
            if($rsvp->email == $data->email){
                $result['status'] = 'duplicate';
                $result['message'] = '';

                if($rsvp->rsvpComplete){
                    $result['status'] = 'duplicate_complete';
                }
                return new JsonModel($result);
            }
        }



        $result['status'] = 'success';
        $result['message'] = '';



        return new JsonModel($result);
    }

    public function registerEmailAction() {

        $request = $this->getRequest();

        $result = array('status' => 'error', 'message' => 'There was some error. Try again.', 'isXmlHTTP' => $request->isXmlHttpRequest());



        if(!$this->validateRSVPRequest($request)){
            return new JsonModel($result);
        }



        $data = \Zend\Json\Json::decode($request->getContent());



        if(!isset($data->email) || empty($data->email)){
            return new JsonModel($result);
        }

        if(!$this->validateAccessToken($data)){
            return new JsonModel($result);
        }


        //Validate again that this email isn't a duplicate
        $rsvps = $this->getRsvpTable()->fetchAll();

        //@todo the database would do this anyway, maybe let it handle this logic....
        foreach ($rsvps as $rsvp) {
            if($rsvp->email == $data->email){
                $result['status'] = 'duplicate';
                $result['message'] = '';

                return new JsonModel($result);
            }
        }


        $rsvp = new Rsvp();

        $data->name = "New Email";

        $dataArray = json_decode(json_encode($data), True);

        $rsvp->exchangeArray($dataArray);
        $this->getRsvpTable()->saveRsvp($rsvp);



        $result['status'] = 'success';
        $result['message'] = '';



        return new JsonModel($result);
    }

    public function registerNameAction() {

        $request = $this->getRequest();

        $result = array('status' => 'error', 'message' => 'There was some error. Try again.', 'isXmlHTTP' => $request->isXmlHttpRequest());



        if(!$this->validateRSVPRequest($request)){
            $result = array('status' => 'error', 'message' => 'Was not an isXmlHttpRequest');
            return new JsonModel($result);
        }



        $data = \Zend\Json\Json::decode($request->getContent());



        if(!isset($data->email) || empty($data->email) || !isset($data->name) || empty($data->name)){
            $result = array('status' => 'error', 'message' => 'Missing Email or Name');
            return new JsonModel($result);
        }

        if(!$this->validateAccessToken($data)){
            $result = array('status' => 'error', 'message' => 'Token not valid.');
            return new JsonModel($result);
        }


        $this->updateRsvp($data, 'name');


        $result['status'] = 'success';
        $result['message'] = '';



        return new JsonModel($result);
    }

    public function attendingCeremonyAction() {

        $request = $this->getRequest();

        $result = array('status' => 'error', 'message' => 'There was some error. Try again.', 'isXmlHTTP' => $request->isXmlHttpRequest());



        if(!$this->validateRSVPRequest($request)){
            $result = array('status' => 'error', 'message' => 'Was not an isXmlHttpRequest');
            return new JsonModel($result);
        }



        $data = \Zend\Json\Json::decode($request->getContent());

        if(!isset($data->email) || empty($data->email) || !isset($data->attendingCeremony)){
            $result = array('status' => 'error', 'message' => 'Missing Email or Attending Ceremony flag');
            return new JsonModel($result);
        }

        if(!$this->validateAccessToken($data)){
            $result = array('status' => 'error', 'message' => 'Token not valid.');
            return new JsonModel($result);
        }


        $this->updateRsvp($data, 'attendingCeremony');

        $result['status'] = 'success';
        $result['message'] = '';



        return new JsonModel($result);
    }

    public function registerAdultsCeremonyAction() {

        $request = $this->getRequest();

        $result = array('status' => 'error', 'message' => 'There was some error. Try again.', 'isXmlHTTP' => $request->isXmlHttpRequest());



        if(!$this->validateRSVPRequest($request)){
            $result = array('status' => 'error', 'message' => 'Was not an isXmlHttpRequest');
            return new JsonModel($result);
        }



        $data = \Zend\Json\Json::decode($request->getContent());

        if(!isset($data->email) || empty($data->email) || !isset($data->adultsCeremony)){
            $result = array('status' => 'error', 'message' => 'Missing Email or Adult Ceremony count');
            return new JsonModel($result);
        }

        if(!$this->validateAccessToken($data)){
            $result = array('status' => 'error', 'message' => 'Token not valid.');
            return new JsonModel($result);
        }


        $this->updateRsvp($data, 'adultsCeremony');

        $result['status'] = 'success';
        $result['message'] = '';



        return new JsonModel($result);
    }


    public function registerChildrenCeremonyAction() {

        $request = $this->getRequest();

        $result = array('status' => 'error', 'message' => 'There was some error. Try again.', 'isXmlHTTP' => $request->isXmlHttpRequest());



        if(!$this->validateRSVPRequest($request)){
            $result = array('status' => 'error', 'message' => 'Was not an isXmlHttpRequest');
            return new JsonModel($result);
        }



        $data = \Zend\Json\Json::decode($request->getContent());

        if(!isset($data->email) || empty($data->email) || !isset($data->childrenCeremony)){
            $result = array('status' => 'error', 'message' => 'Missing Email or Children Ceremony count');
            return new JsonModel($result);
        }

        if(!$this->validateAccessToken($data)){
            $result = array('status' => 'error', 'message' => 'Token not valid.');
            return new JsonModel($result);
        }


        $this->updateRsvp($data, 'childrenCeremony');

        $result['status'] = 'success';
        $result['message'] = '';



        return new JsonModel($result);
    }

    public function attendingReceptionAction() {

        $request = $this->getRequest();

        $result = array('status' => 'error', 'message' => 'There was some error. Try again.', 'isXmlHTTP' => $request->isXmlHttpRequest());



        if(!$this->validateRSVPRequest($request)){
            $result = array('status' => 'error', 'message' => 'Was not an isXmlHttpRequest');
            return new JsonModel($result);
        }



        $data = \Zend\Json\Json::decode($request->getContent());

        if(!isset($data->email) || empty($data->email) || !isset($data->attendingReception)){
            $result = array('status' => 'error', 'message' => 'Missing Email or Attending Reception flag');
            return new JsonModel($result);
        }

        if(!$this->validateAccessToken($data)){
            $result = array('status' => 'error', 'message' => 'Token not valid.');
            return new JsonModel($result);
        }


        $this->updateRsvp($data, 'attendingReception');

        $result['status'] = 'success';
        $result['message'] = '';



        return new JsonModel($result);
    }

    public function registerAdultsReceptionAction() {

        $request = $this->getRequest();

        $result = array('status' => 'error', 'message' => 'There was some error. Try again.', 'isXmlHTTP' => $request->isXmlHttpRequest());



        if(!$this->validateRSVPRequest($request)){
            $result = array('status' => 'error', 'message' => 'Was not an isXmlHttpRequest');
            return new JsonModel($result);
        }



        $data = \Zend\Json\Json::decode($request->getContent());

        if(!isset($data->email) || empty($data->email) || !isset($data->adultsReception)){
            $result = array('status' => 'error', 'message' => 'Missing Email or Adult Reception count');
            return new JsonModel($result);
        }

        if(!$this->validateAccessToken($data)){
            $result = array('status' => 'error', 'message' => 'Token not valid.');
            return new JsonModel($result);
        }


        $this->updateRsvp($data, 'adultsReception');

        $result['status'] = 'success';
        $result['message'] = '';



        return new JsonModel($result);
    }


    public function registerChildrenReceptionAction() {

        $updateParam = 'childrenReception';

        $request = $this->getRequest();

        $result = array('status' => 'error', 'message' => 'There was some error. Try again.', 'isXmlHTTP' => $request->isXmlHttpRequest());



        if(!$this->validateRSVPRequest($request)){
            $result = array('status' => 'error', 'message' => 'Was not an isXmlHttpRequest');
            return new JsonModel($result);
        }



        $data = \Zend\Json\Json::decode($request->getContent());

        if(!isset($data->email) || empty($data->email) || !isset($data->$updateParam)){
            $result = array('status' => 'error', 'message' => 'Missing Email or Children Reception count');
            return new JsonModel($result);
        }

        if(!$this->validateAccessToken($data)){
            $result = array('status' => 'error', 'message' => 'Token not valid.');
            return new JsonModel($result);
        }


        $this->updateRsvp($data, $updateParam);

        $result['status'] = 'success';
        $result['message'] = '';



        return new JsonModel($result);
    }

    public function registerVegiterianCountAction() {

        $updateParam = 'vegiterianCount';

        $request = $this->getRequest();

        $result = array('status' => 'error', 'message' => 'There was some error. Try again.', 'isXmlHTTP' => $request->isXmlHttpRequest());



        if(!$this->validateRSVPRequest($request)){
            $result = array('status' => 'error', 'message' => 'Was not an isXmlHttpRequest');
            return new JsonModel($result);
        }



        $data = \Zend\Json\Json::decode($request->getContent());

        if(!isset($data->email) || empty($data->email) || !isset($data->$updateParam)){
            $result = array('status' => 'error', 'message' => 'Missing Email or Children Reception count');
            return new JsonModel($result);
        }

        if(!$this->validateAccessToken($data)){
            $result = array('status' => 'error', 'message' => 'Token not valid.');
            return new JsonModel($result);
        }


        $this->updateRsvp($data, $updateParam);





        //@todo update the isComplete as well...
        $this->updateRsvp($data, 'rsvpComplete');






        $result['status'] = 'success';
        $result['message'] = '';



        return new JsonModel($result);
    }

    public function rsvpCommentAction() {

        $updateParam = 'rsvpComments';

        $request = $this->getRequest();

        $result = array('status' => 'error', 'message' => 'There was some error. Try again.', 'isXmlHTTP' => $request->isXmlHttpRequest());



        if(!$this->validateRSVPRequest($request)){
            $result = array('status' => 'error', 'message' => 'Was not an isXmlHttpRequest');
            return new JsonModel($result);
        }



        $data = \Zend\Json\Json::decode($request->getContent());

        if(!isset($data->email) || empty($data->email) || !isset($data->$updateParam)){
            $result = array('status' => 'error', 'message' => 'Missing Email or RSVP comment');
            return new JsonModel($result);
        }

        if(!$this->validateAccessToken($data)){
            $result = array('status' => 'error', 'message' => 'Token not valid.');
            return new JsonModel($result);
        }

        $this->updateRsvp($data, $updateParam);

        $result['status'] = 'success';
        $result['message'] = '';



        return new JsonModel($result);
    }
 }