<?php print_r($userModel); ?>

<div class="row topRow">
    <div class="col-md-12">
        <h1>Create User</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <form action="<?php echo URL;?>team/saveTeam/" method="post">
            <table class="table">
                <tbody>
                <tr>
                    <th>Username (Active Directory)</th>
                    <td>
                        <input type="text" value="<?php echo (isset($userModel->userName)) ? $userModel->userName : ''; ?>" placeholder="userName" id="userName" name="userName" class="form-control"/>
                    </td>
                </tr>
                
                </tbody>
            </table>
            <input type="submit" name="submit_create_user" value="Save" class="btn btn-success">
        </form>
    </div>
</div>