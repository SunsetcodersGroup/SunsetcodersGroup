<!DOCTYPE html>
<html>
    <head>
        <title>Sunsecoders Group. Management Tool.</title>
        <meta name="description" content="Sunsetcoders Group. Management Tool. ">
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="/css/style.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="canonical" href="http://www.sunsetcodersgroup.tk/" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-83693944-1', 'auto');
            ga('send', 'pageview');

        </script>

    </head>
    <body>

        <style>
            #header-background
            {
                width: 100%;
                height: 80px;
                clear: both;
            }

            ul
            {
                margin: 0;
                padding: 0;
            }

            li
            {
                display: inline; 
                margin: 15px;
                text-decoration: none;
                color: #60969a;
            }

            li a
            {
                display: inline;
                margin: 15px;
                text-decoration: none;
                color: #60969a;
            }

            .social-media-icon
            {
                vertical-align: middle;
                height: 20px;
                width: 20px;
            }

            .moduleclear
            {
                clear: both;
                height: 220px;
            }

            .left-header-bar, .middle-header-bar
            {
                float: left;
                height: 70px;
                margin: 15px;

            }

            .middle-header-bar
            {
                width: 50%;
                line-height: 70px;
            }

            .left-header-bar
            {
                float: left;
                width: 15%;
            }

            .right-header-bar
            {
                height: 70px;
                float: right;
                width: 20%;
                margin: 15px;
            }
            .top-bar
            {
                height: 5px;
                background-color: #60969a;
                width: 100%;
            }
            .header-image
            {
                height: 70px;
            }
        </style>
        <div id="header-background">
            Sunsetcoders Group
        </div> 


        <?php

        function showMenu() {

            global $dbConnection;

            echo ' <ul>';
            $stmt = $dbConnection->prepare("SELECT menuLabel, menuLink FROM menus WHERE menuLocation='Header' ORDER BY menuOrder ");
            $stmt->execute();

            $stmt->bind_result($menuLabel, $menuLink);

            while ($checkRow = $stmt->fetch()) {

                echo '<li><a href="' . PATH_NAME . '/index.php' . $menuLink . '">' . strtoupper($menuLabel) . '</a></li>';
            }
            echo '</ul>';
        }

        function showFooterMenu() {

            global $dbConnection;

            echo ' <ul>';
            $stmt = $dbConnection->prepare("SELECT menuLabel, menuLink FROM menus WHERE menuLocation='Footer' ORDER BY menuOrder ");
            $stmt->execute();

            $stmt->bind_result($menuLabel, $menuLink);

            while ($checkRow = $stmt->fetch()) {

                echo '<li><a href="' . PATH_NAME . '/index.php' . $menuLink . '">' . strtoupper($menuLabel) . '</a></li>';
            }
            echo '</ul>';
        }
        