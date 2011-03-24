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
?>

<html>
    <head>
        <title>Freedcamp PHP API example</title>
    </head>
    <body>

        <h2>Todo View</h2>
        <table>
            <tr>
                <td><b>Description:</b></td>
                <td><?php echo $todo->description;?></td>
            </tr>
            <tr>
                <td><b>Priority:</b></td>
                <td><?php 
                    $priority  = (int) $todo->priority;
                    switch($priority) {
                        case '1': echo 'Low';
                            break;
                        case '2': echo 'Medium';
                            break;
                        case '3': echo 'High';
                            break;
                        default: echo 'No priority';
                            break;
                    }

                ?></td>
            </tr>
            <tr>
                <td><b>Assigned by:</b></td>
                <td><?php echo $todo->assigned_by_fullname;?></td>
            </tr>
            <tr>
                <td><b>Assigned to:</b></td>
                <td><?php echo $todo->assigned_to_fullname;?></td>
            </tr>
            <tr>
                <td><b>Due date:</b></td>
                <td><?php echo $todo->due_date;?></td>
            </tr>

        </table>


        <a href="todos.php?project_id=<?php echo $project_id;?>">Back to todos</a>
    </body>
</html>