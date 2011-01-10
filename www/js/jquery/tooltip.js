js.module("jquery.tooltip");
js.include("jquery.min");
js.include("jquery.dimensions");
(function ($) {
	var helper = {},
			current, title, tID, IE = $.browser.msie && /MSIE\s(5\.5|6\.)/.test(navigator.userAgent),
			track = false;
	$.tooltip = {
		blocked: false,
		defaults: {
			delay: 200,
			fade: false,
			showURL: true,
			extraClass: "",
			top: 15,
			left: 15,
			id: "tooltip",
			keepShowed: false,
			closeButton: false
		},
		block: function () {
			$.tooltip.blocked = !$.tooltip.blocked;
		}
	};
	$.fn.extend({
		tooltip: function (settings) {
			settings = $.extend({}, $.tooltip.defaults, settings);
			createHelper(settings);
			return this.each(function () {
				$.data(this, "tooltip", settings);
				this.tOpacity = helper.parent.css("opacity");
				this.tooltipText = this.title;
				$(this).removeAttr("title");
				this.alt = "";
			}).mouseover(save).mouseout(hide).click(hide);
		},
		fixPNG: IE ?
				function () {
					return this.each(function () {
						var image = $(this).css('backgroundImage');
						if (image.match(/^url\(["']?(.*\.png)["']?\)$/i)) {
							image = RegExp.$1;
							$(this).css({
								'backgroundImage': 'none',
								'filter': "progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=crop, src='" + image + "')"
							}).each(function () {
								var position = $(this).css('position');
								if (position != 'absolute' && position != 'relative') $(this).css('position', 'relative');
							});
						}
					});
				} : function () {
			return this;
		},
		unfixPNG: IE ?
				  function () {
					  return this.each(function () {
						  $(this).css({
							  'filter': '',
							  backgroundImage: ''
						  });
					  });
				  } : function () {
			return this;
		},
		hideWhenEmpty: function () {
			return this.each(function () {
				$(this)[$(this).html() ? "show" : "hide"]();
			});
		},
		url: function () {
			return this.attr('href') || this.attr('src');
		}
	});

	function createHelper(settings) {
		if (helper.parent) return;
		helper.parent = $('<div id="' + settings.id + '"><h3></h3><div class="body"></div><div class="url"></div></div>').appendTo("#siteBox").hide();
		if ($.fn.bgiframe) helper.parent.bgiframe();
		helper.title = $('h3', helper.parent);
		helper.body = $('div.body', helper.parent);
		helper.url = $('div.url', helper.parent);
	}
	function settings(element) {
		return $.data(element, "tooltip");
	}
	function handle(event) {
		if (settings(this).delay) tID = setTimeout(show, settings(this).delay);
		else show();
		track = !! settings(this).track;
		$("#siteBox").bind('mousemove', update);
		update(event);
	}
	function save() {
		if ($.tooltip.blocked || this == current || (!this.tooltipText && !settings(this).bodyHandler)) return;
		current = this;
		title = this.tooltipText;
		helper.parent.hide();
		if (settings(this).bodyHandler) {
			helper.title.hide();
			var bodyContent = settings(this).bodyHandler.call(this);
			if (bodyContent.nodeType || bodyContent.jquery) {
				helper.body.empty().append(bodyContent)
			} else {
				helper.body.html(bodyContent);
			}
			helper.body.show();
		} else if (settings(this).showBody) {
			var parts = title.split(settings(this).showBody);
			helper.title.html(parts.shift()).show();
			helper.body.empty();
			for (var i = 0, part;
				 (part = parts[i]); i++) {
				if (i > 0) helper.body.append("<br/>");
				helper.body.append(part);
			}
			helper.body.hideWhenEmpty();
		} else {
			helper.title.html(title).show();
			helper.body.hide();
		}
		if (settings(this).closeButton){
			$(settings(this).closeButton, helper.body).click(function(){
				if (tID) clearTimeout(tID);
				current = null;
				helper.parent.hide().css("opacity", "");
			});
		}

		if (settings(this).showURL && $(this).url()) helper.url.html($(this).url().replace('http://', '')).show();
		else helper.url.hide();
		helper.parent.addClass(settings(this).extraClass);
		if (settings(this).fixPNG) helper.parent.fixPNG();
		handle.apply(this, arguments);
	}
	function show() {
		tID = null;
		if ((!IE || !$.fn.bgiframe) && settings(current).fade) {
			if (helper.parent.is(":animated")) helper.parent.stop().show().fadeTo(settings(current).fade, current.tOpacity);
			else helper.parent.is(':visible') ? helper.parent.fadeTo(settings(current).fade, current.tOpacity) : helper.parent.fadeIn(settings(current).fade);
		} else {
			helper.parent.show();
		}
		update();
	}
	function update(event) {
		if ($.tooltip.blocked) return;
		if (event && event.target.tagName == "OPTION") {
			return;
		}
		if (!track && helper.parent.is(":visible")) {
			$("#siteBox").unbind('mousemove', update)
		}
		if (current == null) {
			$("#siteBox").unbind('mousemove', update);
			return;
		}
		helper.parent.removeClass("tooltip-viewport-right").removeClass("tooltip-viewport-bottom");
		var left = helper.parent[0].offsetLeft;
		var top = helper.parent[0].offsetTop;
		if (event) {
			left = event.pageX + $("#siteBox").scrollLeft() + settings(current).left;
			top = event.pageY + $("#siteBox").scrollTop() + settings(current).top;
			var right = 'auto';
			if (settings(current).positionLeft) {
				right = $("#siteBox").width() - left;
				left = 'auto';
			}
			helper.parent.css({
				left: left,
				right: right,
				top: top
			});
		}
		var v = viewport(),
				h = helper.parent[0];
		if (v.x + v.cx < h.offsetLeft + h.offsetWidth) {
			left -= h.offsetWidth + 20 + settings(current).left;
			helper.parent.css({
				left: left + 'px'
			}).addClass("tooltip-viewport-right");
		}
	}
	function viewport() {
		return {
			x: $("#siteBox").scrollLeft(),
			y: $("#siteBox").scrollTop(),
			cx: $("#siteBox").width(),
			cy: $("#siteBox").height()
		};
	}
	function hide(event) {
		if ($.tooltip.blocked) return;
		if (settings(this).keepShowed && helper.parent.css('display')!='none') return;

		if (tID) clearTimeout(tID);
		current = null;
		var tsettings = settings(this);

		function complete() {
			helper.parent.removeClass(tsettings.extraClass).hide().css("opacity", "");
		}
		if ((!IE || !$.fn.bgiframe) && tsettings.fade) {
			if (helper.parent.is(':animated')) helper.parent.stop().fadeTo(tsettings.fade, 0, complete);
			else helper.parent.stop().fadeOut(tsettings.fade, complete);
		} else complete();
		if (settings(this).fixPNG) helper.parent.unfixPNG();
	}
})(jQuery);