<?php


/**
 * Description of function_class
 *
 * @author sated
 */
$dbConnection = databaseConnection();
$setPostID = filter_input(INPUT_POST, 'id');
$setGetID = filter_input(INPUT_GET, 'id');

function checklogin() {
	return true;
}

function have_management() {

	global $dbConnection;

	if (!checklogin()) {

	} else {

		echo '<div id="management-background">';
		echo '<h1>Sunsetcoders Project Management</h1>';
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

	$optionArray = array (
		'assigned',
		'in-progess',
		'waiting on client',
		'Completed'
	);

	$projectID = isset ($_GET['projectID']) ? $_GET['projectID'] : NULL;
	echo '<div>';

	if ($stmt = $dbConnection->prepare("SELECT projectID, projectName, projectWebsite, projectLogo, projectWireframe, projectDatabase, projectGraphics, projectDevelopment, projectCompletion FROM projects WHERE projectID=? ")) {
		$stmt->bind_param('i', $projectID);
		$stmt->execute();

		$stmt->bind_result($projectID, $projectName, $projectWebsite, $projectLogo, $projectWireframe, $projectDatabase, $projectGraphics, $projectDevelopment, $projectCompletion);
		$stmt->fetch();

		echo '<table border=1 cellpadding=1 cellspacing=1 width=100%>';
		echo '<tr><td colspan=3>Client Profile<hr></td></tr>';
		echo '<tr><td></td><td><img src="Images/' . $projectLogo . '"></td><td><h1>' . $projectName . '</h1></td></tr>';
		echo '<tr><td rowspan=9 width=20%></td><td>ProjectID: ' . $projectID . '</td><td>' . $projectWebsite . '</td></tr>';
		echo '<tr><td>&nbsp;</td></tr>';
		echo '<tr><td>Project Steps:</td></tr>';
		echo '<tr><td>&nbsp;</td></tr>';
		echo '<tr><td>Wireframe</td><td>';

		echo '<select name=wireframe>';
		echo '<option>' . $projectWireframe . '</option>';

		echo '<option>Assigned</option>';
		echo '<option>In-Progess</option>';
		echo '<option>Waiting on client</option>';
		echo '<option>Completed</option>';

		echo '</select>';

		echo '</td></tr>';
		echo '<tr><td>Database</td><td>';

		echo '<select name=database>';
		echo '<option>' . $projectDatabase . '</option>';

		echo '<option>Assigned</option>';
		echo '<option>In-Progess</option>';
		echo '<option>Waiting on client</option>';
		echo '<option>Completed</option>';

		echo '</select>';

		echo '</td></tr>';
		echo '<tr><td>Graphix</td><td>';

		echo '<select name="Graphics">';
		echo '<option>' . $projectGraphics . '</option>';

		echo '<option>Assigned</option>';
		echo '<option>In-Progess</option>';
		echo '<option>Waiting on client</option>';
		echo '<option>Completed</option>';

		echo '</select>';
		echo '</td></tr>';

		echo '<tr><td>Development</td><td>';

		echo '<select name="Development">';
		echo '<option>' . $projectDevelopment . '</option>';

		echo '<option>Assigned</option>';
		echo '<option>In-Progess</option>';
		echo '<option>Waiting on client</option>';
		echo '<option>Completed</option>';

		echo '</select>';
		echo '</td></tr>';

		echo '<tr><td>Completed</td><td>';

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