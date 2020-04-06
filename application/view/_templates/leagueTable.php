<?php
foreach ($leagueTables as $divisionId => $divisionData) {
    
    $divisionModel = new DivisionModel($this->db);
    $divObj = $divisionModel->load($divisionId);
    
    echo "<h4>" . $divObj->divisionName . "</h4>";
    ?>
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th data-toggle="tooltip" data-placement="top" title="Position" data-container="th">P</th>
                <th>Team</th>
                <th data-toggle="tooltip" data-placement="top" title="Matches Played" data-container="th">Pl</th>
                <th data-toggle="tooltip" data-placement="top" title="Matches Won" data-container="th">W</th>
                <th data-toggle="tooltip" data-placement="top" title="Matches Lost" data-container="th">L</th>
                <th data-toggle="tooltip" data-placement="top" title="Goals Scored" data-container="th">F</th>
                <th data-toggle="tooltip" data-placement="top" title="Goals Against" data-container="th">A</th>
                <th data-toggle="tooltip" data-placement="top" title="Goal Difference" data-container="th">GD</th>
                <th data-toggle="tooltip" data-placement="top" title="Win Pts" data-container="th">Win Pts</th>
                <th data-toggle="tooltip" data-placement="top" title="Bonus Granny Pts" data-container="th">Gran Pts</th>
                <th data-toggle="tooltip" data-placement="top" title="Bonus Lose Pts" data-container="th">Lose Pts</th>
                <th data-toggle="tooltip" data-placement="top" title="Total Pts" data-container="th">Pts</th>
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
                
                
                
                if(NUM_TEAMS_PROMOTED_ACTIVE && $counter <= NUM_TEAMS_PROMOTED && $highestDivisionId != $divObj->divisionId) {
                    $rowClass .= " team_promoted";
                    $promotedSymbol = "";
                    
                }
                if(NUM_TEAMS_RELEGATED_ACTIVE && ($numTeamsInLeague - $counter) < NUM_TEAMS_RELEGATED && $lowestDivisionId !== $divObj->divisionId) {
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
                    <td><strong><?php echo $tableRow->totalPoints; ?></strong></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php
} 