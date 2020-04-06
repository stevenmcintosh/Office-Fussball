<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">


            <div class="btn-group pull-right header-button">
                <a class="btn btn-danger" href="<?php echo URL;?>stats/" role="button">Season <?php echo $seasonToView;?></a>
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="<?php echo URL;?>stats/">View Current Season <?php echo $currentSeason;?></a></li>
                    <?php foreach($allPastSeasons as $season) { ?>
                        <li><a href="<?php echo URL;?>stats/past/<?php echo $season->seasonId;?>">View Past Season <?php echo $season->seasonId;?></a></li>
                    <?php } ?>

                </ul>
            </div>
            <h1>Season <?php echo $seasonToView; ?> <small>Statistics so far</small></h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h4>General Stats</h4>
        <table class="table table-striped table-bordered table-condensed">
            <tr>
                <th>Total Matches / Unplayed </th>
                <td><?php echo $totalFixtures . " / ". $totalOpenFixtures; ?></td>
            </tr>

            <tr>
                <th>Biggest Season Win</th>
                <td>
                <?php
                foreach($biggestWins as $wins) {
                    echo $wins->homeTeam->teamName." " . $wins->homeScore . " - " . $wins->awayScore . " " . $wins->awayTeam->teamName . "<br />";
                }?>
                </td>
            </tr>

        </table>




    </div>
    <div class="row">
        <div class="col-md-6">
            <h4>Top 5 Goal Scorers by Avg</h4>
            <table class="table table-striped table-bordered table-condensed">
                <tr>
                    <th>Team</th>
                    <td>Average goals scored</td>
                    <td>Games</td>
                    <td>Goals</td>
                </tr>
                <?php
                foreach($topScorers as $teams) {
                    echo "<tr>";
                    echo "<td>".$teams->teamName."</td>";
                    echo "<td>".$teams->avgGoals."</td>";
                    echo "<td>".$teams->totalGames."</td>";
                    echo "<td>".$teams->totalGoals."</td>";
                    echo "</tr>";
                }?>
            </table>
        </div>
    </div>
</div>

