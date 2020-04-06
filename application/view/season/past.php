<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">


                <div class="btn-group pull-right header-button">
                    <a class="btn btn-danger" href="<?php echo URL;?>season/" role="button">Season <?php echo $seasonToView;?></a>
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?php echo URL;?>season/">View Current Season <?php echo $currentSeason;?></a></li>
                        <?php foreach($allPastSeasons as $season) { ?>
                            <li><a href="<?php echo URL;?>season/past/<?php echo $season->seasonId;?>">View Past Season <?php echo $season->seasonId;?></a></li>
                        <?php } ?>

                    </ul>
                </div>
            <h1>Season <?php echo $seasonToView; ?></h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php if($seasonChampion) { ?>
            <div class="seasonChampion">
                <img src="<?php echo URL;?>img/trophy.png" class="pull-left">
                <p><?php echo $seasonChampion; ?></p>
            </div>
        <?php } ?>
        <table class="table table-striped table-bordered table-condensed fixtureTable">
            <thead>
            <tr>
                <th colspan="4">Season Results</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($leagueFixtures as $fixture) {
                $rowClass = "";
                foreach($fixture->homeTeam->teamMembers as $key => $teamUser) {
                    if($teamUser->userId == $user->userId) { $rowClass = 'userRow'; }
                }
                foreach($fixture->awayTeam->teamMembers as $key => $teamUser) {
                    if($teamUser->userId == $user->userId) { $rowClass = 'userRow'; }
                }
                echo "<tr class=\"".$rowClass."\">";
                echo "<td class=\"gw\">R".stripslashes(htmlspecialchars($fixture->gameweek))."</td>";
                echo "<td class=\"home\">".stripslashes(htmlspecialchars($fixture->homeTeam->teamName))."</td>";
                echo "<td class=\"score\">".stripslashes(htmlspecialchars($fixture->homeScore))." v " . stripslashes(htmlspecialchars($fixture->awayScore))."</td>";
                echo "<td class=\"away\">".stripslashes(htmlspecialchars($fixture->awayTeam->teamName))."</td>";
                echo "</tr>";
            }?>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h4>League Table</h4>
        <?php include APP . 'view/_templates/leagueTable.php'; ?>

    </div>
</div>