<?php //print_r($user); ?>

<div class="row topRow">
    <div class="col-md-12">
        <h1>Create User</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <form action="<?php echo URL;?>admin/saveUser" method="post">
            <table class="table">
                <tbody>
                <tr>
                    <th>Username (Active Directory)</th>
                    <td>
                        <input type="text" value="<?php echo stripslashes(htmlspecialchars($user->ldapUsername)); ?>" placeholder="ldapUsername" id="ldapUsername" name="ldapUsername" class="form-control"/>
                    </td>
                </tr>
                <tr>    
                    <th>Email</th>
                    <td>
                        <input type="text" value="<?php echo stripslashes(htmlspecialchars($user->email)); ?>" placeholder="email" id="email" name="email" class="form-control"/>
                    </td>
                </tr>
                <tr>    
                    <th>First Name</th>
                    <td>
                        <input type="text" value="<?php echo stripslashes(htmlspecialchars($user->firstName)); ?>" placeholder="firstName" id="userNfirstNameame" name="firstName" class="form-control"/>
                    </td>
                </tr>
                <tr>    
                    <th>Last Name</th>
                    <td>
                        <input type="text" value="<?php echo stripslashes(htmlspecialchars($user->lastName)); ?>" placeholder="lastName" id="lastName" name="lastName" class="form-control"/>
                    </td>
                </tr>
                <tr>    
                    <th>Nickname</th>
                    <td>
                        <input type="text" value="<?php echo stripslashes(htmlspecialchars($user->nickname)); ?>" placeholder="nickname" id="nickname" name="nickname" class="form-control"/>
                    </td>
                </tr>
                <tr>    
                    <th>Is Admin?</th>
                    <td>
                    <input type="radio" id="yes" name="isAdmin" value="yes" <?php if($user->admin === 'y') { echo "checked=\"checked\""; } ?>">
                    <label for="yes">Yes</label><br>
                    <input type="radio" id="no" name="isAdmin" value="no" <?php if($user->admin === 'n') { echo "checked=\"checked\""; } ?>">
                    <label for="no">No</label><br>
                    </td>
                </tr>
                </tbody>
            </table>
            <input type="hidden" name="userId" value="<?php echo $user->userId;?>" />
            <input type="submit" name="submit_update_user" value="Save" class="btn btn-success">
        </form>
    </div>
</div>