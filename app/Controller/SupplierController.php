<?php
class SupplierController extends AppController {

	public $name = 'Supplier';
	public $helpers = array();
	public $uses = array('Supplier','SupplierAttachmentField','SupplierAttachmentOrder','SupplierCheckboxRadioField','SupplierCheckboxRadioOrder','SupplierHospital','SupplierOrder','SupplierOrderDetail','SupplierOrderForm','SupplierTeethNumber','SupplierTeethNumberOrder','SupplierTextTextareaField','SupplierTitleField','SupplierTextTextareaFieldOrder','SupplireOrderStatus');
	public $components = array('RequestHandler','Custom');

        /*****************************************
        function :- index 
        *****************************************/

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'support_home';
        $this->Auth->allow('login');
        $action = $this->request->params['action'];
        $not_validate = array('login');
        if (!in_array($action, $not_validate)) {
            if(!$this->Auth->loggedIn()){

                $this->redirect(array('controller' => 'supplier', 'action' => 'login', 'admin' => false));
            }
        }
	}


    public function login()
    {


        $this->layout = "admin_home";

        if ($this->Auth->loggedIn()) {

            $this->redirect('index');


        } else if ($this->request->is('post')) {

            if(isset($this->data['Login']))
            {
                $mobile = $this->data['Login']['mobile'];
                $password = md5($this->data['Login']['password']);

                $conditions = array(
                    'Supplier.mobile' => "+91" . $mobile,
                    'Supplier.password' => $password,
                    'Supplier.status' => 'ACTIVE');
                $supplier = $this->Supplier->find('first', array('conditions' => $conditions));
                if (empty($supplier)) {
                    $this->Session->setFlash(__('Invalid Mobile No or Password'), 'default', array(), 'error');
                    //$this->redirect(array('action' => 'login'));
                } else {
                    if ($this->Auth->login($supplier)) {
                        $this->redirect(array('controller' => 'supplier', 'action' => 'index'));
                    }
                }
            }

        }
    }

    public function get_order_status_history(){
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $dataToSave = $this->request->data;
            $orderID = $dataToSave['orderID'];
            $thinappID = $dataToSave['thinappID'];
            $statusHistory = $this->SupplireOrderStatus->find('all',array('conditions'=>array("SupplireOrderStatus.supplier_order_id"=>$orderID),'order'=>'SupplireOrderStatus.id ASC','contain'=>false));
            $this->set(array('statusHistory'=>$statusHistory,'orderID'=>$orderID,'thinappID'=>$thinappID,'hasForm'=>'YES'));
            $this->render('update_order_status_supplier', 'ajax');
        }
    }

    public function update_order_status_history(){
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $dataToSave = $this->request->data;
            $thinappID = $dataToSave['thinappID'];
            $orderID = $dataToSave['orderID'];
            $status = $dataToSave['status'];
            $comment = $dataToSave['comment'];
            $dataToSaveInHistory = array('thinapp_id'=>$thinappID,'supplier_order_id'=>$orderID,'status'=>$status,'comment'=>$comment,);
            $this->SupplireOrderStatus->saveAll($dataToSaveInHistory);
            $this->SupplierOrder->updateAll(array('SupplierOrder.status_from_supplire'=>"'".$status."'",),array('SupplierOrder.id'=>$orderID,'SupplierOrder.thinapp_id'=>$thinappID,));
            $dataToSend = array('status'=>1,'message'=>$status);
            echo json_encode($dataToSend); die;
        }
    }

    public function index(){
        $login = $this->Session->read('Auth.User.Supplier');
        $supplierID = $login['id'];
        //Error: syntax error, unexpected ',', expecting ')'
        $orderList = $this->SupplierOrder->find('all',array(
            'conditions'=>array(
                'SupplierOrder.status'=>'ACTIVE',
                'SupplierOrder.supplier_id'=>$supplierID
            ),
            'contain'=>false,
            "order"=>"SupplierOrder.id DESC"
        ));
        $this->set(array('orderList'=>$orderList));
    }

    public function get_order_details($orderID = null){
        $this->layout = false;
        if($orderID != "")
        {
            $orderID = base64_decode($orderID);
            $orderData = $this->SupplierOrder->find("first",array("conditions"=>array("SupplierOrder.id"=>$orderID,),"contain"=>array("Thinapp","SupplierHospital","SupplierOrderDetail"=>array("SupplierAttachmentOrder","SupplierCheckboxRadioOrder","SupplierTeethNumberOrder","SupplierTextTextareaFieldOrder"))));

//pr($orderData); die;

            $teethNumber = $this->SupplierTeethNumber->find('all',array('conditions'=>array(/*"thinapp_id"=>$thinappID,*/"status"=>"ACTIVE"),"contain"=>false,"order"=>"id DESC"));
            $teethNumberFormated = array('UPPER_RIGHT'=>array(),'UPPER_LEFT'=>array(),'LOWER_LEFT'=>array(),'LOWER_RIGHT'=>array());

            foreach($teethNumber AS $numData){
                if($numData['SupplierTeethNumber']['type'] == 'UPPER_RIGHT')
                {
                    $teethNumberFormated['UPPER_RIGHT'][] = $numData;
                }
                else if($numData['SupplierTeethNumber']['type'] == 'UPPER_LEFT')
                {
                    $teethNumberFormated['UPPER_LEFT'][] = $numData;
                }
                else if($numData['SupplierTeethNumber']['type'] == 'LOWER_RIGHT')
                {
                    $teethNumberFormated['LOWER_RIGHT'][] = $numData;
                }
                else if($numData['SupplierTeethNumber']['type'] == 'LOWER_LEFT')
                {
                    $teethNumberFormated['LOWER_LEFT'][] = $numData;
                }
            }

            usort($teethNumberFormated['UPPER_RIGHT'], function ($a, $b) {
                return $a['SupplierTeethNumber']['number'] < $b['SupplierTeethNumber']['number'] ? 1 : -1;
            });
            usort($teethNumberFormated['UPPER_LEFT'], function ($a, $b) {
                return $a['SupplierTeethNumber']['number'] < $b['SupplierTeethNumber']['number'] ? -1 : 1;
            });
            usort($teethNumberFormated['LOWER_RIGHT'], function ($a, $b) {
                return $a['SupplierTeethNumber']['number'] < $b['SupplierTeethNumber']['number'] ? 1 : -1;
            });
            usort($teethNumberFormated['LOWER_LEFT'], function ($a, $b) {
                return $a['SupplierTeethNumber']['number'] < $b['SupplierTeethNumber']['number'] ? -1 : 1;
            });


            $this->set(array('teethNumberFormated'=>$teethNumberFormated,'orderData'=>$orderData));
        }
        else
        {
            die("No data available at the moment!");
        }
    }

	public function logout()
	{

		$logintime = date("Y-m-d H:i:s");
		$this->Session->destroy();
		$this->Cookie->delete('Auth.User');
        $this->Auth->logout();
        $this->redirect(array('controller' => 'supplier', 'action' => 'login'));
	}


}
