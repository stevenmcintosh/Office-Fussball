<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>'Season <?php echo $newSeason; ?>' Preview</h1>
        </div>

    </div>
</div>

<div class="row">
    <form action="<?php echo URL; ?>admin/addSeasonPreview/" method="post">

        <?php
        foreach ($setUpDivisions as $div => $teamObj) {
            ?>
            <div class="col-md-12">
                <table class="table table-striped table-bordered table-condensed">
                    <tr>
                        <th>#</th>
                        <th>
                            <?php
                            $divisionObj = $divisionModel->load($div);
                            echo $divisionObj->divisionName;
                            ?>
                        </th>
                        <th>Division Change?</th>
                    </tr>

                    <?php
                    $counter = 0;
                    foreach ($teamObj as $teamId => $teamObj) {
                        $counter++;
                        ?>
                        <tr>
                            <td width="5%"><?php echo $counter . "."; ?></td>
                            <td width="20%"><?php
                                echo $teamObj->teamName . "<br>";
                                ?>
                            </td>
                            <td>
                                <select name="teamIdDivisionId[<?php echo $teamObj->teamId; ?>]">
                                    <?php
                                    foreach ($allDivisions as $division) {

                                        echo "<option value=\"" . $division->divisionId . "\"";
                                        if ($divisionObj->divisionId == $division->divisionId) {
                                            echo "selected";
                                        }

                                        echo ">" . $division->divisionName . "</option>";
                                    }
                                    echo "</select>";
                                    ?>

                                    <input type="hidden" name="teams[<?php echo $teamObj->teamId; ?>]" value="<?php echo $teamObj->teamId; ?>">

                                    </td>

                                    </tr>
                                    <?php
                                }
                                ?>
                                </td>
                                </tr>
                                </table>
                                </div>
                            <?php }
                            ?>
                            <input type="submit" value="Update Divisions" />
                            </form>
                            </div>


                            <hr />
                            OLD
                            <hr />


                            <div class="row">
                                <div class="col-md-12">
                                    <h3>Fixtures</h3>

                                    <table class="table table-striped table-bordered table-condensed fixtureTable">
                                        <thead>
                                            <tr>
                                                <th class="center">Round</th>
                                                <th class="center">Division</th>
                                                <th>Home</th>
                                                <th class="center">V</th>
                                                <th>Away</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($tmpFixtures as $key => $value) {

                                                // echo "**" . $value['homeTeamId'];
                                                $rowClass = "";
                                                //foreach ($tmpFixture->homeTeam->teamMembers as $key => $teamUser) {
                                                if ($value['homeTeamId'] == $user->userId) {
                                                    $rowClass = 'userRow';
                                                }
                                                //}
                                                //foreach ($tmpFixture->awayTeam->teamMembers as $key => $teamUser) {
                                                if ($value['awayTeamId'] == $user->userId) {
                                                    $rowClass = 'userRow';
                                                }
                                                //}
                                                echo "<tr class=\"" . $rowClass . "\">";
                                                echo "<td class=\"gw\">" . stripslashes(htmlspecialchars($value['round'])) . "</td>";
                                                echo "<td class=\"division\">" . stripslashes(htmlspecialchars($value['divisionName'])) . "</td>";

                                                echo "<td class=\"home\">" . stripslashes(htmlspecialchars($value['homeTeamName'])) . "</td>";
                                                echo "<td class=\"score\">&nbsp;V&nbsp;</td>";
                                                echo "<td class=\"away\">" . stripslashes(htmlspecialchars($value['awayTeamName'])) . "</td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <form action="<?php echo URL; ?>admin/createSeason/" method="post">
                                        <p>Press F5 to reshuffle the fixtures. When your happy, press the Create below.</p>
                                        <input type="submit" name="confirm_new_season" value="Create Season?" />
                                        <p><a href="<?php echo URL; ?>admin/addSeason">Cancel this, lets go back.</a></p>
                                    </form>
                                </div>


                            </div>