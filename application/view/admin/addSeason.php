<?php //print_r($_POST);  ?>
<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Create 'Season <?php echo $newSeason; ?>'</h1>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <form action="<?php echo URL; ?>admin/addSeasonPreview/" method="post">

            <h3>Single Teams</h3>
            <table class="table">
                <tr>
                    <th>Team Name</th>
                    <th>Player</th>
                    <th>Divison</th>
                </tr>
                <?php foreach ($activeSingleTeams as $team) { ?>
                    <tr>
                        <td><input type="checkbox" name="teams[<?php echo $team->teamId; ?>]" value="<?php echo $team->teamId; ?>"
                                   
                                   <?php
                                   
                                    $LeagueFixtureModel = new LeagueFixtureModel($this->db);
                                    $previousDivision = $LeagueFixtureModel->getDivisionIdByTeamIdAndSeasonId($team->teamId, $previousSeason);
                                    if($previousDivision) { echo "checked"; }
                                   
                                    ?>
                                   
                                   />
                                       
                                       
                                       <?php echo "&nbsp;" . $team->teamName; ?></td>
                        <td><?php echo $team->teamMembers[0]->firstName; ?></td>
                        <td>
                            <select name="teamIdDivisionId[<?php echo $team->teamId; ?>]">
                                <?php
                                foreach ($divisions as $division) {
                                    echo "<option value=\"" . $division->divisionId . "\"";

                                    //$divisionTeamSeasonModel = new DivisionTeamSeasonModel($this->db);
                                    //$previousDivision = $divisionTeamSeasonModel->getDivisionIdByTeamIdAndSeasonId($team->teamId, $previousSeason);
                                    $LeagueFixtureModel = new LeagueFixtureModel($this->db);
                                    $previousDivision = $LeagueFixtureModel->getDivisionIdByTeamIdAndSeasonId($team->teamId, $previousSeason);
                                    
                                    if($previousDivision == 0) {
                                        $divisionModel = new DivisionModel($this->db);
                                        $divObj = $divisionModel->getLowestDivision();
                                        
                                        $previousDivision = $divObj->divisionId;
                                    }
                                    
                                    if ($previousDivision == $division->divisionId) {
                                        echo "selected";
                                    }

                                    echo ">" . $division->divisionName . "</option>";
                                }
                                ?>
                            </select>

                        </td>
                    </tr>
<?php } ?>

            </table>
    </div>

    <div class="col-md-6">
        <h3>Doubles Teams</h3>
        <table class="table">
            <tr>
                <th>Team Name</th>
                <th>Player 1</th>
                <th>Player 2</th>
                <th>Divison</th>
            </tr>
<?php foreach ($activeDoubleTeams as $team) { ?>
                <tr>
                    <td><input type="checkbox" name="teams[]" value="<?php echo $team->teamId; ?>" checked /><?php echo "&nbsp;" . $team->teamName; ?></td>
                    <td><?php echo $team->teamMembers[0]->firstName; ?></td>
                    <td><?php echo $team->teamMembers[1]->firstName; ?></td>
                    <td><?php echo $team->teamMembers[0]->firstName; ?></td>
                </tr>
<?php } ?>
        </table>


    </div>

    <div class="row">
        <div class="col-md-12">
            <input type="submit" name="submit" />
            </form>
        </div>
    </div>


</div>

