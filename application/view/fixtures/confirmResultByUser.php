<?php //print_r($_POST);  ?>
<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Confirm Result</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <p>Are you absolutely sure that this was the final score?</p>
        <p>Once you have confirmed the result, your opponent will need to verify it before it appears as an official result.</p>
    </div>
</div>
<div class="row">
    <div class="col-md-4">       
        <table class="table table-striped table-bordered">
            <tr>
                <td class="div" colspan="2"><?php echo $fixture->division->divisionName . ", Round " . $fixture->gameweek; ?></td>
            </tr>
            <tr>
                <td class="home"><?php echo $fixture->homeTeam->teamName; ?></td><td><big><?php echo $fixture->team_a_provisional_score; ?></big></td>
            </tr>
            <tr>
                <td class="away"><?php echo $fixture->awayTeam->teamName; ?></td><td><big><?php echo $fixture->team_b_provisional_score; ?></big></td>
            </tr>
        </table>

        <form action="<?php echo URL; ?>fixtures/addProvisionalResult" method="post">
            <input type="hidden" name="provisionalHomeScore" value="<?php echo $fixture->team_a_provisional_score; ?>" />
            <input type="hidden" name="provisionalAwayScore" value="<?php echo $fixture->team_b_provisional_score; ?>" />
            <input type="hidden" name="InputByTeamId" value="<?php echo $fixture->provisional_score_added_by_team_id; ?>" />
            <input type="hidden" name="fixtureId" value="<?php echo $fixture->fixtureId; ?>" />
            <input type="submit" name="resultConfirmed" value="Yes. I'm sure" />
        </form>

    </div>
</div>


