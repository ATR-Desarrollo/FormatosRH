

// Dashboard Morris-chart

Morris.Area({
        element: 'morris-area-chart',
        data: [{
            period: '2010',
            Aztecas: 50,
            Jilo: 80,
            Gomez: 20
        }, {
            period: '2011',
            Aztecas: 130,
            Jilo: 100,
            Gomez: 80
        }, {
            period: '2012',
            Aztecas: 80,
            Jilo: 60,
            Gomez: 70
        }, {
            period: '2013',
            Aztecas: 70,
            Jilo: 200,
            Gomez: 140
        }, {
            period: '2014',
            Aztecas: 180,
            Jilo: 150,
            Gomez: 140
        }, {
            period: '2015',
            Aztecas: 105,
            Jilo: 100,
            Gomez: 80
        },
         {
            period: '2016',
            Aztecas: 250,
            Jilo: 150,
            Gomez: 200
        }],
        xkey: 'period',
        ykeys: ['Aztecas', 'Jilo', 'Gomez'],
        labels: ['Aztecas', 'Jilo', 'Gomez'],
        pointSize: 3,
        fillOpacity: 0,
        pointStrokeColors:['#00bfc7', '#fb9678', '#9675ce'],
        behaveLikeLine: true,
        gridLineColor: 'rgba(255, 255, 255, 0.1)',
        lineWidth: 3,
        gridTextColor:'#96a2b4',
        hideHover: 'auto',
        lineColors: ['#00bfc7', '#fb9678', '#9675ce'],
        resize: true
        
    });

Morris.Area({
        element: 'morris-area-chart2',
        data: [{
            period: '2010',
            ArnesA: 0,
            ArnesB: 0,
            
        }, {
            period: '2011',
            ArnesA: 130,
            ArnesB: 100,
            
        }, {
            period: '2012',
            ArnesA: 80,
            ArnesB: 60,
            
        }, {
            period: '2013',
            ArnesA: 70,
            ArnesB: 200,
            
        }, {
            period: '2014',
            ArnesA: 180,
            ArnesB: 150,
            
        }, {
            period: '2015',
            ArnesA: 105,
            ArnesB: 90,
            
        },
         {
            period: '2016',
            ArnesA: 250,
            ArnesB: 150,
           
        }],
        xkey: 'period',
        ykeys: ['ArnesA', 'ArnesB'],
        labels: ['Arnes A', 'Arnes B'],
        pointSize: 0,
        fillOpacity: 0.4,
        pointStrokeColors:['#5e6d88', '#01c0c8'],
        behaveLikeLine: true,
        gridLineColor: 'rgba(255, 255, 255, 0.1)',
        lineWidth: 0,
        gridTextColor: '#96a2b4',
        smooth: false,
        hideHover: 'auto',
        lineColors: ['#5e6d88', '#01c0c8'],
        resize: true
        
    });

 
 $('.vcarousel').carousel({
            interval: 3000
         })
$(".counter").counterUp({
        delay: 100,
        time: 1200
    });


$(document).ready(function() {
    
   var sparklineLogin = function() { 
        $('#sales1').sparkline([20, 40, 30], {
            type: 'pie',
            height: '90',
            resize: true,
            sliceColors: ['#01c0c8', '#7d5ab6', '#ffffff']
        });
        $('#sparkline2dash').sparkline([6, 10, 9, 11, 9, 10, 12], {
            type: 'bar',
            height: '154',
            barWidth: '4',
            resize: true,
            barSpacing: '10',
            barColor: '#25a6f7'
        });
        
   }
    var sparkResize;
 
        $(window).resize(function(e) {
            clearTimeout(sparkResize);
            sparkResize = setTimeout(sparklineLogin, 500);
        });
        sparklineLogin();

});
