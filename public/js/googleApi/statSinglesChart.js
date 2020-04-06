$(document).ready(function() {

  var date = new Date();
  var timestamp = date.getTime();
  getStats(timestamp);


  function getStats(timestamp) {

      var timestamp = timestamp;

	  $.ajax({
			url:url + "/stats/loadStatsForUser/"+userId+"/"+timestamp,
			dataType: 'json',
			cache: 'fasle',
            beforeSend: function(){
                $("#homeMatches").html('<img src=' + url + 'public/img/trophy.png">');
            },
			success:function(result)
			{
                matchesPlayed = result['matchesPlayed'];
				drawHomeMatches();
				drawAwayMatches();
				drawAllMatches();
			},
			error : function() {
				alert('Error, stats are not retreivable.'); 
			}
		});
  }
});

    function drawHomeMatches() {

    // Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Home Matches');
    data.addColumn('number', 'Stats');

     data.addRows([['Won',matchesPlayed.totalHomeLeagueMatchesWon],
     ['Lost',(matchesPlayed.totalHomeLeagueMatchesPlayed-matchesPlayed.totalHomeLeagueMatchesWon)]
    ]);

    // Set chart options
    var options = {'legend':'top',
                   'width':'100%',
                   'height':300,
                   colors:['blue', '#888'],
                   backgroundColor: 'transparent'
                   };

    // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart(document.getElementById('homeMatches'));
      chart.draw(data, options);
    }

    function drawAwayMatches() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Away Matches');
        data.addColumn('number', 'Stats');

        data.addRows([['Won',matchesPlayed.totalAwayLeagueMatchesWon],
            ['Lost',(matchesPlayed.totalAwayLeagueMatchesPlayed-matchesPlayed.totalAwayLeagueMatchesWon)]
        ]);

        // Set chart options
        var options = {'legend':'top',
            'width':'100%',
            'height':300,
            colors:['red', '#888'],
            backgroundColor: 'transparent'
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('awayMatches'));
        chart.draw(data, options);
    }

    function drawAllMatches() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'All Matches');
        data.addColumn('number', 'Stats');

        data.addRows([['Won',(matchesPlayed.totalHomeLeagueMatchesWon+matchesPlayed.totalAwayLeagueMatchesWon)],
            ['Lost',(matchesPlayed.totalHomeLeagueMatchesPlayed-matchesPlayed.totalHomeLeagueMatchesWon)
            +(matchesPlayed.totalAwayLeagueMatchesPlayed-matchesPlayed.totalAwayLeagueMatchesWon)]
        ]);

        // Set chart options
        var options = {'legend':'top',
            'width':'100%',
            'height':300,
            colors:['green', '#888'],
            backgroundColor: 'transparent'
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('allMatches'));
        chart.draw(data, options);
    }

// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawHomeMatches);
google.setOnLoadCallback(drawAwayMatches);
google.setOnLoadCallback(drawAllMatches);

  

