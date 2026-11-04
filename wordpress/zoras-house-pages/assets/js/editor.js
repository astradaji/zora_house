/* Zora's House — page editor helper.
   1. Choosing a Zora's House template on an empty page loads that template's layout.
   2. Inserting a Zora's House layout (e.g. from "Choose a pattern") selects the matching template.
   Layouts and template names come from window.zhPagesLayouts (zoras-house-pages.php). */
(function (wp, layouts) {
  'use strict';
  if (!wp || !wp.data || !wp.blocks || !layouts || !layouts.length) return;

  var data = wp.data;

  // "zh-about", "zh-about.php" and "zoras-house-pages//zh-about" all name the same template.
  function slugOf(template) {
    var m = /(?:^|\/)zh-([a-z-]+?)(?:\.php)?$/.exec(template || '');
    return m ? m[1] : '';
  }
  function layoutFor(slug) {
    for (var i = 0; i < layouts.length; i++) if (layouts[i].slug === slug) return layouts[i];
    return null;
  }

  function isEmpty(blocks) {
    return blocks.every(function (b) {
      return b.name === 'core/paragraph' && !String(b.attributes.content || '').trim();
    });
  }

  // Layout marker on a layout's first section: "zh-layout--individuals".
  function markerOf(blocks) {
    var cls = blocks.length ? String(blocks[0].attributes.className || '') : '';
    var m = /\bzh-layout--([a-z-]+)/.exec(cls);
    return m ? m[1] : '';
  }

  function notify(text) {
    if (data.dispatch('core/notices')) {
      data.dispatch('core/notices').createNotice('info', text, { type: 'snackbar', isDismissible: true });
    }
  }

  var loadedFor = null; // template whose layout was last loaded automatically
  var lastMarker = null;

  data.subscribe(function () {
    var editor = data.select('core/editor');
    if (!editor || !editor.getCurrentPostType || editor.getCurrentPostType() !== 'page') return;
    // Wait until the page and its saved content are fully loaded, so existing content is never mistaken for empty.
    if (editor.__unstableIsEditorReady && !editor.__unstableIsEditorReady()) return;

    var template = editor.getEditedPostAttribute('template') || '';
    var blocks = editor.getEditorBlocks();
    var marker = markerOf(blocks);
    if (lastMarker === null) lastMarker = marker;

    // 1. The page uses one of our templates but has no content yet: load that template's layout.
    //    Works whether the template was chosen in the editor, before it opened, or on an earlier visit.
    //    Once loaded, clearing the page by hand doesn't bring the layout back for the same template.
    var layout = layoutFor(slugOf(template));
    if (layout && template !== loadedFor && isEmpty(blocks)) {
      loadedFor = template;
      lastMarker = layout.slug;
      data.dispatch('core/editor').resetEditorBlocks(wp.blocks.parse(layout.content));
      notify('Layout loaded. Replace the [bracketed] text, images and links with this page’s content.');
      return;
    }

    // 2. A Zora's House layout was just inserted: select its template if none is chosen.
    if (marker !== lastMarker) {
      lastMarker = marker;
      var match = marker && layoutFor(marker);
      if (match && !slugOf(template)) {
        loadedFor = match.template;
        data.dispatch('core/editor').editPost({ template: match.template });
        notify('Template set to match this layout.');
      }
    }
  });
})(window.wp, window.zhPagesLayouts);
