<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">


            <div class="btn-group pull-right header-button">
                <a class="btn btn-danger" href="<?php echo URL; ?>fixtures/" role="button">Season <?php echo $seasonToView; ?></a>
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="<?php echo URL; ?>fixtures/">View Current Season <?php echo $currentSeason; ?></a></li>
                    <?php foreach ($allPastSeasons as $season) { ?>
                        <li><a href="<?php echo URL; ?>fixtures/past/<?php echo $season->seasonId; ?>">View Past Season <?php echo $season->seasonId; ?></a></li>
                    <?php } ?>

                </ul>
            </div>
            <h1>My Results <small>Season <?php echo $seasonToView; ?></small></h1>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">


        <?php
        $fixtures_awaiting_verification = Array();
        $fixtures_open = Array();
        $fixtures_closed = Array();

        foreach ($leagueFixturesAllTeams as $leagueFixtures) {
            foreach ($leagueFixtures as $fixture) {

                $loggedInUserIsHomeTeam = true;
                $userIsTeamId = $fixture->homeTeam->teamId;
                foreach ($fixture->awayTeam->teamMembers as $teamMember) {
                    if ($teamMember->userId == $user->userId) {
                        $loggedInUserIsHomeTeam = false;
                        $userIsTeamId = $fixture->awayTeam->teamId;
                    }
                }

                if ($fixture->resultStatus == 'awaiting verification') {
                    $fixtures_awaiting_verification[] = $fixture;
                } else if ($fixture->resultStatus == 'open') {
                    $fixtures_open[] = $fixture;
                } else {
                    $fixtures_closed[] = $fixture;
                }
                
                
            }
        }
        //print_r($fixtures_awaiting_verification);
        if (count($fixtures_awaiting_verification) > 0) {
            ?>
            <h4>Awaiting Verification</h4>
            <table class="table table-striped table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>GW</th>
                        <th>Division</th>
                        <th>Home</th>
                        <th>Away</th>
                        <th>Score</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($fixtures_awaiting_verification as $fix_a) {
                    ?>
                                    <tr>
                                <form method="post" action="<?php echo URL; ?>fixtures/confirmResult">
                                    <td><?php echo $fix_a->fixtureId; //stripslashes(htmlspecialchars($fix_a->gameweek));       ?></td>
                                    <td><?php echo stripslashes(htmlspecialchars($fix_a->division->divisionName)); ?></td>
                                    <td class="home"><?php echo stripslashes(htmlspecialchars($fix_a->homeTeam->teamName)); ?></td>
                                    <td class="away"><?php echo stripslashes(htmlspecialchars($fix_a->awayTeam->teamName)); ?></td>

                                    <?php
                                    if ($fix_a->provisional_score_added_by_team_id > 0 && $fix_a->provisional_score_added_by_team_id == $userIsTeamId && $fix_a->provisional_score_verified_by_team_id == 0) {
                                            ?><td><input type="text" size="2" maxlength="2" disabled="true" name="resultHome[<?php echo $fix_a->fixtureId; ?>]" value="<?php echo $fix_a->team_a_provisional_score; ?>"> -
                                                <input type="text" size="2" maxlength="2" disabled="true" name="resultAway[<?php echo $fix_a->fixtureId; ?>]" value="<?php echo $fix_a->team_b_provisional_score; ?>"></td>
                                            <td>Awaiting Verification by <?php echo ($userIsTeamId == $fix_a->homeTeam->teamId) ? $fix_a->awayTeam->teamName : $fix_a->homeTeam->teamName; ?></td>

                                            <?php
                                            //if other team has added scores and you need to verify them
                                        } elseif ($fix_a->provisional_score_added_by_team_id > 0 && $fix_a->provisional_score_added_by_team_id != $userIsTeamId && $fix_a->provisional_score_verified_by_team_id == 0) {
                                            ?> <td><input type="text" size="2" maxlength="2" disabled="true" name="resultHome[<?php echo $fix_a->fixtureId; ?>]" value="<?php echo $fix_a->team_a_provisional_score; ?>"> -
                                                <input type="text" size="2" maxlength="2" disabled="true" name="resultAway[<?php echo $fix_a->fixtureId; ?>]" value="<?php echo $fix_a->team_b_provisional_score; ?>"></td>
                                            <td>
                                                <input type="radio" name="verify" value="Verify"/> Verify Result
                                                <input type="radio" name="verify" value="Undo"/> Undo Result
                                                <input type="hidden" name="resultHome[<?php echo $fix_a->fixtureId; ?>]" value="<?php echo $fix_a->team_a_provisional_score; ?>">
                                                <input type="hidden" name="resultAway[<?php echo $fix_a->fixtureId; ?>]" value="<?php echo $fix_a->team_b_provisional_score; ?>">
                                                <input type="hidden" name="VerifyByTeamId" value="<?php echo $userIsTeamId; ?>"/>
                                                <input type="hidden" name="fixId" value="<?php echo $fix_a->fixtureId; ?>"/>
                                                <input type="submit" name="Submit" value="Submit"/>
                                            </td>
                                            <?php
                                        }

                                        ?>
                                </form>
                                </tr>
                <?php
                } ?>
                </tbody>
            </table> 
            <?php
    }
    
    // // open results
    //print_r($fixtures_open);
        if (count($fixtures_open) > 0) {
            ?>
            <h4>Awaiting Scores</h4>
            <table class="table table-striped table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>GW</th>
                        <th>Division</th>
                        <th>Home</th>
                        <th>Away</th>
                        <th>Score</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($fixtures_open as $fix_o) {
                    ?>
                                    <tr>
                                <form method="post" action="<?php echo URL; ?>fixtures/confirmResult">
                                    <td><?php echo $fix_o->fixtureId; //stripslashes(htmlspecialchars($fix_o->gameweek));       ?></td>
                                    <td><?php echo stripslashes(htmlspecialchars($fix_o->division->divisionName)); ?></td>
                                    <td class="home"><?php echo stripslashes(htmlspecialchars($fix_o->homeTeam->teamName)); ?></td>
                                    <td class="away"><?php echo stripslashes(htmlspecialchars($fix_o->awayTeam->teamName)); ?></td>

                                    <td><input type="text" size="2" maxlength="2" name="resultHome[<?php echo $fix_o->fixtureId; ?>]"> -
                                    <input type="text" size="2" maxlength="2" name="resultAway[<?php echo $fix_o->fixtureId; ?>]">
                                </td>
                                <td>
                                    <input type="hidden" name="InputByTeamId" value="<?php echo $userIsTeamId; ?>"/>
                                    <input type="hidden" name="fixId" value="<?php echo $fix_o->fixtureId; ?>"/>

                                    <input type="submit" name="submit" value="Submit New Result"/></td>

                           
                                </form>
                        </tr>
                <?php
                } ?>
                </tbody>
            </table> 
            <?php
    }
    
    
         if (count($fixtures_closed) > 0) {
            ?>
            <h4>Confirmed Results</h4>
            <table class="table table-striped table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>GW</th>
                        <th>Division</th>
                        <th>Home</th>
                        <th>Away</th>
                        <th>Score</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($fixtures_closed as $fix_c) { ?>
                <tr>
                    <form method="post" action="<?php echo URL; ?>fixtures/confirmResult">
                            <td><?php echo $fix_c->fixtureId; //stripslashes(htmlspecialchars($fix_c->gameweek));       ?></td>
                            <td><?php echo stripslashes(htmlspecialchars($fix_c->division->divisionName)); ?></td>
                            <td class="home"><?php echo stripslashes(htmlspecialchars($fix_c->homeTeam->teamName)); ?></td>
                            <td class="away"><?php echo stripslashes(htmlspecialchars($fix_c->awayTeam->teamName)); ?></td>

                            
                                <td><?php echo $fix_c->homeScore . " - " . $fix_c->awayScore; ?></td>
                                <td>Added by <?php
                    if ($fix_c->provisional_score_added_by_team_id > 0 && $fix_c->provisional_score_verified_by_team_id > 0) {
                        echo $fix_c->provisional_score_added_by_team->teamName . ", verified by " . $fix_c->provisional_score_verified_by_team->teamName;
                    } else {
                        echo $fix_c->result_verfied_by_user->firstName . " " . $fix_c->result_verfied_by_user->lastName;
                        echo ($fix_c->result_verfied_by_user->admin == 'y') ? ' (admin)' : '';
                    }
                                ?></td>
                    </tr>
                </form>
                    
                    
                <?php
                } ?>
                </tbody>
            </table> 
            <?php
    }   
    
?>

    </div>
</div>
