<?php

require_once './libraries/Freedcamp.php';

$oFreedcamp = new Freedcamp();

$aProjects = $oFreedcamp->getProjects();

if($aProjects['http_code'] != 200) {
    die('Error finding projects!');
} else {
    $projects_array = array();
    if (get_class($aProjects['item']) == 'SimpleXMLElement') {
        $projects_array[] = $aProjects['item'];
    } else {
        $projects_array = $aProjects['item'];
    }
}
?>

<html>
    <head>
        <title>Freedcamp PHP API example</title>
    </head>
    <body>
        <h2>Freedcamp projects that you have access</h2>
        <table>
            <tr>
                <th>Project name</th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach($projects_array as $project_object):?>
                <tr>
                    <td>
                        <?php echo $project_object->name?>
                    </td>
                    <td>
                        <a href="todos.php?project_id=<?php echo (int) $project_object->projectid;?>">View todos</a>
                    </td>
                </tr>
            <?php endforeach;?>

        </table>

    </body>
</html>