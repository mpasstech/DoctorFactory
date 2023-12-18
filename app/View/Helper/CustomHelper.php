<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
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
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Helper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class CustomHelper extends AppHelper {
    
    public function GetSubscribersCount($channel_id = null)
    {
	$table_obj = ClassRegistry::init('Subscriber');
	return $table_obj->find('count',array('conditions'=>array('channel_id'=>$channel_id),'recursive'=>-1));
	
    }
    
    public function GetMessagesCount($channel_id = null)
    {
	$table_obj = ClassRegistry::init('Message');
	return $table_obj->find('count',array('conditions'=>array('channel_id'=>$channel_id),'recursive'=>-1));
	
    }
    
    public function GetMessageQueuesCount($message_id = null)
    {
	$table_obj = ClassRegistry::init('MessageQueue');
	return $table_obj->find('count',array('conditions'=>array('message_id'=>$message_id),'recursive'=>-1));
	
    }
    
    public function GetField($model = null,$field =  null,$is_equal = null)
    {
	$table_obj = ClassRegistry::init($model);
	$data = $table_obj->find('first',array('conditions'=>array('id'=>$is_equal),'fields'=>array($field),'recursive'=>-1));
	if(!empty($data) && isset($data)){
	    return $data[$model][$field];
	}else{
	    return false;
	}
    }
    
    public function GetUserImage($user_id)
    {
	$user = ClassRegistry::init('User');
	$user_data = $user->find('first',array('conditions'=>array('User.id'=>$user_id),'fields'=>array('image'),'recursive'=>-1));
	if(!empty($user_data['User']['image']) && isset($user_data['User']['image'])){
	    $user_image = $user_data['User']['image'];
	}else{
	    $user_image = SITE_URL.'img/profile/profile_default.png';
	}
	return $user_image;
    }
    
    public function GetChannelImage($channel_id)
    {
	$channel_obj = ClassRegistry::init('Channel');
	$channel = $channel_obj->find('first',array('conditions'=>array('Channel.id'=>$channel_id),'fields'=>array('Channel.image','Channel.user_id'),'recursive'=>-1));
	if(!empty($channel['Channel']['image']) && isset($channel['Channel']['image'])){
	    $channel_image = $channel['Channel']['image'];
	}else{
	    $channel_image = $this->GetUserImage($channel['Channel']['user_id']);
	}
	return $channel_image;
    }
    
    public function GetEntityCount($field_by = null, $id = null, $model = null)
    {
        if(isset($id) && isset($model) && isset($field_by)){
            $table_obj = ClassRegistry::init($model);
            $return  = $table_obj->find('count',array('conditions'=>array($field_by=>$id),'recursive'=>-1));
        }else{
            $return = 0;
        }
        return $return ;
    }
    
    public function Getpages()
    {
	$page = ClassRegistry::init('Page');
	return $page->find('all',array('conditions'=>array('Page.is_active'=> 't')));
	
    }




    /* function add by mahendra*/
    public function total_subscribe($channel_id = null) {
        $count = ClassRegistry::init('Subscriber')->find("count", array("conditions" => array(
            "Subscriber.channel_id" =>$channel_id ,
            "Subscriber.status" =>'SUBSCRIBED'
        )));
        if ($count==0){
            return 0;
        } else {
            return $count;
        }

    }


    /* function add by mahendra*/
    public function getChannelList($thin_app_id) {
        $count = ClassRegistry::init('Channel')->find("list", array(
            "conditions" => array(
                "Channel.app_id" =>$thin_app_id
            ),
            "fields"=>array('id','channel_name')
        ));
        return $count;
    }

    /* api created by mahendra */
    public function getActionType(){
        return ClassRegistry::init('ActionType')->find('all');
    }


    public function getPollChart($questionID = null){

        $questionTable = ClassRegistry::init('ActionQuestion');

        $question = $questionTable->find('first',array(
            'conditions'=>array('ActionQuestion.id'=>$questionID),
            'contain'=>array('ActionType','QuestionChoice','ActionResponse','Thinapp'),
            'fields'=>array(
                'ActionQuestion.id','ActionQuestion.question_text','ActionQuestion.participates_count',
                'ActionQuestion.response_count','ActionType.name','Thinapp.name'
                )
        ));

        $actionResponse = $question['ActionResponse'];
        $actionChoice = $question['QuestionChoice'];
        $actionType = $question['ActionType'];
        $thinApp = $question['Thinapp'];
        $responseCount = array();


        if(in_array($actionType['name'],array('DROPDOWN','YES/NO','RATING','SCALING','MULTIPLE CHOICES'))) {

            foreach($actionResponse as $value )
            {
                if( isset($responseCount[$value['question_choice_id']]) )
                {
                    $responseCount[ $value['question_choice_id'] ]  = $responseCount[$value['question_choice_id']] + 1;
                }
                else
                {
                    $responseCount[ $value['question_choice_id'] ]  = 1;
                }
            }
            $actionChoiceArr = array();

            foreach($actionChoice as $val)
            {
                $actionChoiceArr[$val['id']] = $val['choices'];
            }


        }
        elseif ($actionType['name'] == 'RANKING')
        {

            $totalAns = $question['ActionQuestion']['response_count'];
            $totalResCount = 0;
                    foreach($actionResponse as $value )
                    {

                        if( isset($responseCount[$value['question_choice_id']]) )
                        {
                            $responseCount[ $value['question_choice_id'] ]  = $responseCount[ $value['question_choice_id'] ] + $value['user_input_values'];
                        }
                        else
                        {
                            $responseCount[ $value['question_choice_id'] ]  = $value['user_input_values'];
                        }
                        $totalResCount = $totalResCount + $value['user_input_values'];
                    }

                    $responseTmp = array();

            //echo $totalResCount;
                    foreach($responseCount as $key => $value)
                    {
                        $responseTmp[$key] = round(($value/$totalResCount)*100);
                    }
                    $responseCount = $responseTmp;

                    $actionChoiceArr = array();
                    foreach($actionChoice as $val)
                    {
                        $actionChoiceArr[$val['id']] = $val['choices'];
                    }



        }
        else
        {
            $totalAns = $question['ActionQuestion']['response_count'];
            $totalResCount = 0;
            $responseCount = array();
            $questionTable = ClassRegistry::init('ActionResponse');

            $response_data = $questionTable->find('all',array(
                "conditions"=>array(
                    "ActionResponse.action_question_id"=>$questionID
                ),
                'contain'=>false,
                'fields'=>array('ActionResponse.mobile_number','ActionResponse.user_input_values')
            ));
            $actionChoiceArr = array();
            $mobile="";

            if(!empty($response_data)){

                $tmp = array();
                foreach($response_data as $val)
                {
                    $actionChoiceArr[$val["ActionResponse"]["mobile_number"]][] = $val["ActionResponse"]["user_input_values"];

                }
            }
        }

     //   pr($responseCount);

        return array('responseCount'=>$responseCount,'actionChoice'=>$actionChoiceArr,'questionVal'=>$question['ActionQuestion'],'actionType'=>$actionType,'thinApp'=>$thinApp);

      }



    function timeElapsedString($ptime)
    {
        $etime = time() - $ptime;

        if ($etime < 1)
        {
            return '0 seconds';
        }

        $a = array( 365 * 24 * 60 * 60  =>  'year',
            30 * 24 * 60 * 60  =>  'month',
            24 * 60 * 60  =>  'day',
            60 * 60  =>  'hour',
            60  =>  'minute',
            1  =>  'second'
        );
        $a_plural = array( 'year'   => 'years',
            'month'  => 'months',
            'day'    => 'days',
            'hour'   => 'hours',
            'minute' => 'minutes',
            'second' => 'seconds'
        );

        foreach ($a as $secs => $str)
        {
            $d = $etime / $secs;
            if ($d >= 1)
            {
                $r = round($d);
                return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
            }
        }
    }

    /* function add by mahendra*/
    public function getDepartmentList($dep_type) {


        $department = ClassRegistry::init('DepartmentCategory')->find("list", array(
            "fields"=>array('department_name','department_name'),
            "group"=>array('department_name')
        ));
        $list_array =array();
        if(!empty($department)){
                foreach ($department as $key => $value){
                    $count = ClassRegistry::init('DepartmentCategory')->find("list", array(
                        "conditions" => array(
                            "DepartmentCategory.department_name" =>$value
                        ),
                        "fields"=>array('category_name','category_name')
                    ));
                    $list_array[$value] = $count;

                }
            return $list_array;
        }else{
            return false;
        }


    }


}
