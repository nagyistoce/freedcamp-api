<?php

require_once './libraries/Freedcamp.php';

$oFreedcamp = new Freedcamp();

$project_id = $_GET['project_id'];
$todo_id = $_GET['todo_id'];

$res = $oFreedcamp->deleteTodo($todo_id);
?>

<html>
    <head>
        <title>Freedcamp PHP API example</title>
    </head>
    <body>

        <?php if($res['http_code'] == 200):?>
        Todo deleted successfully!
        <a href="todos.php?project_id=<?php echo $project_id;?>">Back to todos</a>
        <?php else:?>
        <?php endif;?>
    </body>
</html>