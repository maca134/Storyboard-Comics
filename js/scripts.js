/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(function ($) {
    /* Makes the whole panel a clickable link */
    $('.image-panel').on('click', function () {
        window.location = $(this).find('a').attr('href');
    });
    
    /* Prevent clicks */
    $('.no-click').on('click', function () {
        return false;
    });
    
    /* Fade is excerpt when hovering over panels */
    $('.image-panel').hover(function () {
        $(this).find('.storyboard_gallery_overlay').stop(true, true).fadeIn(500);
    }, function () {
        $(this).find('.storyboard_gallery_overlay').stop(true, true).fadeOut(500);
    });
    
    /* Sets height of hidden part of panels */
    var panel_height = $('.image-panel').height();
    $('.image-panel h2').each(function (i, ele) {
        var h = panel_height - $(ele).outerHeight();
        $(ele).next().height(h);
    });
    
    /* Added left/right class to each frontpage panels for IE */
    $('#frontpage-panels li:nth-child(3n+1)').addClass('left').css({
        'margin-left': 0
    }); 
    $('#frontpage-panels li:nth-child(3n+3)').addClass('right').css({
        'margin-right': 0
    });
    
    /* Added left/right class to gallery page panels for IE */
    $('#galleries-panels .image-panel:nth-child(' + COLUMNS_GALLERIES + 'n+1)').addClass('left').css({
        'margin-left': 0
    }); 
    $('#galleries-panels .image-panel:nth-child(' + COLUMNS_GALLERIES + 'n+<?php echo $gallery_cols; ?>)').addClass('right').css({
        'margin-right': 0
    }); 

});