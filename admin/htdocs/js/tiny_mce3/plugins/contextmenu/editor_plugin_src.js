/**
 * $Id: editor_plugin_src.js 324 2007-11-01 12:58:49Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	var Event = tinymce.dom.Event, each = tinymce.each, DOM = tinymce.DOM;

	tinymce.create('tinymce.plugins.ContextMenu', {
		ContextMenu : function(ed) {
			var t = this;

			t.editor = ed;
			t.onContextMenu = new tinymce.util.Dispatcher(this);

			ed.onContextMenu.add(function(e) {
				t._getMenu(ed).showMenu(e.clientX, e.clientY);
				Event.cancel(e);
			});

			function hide() {
				if (t._menu) {
					t._menu.removeAll();
					t._menu.destroy();
				}
			};

			ed.onMouseDown.add(hide);
			ed.onKeyDown.add(hide);
			Event.add(document, 'click', hide);
		},

		_getMenu : function(ed) {
			var t = this, m = t._menu, se = ed.selection, col = se.isCollapsed(), el = se.getNode() || ed.getBody(), am;

			p1 = DOM.getPos(ed.getContentAreaContainer());
			p2 = DOM.getPos(ed.getContainer());

			m = ed.controlManager.createDropMenu('contextmenu', {
				offset_x : p1.x - p2.x,
				offset_y : p1.y - p2.y,
				vp_offset_x : p2.x,
				vp_offset_y : p2.y
			});

			t._menu = m;

			m.add({title : 'advanced.cut_desc', icon : 'cut', command : 'Cut'}).setDisabled(col);
			m.add({title : 'advanced.copy_desc', icon : 'copy', command : 'Copy'}).setDisabled(col);
			m.add({title : 'advanced.paste_desc', icon : 'paste', command : 'Paste'});

			if ((el.nodeName == 'A' && !ed.dom.getAttrib(el, 'name')) || !col) {
				m.addSeparator();
				m.add({title : 'advanced.link_desc', icon : 'link', command : ed.plugins.advlink ? 'mceAdvLink' : 'mceLink', ui : true});
				m.add({title : 'advanced.unlink_desc', icon : 'unlink', command : 'UnLink'});
			}

			m.addSeparator();
			m.add({title : 'advanced.image_desc', icon : 'image', command : ed.plugins.advimage ? 'mceAdvImage' : 'mceImage', ui : true});

			m.addSeparator();
			am = m.addMenu({title : 'contextmenu.align'});
			am.add({title : 'contextmenu.left', icon : 'justifyleft', command : 'JustifyLeft'});
			am.add({title : 'contextmenu.center', icon : 'justifycenter', command : 'JustifyCenter'});
			am.add({title : 'contextmenu.right', icon : 'justifyright', command : 'JustifyRight'});
			am.add({title : 'contextmenu.full', icon : 'justifyfull', command : 'JustifyFull'});

			t.onContextMenu.dispatch(m, el, col);

			return m;
		}
	});

	// Register plugin
	tinymce.PluginManager.add('contextmenu', tinymce.plugins.ContextMenu);
})();