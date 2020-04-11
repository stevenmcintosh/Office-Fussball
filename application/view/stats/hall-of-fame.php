<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Hall of Fame</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h4>Champions</h4>

        <?php

        foreach ($league as $divName => $season) {
            ?>

            <table class="table table-striped table-bordered table-condensed">
                <thead>
                    <tr>
                        <th colspan="3"><?php echo $divName; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Season</th>
                        <th>Champion</th>
                        <th>Runner Up</th>
                    </tr>
                    <?php
                    foreach ($season as $key => $position) {
                        echo "<tr>";
                        echo "<td>Season " . $key . "</td>";
                        echo "<td>" . $position['winner'] . "</td>";
                        echo "<td>" . $position['runnerup'] . "</td>";
                        echo "<tr>";
                    }
                    ?>
                </tbody>
            </table>
            <?php }  ?>
        </div>

        <div class="col-md-6">
            <h4>Player Stats</h4>
            <table class="table table-striped table-bordered table-condensed">
                <tr>
                    <th>Event</th>
                    <th>All Time</th>
                    <th>Single Season</th>
                </tr>
                <?php /*<tr>
                <td>Most wins</td>
                <td>x</td>
                <td>x</td>
                </tr>
                <tr>
                <td>Highest win avg</td>
                <td>x</td>
                <td>x</td>
                </tr>*/ ?>
                <tr>
                    <td>Most goals scored</td>
                    <td><?php echo $allTimeTopScorer['0']->teamName ? $allTimeTopScorer['0']->teamName . " (" . $allTimeTopScorer['0']->totalGoals . ")" : $allTimeTopScorer; ?></td>
                    <td><?php echo $allTimeTopScorerOfSingleSeason['0']->teamName ? $allTimeTopScorerOfSingleSeason['0']->teamName . " (" . $allTimeTopScorerOfSingleSeason['0']->totalGoals . ") (season " . $allTimeTopScorerOfSingleSeason['0']->seasonName . ")" : $allTimeTopScorerOfSingleSeason; ?></td>
                </tr>
                <tr>
                    <td>Highest goals scored avg</td>
                    <td><?php echo $allTimeTopAvgScorer['0']->teamName ? $allTimeTopAvgScorer['0']->teamName . " (" . $allTimeTopAvgScorer['0']->avgGoals . ")" : $allTimeTopAvgScorer; ?></td>
                    <td><?php echo $allTimeTopAvgScorerOfSingleSeason['0']->teamName . " (" . $allTimeTopAvgScorerOfSingleSeason['0']->avgGoals . ") (season " . $allTimeTopAvgScorerOfSingleSeason['0']->seasonName . ")"; ?></td>
                </tr>
                <tr>
                    <td>Best goal difference</td>
                    <td><?php echo $allTimeBestGoalDifference['0']->teamName . " (+" . $allTimeBestGoalDifference['0']->goal_diff . ")"; ?></td>
                    <td><?php echo $allTimeBestGoalDifferenceSingleSeason['0']->teamName . " (+" . $allTimeBestGoalDifferenceSingleSeason['0']->goal_diff . ") (season " . $allTimeBestGoalDifferenceSingleSeason['0']->seasonName . ")"; ?></td>
            </tr>
        </table>

        </div>
</div>