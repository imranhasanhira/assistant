
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title><?php echo $pageTitle; ?></title>

        <link rel="stylesheet" href="css/reset.css" />
        <link rel="stylesheet" href="css/960.css" />
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen"></link>

    </head>
    <body>
        <div id="header">

            <div class="navbar navbar-fixed-top">
                <div class="navbar-inner">
                    <div class="container">
                        <a class="brand" href="#">Personal Assistance</a>
                        <div class="nav-collapse collapse">
                            <ul class="nav">
                                <li class="active"><a href="#">Financial</a></li>
                                <li><a href="#">Tasks</a></li>

                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Help <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">About</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Contact</a></li>
                                    </ul>
                                </li>
                            </ul>
                            <?php if (!$isLoggedIn) { ?>
                                <form class="navbar-form pull-right">
                                    <input class="span2" type="text" placeholder="Username or Email">
                                        <input class="span2" type="password" placeholder="Password">
                                            <button type="submit" class="btn">Sign in</button>
                                            </form>
                                        <?php } else { ?>
                                            <form class="navbar-form pull-right">
                                                <button type="submit" class="btn">Sign out</button>
                                            </form>
                                        <?php } ?>

                                        </div><!--/.nav-collapse -->
                                        </div>
                                        </div>
                                        </div>
                                        </div>

                                        <div id="container">
                                            <?php if ($sidebarMenus != NULL) { ?>
                                                <div id="sidebar" class="span3">
                                                    <ul>
                                                        <?php foreach ($sidebarMenus as $menuName => $menuUrl) { ?>
                                                            <li><a href="<?php echo $menuUrl ?>"> <?php $menuName ?> </a></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                                <?php
                                            }

                                            if ($sidebarMenus = NULL) {
                                                ?>
                                                <div id="main-content" class="span9"> 
                                                <?php } else { ?>
                                                    <div id="main-content" class="span12"> 
                                                    <?php } ?>


