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
            <h1>Fixtures <small>Season <?php echo $seasonToView; ?></small></h1>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-3">


        <table class="table table-striped table-bordered table-condensed fixtureTable dataTable">
            <thead>
                <tr>
                    <th class="center">Div</th>
                    <th>Home</th>
                    <th class="center">Score</th>
                    <th>Away</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($leagueFixtures as $divId) {
                    foreach ($divId as $fixture) {
                        $rowClass = "";
                        foreach ($fixture->homeTeam->teamMembers as $key => $teamUser) {
                            if ($teamUser->userId == $user->userId) {
                                $rowClass = 'userRow';
                            }
                        }
                        foreach ($fixture->awayTeam->teamMembers as $key => $teamUser) {
                            if ($teamUser->userId == $user->userId) {
                                $rowClass = 'userRow';
                            }
                        }
                        echo "<tr class=\"" . $rowClass . "\">";
                        echo "<td class=\"gw\">" . stripslashes(htmlspecialchars($fixture->division->divisionShortName)) . "</td>";
                        echo "<td class=\"home\">" . stripslashes(htmlspecialchars($fixture->homeTeam->teamName)) . "</td>";
                        
                        if($fixture->statusId == '4') {
                            echo "<td class=\"score\">Canc.</td>";    
                        } else {
                            echo "<td class=\"score\">" . stripslashes(htmlspecialchars($fixture->homeScore)) . " v " . stripslashes(htmlspecialchars($fixture->awayScore)) . "</td>";    
                        }
                        
                        
                        echo "<td class=\"away\">" . stripslashes(htmlspecialchars($fixture->awayTeam->teamName)) . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>

    </div>

    <div class="col-md-9">

        <?php
        foreach ($leagueFixtures as $divId => $fixtures) {

            $leagueFixtures = $leagueFixturesModel->loadAllFixturesByCompetitionIdAndSeasonIdAndDivisionId(1, $seasonToView, $divId);
            $teamsInSeason = $leagueFixturesModel->getTeamsParticipatingInSeasonAndDivisionId($seasonToView, $divId);
            $numTeamsInSeason = count($teamsInSeason);


            $divisionModel = new DivisionModel($this->db);
            $divObj = $divisionModel->load($divId);

            echo "<h4>" . $divObj->divisionName . "</h4>";
            ?>
            <table class="table table-striped table-bordered table-condensed fixtureGrid">
                <tr>
                    <td class="fixture_block"></td>

                    <?php
                    //print_r($teamsInSeason);
                    foreach ($teamsInSeason as $team) {
                        $rowClass = "";
                        foreach ($team->teamMembers as $key => $teamUser) {
                            if ($teamUser->userId == $user->userId) {
                                $rowClass = 'userRow';
                            }
                        }

                        echo "<td class=\"away " . $rowClass . "\">" . $team->teamName . "</td>";
                    }
                    echo "</tr>";

                    for ($i = 0; $i < $numTeamsInSeason; $i++) {
                        echo "<tr>";
                        $rowClass = '';
                        foreach ($teamsInSeason[$i]->teamMembers as $key => $teamUser) {
                            if ($teamUser->userId == $user->userId) {
                                $rowClass = 'userRow';
                            }
                        }
                        $tmpRowClass = $rowClass;

                        echo "<td class=\"home " . $rowClass . "\">" . $teamsInSeason[$i]->teamName . "</td>";
                        $jCounter = 0;
                        for ($j = 0; $j <= $numTeamsInSeason - 1; $j++) {
                            $jCounter++;

                            $rowClass = '';
                            foreach ($teamsInSeason[$j]->teamMembers as $key => $teamUser) {
                                if ($teamUser->userId == $user->userId) {
                                    $rowClass = 'userRow';
                                }
                            }


                            if ($rowClass == 'userRow' || $tmpRowClass == 'userRow') {
                                $rowClass = 'userRow';
                            }

                            

                            if ($teamsInSeason[$i]->teamId == $teamsInSeason[$j]->teamId) {
                                echo "<td class=\"fixture_block " . $rowClass . "\"><em>";
                            } else {
                                echo "<td class=\"" . $rowClass . "\"><em>";
                                $fixture = $leagueFixturesModel->getFixtureByHomeTeamIdAndAwayTeamIdAndSeasonIdAndDivisionId($teamsInSeason[$i]->teamId, $teamsInSeason[$j]->teamId, $currentSeason, $divId);
                                if ($fixture[0]->statusId == '1') {
                                    echo "R" . $fixture[0]->gameweek;
                                } elseif($fixture[0]->statusId == '4') {
                                    echo "Canc.";
                                }
                                else {
                                    echo "<span class=\"home\">" . $fixture[0]->homeScore . "</span>" . "-<span class=\"away\">" . $fixture[0]->awayScore . "</span>";
                                }
                            }
                            echo "</em></td>";
                        }


                        echo "</tr>";
                    }
                    ?>
                </tr>
            </table>
<?php } ?>
    </div>

</div>