<?php
foreach ($leagueTables as $divisionId => $divisionData) {

    $divisionModel = new DivisionModel($this->db);
    $divObj = $divisionModel->load($divisionId);

    echo "<h4>" . $divObj->divisionName . "</h4>";
    ?>
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th colspan="12">Total</th>
                <th colspan="7">Home</th>
                <th colspan="7">Away</th>
                
            </tr>
            <tr>
                <th data-toggle="tooltip" data-placement="top" title="Position" data-container="th">P</th>
                <th>Team</th>
                <th data-toggle="tooltip" data-placement="top" title="Total Matches Played" data-container="th">Pl</th>
                <th data-toggle="tooltip" data-placement="top" title="Total Matches Won" data-container="th">W</th>
                <th data-toggle="tooltip" data-placement="top" title="Total Matches Lost" data-container="th">L</th>
                <th data-toggle="tooltip" data-placement="top" title="Total Goals Scored" data-container="th">F</th>
                <th data-toggle="tooltip" data-placement="top" title="Total Goals Against" data-container="th">A</th>
                <th data-toggle="tooltip" data-placement="top" title="Total Goal Difference" data-container="th">GD</th>
                <th data-toggle="tooltip" data-placement="top" title="Total Win Pts" data-container="th">WP</th>
                <th data-toggle="tooltip" data-placement="top" title="Total Bonus Granny Pts" data-container="th">GP</th>
                <th data-toggle="tooltip" data-placement="top" title="Total Bonus Lose Pts" data-container="th">LP</th>
                <th data-toggle="tooltip" data-placement="top" title="Total Overall Pts" data-container="th" class="rightBorder">Total Pts</th>
                
                <th data-toggle="tooltip" data-placement="top" title="Matches Played" data-container="th">Pl</th>
                <th data-toggle="tooltip" data-placement="top" title="Matches Won" data-container="th">W</th>
                <th data-toggle="tooltip" data-placement="top" title="Matches Lost" data-container="th">L</th>
                <th data-toggle="tooltip" data-placement="top" title="Goals Scored" data-container="th">F</th>
                <th data-toggle="tooltip" data-placement="top" title="Goals Against" data-container="th">A</th>
                <th data-toggle="tooltip" data-placement="top" title="Goal Difference" data-container="th">GD</th>
                <th data-toggle="tooltip" data-placement="top" title="Total Pts" data-container="th" class="rightBorder">Pts</th>

                <th data-toggle="tooltip" data-placement="top" title="Away Matches Played" data-container="th">Pl</th>
                <th data-toggle="tooltip" data-placement="top" title="Away Matches Won" data-container="th">W</th>
                <th data-toggle="tooltip" data-placement="top" title="Away Matches Lost" data-container="th">L</th>
                <th data-toggle="tooltip" data-placement="top" title="Away Goals Scored" data-container="th">F</th>
                <th data-toggle="tooltip" data-placement="top" title="Away Goals Against" data-container="th">A</th>
                <th data-toggle="tooltip" data-placement="top" title="Away Goal Difference" data-container="th">GD</th>
                <th data-toggle="tooltip" data-placement="top" title="Away Total Pts" data-container="th">Pts</th>

                

            </tr>
        </thead>
        <tbody>
            <?php
            //print_r($leagueTables);
            $counter = 0;
            $numTeamsInLeague = count($divisionData);
            foreach ($divisionData as $key => $tableRow) {
                $promotedSymbol = '';
                $relegatedSymbol = '';
                $teamModel = new TeamModel($this->db);
                $divisionData[$key]->team = $teamModel->load($tableRow->teamId);


                //print_r($tableRow);
                $counter++;
                $rowClass = "";
                foreach ($tableRow->team->teamMembers as $key => $teamUser) {
                    if ($teamUser->userId == $user->userId) {
                        $rowClass = 'userRowX';
                    }
                }
                
                
                if (NUM_TEAMS_PROMOTED_ACTIVE && $counter <= NUM_TEAMS_PROMOTED && $highestDivisionId != $divObj->divisionId) {
                    $rowClass .= " team_promoted";
                    $promotedSymbol = "";
                }
                if (NUM_TEAMS_RELEGATED_ACTIVE && ($numTeamsInLeague - $counter) < NUM_TEAMS_RELEGATED && $lowestDivisionId !== $divObj->divisionId) {
                    $rowClass .= " team_relegated";
                    $promotedSymbol = "";
                }
                if($highestDivisionId == $divObj->divisionId && $counter == 1) {
                    $rowClass .= " team_champion_position";
                }
                ?>
                <tr class="<?php echo $rowClass; ?>">
                    <td><?php echo $counter . "." . $promotedSymbol . $relegatedSymbol; ?></td>
                    <td><?php echo $tableRow->teamName; ?></td>

                    <td><?php echo $tableRow->P; ?></td>
                    <td><?php echo $tableRow->W; ?></td>
                    <td><?php echo $tableRow->L; ?></td>
                    <td><?php echo $tableRow->totalFor; ?></td>
                    <td><?php echo $tableRow->totalAgainst; ?></td>
                    <td><?php echo $tableRow->totalGoalDiff; ?></td>
                    <td><?php echo $tableRow->WinPts; ?></td>
                    <td><?php echo $tableRow->GranPts; ?></td>
                    <td><?php echo $tableRow->LosePts; ?></td>
                    <td class="rightBorder"><strong><?php echo $tableRow->totalPoints; ?></strong></td>
                    
                    
                    <td class="home"><?php echo $tableRow->homePlayed; ?></td>
                    <td class="home"><?php echo $tableRow->wonHome; ?></td>
                    <td class="home"><?php echo $tableRow->lostHome; ?></td>
                    <td class="home"><?php echo $tableRow->homeFor; ?></td>
                    <td class="home"><?php echo $tableRow->homeAgainst; ?></td>
                    <td class="home"><?php echo $tableRow->homeGoalDiff; ?></td>
               
                    <td class="home rightBorder"><strong><?php echo $tableRow->totalHomePoints; ?></strong></td>

                    <td class="away"><?php echo $tableRow->awayPlayed; ?></td>
                    <td class="away"><?php echo $tableRow->wonAway; ?></td>
                    <td class="away"><?php echo $tableRow->lostAway; ?></td>
                    <td class="away"><?php echo $tableRow->awayFor; ?></td>
                    <td class="away"><?php echo $tableRow->awayAgainst; ?></td>
                    <td class="away"><?php echo $tableRow->awayGoalDiff; ?></td>
                
                    <td class="away"><strong><?php echo $tableRow->totalAwayPoints; ?></strong></td>



                    
                </tr>
    <?php } ?>
        </tbody>
    </table>
    <?php
} 