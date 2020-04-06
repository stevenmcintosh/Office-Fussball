<?php //print_r($_POST); ?>
<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Confirm Result</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <p>Are you absolutely sure that this was the final score?</p>

        
        <table class="table table-striped table-bordered">
            <tr>
                <td class="div" colspan="2"><?php echo $fixture->division->divisionName . ", Round " .  $fixture->gameweek;?></td>
            </tr>
            <tr>
                <td class="home"><?php echo $fixture->homeTeam->teamName;?></td><td><big><?php echo $fixture->homeScore;?></big></td>
            </tr>
            <tr>
                <td class="away"><?php echo $fixture->awayTeam->teamName;?></td><td><big><?php echo $fixture->awayScore;?></big></td>
            </tr>
        </table>
    </div>
</div>


<form action="<?php echo URL;?>admin/InsertResult" method="post">
<input type="hidden" name="homeScore" value="<?php echo $fixture->homeScore; ?>" />
<input type="hidden" name="awayScore" value="<?php echo $fixture->awayScore; ?>" />
<input type="hidden" name="fixtureId" value="<?php echo $fixture->fixtureId; ?>" />
<input type="submit" name="resultConfirmed" value="Yes. I'm sure" class="btn btn-success" />
<a href="/admin/cancelGame" class="btn btn-link" role="button">Cancel and go back</a>
</form>