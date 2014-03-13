/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready( function(){	
    var buttons = { 
        previous:$('#jslidernews1 .button-previous') ,
        next:$('#jslidernews1 .button-next') 
    };			 
    $('#jslidernews1').lofJSidernews({ 
        interval : 4000,
        direction : 'opacitys',	
        easing : 'easeInOutExpo',
        duration : 1200,
        auto : true,
        maxItemDisplay : 4,
        navPosition : 'horizontal', // horizontal
        navigatorHeight : 32,
        navigatorWidth : 80,
        mainWidth : 980,
        buttons : buttons 
    });						
});

