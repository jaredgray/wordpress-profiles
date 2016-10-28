// JScript File
var ImageGallery = new Class({
    initialImage: 0,
    currentImage: null,
    slideshowDelay: 4000,
    repeat: false,
    autoHeight: true,
    Width: 0,
    Height: 0,
    DebugContainer: null,
    initialize: function (element, attachment_identifiers, mode, width, height)
    {
        this.container = $(element);
        var parent = this.container.getParent().getParent();
        var slip = new Element('div', { 'style': 'clear:both' });
        parent.appendChild(slip);
        this.DebugContainer = new Element('div', {});
        parent.appendChild(this.DebugContainer);
        this.container.empty();
        this.mode = mode;
        if ((height) && null != height)
        {
            this.autoHeight = false;
            this.container.setStyle('height', height);
            this.Height = height;
            this.Width = width;
        }
        // always add mousewheel event.. it goes well with both
        this.container.addEvent('mousewheel', this.handleWheel.bindWithEvent(this));
        /* Helps the container receive key events */
        if (this.mode != 'auto')
        {
            this.container.addEvent('click', function () { this.container.focus(); } .bind(this));
            this.container.addEvent('keydown', this.handleKey.bindWithEvent(this));
            this.container.setProperty('tabIndex', -1);

        }

        if (this.mode == 'auto')
        {
            this.container.addEvent('mouseenter', this.stopSlideShow.bind(this));
            this.container.addEvent('mouseleave', this.startSlideShow.bind(this));
        }

        this.container.addEvent('mouseenter', this.showNav.bind(this));
        this.container.addEvent('mouseleave', this.hideNav.bind(this));

        /* Preload the images */
        var image_urls = attachment_identifiers.map(function (element) { return element[0]; });
        this['images'] = Asset.images(image_urls, {
            onProgress: this.imageLoaded.bind(this)
        });

        /* Store the image post permalinks */
        this.imageLinks = attachment_identifiers.map(function (element) { return element[1]; });

        /* Create the nav */
        this.nav = this.createNav(this['images'].length);
        if (this.nav)
        {
            this.container.adopt(this.nav);
            this.navAnimation = new Fx.Morph(this.nav, { duration: 1250, link: 'cancel', transition: 'elastic:out' });
        }

        /* Kick off the timer if we're in auto mode */
        if (this.mode == 'auto') this.startSlideShow();
    },

    disp: function ig_disp(ml, mt, w, h)
    {
        var o = '';
        o += '<table><tr>';
        o += '<td> Total width: ' + this.Width + '</td>';
        o += '<td> Total height: ' + this.Height + '</td>';
        o += '</tr></table>'
        o += '<table><tr>'
        o += '<td> left: ' + ml + '</td>';
        o += '<td> top: ' + mt + '</td>';
        o += '<td> image-width: ' + w + '</td>';
        o += '<td> image-height: ' + h + '</td>';
        o += '</tr></table>'
        this.DebugContainer.set('html', o);
    },

    stopSlideShow: function ()
    {
        $clear(this.slideshowInterval);
    },

    startSlideShow: function ()
    {
        this.slideshowInterval = this.nextImage.periodical(this.slideshowDelay, this);
    },

    handleKey: function (e)
    {
        if (e.key == 'left')
        {
            this.prevImage();
            e.preventDefault();
        } else if (e.key == 'right')
        {
            this.nextImage();
            e.preventDefault();
        }
    },

    handleWheel: function (e)
    {
        if (e.wheel > 0)
        {
            this.prevImage();
        } else if (e.wheel < 0)
        {
            this.nextImage();
        }
        e.preventDefault();
    },

    imageLoaded: function (total, index)
    {
        /* Add the image to the container */
        this['images'][index].setStyle('opacity', 0).inject(this.container, 'top');

        /* Turn an image's nav button "on" */
        //if(this.mode != 'auto') 
        this.thumbs[index].removeClass('disabled');

        /* If the image has an associated link, sprinkle that functionality in */
        if (this.imageLinks[index])
        {
            this['images'][index].setStyle('cursor', 'pointer');
            this['images'][index].addEvent('click', this.visitUrl.pass(index, this));
        }

        /* Create the image's animation */
        this['images'][index].store('animation', new Fx.Morph(this['images'][index], {
            duration: 850,
            transition: 'quad:in:out'
        }));

        if (index == this.initialImage)
        {
            /* If the initial image has preloaded, bring it up right away */
            this.goToImage(index, false);

            /* Set the height of the gallery based on this first image -err better to set it off of external setting */
            if (true == this.autoHeight)
                this.setGalleryHeight(index);

            this.container.fireEvent('redraw');

            /* Show and hide the nav so the user knows it's there */
            this.showNav();
            this.navDelay = this.hideNav.delay(1000, this);
        }
    },

    setGalleryHeight: function (imageIndex)
    {
        var size = this['images'][imageIndex].getSize();
        this.container.setStyle('height', size['y']);
        this.Height = size['y'];
        this.Width = size['x'];
    },

    goToImage: function (index, animate)
    {
        /* If there's no image at this index, bail out */
        if (index >= this['images'].length || index < 0) return;

        /* If the image hasn't loaded yet, bail out */
        if (!this['images'][index].retrieve('animation')) return;

        if ($type(animate) == false) animate = true;
        var oldImage = null;
        if ($type(this.currentImage) != false)
        {
            /* move the old image to the back */
            oldImage = this['images'][this.currentImage].setStyles({
                'zIndex': 1
            });
        }
        this.currentImage = index;
        var size = this['images'][this.currentImage].getSize();
        var h = this.Height / 2 - size['y'] / 2;
        var w = this.Width / 2 - size['x'] / 2;

        //this.disp(w, h, size['x'], size['y']);

        this['images'][this.currentImage].setStyles({
            'zIndex': 2,
            'opacity': animate ? 0 : 1,
            'display': 'block',
            'margin-left': w + "px",
            'margin-top': h + "px"
        }).inject(this.nav, 'before');

        /* move the old image just behind the new one */
        if (oldImage) oldImage.inject(this['images'][this.currentImage], 'before');

        if (animate)
        {
            var animation = this['images'][this.currentImage].retrieve('animation');
            animation.start({
                opacity: 1
            });
            if (null != oldImage)
            {
                var animation2 = oldImage.retrieve('animation');
                animation2.start({
                    opacity: 0
                });
            }
        }

        this.configureNav(index);
    },

    visitUrl: function (index)
    {
        window.location.href = this.imageLinks[index];
    },

    prevImage: function ()
    {
        this.goToImage(this.currentImage - 1);
    },

    nextImage: function ()
    {
        if (this.mode == 'auto' && this.currentImage == (this['images'].length - 1))
        {
            this.goToImage(0);
        } else
        {
            this.goToImage(this.currentImage + 1);
        }
    },

    createNav: function (length)
    {
        if (length <= 1) return;

        var navContainer = new Element('div', {
            'class': 'navigation'
        });

        var navControls = new Element('div', {
            'class': 'navigationControls'
        });

        //		if(this.mode == 'auto') {
        //			this.teaserLink = new Element('a', {
        //				'class': 'teaser',
        //				html: 'Click to view case study',
        //				href: '#'
        //			});

        //			navControls.adopt(this.teaserLink);
        //		} else {
        this.previousButton = new Element('a', {
            'class': 'previous disabled',
            html: 'Previous',
            href: '#',
            events:
				{
				    click: function (e)
				    {
				        this.prevImage();
				        e.preventDefault();
				    } .bindWithEvent(this)
				}
        });

        this.nextButton = new Element('a', {
            'class': 'next disabled',
            html: 'Next',
            href: '#',
            events: {
                click: function (e)
                {
                    this.nextImage();
                    e.preventDefault();
                } .bindWithEvent(this)
            }
        });

        this.thumbs = [];
        length.times(function (index)
        {
            this.thumbs.push(new Element('a', {
                'class': 'thumb disabled',
                html: index + 1,
                href: '#',
                events: {
                    click: function (e)
                    {
                        this.goToImage(index);
                        e.preventDefault();
                    } .bindWithEvent(this)
                }
            }));
        } .bind(this));

        navControls.adopt(this.previousButton, this.thumbs, this.nextButton);
        //	}

        navContainer.adopt(navControls);

        return navContainer;
    },

    configureNav: function (index)
    {
        //		if(this.mode == 'auto') {
        //			this.teaserLink.set('href', this.imageLinks[this.currentImage]);
        //		} else {
        //		}
        var thumbToActivate = this.thumbs[index];
        var thumbsToDeactivate = $A(this.thumbs).erase(thumbToActivate);

        thumbToActivate.addClass('active');
        thumbsToDeactivate.each(function (thumb) { thumb.removeClass('active'); });

        if (index == 0)
        {
            this.disableButton(this.previousButton);
        } else
        {
            this.enableButton(this.previousButton);
        }

        if (index == this['images'].length - 1)
        {
            this.disableButton(this.nextButton);
        } else
        {
            this.enableButton(this.nextButton);
        }
    },

    disableButton: function (element)
    {
        element.addClass('disabled');
    },

    enableButton: function (element)
    {
        element.removeClass('disabled');
    },

    showNav: function ()
    {
        if (this.navAnimation)
        {
            $clear(this.navDelay);
            this.animateNav('show');
        }
    },

    hideNav: function ()
    {
        if (this.navAnimation)
        {
            this.navDelay = this.animateNav.delay(300, this, 'hide');
        }
    },

    animateNav: function (destinationState)
    {
        var navSize = this.nav.getSize();
        var destinationStateFactor = {
            show: 0,
            hide: -1
        };

        this.navAnimation.start({
            bottom: destinationStateFactor[destinationState] * navSize['y'] - (destinationState == 'show' ? 40 : 0) - 1 // the minus one is for IE6. Sigh
        });
    }
});