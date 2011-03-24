<?php

require_once './libraries/Freedcamp.php';

$oFreedcamp = new Freedcamp();

$project_id = $_GET['project_id'];
$todo_id = $_GET['todo_id'];

$aTodo = $oFreedcamp->getTodoByTodoId($todo_id);

if($aTodo['http_code'] != 200) {
    die('Error finding todo!');
}
$todo = $aTodo['item'];

$todo_groups = $oFreedcamp->getTodoGroupsByProjectId($project_id);

 if($todo_groups['http_code'] != '200') {
     die('Error finding todo groups');
 }

$todos_groups_array = array();
    if (get_class($todo_groups['item']) == 'SimpleXMLElement') {
    $todos_groups_array[] = $todo_groups['item'];
} else {
    $todos_groups_array = $todo_groups['item'];
}



if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'];
    $todo_groupid = $_POST['todo_groupid'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];

    $res = $oFreedcamp->editTodo($todo_id,$description,$todo_groupid,$priority,$due_date);

    if($res['http_code'] == '200') {
        header('Location: todo_view.php?todo_id='.$todo_id.'&project_id='.$project_id);
    } else {
        die($res['item']);
    }
}

?>


<html>
    <head>
        <title>Freedcamp PHP API example</title>
    </head>
    <body>

        <h2>Edit todo</h2>

        <form action="" method="post">
            <table>
                <tr>
                    <td>Descritption</td>
                    <td><input type="text" name="description" value="<?php echo $todo->description;?>" />
                </tr>
                <tr>
                    <td>Todo group</td>
                    <td>
                        <select name="todo_groupid">
                            <?php foreach($todos_groups_array as $todo_group_object):?>
                                <option value="<?php echo $todo_group_object->todos_groupid;?>"
                                                         <?php 
                                                            if((int) $todo->todo_groupid == (int)$todo_group_object->todos_groupid) echo 'selected="selected"';?>
                                 >
                                                                 <?php echo $todo_group_object->todos_group_name;?>
                                 </option>
                            <?php endforeach;?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Priority
                    </td>
                    <td>
                        <select name="priority">
                            <option value="0">Choose</option>
                            <option value="1" <?php if($todo->priority == 1) echo 'selected="selected"';?>>Low</option>
                            <option value="2" <?php if($todo->priority == 2) echo 'selected="selected"';?>>Medium</option>
                            <option value="3" <?php if($todo->priority == 3) echo 'selected="selected"';?>>High</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Due date
                    </td>
                    <td>
                        <input type="text" name="due_date" value="<?php echo $todo->due_date;?>" /> YYYY-MM-DD
                    </td>
                </tr>
            </table>
            <input type="submit" value="Submit"/>
        </form>
        <a href="todos.php?project_id=<?php echo $project_id;?>">Back to todos</a>
    </body>
</html>