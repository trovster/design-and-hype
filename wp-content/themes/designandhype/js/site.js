'use strict';

/*global window, document, console, jQuery, App:true, google, _gaq */

/**
 * @desc Specific application options
 */
App = (function (App, $) {

	App.options = $.extend({}, App.options, {
		timeout: [],
		className: {
			active:			'active',
			hide:			'hide',
			current_cat:	'current-cat',
			current_nav:	'current-menu-item'
		}
	});

	return App;

}(typeof App === 'object' ? App : {}, jQuery));

/**
 * @desc Specific application page autoload methods
 */
App = (function (App, $) {

	App.model.isotope = {
		enabled: $.fn.isotope,
		initialized: false,
		$context: $('#content'),
		$home: $('header').find('h1, nav li:first-child'),
		$isotope: null,
		$nav: null,
		$items: null,
		total: 0,
		columns: 3,
		options: {
			layoutMode:	'straightDown',
			resizable:	false,
			homepage:	false
		},
		init: function ($context, options) {
			if (typeof $context === 'object' && $context !== null) {
				this.$context	= $context;
			}
			if (typeof options === 'object') {
				this.options = $.extend({}, this.options, options);
			}

			this.$isotope	= this.$context.find('.archive');
			this.$nav		= this.$context.find('aside > ul.categories');
			this.$items		= this.$isotope.children('li');
			this.total		= this.$items.length;

			if (this.options.homepage === true) {
				this.homepage();
			}

			return this;
		},
		run: function () {
			var self = this;

			if (this.initialized === false && this.enabled) {
				this.initialized = true;

				this.$isotope.isotope(this.options);

				this.$nav.on({
					click: function (event, data) {
						var $a			= $(event.target).closest('a'),
							$li			= $(event.target).closest('li'),
							title		= $a.text(),
							filter		= '.category-' + $li.data('isotope-filter'),
							siteTitle	= title + ' | ';

						$a.blur();
						event.preventDefault();

						$li.addClass(App.options.className.current_cat).siblings().removeClass(App.options.className.current_cat);

						if ($li.index() === 0) {
							filter		= '*';
							siteTitle	= '';
							self.$home.addClass(App.options.className.current_nav);
						} else {
							self.$home.removeClass(App.options.className.current_nav);
						}

						self.$isotope.isotope({
							filter: filter
						});

						if (App.model.isotope._trigger(event, data)) {
							App.model.history.push({
								filter: $li.data('isotope-filter')
							}, title, event.target.href);
						}

						App.model.history.setTitle(siteTitle);
					}
				}, 'a');
			}
		},
		homepage: function () {
			var self = this;

			this.$home.on({
				click: function (event) {
					var $a = $(event.target).closest('a');

					$a.blur();
					event.preventDefault();

					self.$nav.find('li:first-child a').trigger('click');

					App.model.history.push(null, null, event.target.href);
				}
			}, 'a');
		},
		_trigger: function (event, data) {
			return !event.isTrigger;
		},
		append: function ($newElements) {
			if (this.enabled && typeof this.$isotope.isotope === 'function') {
				//this.$isotope.isotope('reloadItems');
				this.$isotope.isotope('insert', $newElements);
			}
		}
	};

	App.model.history = {
		enabled: typeof window.history === 'object' && typeof window.history.pushState === 'function',
		initialized: false,
		$title: $('title'),
		siteName: $('title').text(),
		$categories: $('.categories li'),
		init: function () {
			return this;
		},
		run: function () {
			var $current	= this.$categories.filter('.' + App.options.className.current_cat),
				self		= this;

			if (this.enabled) {
				$(window).on('popstate', function (event) {
					if (window.history.state) {
						$current = self.$categories.filter('[data-isotope-filter="' + window.history.state.filter + '"]');
					} else {
						$current = self.$categories.filter(':first-child');
					}

					$current.find('a').trigger('click');
				});

				if (!$current.length) {
					$current = self.$categories.filter(':first-child');
				}

				$current.find('a').trigger('click');
			}
		},
		setTitle: function (prefix) {
			if (typeof prefix === 'undefined') {
				prefix = '';
			}

			this.$title.text(prefix + this.siteName);
		},
		push: function (state, title, href) {
			if (this.enabled) {
				window.history.pushState(state, title, href);
			}
		}
	};

	App.model.slide = {
		initialized: false,
		$context: null,
		$articles: null,
		total: 0,
		options: {
			duration:	500,
			easing:		'swing'
		},
		init: function ($context, options) {
			if (typeof $context === 'object' && $context !== null) {
				this.$context	= $context;
			}
			if (typeof options === 'object') {
				this.options = $.extend({}, this.options, options);
			}

			this.$articles	= $context.find('article');
			this.total		= this.$articles.length;

			if (typeof options === 'object') {
				this.options = $.extend({}, this.options, options);
			}

			this.articleSetup(this.$articles);

			return this;
		},
		run: function () {
			var self = this;

			this.$context.on('click', 'article li.previous a, article li.next a', function (event) {
				var $a			= $(event.target).closest('a'),
					$li			= $a.closest('li'),
					$article	= $a.closest('article'),
					$scroll		= $article.find('div.scroll'),
					distance	= $article.data('distance'),
					width		= $article.data('width'),
					left		= parseInt($scroll.css('left'), 10),
					area		= self.$context.outerWidth(false),
					status		= {
						next:		$li.is('.next'),
						previous:	$li.is('.previous'),
						isEnd:		false,
						isStart:	false
					};

				if (status.next && (Math.abs(left) + area + distance) >= width) {
					// restrict the last movement so it stops at the correct place
					distance		= width - area - Math.abs(left);
					status.isEnd	= true;
				} else if (status.previous && Math.abs(left) - distance <= 0) {
					// make sure it is never less than 0
					distance		= Math.abs(left);
					status.isStart	= true;
				}

				if (status.next) {
					$scroll.animate({
						left: left - distance
					}, $.extend({}, {
						complete: function () {
							self._complete.call($li, status, self._shouldScroll(width));
						}
					}, self.options));
				} else {
					$scroll.animate({
						left: left + distance
					}, $.extend({}, {
						complete: function () {
							self._complete.call($li, status, self._shouldScroll(width));
						}
					}, self.options));
				}

				$a.blur();
				event.preventDefault();
			});
		},
		prevNext: function () {
			var prevNext	= ['Previous', 'Next'],
				html		= [],
				ii			= 0;

			$.each(prevNext, function (i, text) {
				html[ii++] = '<li class="' + text.toLowerCase() + '">';
				html[ii++] = '<a href="#' + text.toLowerCase() + '">';
				html[ii++] = text;
				html[ii++] = '</a>';
				html[ii++] = '</li>';
			});

			return $('<ul />', {
				'class': 'controls previous-next'
			}).append(html.join(''));
		},
		articleSetup: function ($articles) {
			var self = this;

			$articles.each(function (i, article) {
				var $article	= $(article),
					width		= self._getArticleWidth($article);

				$article.wrapInner($('<div />', {
					'class': 'scroll',
					'width': width
				})).css({
					'width':		'auto',
					'max-width':	'100%'
				}).data('width', width).data('distance', parseInt(width / 4, 10));

				$article.append(self.prevNext());

				if (!self._shouldScroll(width)) {
					$article.find('li.previous, li.next').addClass(App.options.className.hide);
				} else {
					$article.find('li.previous').addClass(App.options.className.hide);
				}
			});
		},
		_getArticleWidth: function ($article) {
			var width = 0;

			$article.find('section').each(function (i, section) {
				width += parseInt($(section).outerWidth(true), 10);
			});

			return width;
		},
		_complete: function (status, shouldScroll) {
			if ((status.next && status.isEnd) || status.isStart) {
				this.addClass(App.options.className.hide);
				if (shouldScroll) {
					this.siblings().removeClass(App.options.className.hide);
				}
			} else if (shouldScroll) {
				this.siblings().removeClass(App.options.className.hide);
			}
		},
		_shouldScroll: function (width) {
			return width > (this.$context.width() - 20);
		}
	};

	App.model.infinitescroll = {
		enabled: $.fn.infinitescroll,
		initialized: false,
		$context: $('#post'),
		options: {
			debug: false,
			loading: {
				img:			'',
				msgText:		'<em class="loading">Loading the next set of posts…</em>',
				finishedMsg:	'<em class="end">Congratulations, you’ve reached the end.</em>'
			},
			state: {
				currPage:		typeof current_page === 'number' ? current_page : 1
			},
			animate:			false,
			extraScrollPx:		150,
			bufferPx:			200,
			nextSelector:		'#content-primary nav .next a',
			navSelector:		'#content-primary nav',
			contentSelector:	'#post',
			itemSelector:		'#post article',
			pathParse:			['/page/', '/'],
			errorCallback:		function () {}
		},
		init: function () {
			return this;
		},
		run: function () {
			if (this.enabled) {
				this.$context.infinitescroll(this.options, this.callback);
			}
		},
		callback: function (newElements) {
			var $newElements = $(newElements);

			App.model.isotope.append($newElements);
			App.model.slide.articleSetup($newElements);
		}
	};

	App.routes = $.extend({}, App.routes, {
		common: {
			initialize: function () {},
			finalize: function () {
				var $document		= $(document),
					$window			= $(window),
					$body			= $('body'),
					$content		= $('#content'),
					$header			= $('header'),
					$contentPrimary	= $('#content-primary'),
					$top			= $('a.top');

				App.model.history.init().run();

				$document.on('scroll', function (event) {
					var distance = parseInt($window.scrollTop(), 10),
						height			= parseInt($document.height(), 10),
						bottomOffset	= $header.css('position') === 'fixed' ? 1010 : 653,
						bottom			= distance - (height - bottomOffset);

					if (distance > 50) {
						$body.addClass('scrolled');
					} else {
						$body.removeClass('scrolled');
					}

					if (distance < 350) {
						$top.addClass(App.options.className.hide);
					} else if (!$top.is('.' + App.options.className.active)) {
						$top.removeClass(App.options.className.hide).removeClass(App.options.className.active);
					}

					if (bottom < 0) {
						$top.css('bottom', 0);
					} else {
						$top.css('bottom', bottom);
					}
				}).trigger('scroll');

				if ($.fn.lettering) {
					$content.find('h1').lettering('words');
				}

				if (!$body.is('.single, .page')) {
					App.model.slide.init($contentPrimary).run();
					App.model.infinitescroll.init().run();
				}
			}
		},

		homepage: {
			initialize: function () {
				App.model.isotope.init(null, {
					homepage: !$('body').is('.paged')
				}).run();
			}
		},

		single: {
			initialize: function () {
				var $scroll		= $('html, body'),
					$content	= $('#content'),
					$top		= $('<a />', {
						'text':		'Top',
						'title':	'Back to the top!',
						'href':		'#top',
						'class':	'top ' + App.options.className.hide,
						'click':	function (event) {
							var	$a	= $(event.target).closest('a');

							$scroll.animate({scrollTop: 0}, {
								duration: 500,
								complete: function () {
									$a.removeClass(App.options.className.active);
								}
							});

							$a.addClass(App.options.className.hide).addClass(App.options.className.active).blur();
							event.preventDefault();
						}
					});

				$content.append($top);
			}
		}
	});

	return App;

}(typeof App === 'object' ? App : {}, jQuery));

/**
 * @desc Start the application
 */
if (typeof jQuery !== 'undefined') {
	(function (App, $) {
		$(document).ready(function () {
			App.util.route.start(App.routes);
		});
	}(typeof App === 'object' ? App : {}, jQuery));
}