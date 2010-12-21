{if !$editor_type}{assign var="editor_type" value="simple"}{/if}
<SCRIPT src="{$document_root}js/tiny_mce/tiny_mce.js"></SCRIPT>
<SCRIPT src="{$document_root}js/adminListHelper.js"></SCRIPT>
<SCRIPT src="{$document_root}js/adminDownloadsListHelper.js"></SCRIPT>
<SCRIPT type="text/javascript">

Shelby_Backend.DownloadsListHelper.docRoot = '{$document_root}';

Shelby_Backend.DownloadsListHelper.baseImagesUrl = 'http://admin.expopromoter.com/data/images/';

Shelby_Backend.DownloadsListHelper.feedImagesUrl = '{getUrl controller="admin_files_images" action="list" feed="json"}';

Shelby_Backend.DownloadsListHelper.imagesUploadUrl = '{getUrl controller="admin_files_images" action="insert"}';

{if !empty($imagesDefaultParent)}Shelby_Backend.DownloadsListHelper.imagesListParent = '{$imagesDefaultParent}';{/if}

{if $editor_type == 'advanced'}
{literal}
tinyMCE.init({
	mode : "exact",
	elements : "{/literal}{$textarea}{literal}",
	plugins : "table,save,advhr,advimage,advlink,preview,paste,searchreplace,media",
	theme_advanced_buttons1_add_before : "save,separator",
	theme_advanced_buttons2_add : "separator,preview,separator",
	theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
	theme_advanced_buttons3_add_before : "tablecontrols,separator",
	theme_advanced_buttons3_add : "media,advhr,separator",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_path_location : "bottom",
	extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],hr[class|width|size|noshade],span[class|align|style],div[class|align|style],style[type],link[rel|href|type|media]",
	theme : "advanced",
	entities : "160,nbsp,38,amp,162,cent,8364,euro,163,pound,165,yen,169,copy,174,reg,8482,trade,8240,permil,60,lt,8804,le,8805,ge,176,deg,8722,minus",
	convert_urls : false,
	file_browser_callback : "Shelby_Backend.DownloadsListHelper.browseWindowCall"
});
{/literal}
{else}
{literal}
tinyMCE.init({
	mode : "exact",
	elements : "{/literal}{$textarea}{literal}",
	plugins : "preview,paste,searchreplace",
	theme_advanced_buttons1 : "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator,bullist,numlist,hr,separator,cleanup,removeformat,separator,code",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_path_location : "bottom",
	valid_elements : "p,ul,ol,li,br,hr",
	theme : "advanced",
	entities : "160,nbsp,38,amp,162,cent,8364,euro,163,pound,165,yen,169,copy,174,reg,8482,trade,8240,permil,60,lt,8804,le,8805,ge,176,deg,8722,minus",
	convert_urls : false,
	file_browser_callback : "Shelby_Backend.DownloadsListHelper.browseWindowCall"
});
{/literal}
{/if}
</SCRIPT>