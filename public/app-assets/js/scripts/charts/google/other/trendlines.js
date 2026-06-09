
google.load('visualization', '1.0', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawTrendlines);

// Callback that creates and populates a data table, instantiates the pie chart, passes in the data and draws it.
function drawTrendlines() {

    // Create the data table.
    var data = google.visualization.arrayToDataTable([
        ['Tahun', 'Memenuhi Syarat', 'Tidak Memenuhi Syarat', 'BerKontrak','Belum Berkontrak'],
        [2019, 125, 20, 699,233],
        [2020, 176, 30,700,300],
        [2021, 75, 47,234,455],
        [2022, 149, 60,984,100],
        [2023, 94, 75,300,500]
    ]);


    // Set chart options
    var options_trendlines = {
        height: 400,
        fontSize: 12,
        colors: ['#673AB7', '#E91E63'],
        trendlines: {
            0: {
                labelInLegend: 'Bug line',
                visibleInLegend: true,
            },
            1: {
                labelInLegend: 'Test line',
                visibleInLegend: true,
            }
        },
        chartArea: {
            left: '5%',
            width: '90%',
            height: 350
        },
        vAxis: {
            gridlines:{
                color: '#e9e9e9',
                count: 10
            },
            minValue: 0
        },
        legend: {
            position: 'top',
            alignment: 'center',
            textStyle: {
                fontSize: 12
            }
        }
    };

    // Instantiate and draw our chart, passing in some options.
    var trendlines = new google.visualization.ColumnChart(document.getElementById('trendlines'));
    trendlines.draw(data, options_trendlines);

}


// Resize chart
// ------------------------------

$(function () {

    // Resize chart on menu width change and window resize
    $(window).on('resize', resize);
    $(".menu-toggle").on('click', resize);

    // Resize function
    function resize() {
        drawTrendlines();
    }
});