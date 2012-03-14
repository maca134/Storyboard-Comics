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
    nextPost: '',
    easing: 'linear',
    visible: true,
    offset: 0,
    init: function(id) {
        this.storyBoard = jQuery('#story-board');
        this.storyBoardContainer = jQuery('#story-board-container');
        this.imagesContainers = jQuery('#story-board div');
        this.nextPostSlideText = jQuery('.goto-next-post h1');
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
        if (currentSlide < this.totalSlides) {
            this.slideTo(currentSlide);
        }
    },
    prev: function () {
        var currentSlide = (this.currentSlide > 1) ? this.currentSlide - 1 : this.totalSlides - 1;
        if (this.currentSlide > 1) {
            this.slideTo(currentSlide);
        }
    },
    resize: function () {
        var windowHeight = this.window.height();
        var windowWidth = this.window.width();
        this.imagesContainers.width((windowWidth / 2) - 1).find('img').width(windowWidth / 2);
        this.storyBoardContainer.height(windowHeight);
        var middle = (windowHeight / 2) - (this.imagesContainers.height() / 2);
        this.storyBoard.css({
            'margin-top': (middle + this.offset) + 'px'
        });
        
        var imgHeight = this.imagesContainers.height();
        this.nextPostSlideText.css({
            'font-size': Math.round(imgHeight / 16.7666) + 'px',
            'width': (windowWidth / 2) - 1
        });
        if (this.storyBoard.height() > (windowHeight - 50)) {
            jQuery("#story-board-pagination .jquery-pagination").fadeOut();
        } else {
            jQuery("#story-board-pagination .jquery-pagination").fadeIn();
        }
        this.slideTo(this.currentSlide, 1);
    }
};