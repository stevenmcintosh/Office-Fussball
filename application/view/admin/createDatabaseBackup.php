<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Create Database Backup</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <form method="post" action="<?php echo URL; ?>admin/createDatabaseBackup">
            <form>
                <div class="form-group">
                    <label for="divisionName">Division Name</label>
                    <input type="text" class="form-control" name="divisionName" id="divisionName" value="<?php echo stripslashes(htmlspecialchars($division->divisionName)); ?>">
                    <label for="divisionShortName">Division Short Name</label>
                    <input type="text" class="form-control" name="divisionShortName" id="divisionShortName" value="<?php echo stripslashes(htmlspecialchars($division->divisionShortName)); ?>">
                    <label for="divisionOrder">Division Order</label>
                    <input type="text" maxlength="3" class="form-control" name="divisionOrder" id="divisionOrder" value="<?php echo stripslashes(htmlspecialchars($division->divisionOrder)); ?>">
                </div>
                <button type="submit" class="btn btn-default">Save</button>
            </form>


           
        </form>
    </div>
</div>