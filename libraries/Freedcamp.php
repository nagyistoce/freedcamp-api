<?php
/**
 * Project:     Freedcamp PHP API
 * File:        Freedcamp.php
 *
 *
 *
 * @link http://freedcamp.com/
 * @copyright Freedcamp LLC
 * @author Stanislav Ognyanov
 * @package Freedcamp Codeigniter API
 * @version 1.0
 */


/*
 * The Freedcamp PHP API requires the Rest library class.
 */

require_once 'Rest.php';
require_once 'Curl.php';

class Freedcamp {    

    protected $access_type = 'user'; // user and project

    protected $rest  = NULL;

    protected $api_url;
    protected $api_key = '';
    public $return_format = 'xml';

    function  __construct($params = NULL) {
        
        $api_key = isset($params['api_key']) ? $params['api_key'] : '';
        $access_type = isset($params['access_type']) ? $params['access_type'] : 'user';
        $api_url = isset($params['api_url']) ? $params['api_url'] : 'http://freedcamp.com';
        

        if(!empty($api_key)) {
            $this->setApiKey($api_key);
        }                

        if(!empty($access_type)) {
            $this->setAccessType($access_type);
        }

        $this->api_url = $api_url."_".$access_type;


        if(empty($this->rest)) {
            if($access_type == 'user') {
                $this->rest = new Rest(
                        array(
                            'server' => $this->api_url,
                            'http_user' => 'api_key',
                            'http_pass' => $this->api_key,
                            'http_auth' => 'basic'
                        )
                );
            }
        }        
    }

    function setApiKey($api_key) {
        $this->api_key = $api_key;
    }    
    
    function setAccessType($access_type) {
        $this->access_type = $access_type;
    }

    function getProjects() {
         return $this->rest->get('project',NULL,$this->return_format);
    }

    function getProjectById($project_id) {
        $get_params = array();
        $get_params['project_id'] = $project_id;
        return $this->rest->get('project',$get_params,$this->return_format);
    }

    function getTodoGroupsByProjectId($project_id) {
        $project_id = (int) $project_id;
        if(empty($project_id)) return false;
        $get_params['project_id'] = $project_id;
        
        return $this->rest->get('todo_group',$get_params,$this->return_format);        
    }

    function getTodoByTodoId($todo_id) {
        $todo_id = (int) $todo_id;
        if(empty($todo_id)) return false;

        $get_params = array();
        $get_params['todo_id'] = $todo_id;

        return $this->rest->get('todo',$get_params,$this->return_format);
    }

    function getTodosByTodoGroupId($todo_groupid) {
        $todo_groupid = (int) $todo_groupid;
        if(empty($todo_groupid)) return false;

        $get_params = array();        
        $get_params['todo_groupid'] = $todo_groupid;
        
        return $this->rest->get('todo',$get_params,$this->return_format);
    }

    function getTodosByProjectId($project_id) {
        $project_id = (int) $project_id;
        if(empty($project_id)) return false;
        $get_params['project_id'] = $project_id;

        return $this->rest->get('todo',$get_params,$this->return_format);
    }

    function addTodo($description,$todo_groupid,$priority = 0,$due_date = '',$time=0,$assigned_to_id = 0) {
        $put_params = array();                        
        $put_params['description'] = $description;
        $put_params['todo_groupid'] = $todo_groupid;
        $put_params['priority'] = $priority;
        $put_params['due_date'] = $due_date;
        $post_params['time'] = $time;
        $post_params['userid'] = $assigned_to_id;

        return $this->rest->put('todo', $put_params,$this->return_format);
    }

    function editTodo($todo_id,$description,$todo_groupid,$priority = 0,$due_date = '',$time=0,$assigned_to_id = 0) {
        $post_params = array();
        $post_params['todoid'] = $todo_id;
        $post_params['userid'] = $assigned_to_id;
        $post_params['todo_groupid'] = $todo_groupid;
        $post_params['priority'] = $priority;
        $post_params['description'] = $description;
        $post_params['time'] = $time;
        $post_params['due_date'] = $due_date;

        return $this->rest->post('todo', $post_params,$this->return_format);
    }

    function deleteTodo($todo_id) {
        $delete_params = array();
        $delete_params['todoid'] = $todo_id;

        return $this->rest->delete('todo', $delete_params,$this->return_format);
    }

    function getDiscussionGroupsByProjectId($project_id) {
        $project_id = (int) $project_id;
        if(empty($project_id)) return false;
        $get_params['project_id'] = $project_id;

        return $this->rest->get('discussion_group',$get_params,$this->return_format);
    }

    function getDiscussionsByDiscussionGroupId($discussion_groupid) {
        $discussion_groupid = (int) $discussion_groupid;
        if(empty($discussion_groupid)) return false;

        $get_params = array();
        $get_params['discussion_groupid'] = $discussion_groupid;
        
        return $this->rest->get('discussion',$get_params,$this->return_format);
    }

    function getMilestonesByProjectId($project_id) {
        $project_id = (int) $project_id;
        if(empty($project_id)) return false;
        $get_params['project_id'] = $project_id;

        return $this->rest->get('milestone',$get_params,$this->return_format);
    }

    function getTimesByProjectId($project_id) {
        $project_id = (int) $project_id;
        if(empty($project_id)) return false;
        $get_params['project_id'] = $project_id;

        return $this->rest->get('time',$get_params,$this->return_format);
    }

    function getTodoComments($todo_id,$project_id) {
        $get_params = array();
        $get_params['type_id'] = (int) $todo_id;
        $get_params['project_id'] = (int) $project_id;
        $get_params['type'] = 'todos';

        return $this->rest->get('comment',$get_params,$this->return_format);
    }
    
    function getDiscussionComments($discussion_id,$project_id) {
        $get_params = array();
        $get_params['type_id'] = (int) $discussion_id;
        $get_params['project_id'] = (int) $project_id;
        $get_params['type'] = 'discussions';

        return $this->rest->get('comment',$get_params,$this->return_format);
    }
}
