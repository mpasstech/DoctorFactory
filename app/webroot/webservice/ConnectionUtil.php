<?php

/**
 * Created by PhpStorm.
 * User: Mobzway_pass
 * Date: 12/14/2016
 * Time: 12:13 PM
 */
//namespace ConnectionUtil;

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
class ConnectionUtil
{

    public static function getConnection(){

               
    			 /*if(isset($GLOBALS['connection'])){
                    $connection = $GLOBALS['connection'];
                }else{
                    $connection = mysqli_connect("p:localhost", "mbroadcast_db_user", ".MbroadcastDbUsER.", "doctor_live");
                    $GLOBALS['connection'] = $connection;
                    if($connection->connect_error){
                        $response =array();
                        $response['status'] = 0;
                        $response['message'] = "Database connection errro";
                        echo json_encode($response);die;
                    }

                }*/
     			//mysqli_set_charset( $connection, "latin1" );
    				//$connection = mysqli_connect("p:localhost", "mbroadcast_db_user", ".MbroadcastDbUsER.", "doctor_live");
    //$connection = mysqli_connect("mengage.c537q8n00mkl.ap-south-1.rds.amazonaws.com", "doctor_user", ".D8trabE8#.", "doctor_live");
    $connection = mysqli_connect("127.0.0.1", "root", "", "doctor_staging_2_6");
                    
                    if($connection->connect_error){
                        $response =array();
                        $response['status'] = 0;
                        $response['message'] = "Database connection errro";
                        echo json_encode($response);die;
                    }
                return $connection;
    			
    
    
    
    }
}