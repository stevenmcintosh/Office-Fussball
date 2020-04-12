<div class="row topRow">
	<div class="col-md-7">
		<div class="page-header">
		  <h1>League Tables <small><a href="<?php echo URL . "stats/fullLeagueTable/" . $currentSeason;?>">Full table</a></small></h1>
		</div>
        <?php include APP . 'view/_templates/leagueTable.php'; ?>
	</div>
	

    <div class="col-md-5">
        
        <?php if(HOME_PAGE_RECENT_RESULTS_ACTIVE) { ?>
        <div class="page-header">
            <h1>Latest Results</h1>
        </div>
        <h4>Newset Results on Top</h4>
        <table class="table table-striped table-bordered table-condensed">
            <thead>
            <tr>
                <th>Div</th>
                <th>Home</th>
                <th>Score</th>
                <th>Away</th>
            </tr>
            </thead>
            <tbody>
            <?php
            //print_r($recentResults);
            foreach($recentResults as $result) {
                $rowClass = "";
                foreach($result->homeTeam->teamMembers as $key => $teamUser) {
                    if($teamUser->userId == $user->userId) { $rowClass = 'userRow'; }
                }
                foreach($result->awayTeam->teamMembers as $key => $teamUser) {
                    if($teamUser->userId == $user->userId) { $rowClass = 'userRow'; }
                }
                ?>
                <tr class="<?php echo $rowClass;?>">
                    <td class="gw"><?php echo $result->division->divisionShortName; ?></td>
                    <td class="home"><?php echo stripslashes(htmlspecialchars($result->homeTeam->teamName));?></td>
                    <td class="score"><?php echo $result->homeScore . "&nbsp;&nbsp;-&nbsp;&nbsp;" . $result->awayScore;?></td>
                    <td class="away"><?php echo stripslashes(htmlspecialchars($result->awayTeam->teamName));?></td>
                    <!--td class="away"><?php echo Helper::time_passed($result->lastUpdated);?></td-->
                    
                    
                </tr>
            <?php }?>
            </tbody>
        </table>
        <?php } ?>
    </div>
	
</div>