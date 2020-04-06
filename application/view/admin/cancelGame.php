<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>All Open Fixtures</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <form method="post" action="<?php echo URL; ?>admin/confirmCancel">
            <table class="table table-striped table-bordered table-condensed dataTable">
                <thead>
                    <tr>
                        <th>GW</th>
                        <th>Division</th>
                        <th>Home</th>
                        <th>Away</th>
                        <th>Cancel?</th>
                        <th>Submit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leagueFixtures as $fixture) { ?>

                        <tr>
                            <td><?php echo stripslashes(htmlspecialchars($fixture->gameweek)); ?></td>
                            <td><?php echo stripslashes(htmlspecialchars($fixture->division->divisionName)); ?></td>
                            <td class="home"><?php echo stripslashes(htmlspecialchars($fixture->homeTeam->teamName)); ?></td>
                            <td class="away"><?php echo stripslashes(htmlspecialchars($fixture->awayTeam->teamName)); ?></td>
                            <td>Cancel Game? <input type="radio" name="cancelGame" value="<?php echo $fixture->fixtureId; ?>"/></td>
                            <td><input type="radio" name="fixId" value="<?php echo $fixture->fixtureId; ?>"/>&nbsp;
                                <input type="submit" name="submit" value="Submit" class="btn btn-success"/></td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        </form>
    </div>
</div>