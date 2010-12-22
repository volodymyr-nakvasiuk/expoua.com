/**
 * $Id: editor_template_src.js 324 2007-11-01 12:58:49Z spocke $
 *
 * This file is meant to showcase how to create a simple theme. The advanced
 * theme is more suitable for production use.
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	var DOM = tinymce.DOM;

	// Tell it to load theme specific language pack(s)
	tinymce.ThemeManager.requireLangPack('simple');

	tinymce.create('tinymce.themes.SimpleTheme:tinymce.Theme', {
		SimpleTheme : function(ed, url) {
			var t = this, states = ['Bold', 'Italic', 'Underline', 'Strikethrough', 'InsertUnorderedList', 'InsertOrderedList'], s = ed.settings;

			t.parent(ed);
			t.editor = ed;

			ed.onInit.add(function() {
				ed.onNodeChange.add(function(cm) {
					tinymce.each(states, function(c) {
						cm.get(c.toLowerCase()).setActive(ed.queryCommandState(c));
					});
				});

				ed.dom.loadCSS(url + "/skins/" + s.skin + "/content.css");
			});

			DOM.loadCSS(url + "/skins/" + s.skin + "/ui.css");
		},

		renderUI : function(o) {
			var t = this, n = o.targetNode, ic, tb, ed = t.editor, cf = ed.controlManager, sc;

			n = DOM.insertAfter(DOM.create('div', {id : ed.id + '_container', 'class' : 'mceEditor ' + ed.settings.skin + 'Skin'}), n);
			n = sc = DOM.add(n, 'table', {cellPadding : 0, cellSpacing : 0, 'class' : 'mceLayout'});
			n = tb = DOM.add(n, 'tbody');

			// Create iframe container
			n = DOM.add(tb, 'tr');
			n = ic = DOM.add(DOM.add(n, 'td'), 'div', {'class' : 'mceIframeContainer'});

			// Create toolbar container
			n = DOM.add(DOM.add(tb, 'tr'), 'td', {'class' : 'mceToolbar', align : 'center'});

			// Create toolbar
			tb = t.toolbar = cf.createToolbar("tools1");
			tb.add(cf.createButton('bold', 'simple.bold_desc', 'Bold'));
			tb.add(cf.createButton('italic', 'simple.italic_desc', 'Italic'));
			tb.add(cf.createButton('underline', 'simple.underline_desc', 'Underline'));
			tb.add(cf.createButton('strikethrough', 'simple.striketrough_desc', 'Strikethrough'));
			tb.add(cf.createSeparator());
			tb.add(cf.createButton('undo', 'simple.undo_desc', 'Undo'));
			tb.add(cf.createButton('redo', 'simple.redo_desc', 'Redo'));
			tb.add(cf.createSeparator());
			tb.add(cf.createButton('cleanup', 'simple.cleanup_desc', 'mceCleanup'));
			tb.add(cf.createSeparator());
			tb.add(cf.createButton('insertunorderedlist', 'simple.bullist_desc', 'InsertUnorderedList'));
			tb.add(cf.createButton('insertorderedlist', 'simple.numlist_desc', 'InsertOrderedList'));
			tb.renderTo(n);

			return {
				iframeContainer : ic,
				editorContainer : ed.id + '_container',
				sizeContainer : sc,
				deltaHeight : -20
			};
		},

		getInfo : function() {
			return {
				longname : 'Simple theme',
				author : 'Moxiecode Systems AB',
				authorurl : 'http://tinymce.moxiecode.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			}
		}
	});

	tinymce.ThemeManager.add('simple', tinymce.themes.SimpleTheme);
})();