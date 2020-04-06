<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>All Divisions</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        
            <table class="table table-striped table-bordered table-condensed dataTable">
                <thead>
                    <tr>
                        <th>Division Name</th>
                        <th>Division Short Name</th>
                        <th>Order</th>
                        <th>Removable</th>
                        <th>Action</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allDivisions as $divison) { ?>

                        <tr>
                            <td><?php echo stripslashes(htmlspecialchars($divison->divisionName)); ?></td>
                            <td><?php echo stripslashes(htmlspecialchars($divison->divisionShortName)); ?></td>
                            <td><?php echo stripslashes(htmlspecialchars($divison->divisionOrder)); ?></td>
                            <td><?php echo ($divison->divisionHasBeenUsed) ? 'No' : 'Yes'; ?></td>
                            <td><a href="<?php echo URL . 'admin/editDivision/' . stripslashes(htmlspecialchars($divison->divisionId)); ?>">Edit</a>
                                
                                <?php if(!$divison->divisionHasBeenUsed) { ?>
                                | <a href="<?php echo URL . 'admin/removeDivision/' . stripslashes(htmlspecialchars($divison->divisionId)); ?>">Remove</a>
                                <?php } ?></td>

                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        
    </div>
</div>