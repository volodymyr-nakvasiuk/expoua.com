/**
 * $Id: editor_plugin_src.js 324 2007-11-01 12:58:49Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	var each = tinymce.each;

	tinymce.create('tinymce.plugins.MediaPlugin', {
		MediaPlugin : function(ed, url) {
			var t = this;
			
			t.editor = ed;
			t.url = url;

			// Register commands
			ed.addCommand('mceMedia', function() {
				ed.windowManager.open({
					file : url + '/media.htm',
					width : 430 + ed.getLang('media.delta_width', 0),
					height : 470 + ed.getLang('media.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('media', 'media.desc', 'mceMedia');

			ed.onInit.add(function() {
				var lo = {
					mceItemFlash : 'flash',
					mceItemShockWave : 'shockwave',
					mceItemWindowsMedia : 'windowsmedia',
					mceItemQuickTime : 'quicktime',
					mceItemRealMedia : 'realmedia'
				};

				ed.dom.loadCSS(url + "/css/content.css");

				if (ed.theme.onResolveName) {
					ed.theme.onResolveName.add(function(o) {
						if (o.name == 'img') {
							each(lo, function(v, k) {
								if (ed.dom.hasClass(o.node, k)) {
									o.name = v;
									o.title = ed.dom.getAttrib(o.node, 'title');
									return false;
								}
							});
						}
					});
				}

				if (ed && ed.plugins.contextmenu) {
					ed.plugins.contextmenu.onContextMenu.add(function(m, e) {
						if (e.nodeName == 'IMG' && /mceItem(Flash|ShockWave|WindowsMedia|QuickTime|RealMedia)/.test(e.className)) {
							m.add({title : 'media.edit', icon : 'media', command : 'mceMedia'});
						}
					});
				}
			});

			ed.onBeforeSetContent.add(function(o) {
				var h = o.content;

				h = h.replace(/<script[^>]*>\s*write(Flash|ShockWave|WindowsMedia|QuickTime|RealMedia)\(\{([^\)]*)\}\);\s*<\/script>/gi, '<img class="mceItem$1" title="$2" src="' + url + '/img/trans.gif" />');
				h = h.replace(/<object([^>]*)>/gi, '<div class="mceItemObject" $1>');
				h = h.replace(/<embed([^>]*)>/gi, '<div class="mceItemEmbed" $1>');
				h = h.replace(/<\/(object|embed)([^>]*)>/gi, '</div>');
				h = h.replace(/<param([^>]*)>/gi, '<div $1 class="mceItemParam"></div>');
				h = h.replace(/\/ class=\"mceItemParam\"><\/div>/gi, 'class="mceItemParam"></div>');

				o.content = h;
			});

			ed.onSetContent.add(function() {
				t._divsToImgs(ed.getBody());
			});

			ed.onPreProcess.add(function(o) {
				var dom = ed.dom;

				if (o.set) {
					t._divsToImgs(o.node);

					each(dom.select('IMG', o.node), function(n) {
						var p;

						if (/^(mceItemFlash|mceItemShockWave|mceItemWindowsMedia|mceItemQuickTime|mceItemRealMedia)$/.test(n.className)) {
							p = t._parse(n.title);
							dom.setAttrib(n, 'width', dom.getAttrib(n, 'width', p.width || 100));
							dom.setAttrib(n, 'height', dom.getAttrib(n, 'height', p.height || 100));
						}
					});
				}

				if (o.get) {
					each(dom.select('IMG', o.node), function(n) {
						var ci, cb, mt;

						if (ed.getParam('media_use_script')) {
							if (/^(mceItemFlash|mceItemShockWave|mceItemWindowsMedia|mceItemQuickTime|mceItemRealMedia)$/.test(n.className))
								n.className = n.className.replace(/mceItem/g, 'mceTemp');

							return;
						}

						switch (n.className) {
							case 'mceItemFlash':
								ci = 'd27cdb6e-ae6d-11cf-96b8-444553540000';
								cb = 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0';
								mt = 'application/x-shockwave-flash';
								break;

							case 'mceItemShockWave':
								ci = '166b1bca-3f9c-11cf-8075-444553540000';
								cb = 'http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=8,5,1,0';
								mt = 'application/x-director';
								break;

							case 'mceItemWindowsMedia':
								ci = tinyMCE.getParam('media_wmp6_compatible') ? '05589FA1-C356-11CE-BF01-00AA0055595A' : '6BF52A52-394A-11D3-B153-00C04F79FAA6';
								cb = 'http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701';
								mt = 'application/x-mplayer2';
								break;

							case 'mceItemQuickTime':
								ci = '02bf25d5-8c17-4b23-bc80-d3488abddc6b';
								cb = 'http://www.apple.com/qtactivex/qtplugin.cab#version=6,0,2,0';
								mt = 'video/quicktime';
								break;

							case 'mceItemRealMedia':
								ci = 'cfcdaa03-8be4-11cf-b84b-0020afbbccfa';
								cb = 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0';
								mt = 'audio/x-pn-realaudio-plugin';
								break;
						}

						if (ci) {
							dom.replace(t._buildObj({
								classid : ci,
								codebase : cb,
								type : mt
							}, n), n);
						}
					});
				}
			});

			if (ed.getParam('media_use_script')) {
				function getAttr(s, n) {
					n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);

					return n ? n[1] : '';
				};

				ed.onPostProcess.add(function(o) {
					o.content = o.content.replace(/<img[^>]+>/g, function(im) {
						var cl = getAttr(im, 'class'), at;

						if (/^(mceTempFlash|mceTempShockWave|mceTempWindowsMedia|mceTempQuickTime|mceTempRealMedia)$/.test(cl)) {
							at = t._parse(getAttr(im, 'title'));
							at.width = getAttr(im, 'width');
							at.height = getAttr(im, 'height');
							im = '<script type="text/javascript">write' + cl.substring(7) + '({' + t._serialize(at) + '});</script>';
						}

						return im;
					});
				});
			}
		},

		getInfo : function() {
			return {
				longname : 'Media',
				author : 'Moxiecode Systems AB',
				authorurl : 'http://tinymce.moxiecode.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/media',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		},

		// Private methods

		_buildObj : function(o, n) {
			var ob, ed = this.editor, dom = ed.dom, p = this._parse(n.title);

			p.width = o.width = dom.getAttrib(n, 'width') || 100;
			p.height = o.height = dom.getAttrib(n, 'height') || 100;

			ob = dom.create('div', {
				mce_name : 'object',
				classid : "clsid:" + o.classid,
				codebase : o.codebase,
				width : o.width,
				height : o.height
			});

			if (p.src)
				p.src = ed.convertURL(p.src, 'src', n);

			each (p, function(v, k) {
				if (v)
					dom.add(ob, 'div', {mce_name : 'param', name : k, value : v});
			});

			dom.add(ob, 'div', tinymce.extend({mce_name : 'embed', type : o.type}, p));

			return ob;
		},

		_divsToImgs : function(p) {
			var t = this, dom = t.editor.dom, im, ci;

			each(tinymce.filter(dom.select('div', p)), function(n) {
				// Convert object into image
				if (dom.getAttrib(n, 'class') == 'mceItemObject') {
					ci = dom.getAttrib(n, "classid").toLowerCase().replace(/\s+/g, '');

					switch (ci) {
						case 'clsid:d27cdb6e-ae6d-11cf-96b8-444553540000':
							dom.replace(t._createImg('mceItemFlash', n), n);
							break;

						case 'clsid:166b1bca-3f9c-11cf-8075-444553540000':
							dom.replace(t._createImg('mceItemShockWave', n), n);
							break;

						case 'clsid:6bf52a52-394a-11d3-b153-00c04f79faa6':
						case 'clsid:22d6f312-b0f6-11d0-94ab-0080c74c7e95':
						case 'clsid:05589fa1-c356-11ce-bf01-00aa0055595a':
							dom.replace(t._createImg('mceItemWindowsMedia', n), n);
							break;

						case 'clsid:02bf25d5-8c17-4b23-bc80-d3488abddc6b':
							dom.replace(t._createImg('mceItemQuickTime', n), n);
							break;

						case 'clsid:cfcdaa03-8be4-11cf-b84b-0020afbbccfa':
							dom.replace(t._createImg('mceItemRealMedia', n), n);
							break;
					}
				}
			});
		},

		_createImg : function(cl, n) {
			var im, dom = this.editor.dom, pa = {}, ti = '';

			// Create image
			im = dom.create('img', {
				src : this.url + '/img/trans.gif',
				width : dom.getAttrib(n, 'width') || 100,
				height : dom.getAttrib(n, 'height') || 100,
				'class' : cl
			});

			// Setup base parameters
			each(['id', 'name', 'width', 'height', 'bgcolor', 'align'], function(n) {
				var v = dom.getAttrib(n, 'align');

				if (v)
					pa[v] = v;
			});

			// Add optional parameters
			each(dom.select('div', n), function(n) {
				if (dom.hasClass(n, 'mceItemParam'))
					pa[dom.getAttrib(n, 'name')] = dom.getAttrib(n, 'value');
			});

			// Use src not movie
			if (pa.movie) {
				pa.src = pa.movie;
				delete pa.movie;
			}

			delete pa.width;
			delete pa.height;

			im.title = this._serialize(pa);

			return im;
		},

		_parse : function(s) {
			return tinymce.util.JSON.parse('{' + s + '}');
		},

		_serialize : function(o) {
			return tinymce.util.JSON.serialize(o).replace(/[{}]/g, '');
		}
	});

	// Register plugin
	tinymce.PluginManager.add('media', tinymce.plugins.MediaPlugin);
})();