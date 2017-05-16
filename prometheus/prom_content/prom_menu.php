<?php

function menu() {
    ?>
    <script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="//code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">


    <style>
        ul#sortable {
            list-style-type: none;
            margin: 0;
            padding: 0;

        }

        li a
        {
            text-decoration: none;
        }

        .darkBorder
        {
            background-color: #fff;
            border: 1px solid #f5f5f5;
            
        }
    </style>

    <?php
    global $dbConnection;
    $menuLocation = filter_input(INPUT_GET, "menuLocation");

    if (empty($menuLocation)) {
        $menuLocation = "Header";
    }
    echo '<table cellpadding=10 cellspacing=5 border=1 bgcolor=eeeeee width=1024>';
    echo '<tr><td class="header1" colspan=3>Menu Editor</td></tr>';
    echo '<tr><td colspan=2></td><td><a href="?id=Settings&&moduleID=menuLocation">Current Menus</a></td></tr>';
    echo '<tr><td colspan=3 bgcolor=white class="darkBorder">Edit Menu below, or <a href="?id=Settings&&moduleID=AddMenu">create a new menu</a></td></tr>';
    echo '<tr><td colspan=3></td></tr>';
    echo '<tr><td class="darkBorder" colspan=3>' . $menuLocation . '</td></tr>';
    echo '<tr><td colspan=3>';

    echo '<ul id="sortable">';

    $stmt = $dbConnection->prepare("SELECT menuLabel FROM menus WHERE menuLocation='$menuLocation' ORDER BY menuOrder");
    $stmt->execute();

    $stmt->bind_result($menuLabel);

    while ($checkRow = $stmt->fetch()) {

        echo '<li id="item-' . $menuLabel . '">' . $menuLabel . '</li>';
    }
    ?>
    </ul>
   
    </td></tr>
    <tr><td colspan=3></td></tr>
    </table>
    
     Query string: <span>Update </span>
     
    <script>
        $(document).ready(function () {
            $('ul').sortable({
                axis: 'y',
                stop: function (event, ui) {
                    var data = $(this).sortable('serialize');
                    $('span').text(data);
                }
            });
        });
    </script>
    
    <?php
    
}

function addMenu() {

    global $dbConnection;

    echo '<form method="POST" action="?id=Settings&&moduleID=UploadMenu">';

    echo '<table cellpadding=10 cellspacing=5 border=1 bgcolor=f1f1f1 width=1024>';
    echo '<tr><td class="header1">Menu Editor</td><td></td><td></td></tr>';
    echo '<tr><td>New Menu Name</td><td>Available Pages</td><td>Current Menus</td></tr>';
    echo '<tr><td><input type="text" name="menuName" placeholder="enter menu name"></td><td></td><td>';

    echo '<table>';

    $stmt = $dbConnection->prepare("SELECT DISTINCT menuLocation FROM menus ORDER BY menuLocation");
    $stmt->execute();

    $stmt->bind_result($menuLocation);

    while ($checkRow = $stmt->fetch()) {

        echo '<tr><td>' . $menuLocation . '</td></tr>';
    }
    echo '</table>';

    echo '</td></tr>';
    echo '<tr><td colspan=3><input type="Submit" name="Submit" value="Create"></td></tr>';
    echo '</table>';
    echo '</form>';
}

function editMenu() {

    global $dbConnection;
    $menuLocation = filter_input(INPUT_GET, "menuID");

    echo '<table cellpadding=10 cellspacing=5 border=0 width=50%>';
    echo '<tr><td><h2>Menu Panel</h2></td></tr>';
    echo '<tr><td><a href="?id=Settings&&moduleID=menuLocation">Edit Menu</a></td><td><a href="?id=Settings&&moduleID=menuLocation">Menu Locations</a></td><td></td></tr>';
    echo '<tr><td>';

    echo '<table>';

    $stmt = $dbConnection->prepare("SELECT menuLabel FROM menus WHERE menuLocation='$menuLocation' ORDER BY menuOrder");
    $stmt->execute();

    $stmt->bind_result($menuLabel);

    while ($checkRow = $stmt->fetch()) {

        echo '<tr><td>' . $menuLabel . '</td></tr>';
    }
    echo '</table>';

    echo '</td></tr>';
    echo '</table>';
}

function menuLocation() {

    global $dbConnection;

    echo '<table cellpadding=10 cellspacing=5 border=0 width=50%>';
    echo '<tr><td class="header1">Menu Panel</td><td></td><td></td></tr>';
    echo '<tr><td><a href="?id=Settings&&moduleID=menuLocation">Edit Menu</a></td><td><a href="?id=Settings&&moduleID=menuLocation">Menu Locations</a></td><td></td></tr>';

    echo '<tr><td></td></tr>';
    echo '<tr class="headerMenu"><td colspan=2>Menu Locations</td></tr>';
    echo '<tr><td>';

    echo '<table>';

    $stmt = $dbConnection->prepare("SELECT DISTINCT menuLocation FROM menus ORDER BY menuLocation DESC");
    $stmt->execute();

    $stmt->bind_result($menuLocation);

    while ($checkRow = $stmt->fetch()) {

        echo '<tr><td><a href="?id=Settings&&moduleID=EditMenu&&menuID=' . $menuLocation . '">' . $menuLocation . '</a></td></tr>';
    }
    echo '</table>';

    echo '</td></tr>';
    echo '</table>';
}
