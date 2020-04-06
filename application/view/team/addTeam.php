<?php //print_r($teamModel); ?>

<div class="row topRow">
    <div class="col-md-12">
        <h1>Create Singles or Doubles Team</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <form action="<?php echo URL;?>team/saveTeam/" method="post">
            <table class="table">
                <tbody>
                <tr>
                    <th>Team Name</th>
                    <td>
                        <input type="text" value="<?php echo (isset($teamModel->teamName)) ? $teamModel->teamName : ''; ?>" placeholder="teamName" id="teamName" name="teamName" class="form-control"/>
                    </td>
                </tr>
                <tr>
                    <th>Player 1 (Single Team)</th>
                    <td>
                        <select name="teamMember[]">
                            <option value="0">Please Select</option>
                            <?php
                            foreach($allUsers as $user) {
                                echo '<option value="'.$user->userId.'"';
                                if(isset($teamModel->teamMembers[0]) && $teamModel->teamMembers[0]->userId == $user->userId) {
                                    echo " selected='selected'";
                                }

                                echo '>'.$user->firstName.'</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Player 2 (if doubles)</th>
                    <td>
                        <select name="teamMember[]">
                            <option value="0">Please Select</option>
                            <?php

                            foreach($allUsers as $user) {
                                echo '<option value="'.$user->userId.'"';
                                if(isset($teamModel->teamMembers[1]) && $teamModel->teamMembers[1]->userId == $user->userId) {
                                    echo " selected='selected'";
                                }

                                echo '>'.$user->firstName.'</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                </tbody>
            </table>
            <input type="submit" name="submit_create_team" value="Save" class="btn btn-success">
        </form>
    </div>
</div>