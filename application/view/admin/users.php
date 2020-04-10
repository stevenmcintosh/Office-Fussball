<?php //print_r($allUsers); ?>
<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Users</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h4>Users</h4>
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>#1</th>
                <th>Users</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Nickname</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $counter = 0;
            foreach($allUsers as $user) {
                $counter++;
                echo "<tr>";
                echo "<td>".$counter."</td>";
                echo "<td>".$user->ldapUsername."</td>";
                echo "<td>".$user->email."</td>";
                echo "<td>".$user->firstName."</td>";
                echo "<td>".$user->lastName."</td>";
                echo "<td>".$user->nickname."</td>";
                echo "<td><a href=\"/admin/editUser/".stripslashes(htmlspecialchars($user->userId))."\">Edit</a></td>";
                echo "</tr>";
            }?>
            </tbody>
        </table>

    </div>
</div>