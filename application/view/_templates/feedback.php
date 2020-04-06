<?php
//print_r($_SESSION);
// get the feedback (they are arrays, to make multiple positive/negative messages possible)
$feedback_positive = Session::get('feedback_positive');
$feedback_negative = Session::get('feedback_negative');

// echo out positive messages
if (isset($feedback_positive) && count($feedback_positive) > 0) {
    
	echo '<div class="row feedbackRow">';
	echo '<div class="col-md-12">';
	echo '<div class="alert alert-success" role="alert">';
	echo '<h4><span class="glyphicon glyphicon-thumbs-up"></span> Success</h4>';
	echo '<ul>';
	foreach ($feedback_positive as $key => $feedback) {
		echo '<li>'.$feedback.'</li>';
        unset($_SESSION['feedback_positive'][$key]);
    }
    echo '</ul>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

// echo out negative messages
if (isset($feedback_negative) && count($feedback_negative) > 0) {
    echo '<div class="row feedbackRow">';
	echo '<div class="col-md-12">';
	echo '<div class="alert alert-danger" role="alert">';
	echo '<h4><span class="glyphicon glyphicon-thumbs-down"></span> Oh dear</h4>';
	echo '<ul>';
	foreach ($feedback_negative as $key => $feedback) {
        echo '<li>'.$feedback.'</li>';
        unset($_SESSION['feedback_negative'][$key]);
    }
    echo '</ul>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}