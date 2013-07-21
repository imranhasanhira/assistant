<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title><?php echo $pageTitle; ?></title>

        <link rel="stylesheet" href="<?php echo base_url(); ?>css/reset.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/960.css" />
        <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet" media="screen"></link>
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css" />

    </head>
    <body>


        <div id="header-row" class="navbar">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand" href="<?php echo site_url(); ?>">Personal Assistance</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="active"><?php echo anchor('financials', 'Financial'); ?></li>
                            <li><?php echo anchor('tasks', 'Task'); ?></li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Help <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><?php echo anchor('home/about', 'About'); ?></li>
                                    <li><?php echo anchor('home/contact', 'Contact'); ?></li>
                                </ul>
                            </li>
                        </ul>
                        <?php
                        if (!$isLoggedIn) {
                            echo form_open('home', 'class="navbar-form pull-right"');
                            ?>
                            <input class="span2" type="text" name="username-or-email" placeholder="Username or Email" />
                            <input class="span2" type="password" name="password" placeholder="Password" />
                            <button type="submit" class="btn">Sign in</button>
                            <input type="hidden" name="mode" value="login"/>
                            </form>
                            <?php
                        } else {
                            ?>
                            <form method="post" action="<?php echo site_url('home'); ?>" class="navbar-form pull-right">
                                <button type="submit" class="btn">Sign out</button>
                                <input type="hidden" name="mode" value="logout"/>
                            </form>
                            <?php
                        }
                        ?>

                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>


        <div id="body-row" class="container">
            <?php
            if ($sidebarMenus != NULL) {
                ?>
                <div id="sidebar" class="span3">
                    <ul>
                        <?php
                        foreach ($sidebarMenus as $menuName => $menuUrl) {
                            ?>
                            <li><a href="<?php echo $menuUrl ?>"> <?php $menuName ?> </a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <?php
            }

            if ($sidebarMenus != NULL) {
                ?>
                <div id="content" class="span9"> 
                    <?php
                } else {
                    ?>
                    <div id="content" class="span12"> 
                        <?php
                    }
                    ?>

                    <div class="row">
                        <?php
                        if ($messages != NULL) {
                            foreach ($messages as $type => $message) {
                                ?>
                                <div class="alert alert-<?php echo $type; ?>">
                                    <button type="button" class="close" data-dismiss="<?php echo $type; ?>">&times;</button>
                                    <strong><?php echo ucfirst($type); ?>!</strong> <?php echo $message; ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>


