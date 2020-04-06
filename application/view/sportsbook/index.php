<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Sportsbook</h1>
        </div>
    </div>
</div>


<div class="row">
	<div class="col-md-6">

        <h4>Current odds on outright winner</h4>
<?php if(empty($sportsbookModel->errors)) { ?>
        <table class="table table-striped table-bordered table-condensed sportsbookTable">
            <thead>
            <tr>
                <th>Team</th>
                <th colspan="<?php echo $sportsbookMaxCells-1;?>">Odds</th>
            </tr>
            </thead>
            <?php foreach($sportsbookData as $key => $val) {
                echo "<tr>";
                $class = '';
                for($i=0;$i<$sportsbookMaxCells;$i++) {
                    if($i > 1) {
                        if(isset($sportsbookData[$key][$i]) && (isset($sportsbookData[$key][$i-1])) && ($sportsbookData[$key][$i] > $sportsbookData[$key][$i-1])) {
                            $class = "up";
                        } else if (isset($sportsbookData[$key][$i]) && (isset($sportsbookData[$key][$i-1])) && ($sportsbookData[$key][$i] < $sportsbookData[$key][$i-1])) {
                            $class = "down";
                        }
                    }
                    echo "<td class=\"".$class."\">";
                    echo (isset($sportsbookData[$key][$i])) ? $sportsbookData[$key][$i] : '';
                    echo "</td>";
                }
                echo "</tr>";
            }
            ?>
        </table>
<?php } ?>
    </div>
</div>