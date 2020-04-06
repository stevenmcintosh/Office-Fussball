<?php //print_r($_POST);   ?>
<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Remove Season</h1>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <form action="<?php echo URL; ?>admin/removeSeasonConfirm/" method="post">


            <table class="table">
                <tr>
                    <th>Season Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($allSeasons as $season) { ?>
                    <tr>
                        <td><?php echo $season->seasonName; ?></td>
                        <td><?php echo $season->status->statusName; ?></td>
                        <td><input type="radio" name="seasonId" value="<?php echo $season->seasonId; ?>" />
                        </td>
                    </tr>
                <?php } ?>
                    

            </table>
<input type="submit" name="removeSeason" value="Remove" class="btn btn-danger" />
        </form>
    </div>

    <div class="col-md-6">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Danger!</h3>
            </div>
            <div class="panel-body">
                Removing a season can not be undone. Please only remove a season if you are absolutely sure you wish to do so.
            </div>
        </div>


    </div>



</div>

