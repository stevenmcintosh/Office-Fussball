<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Edit a result</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <form method="post" action="<?php echo URL; ?>admin/confirmResult">
            <table class="table table-striped table-bordered table-condensed dataTable">
                <thead>
                    <tr>
                        <th>GW</th>
                        <th>Division</th>
                        <th>Home</th>
                        <th>Away</th>
                        <th>Score</th>
                        <th>Submit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leagueResults as $result) { ?>

                        <tr>
                            <td><?php echo stripslashes(htmlspecialchars($result->gameweek)); ?></td>
                            <td><?php echo stripslashes(htmlspecialchars($result->division->divisionName)); ?></td>
                            <td class="home"><?php echo stripslashes(htmlspecialchars($result->homeTeam->teamName)); ?></td>
                            <td class="away"><?php echo stripslashes(htmlspecialchars($result->awayTeam->teamName)); ?></td>
                            <td><input type="text" size="2" maxlength="2" name="resultHome[<?php echo $result->fixtureId; ?>]" value="<?php echo $result->homeScore; ?>"> -
                                <input type="text" size="2" maxlength="2" name="resultAway[<?php echo $result->fixtureId; ?>]" value="<?php echo $result->awayScore; ?>"></td>
                            <td><input type="radio" name="fixId" value="<?php echo $result->fixtureId; ?>"/><input type="submit" name="submit" value="Submit"/></td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        </form>
    </div>
</div>