<div class="row topRow">
    <div class="col-md-6">
        <div class="page-header">
            <h1>End of season <?php echo $previousSeason; ?><small> New Season coming soon</small></h1>
        </div>
        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th colspan="3"><?php echo "Season Stats"; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Division</td>
                    <td>Champion</td>
                    <td>Runner Up</td>
                </tr>
                <?php
                //print_r($league);
                foreach ($league as $divName => $positions) {
                    echo "<tr>";
                    echo "<td>" . $divName . "</td>";
                    echo "<td>" . $positions[1] . "</td>";
                    echo "<td>" . $positions[2] . "</td>";
                    echo "<tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="col-md-6">
        <div class="page-header">
            <h1>Final League Tables<small> <?php echo "Season " . $previousSeason; ?></small></h1>
        </div>
        <?php //print_r($leagueTables);  ?>

        <?php include APP . 'view/_templates/leagueTable.php'; ?>
    </div>

</div>