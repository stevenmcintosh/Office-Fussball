<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>FusStars</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <link href="<?php echo URL; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo URL; ?>css/application.css" rel="stylesheet">
    <link href="<?php echo URL; ?>img/favicon.png" rel="icon" type="image/png" >
</head>
<body>

<?php /* REMOVE THIS FROM PRODUCTION **/ ?>
<div class="alert alert-info" role="alert">
  <h4 class="alert-heading">Demo Mode</h4>
  <p>This is a demo mode of FusStars and therefore only has limited functionality.</p>
  <p>The demo site is reset once a day.</p>
  <hr>
  <p class="mb-0">User: demouser</p>
  <hr>
  <p class="mb-0">Things to try (via admin area):</p>
  <ul>
      <li>Add new results</li>
      <li>Create new divisions</li>
      <li>Add new teams</li>
      <li>Create new seasons</li>
  </ul>
</div>
<?php /* END OF REMOVE THIS FROM PRODUCTION **/ ?>


<div class="bodyContainer container">
    <div class="loginArea">
        <div class="row">
            <div class="col-md-12 wrapper" style="">
                <form role="form" id="login" method="post" action="<?php echo URL; ?>login">
                    <!--img src="<?php echo URL;?>img/fusStarsHomeLogo.png" alt="The home of FusStars"/-->
                    <div id="loginAreaForm" style="">
                    <p>Login with your windows username</p>
                    <div class="form-group">
                        <label for="ldapUsername">Username</label>
                        <input type="text" class="form-control" id="ldapUsername" name="ldapUsername" placeholder="Enter username" required>
                    </div>
                    <input type="hidden" name="password" value="noPasswordRequired">
                    <button type="submit" class="btn btn-default">Submit</button>
            </div>
                </form>
            </div>
            
            
        </div>
    </div>

</div>

</body>
</html>