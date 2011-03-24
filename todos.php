<?php

require_once './libraries/Freedcamp.php';

$oFreedcamp = new Freedcamp();


$project_id = $_GET['project_id'];

$aTodos = $oFreedcamp->getTodosByProjectId($project_id);

//var_dump($aTodos);die;

if($aTodos['http_code'] != 200) {
    die('Error finding todos!');
} else {
    $todos_array = array();
    if (get_class($aTodos['item']) == 'SimpleXMLElement') {
        $todos_array[] = $aTodos['item'];
    } else {
        $todos_array = $aTodos['item'];
    }
}
?>

<html>
    <head>
        <title>Freedcamp PHP API example</title>
    </head>
    <body>
        <h2>List of todos</h2>
        <a href="todo_add.php?project_id=<?php echo $project_id;?>">Add new</a>
        <table>
            <tr>
                <th>Todos description</th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach($todos_array as $todo_object):?>
                <tr>
                    <td>
                        <?php echo $todo_object->description?>
                    </td>
                    <td>
                        <a href="todo_view.php?todo_id=<?php echo (int) $todo_object->todoid;?>&project_id=<?php echo $project_id;?>">View</a>
                        <a href="todo_edit.php?todo_id=<?php echo (int) $todo_object->todoid;?>&project_id=<?php echo $project_id;?>">Edit</a>
                        <a href="todo_delete.php?todo_id=<?php echo (int) $todo_object->todoid;?>&project_id=<?php echo $project_id;?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach;?>

        </table>
        <a href="index.php">Back to projects</a>
    </body>
</html>