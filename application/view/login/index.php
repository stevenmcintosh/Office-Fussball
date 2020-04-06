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

<div class="bodyContainer container">
    <div class="loginArea">
        <div class="row">
            <div class="col-md-12 wrapper">
                <form role="form" id="login" method="post" action="<?php echo URL; ?>login">
                    <div id="loginAreaForm" style="">
                    <p>Login with your windows username</p>
                    <div class="form-group">
                        <label for="ldapUsername">Username</label>
                        <input type="text" class="form-control" id="ldapUsername" name="ldapUsername" placeholder="Enter ldap username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>


</body>
</html>