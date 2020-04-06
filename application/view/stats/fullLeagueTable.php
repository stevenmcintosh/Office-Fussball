<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">


            <div class="btn-group pull-right header-button">
                <a class="btn btn-danger" href="<?php echo URL; ?>fullLeagueTable/" role="button">Season <?php echo $seasonToView; ?></a>
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="<?php echo URL; ?>stats/fullLeagueTable/<?php echo $currentSeason; ?>">View Current Season <?php echo $currentSeason; ?></a></li>
                    <?php foreach ($allPastSeasons as $season) { ?>
                        <li><a href="<?php echo URL; ?>stats/fullLeagueTable/<?php echo $season->seasonId; ?>">View Past Season <?php echo $season->seasonId; ?></a></li>
                    <?php } ?>

                </ul>
            </div>
            <h1>League Tables <small>Season <?php echo $seasonToView; ?></small></h1>
        </div>
    </div>
</div>

<div class="row topRow">
    <div class="col-md-12">
            <?php include APP . 'view/_templates/fullLeagueTable.php'; ?>
    </div>
</div>
