<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">


            <div class="btn-group pull-right header-button">
                <a class="btn btn-danger" href="<?php echo URL; ?>stats/" role="button">Season <?php echo $seasonToView; ?></a>
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="<?php echo URL; ?>stats/">View Current Season <?php echo $currentSeason; ?></a></li>
                    <?php foreach ($allPastSeasons as $season) { ?>
                        <li><a href="<?php echo URL; ?>stats/past/<?php echo $season->seasonId; ?>">View Past Season <?php echo $season->seasonId; ?></a></li>
                    <?php } ?>

                </ul>
            </div>
            <h1>Season <?php echo $seasonToView; ?> <small>Statistics</small></h1>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-condensed">
            <tr>
                <th>Stat</th><?php 
                //print_r($allDivisions);
                foreach($allDivisions as $division) {
                    echo "<th>".$division['divisionName']."</th>";
                }?>
            </tr>
            <tr>
                <td>Total Games</td><?php 
               
                foreach($totalFixtures as $division) {
                    echo "<td>".$division."</td>";
                }?>
            </tr>
           <tr>
                <td>Played Games</td><?php 
               
                foreach($totalClosedFixtures as $division) {
                    echo "<td>".$division."</td>";
                }?>
            </tr>
            <tr>
                <td>Remaining Games</td><?php 
               
                foreach($totalOpenFixtures as $division) {
                    echo "<td>".$division."</td>";
                }?>
            </tr>
            <tr>
                <td>Percentage blue wins</td><?php 
                foreach($percentageHomeWins as $division) {
                    echo "<td>".round($division[0]->percent,2)."%</td>";
                }?>
            </tr>
            <tr>
                <td>Avg goals per game</td><?php 
                foreach($avgGoalsPerGame as $division) {
                    echo "<td>".round($division[0]->avgTotalGoals,2)."</td>";
                }?>
            </tr>
            <tr>
                <td>Total grannies</td><?php 
                foreach($totalGrannies as $division) {
                    echo "<td>".$division[0]->totalGrannies."</td>";
                }?>
            </tr>
            <tr>
                <td>Total 10-9's</td><?php 
                foreach($totalCloseGames as $division) {
                    echo "<td>".$division[0]->totalCloseGames."</td>";
                }?>
            </tr>
            
            <tr>
                <td>Biggest Wins</td>
                <?php 
                foreach($biggestWins as $division) {
                    echo "<td>";
                    
                    foreach($division as $fixture) {
                       // echo $fixture->homeScore;
                        echo $fixture->homeTeam->teamName . " " . $fixture->homeScore . " - " . $fixture->awayScore . " " . $fixture->awayTeam->teamName . "<br />";
                    }
                     echo "</td>";   
                }?>
            </tr>
        </table>
    </div>
</div>    
    
    <div class="row">
        <div class="col-md-12">
            <h4>Top 5 Goal Scorers by Avg</h4>
            <table class="table table-striped table-bordered table-condensed">
                <tr>
                    <th>Pos</th>
                    <th>Team</th>
                    <th>Average goals scored</th>
                    <th>Games</th>
                    <th>Goals</th>
                </tr>
                <?php
                $counter = 0;
                foreach ($topScorers as $teams) {
                    $counter++;
                    echo "<tr>";
                    echo "<td>" . $counter . ".</td>";
                    echo "<td>" . $teams->teamName . "</td>";
                    echo "<td>" . $teams->avgGoals . "</td>";
                    echo "<td>" . $teams->totalGames . "</td>";
                    echo "<td>" . $teams->totalGoals . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
            
            <h4>Top 5 Goal Conceeders by Avg</h4>
            <table class="table table-striped table-bordered table-condensed">
                <tr>
                    <th>Pos</th>
                    <th>Team</th>
                    <th>Average goals conceeded</th>
                    <th>Games</th>
                    <th>Goals</th>
                </tr>
                <?php
                $counter = 0;
                foreach ($topConceeders as $teams) {
                    $counter++;
                    echo "<tr>";
                    echo "<td>" . $counter . ".</td>";
                    echo "<td>" . $teams->teamName . "</td>";
                    echo "<td>" . $teams->avgGoals . "</td>";
                    echo "<td>" . $teams->totalGames . "</td>";
                    echo "<td>" . $teams->totalGoals . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>

