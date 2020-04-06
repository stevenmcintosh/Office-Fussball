<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Emails <small>Just throw this into outlook :)</small></h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <h4>All current active league users (<?php echo count($allCurrentActiveLeagueUserEmails);?>)</h4>
            <textarea cols="100" rows="10"><?php foreach($allCurrentActiveLeagueUserEmails as $email) {
            echo $email->email . ";";
        } ?></textarea>
            
    </div>
    
    <div class="col-md-12">
        <h4>All users (<?php echo count($allUserEmails);?>)</h4>
            <textarea cols="100" rows="10"><?php foreach($allUserEmails as $email) {
            echo $email->email . ";";
        } ?></textarea>
            
    </div>
    
    <div class="col-md-12">
        <h4>Committee users (<?php echo count($allCommitteeUserEmails);?>)</h4>
            <textarea cols="100" rows="10"><?php foreach($allCommitteeUserEmails as $email) {
            echo $email->email . ";";
        } ?></textarea>
            
    </div>
    
    <div class="col-md-12">
        <h4>Admin users (<?php echo count($allAdminUserEmails);?>)</h4>
            <textarea cols="100" rows="10"><?php foreach($allAdminUserEmails as $email) {
            echo $email->email . ";";
        } ?></textarea>
            
    </div>
    
    <div class="col-md-12">
        <h4>Waiting to join users (<?php echo count($allUsersWaitingToJoinEmails);?>)</h4>
            <textarea cols="100" rows="10"><?php foreach($allUsersWaitingToJoinEmails as $email) {
            echo $email->email . ";";
        } ?></textarea>
            
    </div>
    
</div>