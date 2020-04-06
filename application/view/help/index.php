<?php //print_r($leagueFixtures);   ?>
<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Help</h1>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6">

        
        <h4>Who's Who? <small>List of all active league players</small></h4>
        <table class="table table-striped table-bordered table-condensed dataTable">
            <thead>
                <tr>
                    <th>League Name</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
            <?php
            
            foreach ($allActiveTeams as $user) {
                echo "<tr>";
                echo "<td>" . $user->teamName. "</td>";
                echo "<td>" . $user->teamMembers[0]->firstName. "</td>";
                echo "<td>" . $user->teamMembers[0]->lastName. "</td>";
                echo "<td><a class='email' title='Send email to ".$user->teamName."' href='mailto:".$user->teamMembers[0]->email."'>" . $user->teamMembers[0]->email. "</a></td>";
                echo "</tr>";
            }
            ?>
            
            </tbody>
        </table>
           
    </div>
    <div class="col-md-6">
        <h4>Points Structure</h4>
        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th>Points</th>
                    <th>Name</th>
                    <th>Description</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>4pts</th>
                    <td>Granny</td>
                    <td>Win by 10 goals without conceeding</td>
                </tr>
                <tr>
                    <th>3pts</th>
                    <td>Clear Win</td>
                    <td>Win by 2 goals or more (example 10-7)</td>
                </tr>
                <tr>
                    <th>2pts</th>
                    <td>Close Win</td>
                    <td>Win by one goal (example 10-9)</td>
                </tr>
                <tr>
                    <th>1pt</th>
                    <td>Consolation loss</td>
                    <td>Lose by one goal (example 9-10)</td>
                </tr>

            </tbody>
        </table>

        <hr/>
        
        <h4>Waiting list</h4>
        
        <?php
        if (count($allUsersWaitingToJoin) > 0) {
            echo "<ul>";
            foreach ($allUsersWaitingToJoin as $user) {
                echo "<li>" . $user->firstName . " (" . $user->email . ")</li>";
            }
            echo "</ul>";
        } else {
            echo "No players are waiting to join";
        }
        ?>
<hr />
        <h4>FAQ</h4>
        <p><strong>Q. </strong>How many teams are in each division? <strong>A. </strong>Max 8 teams, Min 4. The bottom division has less as we await new players.</p>
        <p><strong>Q. </strong>How many teams are promoted? <strong>A. </strong>2 teams.</p>
        <p><strong>Q. </strong>How many teams are relegated? <strong>A. </strong>2 teams.</p>
        <p><strong>Q. </strong>How long is a season? <strong>A. </strong>4 or 6 weeks from the day it starts. 6 weeks during holiday season to allow everyone extra time.</p>
        <p><strong>Q. </strong>What if I can't play all my games within 6 weeks? <strong>A. </strong>We'd rathger you jsut played all your games as the teams you dont play are also penalised which isn't fair. Please make every effort to play all your games.</p>
        <p><strong>Q. </strong>How do enter a result? <strong>A. </strong> Go to the <a href="<?php echo URL; ?>/fixtures/myresults">MyResults</a> page. There you can enter the results of your games. You will need to wait for your opponent to verify the result before it's confirmed.</p>


    </div>


</div>
