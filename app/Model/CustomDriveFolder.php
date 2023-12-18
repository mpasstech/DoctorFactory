<?php
class CustomDriveFolder extends AppModel
{
    public $useTable = 'drive_folders';


    public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
        $recursive = -1;

        // Mandatory to have
        $data = $this->paginate;
        $limit = $data['limit'];
        $conditions = $data['conditions'];
        $thinapp_id = $conditions['thinapp_id'];
        $user_id = $conditions['user_id'];
        $mobile = $conditions['mobile'];
        $this->useTable = false;
        $sql = '';
        $sql .= "select * from drive_folders where user_id =$user_id and thinapp_id = $thinapp_id UNION ALL select df.* from drive_shares as ds join drive_folders as df  on df.id = ds.drive_folder_id where  ds.thinapp_id = $thinapp_id and ds.shared_object='FOLDER' and ds.status ='SHARED' and ds.share_with_mobile = '$mobile' limit ";
        // Adding LIMIT Clause
        $sql .= (($page - 1) * $limit) . ', ' . $limit;
        $results = $this->query($sql);
        return $results;
    }


    public function paginateCount($conditions=0, $recursive=0, $extra=array())
    {
        $data = $this->paginate;
        $conditions = $data['conditions'];
        $thinapp_id = $conditions['thinapp_id'];
        $user_id = $conditions['user_id'];
        $mobile = $conditions['mobile'];
        $r = $this->query("select * from drive_folders where user_id =$user_id and thinapp_id = $thinapp_id UNION ALL select df.* from drive_shares as ds join drive_folders as df  on df.id = ds.drive_folder_id where  ds.thinapp_id = $thinapp_id and ds.shared_object='FOLDER' and ds.status ='SHARED' and ds.share_with_mobile = '$mobile'");
        return count($r);
    }
}