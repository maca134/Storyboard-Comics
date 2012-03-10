jQuery(function ($) {
    window.original_send_to_editor = window.send_to_editor;
    var storyboard_gallery = {
        admin_thumb_ul: '',
        init: function () {
            this.admin_thumb_ul = $('#storyboardcomics_thumbs');
            $("#storyboardcomics_thumbs").sortable({
                placeholder: 'storyboard_gallery_placeholder'
            });
    
            $('#storyboardcomics_thumbs').on('click', '.storyboard_gallery_remove', function () {
                if (confirm('Are you sure you want to delete this?')) {
                    $(this).parent().fadeOut(1000, function () {
                        $(this).remove();
                    });
                }
                return false;
            });
            $('#storyboard_gallery_upload_button').on('click', function () {
                window.send_to_editor = function(html) {
                    var imageid = $(html).find('img').attr('class').match(/wp\-image\-([0-9]+)/)[1];
                    storyboard_gallery.get_thumbnail(imageid);
                    tb_remove();
                    window.send_to_editor = window.original_send_to_editor;
                }
                var title = 'Select Image';
                tb_show( title, 'media-upload.php?post_id=' + POST_ID + '&amp;type=image&amp;TB_iframe=1' );
                return false;
            });
            
            $('#storyboard_add_attachments_button').on('click', function() {
                
                var included = [];
                $('#storyboardcomics_thumbs input[type=hidden]').each(function (i, e) {
                    included.push($(this).val());
                });
                storyboard_gallery.get_all_thumbnails(POST_ID, included);
            });
            
        },
        get_thumbnail: function (id) {
            var data = {
                action: 'storyboard_gallery_get_thumbnail',
                imageid: id
            };
            $.post(ajaxurl, data, function(response) {
                storyboard_gallery.admin_thumb_ul.append(response);
            });
        },
        get_all_thumbnails: function (post_id, included) {
            var data = {
                action: 'storyboard_gallery_get_all_thumbnail',
                post_id: post_id,
                included: included
            };
            $.post(ajaxurl, data, function(response) {
                storyboard_gallery.admin_thumb_ul.append(response);
            });
        }
    };
    storyboard_gallery.init();
    
    
    var storyboard_show_page_func = function (ele) {
        var obj = $(ele);
        var parent = obj.parent().parent();
                                  
        if (obj.is(':checked')) {
            parent.find('.storyboard_link_to').slideDown();
            parent.find('.storyboard_gallery_caption').slideUp();
        } else {
            parent.find('.storyboard_link_to').slideUp();
            parent.find('.storyboard_gallery_caption').slideDown();
        }
    };
                        
    $('.storyboard_show_page').each(function (i, ele) {
        storyboard_show_page_func(ele);
    }).on('click', function () {
        storyboard_show_page_func(this);
    });
    
    
});