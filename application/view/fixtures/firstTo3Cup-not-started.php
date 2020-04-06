<div class="row topRow">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Fixtures <small>First to 3 cup</small></h1>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <p>The cup draw will be played once all fixtures up to and inclusive of gameweek 16 are complete. </p>
        <p>There are <?php echo $remainingGamesUntilCupDraw; ?> games remaining until the draw.</p>
        
        <p>The top 16 will be automatically entered into the draw.</p>    
            
        <?php include APP . 'view/_templates/leagueTable.php'; ?>
        
    </div>

</div>