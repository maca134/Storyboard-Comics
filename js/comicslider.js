var comicslider = {
    currentSlide: 1,
    previousSlide: 0,
    storyBoard: {},
    storyBoardContainer: {},
    imagesContainers: {},
    loadingDiv: {},
    loadingGif: '',
    totalSlides: 0,
    animateTime: 500,
    pageTabs: [],
    window: {},
    nextPostSlide: {},
    nextPost: '',
    easing: 'linear',
    visible: true,
    offset: 0,
    init: function(id) {
        this.storyBoard = jQuery('#story-board');
        this.storyBoardContainer = jQuery('#story-board-container');
        this.imagesContainers = jQuery('#story-board div');
        this.nextPostSlide = jQuery('#goto-next-post');
        this.nextPostSlideText = jQuery('#goto-next-post h1');
        this.window = jQuery(window);

        this.totalSlides = this.imagesContainers.length;
                        
        this.window.resize(function() {
            comicslider.resize();
        });
                        
        this.window.load(function () {
            comicslider.loadingDiv.fadeOut(comicslider.animateTime);
            comicslider.resize();
        });
        this.loadingDiv = jQuery('#loading_overlay');
        jQuery('body').prepend(this.loadingDiv);
        this.nextPostSlide.on('click', function () {
            comicslider.next();
        });
    },
    show: function (cb) {
        cb = cb || function () {};
        this.storyBoardContainer.fadeIn(this.animateTime, cb);
        this.visible = true;
    },
    hide: function () {
        this.storyBoardContainer.stop().fadeOut(this.animateTime);
        this.visible = false;
    },
    slideTo: function (n, t) {
        t = t || this.animateTime;
        this.previousSlide = this.currentSlide;
        this.currentSlide = n;
        var slideToPercent = ((n - 1) * 50) * -1 + '%';
        this.storyBoard.stop().animate({
            'left': slideToPercent
        }, t * 3, this.easing);
    },
    current_tab: function () {
        if (typeof this.pageTabs[this.previousSlide] !== 'undefined') this.pageTabs[this.previousSlide].removeClass('current');
        this.pageTabs[this.currentSlide].addClass('current');
    },
    next: function () {
        var currentSlide = parseInt(this.currentSlide) + 1;
        if (currentSlide >= this.totalSlides) {
            if (this.nextPost !== '') {
                window.location = this.nextPost;
            }
        } else {
            this.slideTo(currentSlide);
        }
    },
    prev: function () {
        var currentSlide = (this.currentSlide > 1) ? this.currentSlide - 1 : this.totalSlides - 1;
        if (this.currentSlide > 1) {
            this.slideTo(currentSlide);
        } else {
            
        }
    },
    resize: function () {
        var windowHeight = this.window.height();
        var windowWidth = this.window.width();
        this.imagesContainers.width(windowWidth / 2).find('img').width(windowWidth / 2);
        this.storyBoardContainer.height(windowHeight);
        var middle = (windowHeight / 2) - (this.imagesContainers.height() / 2);
        this.storyBoard.css({
            'margin-top': (middle + this.offset) + 'px'
        });
        
        var imgHeight = this.imagesContainers.height();
        this.nextPostSlide.css({
            'height' : imgHeight + 'px'
        });
        this.nextPostSlideText.css({
            'line-height' : (imgHeight - (imgHeight / 5)) + 'px',
            'font-size': Math.round(imgHeight / 16.7666) + 'px'
        });
        
        this.imagesContainers.find('img').each(function (i, ele) {
            var obj = jQuery(ele);
            var margin = Math.floor((imgHeight - obj.height()) / 2);
            
            obj.css({
                'margin-top' : margin + 'px'
            });
        });
        this.slideTo(this.currentSlide, 1);
    }
};