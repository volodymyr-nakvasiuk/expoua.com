<?php /* Smarty version 2.6.18, created on 2010-12-21 13:08:54
         compiled from common/contentVisualEdit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'common/contentVisualEdit.tpl', 11, false),)), $this); ?>
<?php if (! $this->_tpl_vars['editor_type']): ?><?php $this->assign('editor_type', 'simple'); ?><?php endif; ?>
<SCRIPT src="<?php echo $this->_tpl_vars['document_root']; ?>
js/tiny_mce/tiny_mce.js"></SCRIPT>
<SCRIPT src="<?php echo $this->_tpl_vars['document_root']; ?>
js/adminListHelper.js"></SCRIPT>
<SCRIPT src="<?php echo $this->_tpl_vars['document_root']; ?>
js/adminDownloadsListHelper.js"></SCRIPT>
<SCRIPT type="text/javascript">

Shelby_Backend.DownloadsListHelper.docRoot = '<?php echo $this->_tpl_vars['document_root']; ?>
';

Shelby_Backend.DownloadsListHelper.baseImagesUrl = 'http://admin.expopromoter.com/data/images/';

Shelby_Backend.DownloadsListHelper.feedImagesUrl = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_files_images','action' => 'list','feed' => 'json'), $this);?>
';

Shelby_Backend.DownloadsListHelper.imagesUploadUrl = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_files_images','action' => 'insert'), $this);?>
';

<?php if (! empty ( $this->_tpl_vars['imagesDefaultParent'] )): ?>Shelby_Backend.DownloadsListHelper.imagesListParent = '<?php echo $this->_tpl_vars['imagesDefaultParent']; ?>
';<?php endif; ?>

<?php if ($this->_tpl_vars['editor_type'] == 'advanced'): ?>
<?php echo '
tinyMCE.init({
	mode : "exact",
	elements : "'; ?>
<?php echo $this->_tpl_vars['textarea']; ?>
<?php echo '",
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
'; ?>

<?php else: ?>
<?php echo '
tinyMCE.init({
	mode : "exact",
	elements : "'; ?>
<?php echo $this->_tpl_vars['textarea']; ?>
<?php echo '",
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
'; ?>

<?php endif; ?>
</SCRIPT>