<?php 
UserAuth::handleLogin();
global $user;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title><?php 
    if(SITE_NAME_ACTIVE) { 
    echo SITE_NAME; } ?>
    </title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="version" content="1.2"/>

    <!-- CSS -->
    <link href="<?php echo URL; ?>public/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo URL; ?>public/css/application.css" rel="stylesheet"/>
    <link href="<?php echo URL; ?>public/css/datatables.min.css"rel="stylesheet"/>
    <link href="<?php echo URL; ?>public/img/favicon.png" rel="icon" type="image/png" />
    <?php if(!empty($headerCssFiles)) {
        foreach($headerCssFiles as $cssFile) {
            echo "<link href=\"" . URL . "public/css/" . $cssFile . ".css\" rel=\"stylesheet\"/>";
        }
    }

    if(!empty($headerJsFiles)) {
        foreach($headerJsFiles as $jsFile) {
            echo "<script src=\"" . URL . "public/js/" . $jsFile . ".js\"></script>";
        }
    } ?>

</head>
<body>

<?php
$seasonModel = new SeasonModel($this->db);
$currentSeason = $seasonModel->getCurrentSeason();?>


<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo URL; ?>">FUSSTARS</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <p class="navbar-text navbar-right">Signed in as <a href="<?php echo URL;?>myfussball" class="navbar-link"><?php echo $user->firstName;?></a></p>
    <ul class="nav navbar-nav">
        <li  class="<?php if($this->activeNav == 'home') { echo "active"; }?>"><a href="<?php echo URL; ?>">Home</a></li>
        
       <?php if(MENU_FIXTURES_ACTIVE) { ?>
              <li class="dropdown <?php if($this->activeNav == 'fixtures') { echo "active"; }?>">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Fix/Res <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="<?php echo URL; ?>fixtures">Fixtures</a></li>
                    <li><a href="<?php echo URL; ?>fixtures/myresults">My Results</a></li>
                    <li><a href="<?php echo URL; ?>stats/fullLeagueTable">Full League Table</a></li>
                  </ul>
              </li>
              <?php } ?>
         
              
              <?php if(MENU_STATS_ACTIVE) { ?>
              <li class="<?php if($this->activeNav == 'stats') { echo "active"; }?>"><a href="<?php echo URL; ?>stats">Stats</a></li>
              <?php } ?>

              <?php if(MENU_TEAMS_ACTIVE) { ?>
              <li class="<?php if($this->activeNav == 'team') { echo "active"; }?>"><a href="<?php echo URL; ?>team">Teams</a></li>
              <?php } ?>

              <?php if(MENU_HALL_OF_FAME_ACTIVE) { ?>
                  <li class="<?php if($this->activeNav == 'halloffame') { echo "active"; }?>"><a href="<?php echo URL; ?>stats/hall_of_fame">Hall of Fame</a></li>
              <?php } ?>

              <?php if(MENU_SPORTSBOOK_ACTIVE) { ?>
              <li class="<?php if($this->activeNav == 'sportsbook') { echo "active"; }?>"><a href="<?php echo URL; ?>sportsbook">Sportsbook</a></li>
              <?php } ?>

              <?php if(MENU_RULES_ACTIVE) { ?>
              <li class="<?php if($this->activeNav == 'rules') { echo "active"; }?>"><a href="<?php echo URL; ?>rules">Rules</a></li>
              <?php } ?>
                  
                  <?php if(MENU_HELP_ACTIVE) { ?>
              <li class="<?php if($this->activeNav == 'help') { echo "active"; }?>"><a href="<?php echo URL; ?>help">Help</a></li>
              <?php } ?>
              
                <?php if(MENU_GALLERY_ACTIVE) { ?>
              <li class="<?php if($this->activeNav == 'gallery') { echo "active"; }?>"><a href="<?php echo URL; ?>gallery">Gallery</a></li>
              <?php } ?>
              
              

              <?php if(MENU_ADMIN_ACTIVE) { 
              if($user->is_admin()) { ?>
              <li class="<?php if($this->activeNav == 'admin') { echo "active"; }?>"><a href="<?php echo URL; ?>admin">Admin</a></li>
              <?php } } ?>

              <?php if(MENU_LOGOUT_ACTIVE) { ?>
              <li class="<?php if($this->activeNav == 'logout') { echo "active"; }?>"><a href="<?php echo URL; ?>logout">Logout</a></li>
              <?php } ?>

              
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>



    
    
    <div class="bodyContainer container clearfix">
    