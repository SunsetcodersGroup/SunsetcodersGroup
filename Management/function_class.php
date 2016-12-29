<?php

$dbConnection = databaseConnection();
$setPostID = filter_input(INPUT_POST, 'id');
$setGetID = filter_input(INPUT_GET, 'id');

function checklogin() {
    return true;
}

function have_process() {

    $projectID = filter_input(INPUT_GET, 'ProjectID');
    $projectCategory = filter_input(INPUT_GET, 'processCategory');

    echo '<div id="management-background">';
    echo ' <a href="/Management">Home</a> -> <a href="index.php?id=Project&&projectID=' . $projectID . '">Project</a> -> ' . $projectCategory . ' <br>';
    echo '<div class="project-process">Current Processes</div>';
    echo '<div class="project-menu">User</div>';
    echo '<div class="project-menu">Logged</div>';
    echo '<div class="project-menu">Category</div>';
    echo '<div class="project-menu">Process Description</div>';
    echo '<div class="spacer"></div>';

    global $dbConnection;

    $stmt = $dbConnection->prepare("SELECT processID, projectID, userFullName, processCategory, processdescription, processlogged FROM users INNER JOIN projects_process ON users.userID=projects_process.userID WHERE projectID=? && processCategory=? ");
    $stmt->bind_param('is', $projectID, $projectCategory);
    $stmt->execute();

    $stmt->bind_result($processID, $projectID, $userFullName, $processCategory, $processdescription, $processlogged);

    while ($checkRow = $stmt->fetch()) {

        echo '<div class="full-row">';
        echo '<div class="project-blankspace"><a href="index.php?id=Project&&projectID=' . $processID . '">&nbsp;</a></div>'
        . '<div class="project-information">' . $userFullName . '</div>'
        . '<div class="project-information">' . $processlogged . '</div>'
        . '<div class="project-information">' . $processCategory . '</div>'
        . '<div class="project-description">' . $processdescription . '</div>';
        echo '</div>';
    }
    echo '<div class="full-row"></div>';
    echo '<div class="project-header"><a class="project-weblink" href="index.php?id=Project&&projectID=' . $projectID . '">+ Add Step Process</a></div>';
    echo '</div>';
}

function have_management() {

    global $dbConnection;

    if (!checklogin()) {
        
    } else {

        echo '<div id="management-background">';
        
        echo '<div class="project-header">Current Projects</div>';
        echo '<div class="project-menu">Project WireFrame</div>';
        echo '<div class="project-menu">Project Database</div>';
        echo '<div class="project-menu">Project Graphics</div>';
        echo '<div class="project-menu">Project Development</div>';
        echo '<div class="project-menu">Project Closed</div>';

        $stmt = $dbConnection->prepare("SELECT projectID, projectName, projectWebsite, projectWireframe, projectDatabase, projectGraphics, projectDevelopment, projectCompletion FROM projects WHERE projectCompletion!='Completed' ORDER BY projectID");
        $stmt->execute();

        $stmt->bind_result($projectID, $projectName, $projectWebsite, $projectWireframe, $projectDatabase, $projectGraphics, $projectDevelopment, $projectCompletion);

        while ($checkRow = $stmt->fetch()) {

            echo '<div class="project-subheader"><a href="index.php?id=Project&&projectID=' . $projectID . '">' . $projectName . '<br><a class="project-weblink" target="_blank" href="' . $projectWebsite . '">' . $projectWebsite . '</a></div>' . '<div class="project-information">' . $projectWireframe . '</div>' . '<div class="project-information">' . $projectDatabase . '</div>' . '<div class="project-information">' . $projectGraphics . '</div>' . '<div class="project-information">' . $projectDevelopment . '</div>' . '<div class="project-information">' . $projectCompletion . '</a></div>';
        }

        echo '<div class="spacer"></div>';
        echo '<div class="project-header">Completed Projects</div>';
        echo '<div class="project-menu">Project WireFrame</div>';
        echo '<div class="project-menu">Project Database</div>';
        echo '<div class="project-menu">Project Graphics</div>';
        echo '<div class="project-menu">Project Development</div>';
        echo '<div class="project-menu">Project Closed</div>';

        $stmt = $dbConnection->prepare("SELECT projectID, projectName, projectWebsite, projectWireframe, projectDatabase, projectGraphics, projectDevelopment, projectCompletion FROM projects WHERE projectCompletion='Completed' ORDER BY projectID");
        $stmt->execute();

        $stmt->bind_result($projectID, $projectName, $projectWebsite, $projectWireframe, $projectDatabase, $projectGraphics, $projectDevelopment, $projectCompletion);

        while ($checkRow = $stmt->fetch()) {

            echo '<div class="project-subheader">' . $projectName . '<br><a class="project-weblink" target="_blank" href="' . $projectWebsite . '">' . $projectWebsite . '</a></div>' . '<div class="project-information">' . $projectWireframe . '</div>' . '<div class="project-information">' . $projectDatabase . '</div>' . '<div class="project-information">' . $projectGraphics . '</div>' . '<div class="project-information">' . $projectDevelopment . '</div>' . '<div class="project-information">' . $projectCompletion . '</div>';
        }
        echo '</div>';
    }
}

