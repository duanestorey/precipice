import Masonry from 'masonry-layout';   
import Chart from 'chart.js/auto';
import chartTrendline from 'chartjs-plugin-trendline';


require('@fortawesome/fontawesome-free/js/all.js');

function doPrecipiceReady() {   
    var tileArea = document.querySelector( '.tile-area' );
    var msnry = new Masonry( tileArea, {
        itemSelector: '.tile',
        columnWidth: 20
    });  
}

jQuery( document ).ready( function() {
    doPrecipiceReady();
    const ctx = document.getElementsByClassName('chart');
for(var i = 0; i < ctx.length; i++)
{


            new Chart(ctx[i], {
            type: 'bar',
            data: {
                labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
                datasets: [
                    {
                        data: [200, 300, 300, 100, 433, 0, 10, 12, 24, 43, 12, 32, 500],
                        trendlineLinear: {
                            colorMin: "rgba(255,105,180, .8)",
                            lineStyle: "dotted",
                            width: 1
                        },
                        maxBarThickness: 100
                    },
                ]
            },
            options: {
                legend: { display: false },
                title: {
                    display: false,
                    text: 'Predicted world population (millions) in 2050'
                },
                 plugins:{
                    legend: {
                        display: false
                    }
                },
                scales: {
                xAxes: [{
                    maxBarThickness: 100
                }]
             }
            }
        });
}

});