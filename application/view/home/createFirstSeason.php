<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>There are no seasons in play!</h1>
        </div>
            <?php if($user->is_admin())
    		{ ?>
                <p><a href="<?php echo URL;?>admin/addSeason">So lets create a PS Fussball season.</a></p>
            <?php } else { ?>
                <p>Please ask an admin to create a new season for you.</p>
            <?php }  ?>
    </div>
</div>