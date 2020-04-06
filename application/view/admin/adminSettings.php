<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Admin Settings</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <form method="post" action="<?php echo URL; ?>admin/adminSettings">
            <?php foreach ($allAdminSettings as $areaName => $adminSettings) { 
                
                
                ?>

                <h3><?php echo $areaName; ?></h3>
                <table class="table table-striped table-bordered table-condensed dataTable">
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Active</th>
                            <th>Value</th>


                        </tr>

                        <?php foreach ($adminSettings as $adminSetting) { 
                            
                            $type = $adminSetting->getSettingType();
                            ?>
                            <tr>
                                <td width="30%"><?php echo $adminSetting->name; ?></td>
                                <td width="30%"><?php echo $adminSetting->description; ?></td>
                                
                                    
                                    <?php if (!$adminSetting->locked) { ?>
                                <td width="10%">
                                        <select name="adminSetting[<?php echo $adminSetting->id; ?>][active]" class="form-control">
                                            <?php
                                            foreach (Helper::ActiveArray() as $key => $val) {
                                                echo "<option value=\"" . $key . "\"";
                                                if ($adminSetting->active == $key) {
                                                    echo "selected";
                                                }

                                                echo ">" . $val . "</option>";
                                            }
                                            ?>
                                        </select>
                                </td>
                                <td width="30%">
                                     <?php
                                        if (array_key_exists('array', $type)) {
                                            ?>
                                            <select name="adminSetting[<?php echo $adminSetting->id; ?>][value]" class="form-control">
                                                <?php
                                                foreach ($type['array'] as $key => $val) {
                                                    echo "<option value=\"" . $key . "\"";
                                                    if ($adminSetting->value == $key) {
                                                        echo "selected";
                                                    }

                                                    echo ">" . $val . "</option>";
                                                }
                                                ?>
                                            </select>
                                        <?php } else if (array_key_exists('array-active-only', $type)) { ?>
                                    <input type="hidden" name="adminSetting[<?php echo $adminSetting->id; ?>][array-active-only]" class="form-control col-lg-12" value="<?php echo $adminSetting->value; ?>" />
                                            <?php } else if (array_key_exists('text', $type)) { ?>
                                            <input type="input" name="adminSetting[<?php echo $adminSetting->id; ?>][value]" class="form-control col-lg-12" value="<?php echo $adminSetting->value; ?>" />
                                        <?php } ?>


                                    <?php
                                    } else { ?>
                                <td width="10%">Locked</td>
                                <td width="30%">Locked</td>
                                <?php } ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>   
                    </tbody>
                </table>

            <?php }
            ?>

            <input type="submit" name="submit" value="Save" class="btn btn-default" />
        </form>
    </div>
</div>