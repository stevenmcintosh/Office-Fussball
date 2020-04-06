<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Active Teams</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h4>Singles</h4>
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>#1</th>
                <th>Team Name</th>
                <th>Player</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $counter = 0;
            foreach($singles as $single) {
                $counter++;
                echo "<tr>";
                echo "<td>".$counter."</td>";
                echo "<td>".stripslashes(htmlspecialchars($single->teamName))."</td>";
                echo "<td>".stripslashes(htmlspecialchars($single->teamMembers[0]->firstName))."</td>";
                echo "</tr>";
            }?>
            </tbody>
        </table>

    </div>

    <div class="col-md-6">
        <h4>Doubles</h4>
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Team Name</th>
                <th>Player 1</th>
                <th>Player 2</th>
            </tr>
            </thead>
            <tbody>
            <?php

            foreach($doubles as $double) {
                echo "<tr>";
                echo "<td>".stripslashes(htmlspecialchars($double->teamName))."</td>";

                foreach($double->teamMembers as $member) {
                    echo "<td>" . stripslashes(htmlspecialchars($member->firstName)) . "</td>";
                }
                echo "</tr>";
            }?>
            </tbody>
        </table>

    </div>
</div>