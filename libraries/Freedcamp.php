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
    protected $api_url_base = 'http://freedcamp.com/api/api_server';

    // FOR USER ACCESS
    protected $api_key = '';

    // FOR PROJECT ACCESS
    protected $project_unique_name = '';
    protected $project_password = '';

    public $return_format = 'xml';

    function  __construct($params = NULL) {
        
        $api_key = isset($params['api_key']) ? $params['api_key'] : '';
        $access_type = isset($params['access_type']) ? $params['access_type'] : '';                

        if(!empty($api_key)) {
            $this->setApiKey($api_key);
        }

        if(empty($this->api_key)) {
            die('Put your Freedcamp API key in Freedcamp library!');
        }

        if(!empty($access_type)) {
            $this->setAccessType($access_type);
        }

        $this->api_url = $this->api_url_base."_".$this->access_type;

        if(empty($this->rest)) {
            if($this->access_type == 'user') {
                $this->rest = new Rest(
                        array(
                            'server' => $this->api_url,
                            'http_user' => 'api_key',
                            'http_pass' => $this->api_key,
                            'http_auth' => 'basic'
                        )
                );
            } elseif($this->access_type == 'project') {
                 $this->rest = new Rest(
                        array(
                            'server' => $this->api_url,
                            'http_user' => $this->project_unique_name,
                            'http_pass' => $this->project_password,
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

    function getTodoGroupByTodoGroupId($todo_group_id) {
        
        $get_params['todo_group_id'] = $todo_group_id;

        return $this->rest->get('todo_group',$get_params,$this->return_format);
    }

    function addTodoGroup($project_id,$name,$description) {
        $put_params = array();
        $put_params['project_id'] = $project_id;
        $put_params['name'] = $name;
        $put_params['description'] = $description;

        return $this->rest->put('todo_group', $put_params,$this->return_format);
    }

    function editTodoGroup($todo_group_id,$name,$description) {
        $post_params = array();
        $post_params['todo_group_id'] = $todo_group_id;
        $post_params['name'] = $name;
        $post_params['description'] = $description;

        return $this->rest->post('todo_group', $post_params,$this->return_format);
    }

    function deleteTodoGroup($todo_group_id) {
        $delete_params = array();
        $delete_params['todo_group_id'] = $todo_group_id;

        return $this->rest->delete('todo_group', $delete_params,$this->return_format);

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

    function getDiscussionGroupById($discussion_groupid) {
        $get_params['discussion_groupid'] = $discussion_groupid;

        return $this->rest->get('discussion_group',$get_params,$this->return_format);
    }

    function addDiscussionGroup($project_id,$name,$description) {
        $put_params = array();
        $put_params['project_id'] = $project_id;
        $put_params['name'] = $name;
        $put_params['description'] = $description;

        return $this->rest->put('discussion_group', $put_params,$this->return_format);
    }

    function editDiscussionGroup($discussion_groupid,$name,$description) {
        $post_params = array();        
        $post_params['discussion_groupid'] = $discussion_groupid;
        $post_params['name'] = $name;
        $post_params['description'] = $description;

        return $this->rest->post('discussion_group', $post_params,$this->return_format);
    }

    function deleteDiscussionGroup($discussion_group_id) {
        $delete_params = array();
        $delete_params['discussion_groupid'] = $discussion_group_id;

        return $this->rest->delete('discussion_group', $delete_params,$this->return_format);
    }

    function getDiscussionsByDiscussionGroupId($discussion_groupid) {
        $discussion_groupid = (int) $discussion_groupid;
        if(empty($discussion_groupid)) return false;

        $get_params = array();
        $get_params['discussion_groupid'] = $discussion_groupid;
        
        return $this->rest->get('discussion',$get_params,$this->return_format);
    }
    
    function getDiscussionsByProjectId($project_id) {
        $get_params = array();
        $get_params['project_id'] = $project_id;

        return $this->rest->get('discussion',$get_params,$this->return_format);
    }
    
    function getDiscussionById($discussion_id) {
        $get_params = array();
        $get_params['discussion_id'] = $discussion_id;

        return $this->rest->get('discussion',$get_params,$this->return_format);
    }

    function addDiscussion($name,$description,$groupid) {
        $put_params = array();
        $put_params['name'] = $name;
        $put_params['description'] = $description;
        $put_params['groupid'] = $groupid;

        return $this->rest->put('discussion', $put_params,$this->return_format);
    }

    function editDiscussion($discussionid,$name,$description,$groupid) {
        $post_params = array();
        $post_params['discussionid'] = $discussionid;
        $post_params['name'] = $name;
        $post_params['description'] = $description;
        $post_params['groupid'] = $groupid;

        return $this->rest->post('discussion', $post_params,$this->return_format);
    }

    function deleteDiscussion($discussionid) {
        $delete_params = array();
        $delete_params['discussionid'] = $discussionid;

        return $this->rest->delete('discussion', $delete_params,$this->return_format);
    }


    function getMilestonesByProjectId($project_id) {
        $project_id = (int) $project_id;
        if(empty($project_id)) return false;
        $get_params['project_id'] = $project_id;

        return $this->rest->get('milestone',$get_params,$this->return_format);
    }
    
    function getMilestoneById($milestoneid) {
        $get_params['milestoneid'] = $milestoneid;

        return $this->rest->get('milestone',$get_params,$this->return_format);
    }

    function addMilestone($project_id,$name,$description,$due_date,$priority =0,$assigned_to_id = 0 ) {
        $put_params = array();
        $put_params['project_id'] = $project_id;
        $put_params['name'] = $name;
        $put_params['description'] = $description;
        $put_params['priority'] = $priority;
        $put_params['due_date'] = $due_date;
        $put_params['assigned_to_id'] = $assigned_to_id;

        return $this->rest->put('milestone', $put_params,$this->return_format);
    }

    function editMilestone($milestoneid,$name,$description,$due_date,$priority =0,$assigned_to_id = 0) {
        $post_params = array();
        $post_params['milestoneid'] = $milestoneid;
        $post_params['name'] = $name;
        $post_params['description'] = $description;
        $post_params['priority'] = $priority;
        $post_params['due_date'] = $due_date;
        $post_params['assigned_to_id'] = $assigned_to_id;

        return $this->rest->post('milestone', $post_params,$this->return_format);
    }

    function deleteMilestone($milestoneid) {
        $delete_params = array();
        $delete_params['milestoneid'] = $milestoneid;

        return $this->rest->delete('milestone', $delete_params,$this->return_format);
    }

    function getTimesByProjectId($project_id) {
        $project_id = (int) $project_id;
        if(empty($project_id)) return false;
        $get_params['project_id'] = $project_id;

        return $this->rest->get('time',$get_params,$this->return_format);
    }
    
    function getTimeById($timeid) {
        $get_params['timeid'] = $timeid;

        return $this->rest->get('time',$get_params,$this->return_format);
    }

    function addTime($project_id,$description,$userid = 0,$type =0 ,$billible = 0,$time =0) {
        $put_params = array();
        $put_params['project_id'] = $project_id;
        $put_params['description'] = $description;
        $put_params['userid'] = $userid;
        $put_params['type'] = $type;
        $put_params['billable'] = $billible;
        $put_params['time'] = $time;

        return $this->rest->put('time', $put_params,$this->return_format);
    }

    function editTime($timeid,$description,$userid = 0,$type =0 ,$billible = 0,$time =0) {
        $post_params = array();
        $post_params['timeid'] = $timeid;        
        $post_params['userid'] = $userid;
        $post_params['description'] = $description;
        $post_params['type'] = $type;
        $post_params['billable'] = $billible;
        $post_params['time'] = $time;

        $post_params['action'] = 'edit';

        return $this->rest->post('time', $post_params,$this->return_format);
    }

    function updateTime($timeid,$time,$description) {
        $post_params = array();
        $post_params['timeid'] = $timeid;        
        $post_params['description'] = $description;        
        $post_params['time'] = $time;

        $post_params['action'] = 'update';

        return $this->rest->post('time', $post_params,$this->return_format);
    }

    function resetTime($timeid) {
        $post_params = array();
        $post_params['timeid'] = $timeid;

        $post_params['action'] = 'reset';

        return $this->rest->post('time', $post_params,$this->return_format);
    }

    function startTime($timeid) {
        $post_params = array();
        $post_params['timeid'] = $timeid;

        $post_params['action'] = 'start';

        return $this->rest->post('time', $post_params,$this->return_format);
    }

    function stopTime($timeid) {
        $post_params = array();
        $post_params['timeid'] = $timeid;

        $post_params['action'] = 'stop';

        return $this->rest->post('time', $post_params,$this->return_format);
    }

    function deleteTime($timeid) {
        $delete_params = array();
        $delete_params['timeid'] = $timeid;

        return $this->rest->delete('time', $delete_params,$this->return_format);
    }


    function getTodoComments($todo_id) {
        $get_params = array();
        $get_params['type_id'] = (int) $todo_id;        
        $get_params['type'] = 'todos';

        return $this->rest->get('comment',$get_params,$this->return_format);
    }
    
    function getTodoComment($comment_id) {
        $get_params = array();
        $get_params['commentid'] = $comment_id;
        $get_params['type'] = 'todos';

        return $this->rest->get('comment',$get_params,$this->return_format);
    }
    
    function getDiscussionComments($discussion_id) {
        $get_params = array();
        $get_params['type_id'] = (int) $discussion_id;        
        $get_params['type'] = 'discussions';

        return $this->rest->get('comment',$get_params,$this->return_format);
    }

    function getDiscussionComment($comment_id) {
        $get_params = array();
        $get_params['commentid'] = $comment_id;
        $get_params['type'] = 'discussions';

        return $this->rest->get('comment',$get_params,$this->return_format);
    }
    
    function addTodoComment($todo_id,$description) {
        $put_params = array();
        $put_params['description'] = $description;
        $put_params['type'] = 'todos';
        $put_params['type_id'] = $todo_id;

        return $this->rest->put('comment', $put_params,$this->return_format);
    }
    
    function addDiscussionComment($discussion_id,$description) {
        $put_params = array();
        $put_params['description'] = $description;
        $put_params['type'] = 'discussions';
        $put_params['type_id'] = $discussion_id;

        return $this->rest->put('comment', $put_params,$this->return_format);
    }

    function editComment($comment_id,$description) {
        $post_params = array();
        $post_params['comment_id'] = $comment_id;
        $post_params['description'] = $description;

        return $this->rest->post('comment', $post_params,$this->return_format);
    }

    function deleteComment($comment_id) {
        $delete_params = array();
        $delete_params['comment_id'] = $comment_id;

        return $this->rest->delete('comment', $delete_params,$this->return_format);
    }


    /*************************************************************************
     *                               ONLY FOR ACCESS TYPE PROJECT                                                            *
     *************************************************************************/

     function getTodoGroupsProject() {
        $get_params = array();
        return $this->rest->get('todo_group', $get_params,$this->return_format);        
    }
    
    function getTodosProject() {
        $get_params = array();
        return $this->rest->get('todo', $get_params,$this->return_format);
    }
    
    function getDiscussionsGroupsProject() {
        $get_params = array();
        return $this->rest->get('discussion_group', $get_params,$this->return_format);
    }
    
    function getDiscussionsProject() {
        $get_params = array();
        return $this->rest->get('discussion', $get_params,$this->return_format);
    }
    
    function getMilestonesProject() {
        $get_params = array();
        return $this->rest->get('milestone', $get_params,$this->return_format);
    }
    
    function getTimesProject() {
        $get_params = array();
        return $this->rest->get('time', $get_params,$this->return_format);
    }
    

}
