<?php
class AppMasterVaccination extends AppModel
{
    public $actsAs = array('Containable');
    var $validate = array(
        'full_name' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter full name.'),
           ),
        'vac_name' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter sub title.')
        ),
        'vac_radio' => array(
            'week' => array (
                'rule' => array('checkWeek'),
                'message' => 'Please enter week number.'
            ),
            'year_month' => array (
                'rule' => array('checkYearMonth'),
                'message' => 'Please enter year or month number.'
            )
        )


    );

    function checkWeek($day)
    {
        $data = $this->data['AppMasterVaccination'];
        if("WEEK" == $data['vac_radio'] && $data['week'] ==0 ){
            return false;
        }else{
            return true;
        }
    }

    function checkYearMonth($day)
    {
        $data = $this->data['AppMasterVaccination'];
        if("OTHER_DAY" == $data['vac_radio'] && $data['month'] ==0 && $data['year'] == 0){
            return false;
        }else{
            return true;
        }
    }
}