<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');
include(WWW_ROOT . "webservice" . DS . "ConnectionUtil.php");
include(WWW_ROOT . "webservice" . DS . "WebservicesFunction.php");
include(WWW_ROOT . "webservice" . DS . "WebServicesFunction_2_3.php");


/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class CmsController extends AppController
{

    function beforeFilter()
    {

        $login = $this->Session->read('Auth.User');
        $this->layout = 'app_admin_home';
        parent::beforeFilter();

        $this->Auth->allow('view_cms.php','view_cms');
        if (!$this->Auth->loggedIn()) {
            $this->Auth->logout();
        }

    }

    public function search_cms()
    {
        $reqData = $this->request->query;
        $pram = array();
        if (!empty($reqData['type'])) {
            $pram['t'] = $reqData['type'];
        }
        if (!empty($reqData['name'])) {
            $pram['n'] = $reqData['name'];
        }

        $this->redirect(
            array(
                "controller" => "cms",
                "action" => "cms",
                "?" => $pram,
            )
        );
    }

    public function cms()
    {
        $this->layout = 'app_admin_home';
        $login = $this->Session->read('Auth.User');


        $searchData = $this->request->query;
        $conditions = array();
        if (isset($searchData['t']) && !empty($searchData['t'])) {
            $this->request->data['Search']['type'] = $searchData['t'];
            $conditions["CmsPage.channel_status"] = $searchData['t'];
        }
        if (isset($searchData['n']) && !empty($searchData['n'])) {
            $this->request->data['Search']['name'] = $searchData['n'];
            $conditions["CmsPage.channel_name LIKE"] = '%' . $searchData['n'] . '%';
        }


        $this->paginate = array(
            "conditions" => array(
                "CmsPage.thinapp_id" => $login['User']['thinapp_id'],
                "CmsPage.user_id" => $login['User']['id'],
                $conditions
            ),
            'contain' => false,
            'limit' => WEB_PAGINATION_LIMIT
        );
        $data = $this->paginate('CmsPage');
        $this->set('channel', $data);
    }

    public function manage($page_category_id=null,$cms_id=null)
    {
        $this->layout = 'app_admin_home';
        $login = $this->Session->read('Auth.User');
        $page_category_id = !empty($page_category_id)?base64_decode($page_category_id):0;
        $cms_id = !empty($cms_id)?base64_decode($cms_id):0;
        $this->set(compact( 'page_category_id','cms_id'));

    }


    public function view_cms($cms_id=null)
    {
        $this->layout = false;
        $page_id = base64_decode($cms_id);
        $query = "select  cp.title as page_title, cp.page_category_id, cpb.* FROM cms_pages as cp left join dashboard_icons as di on di.id = cp.page_category_id left join cms_pages_body as cpb on cp.id = cpb.cms_page_id where cp.id =$page_id order by  cpb.id desc";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        $title = $page_category_id ="";
        if ($data->num_rows) {
            $page_data =mysqli_fetch_all($data,MYSQL_ASSOC);
            $title = $page_data[0]['page_title'];
            $page_category_id = $page_data[0]['page_category_id'];

        }
        $google_api_key = GAPI_ENCRYPT;
        $this->set(compact( 'title','page_data','page_category_id','google_api_key'));


    }

    public function delete_cms($id = null)
    {
        $this->layout = $this->autoRender = false;
        $login = $this->Session->read('Auth.User');
        $response=array();
        if ($this->CmsPage->deleteAll(array('CmsPage.id' => base64_decode($id)))) {
            WebservicesFunction::deleteJson(array('app_cms_' . $login['User']['thinapp_id']),'cms');
            $this->Session->setFlash(__('Cms deleted successfully.'), 'default', array(), 'success');
            $response['status']=1;
            $response['message']="Cms deleted successfully";

        } else {
            $response['status']=0;
            $response['message']="Sorry cms could not deleted";
            $this->Session->setFlash(__('Sorry cms could not deleted.'), 'default', array(), 'error');
        }
        echo json_encode($response);die;

    }

    public function cms_body()
    {

        $this->layout = false;

        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $thin_app_id = $login['thinapp_id'];
            $page_category_id = base64_decode($this->request->data['pci']);
            $data = $this->CmsPage->find('first', array(
                'conditions' => array(
                    'CmsPage.page_category_id' => $page_category_id,
                    'CmsPage.thinapp_id' => $thin_app_id,
                    'CmsPage.status' => 'ACTIVE',
                ),
                'contain' => array('CmsPageBody'))
            );
            $iframe_url = "";
            if(!empty($data)){
                if($data['CmsPage']['request_load_type']=='CONTENT'){
                    $iframe_url = Router::url('/cms/view_cms/',true).base64_encode($data['CmsPage']['id']);
                }else{
                    $iframe_url = $data['CmsPage']['url'];
                }

            }

           if(empty($data) || empty($data['CmsPageBody']) ){

                $id = isset($data['CmsPage']['id'])?$data['CmsPage']['id']:0;
                $tittle = isset($data['CmsPage']['title'])?$data['CmsPage']['title']:'';
                $dashboard_icon_url = isset($data['CmsPage']['dashboard_icon_url'])?$data['CmsPage']['dashboard_icon_url']:'';
                $description = isset($data['CmsPage']['description'])?$data['CmsPage']['description']:'';
                $url = isset($data['CmsPage']['url'])?$data['CmsPage']['url']:'';
                $request_load_type = isset($data['CmsPage']['request_load_type'])?$data['CmsPage']['request_load_type']:'CONTENT';

                $data['CmsPage']=array(
                    'id'=>$id,
                    'title'=>$tittle,
                    'request_load_type'=>$request_load_type,
                    'url'=>$url,
                    'dashboard_icon_url'=>$dashboard_icon_url
                );

                $data['CmsPageBody'][0]=array(
                    'title'=>'',
                    'sub_title'=>'',
                    'description'=>$description,
                    'media'=>'',
                    'category_name'=>'',
                    'field1'=>'',
                    'field2'=>''
                );
            }



            $google_api_key = GAPI_ENCRYPT;
            $this->set(compact( 'data','page_category_id','iframe_url','google_api_key'));
            return $this->render('cms_body', 'ajax');

        }else{
            exit();
        }
    }

    public function save_template()
    {

        $this->layout = 'ajax';
        $this->autoRender = false;
        $response=array();
        if($this->request->is('ajax')) {
            $post=array();
            $login = $this->Session->read('Auth.User.User');
            $thin_app_id = $login['thinapp_id'];
            $data = $this->request->data;
            if(!empty($data['cpi'])){
                $saveData['id']=base64_decode($data['cpi']);
            }

            $saveData['user_id']=$login['id'];
            $saveData['thinapp_id']=$thin_app_id;
            $saveData['title']=$data['icon_title'];
            $saveData['request_load_type']=$data['request_load_type'];
            $saveData['dashboard_icon_url']=$data['dashboard_icon_url'];
            $saveData['url']=$data['url'];
            $saveData['page_category_id']=$data['page_category_id'];

            $title_array = @$data['title'];
            $sub_title_array = @$data['sub_title'];
            $description_array = @$data['description'];
            $category_name_array = @$data['category_name'];
            $media_array = @$data['media'];
            $field1_array = @$data['field1'];
            $field2_array = @$data['field2'];

            $transaction = $this->CmsPage->getDataSource();
            try {
                $transaction->begin();
                if ($this->CmsPage->save($saveData)) {
                    $cms_page_id = empty($saveData['id'])?$this->CmsPage->getLastInsertId():$saveData['id'];
                    if($saveData['request_load_type']=="CONTENT"){
                        $saveBodyData =array();
                        $upload_files = @$_FILES['select_media'];
                        foreach($title_array as $key =>$title){
                            $tmp_array =array();
                            $tmp_array['title']=$title;
                            $tmp_array['cms_page_id']=$cms_page_id;
                            $tmp_array['thinapp_id']=$thin_app_id;
                            $tmp_array['sub_title']=isset($sub_title_array[$key])?$sub_title_array[$key]:'';
                            $tmp_array['description']=isset($description_array[$key])?$description_array[$key]:'';
                            $tmp_array['category_name']=isset($category_name_array[$key])?$category_name_array[$key]:'';
                            $tmp_array['field1']=isset($field1_array[$key])?$field1_array[$key]:'';
                            $tmp_array['field2']=isset($field2_array[$key])?$field2_array[$key]:'';

                            if(!empty($upload_files['tmp_name'][$key])){
                                $tmp_obj['tmp_name']= $upload_files['tmp_name'][$key];
                                $tmp_obj['name']= $upload_files['name'][$key];
                                $tmp_obj['size']= $upload_files['size'][$key];
                                $tmp_obj['type']= $upload_files['type'][$key];
                                $tmp_obj['error']= $upload_files['error'][$key];
                                $result=json_decode(WebServicesFunction_2_3::upload_file_to_aws($tmp_obj),true);
                                if($result['status']==1){
                                    $tmp_array['media']=$result['url'];
                                    $tmp_array['media_type']=Custom::getFileType($tmp_obj['name']);
                                }else{
                                    $tmp_array['media']="";
                                    $tmp_array['media_type']="";
                                }
                            }else{
                                $tmp_array['media']=@isset($media_array[$key])?$media_array[$key]:'';
                                if(!empty($tmp_array['media'])){
                                    $tmp_explode =explode("/",$tmp_array['media']);
                                    $tmp_array['media_type']=Custom::getFileType(end($tmp_explode));
                                }
                            }
                            $saveBodyData[] =$tmp_array;
                        }
                        if(!empty($saveBodyData)){
                            $delete_prev_body = $this->CmsPageBody->deleteAll(array('CmsPageBody.cms_page_id' => $cms_page_id));
                            if ($this->CmsPageBody->saveAll($saveBodyData)){
                                $transaction->commit();
                                WebservicesFunction::deleteJson(array('app_cms_' . $thin_app_id),'cms');
                                $response['status'] = 1;
                                $response['message'] = "Template save successfully";
                                $response['url'] = Router::url('/cms/view_cms/',true).base64_encode($cms_page_id);
                            }else{
                                $response['status'] = 0;
                                $response['message'] = "Sorry, template not save";
                                $transaction->rollback();
                            }
                        }else{
                            $transaction->commit();
                            $response['status'] = 1;
                            $response['message'] = "Template save successfully";
                        }
                    }else{
                        $transaction->commit();
                        WebservicesFunction::deleteJson(array('app_cms_' . $thin_app_id),'cms');
                        $response['status'] = 1;
                        $response['message'] = "Template save successfully";
                        $response['url'] = $saveData['url'];
                    }

                }else{
                    $response['status'] = 0;
                    $response['message'] = "Sorry, template not save";
                    $transaction->rollback();
                }
            }catch (Exception $e){
                $response['status'] = 0;
                $response['message'] = "Sorry, template not save";
                $transaction->rollback();
            }
            return json_encode($response);
        }else{
            exit();
        }
    }

}