function have_project() {

    global $dbConnection;

    $optionArray = array(
        'assigned',
        'in-progess',
        'waiting on client',
        'Completed'
    );

    $projectID = isset($_GET['projectID']) ? $_GET['projectID'] : NULL;
    echo '<div>';

    if ($stmt = $dbConnection->prepare("SELECT projectID, projectName, projectWebsite, projectLogo, projectWireframe, projectDatabase, projectGraphics, projectDevelopment, projectCompletion FROM projects WHERE projectID=? ")) {
        $stmt->bind_param('i', $projectID);
        $stmt->execute();

        $stmt->bind_result($projectID, $projectName, $projectWebsite, $projectLogo, $projectWireframe, $projectDatabase, $projectGraphics, $projectDevelopment, $projectCompletion);
        $stmt->fetch();

        echo '<table border=1 cellpadding=5 cellspacing=5 width=100%>';
        echo '<tr><td colspan=3>Client Profile<hr></td></tr>';
        echo '<tr><td></td><td><img src="Images/' . $projectLogo . '"></td><td><h1>' . $projectName . '</h1></td></tr>';
        echo '<tr><td rowspan=9 width=20%></td><td>ProjectID: ' . $projectID . '</td><td>' . $projectWebsite . '</td></tr>';
        echo '<tr><td>&nbsp;</td></tr>';
        echo '<tr><td>Project Steps:</td></tr>';
        echo '<tr><td>&nbsp;</td></tr>';
        echo '<tr><td><a class="wireframe-links" href="index.php?id=StepProcess&&ProjectID=' . $projectID . '&&processCategory=Wireframe">Wireframe</a></td><td>';

        echo '<select name=wireframe>';
        echo '<option>' . $projectWireframe . '</option>';

        echo '<option>Assigned</option>';
        echo '<option>In-Progess</option>';
        echo '<option>Waiting on client</option>';
        echo '<option>Completed</option>';

        echo '</select>';

        echo '</td></tr>';
        echo '<tr><td><a class="wireframe-links" href="index.php?id=StepProcess&&ProjectID=' . $projectID . '&&processCategory=Database">Database</td><td>';

        echo '<select name=database>';
        echo '<option>' . $projectDatabase . '</option>';

        echo '<option>Assigned</option>';
        echo '<option>In-Progess</option>';
        echo '<option>Waiting on client</option>';
        echo '<option>Completed</option>';

        echo '</select>';

        echo '</td></tr>';
        echo '<tr><td><a class="wireframe-links" href="index.php?id=StepProcess&&ProjectID=' . $projectID . '&&processCategory=Graphix">Graphix</td><td>';

        echo '<select name="Graphics">';
        echo '<option>' . $projectGraphics . '</option>';

        echo '<option>Assigned</option>';
        echo '<option>In-Progess</option>';
        echo '<option>Waiting on client</option>';
        echo '<option>Completed</option>';

        echo '</select>';
        echo '</td></tr>';

        echo '<tr><td><a class="wireframe-links" href="index.php?id=StepProcess&&ProjectID=' . $projectID . '&&processCategory=Development">Development</td><td>';

        echo '<select name="Development">';
        echo '<option>' . $projectDevelopment . '</option>';

        echo '<option>Assigned</option>';
        echo '<option>In-Progess</option>';
        echo '<option>Waiting on client</option>';
        echo '<option>Completed</option>';

        echo '</select>';
        echo '</td></tr>';

        echo '<tr><td><a class="wireframe-links" href="index.php?id=StepProcess&&ProjectID=' . $projectID . '&&processCategory=Completed">Completed</td><td>';

        echo '<select name="Completed">';
        echo '<option>' . $projectCompletion . '</option>';

        echo '<option>Assigned</option>';
        echo '<option>In-Progess</option>';
        echo '<option>Waiting on client</option>';
        echo '<option>Completed</option>';

        echo '</select>';
        echo '</td></tr>';
        echo '</table>';
    }

    echo '</div>';
}
