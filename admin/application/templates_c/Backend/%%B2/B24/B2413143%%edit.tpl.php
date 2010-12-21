<?php /* Smarty version 2.6.18, created on 2010-12-21 16:22:16
         compiled from controllers/admin_ep_banners_banners/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'controllers/admin_ep_banners_banners/edit.tpl', 7, false),array('modifier', 'escape', 'controllers/admin_ep_banners_banners/edit.tpl', 41, false),)), $this); ?>
<SCRIPT type="text/javascript" language="javascript" src="<?php echo $this->_tpl_vars['document_root']; ?>
js/adminListHelper.js"></SCRIPT>

<SCRIPT>
objAdvertisersList = Shelby_Backend.ListHelper.cloneObject('objAdvertisersList');
objAdvertisersList.columns = new Array(new Array('id', 'Id'), new Array('name', 'Название'));
objAdvertisersList.returnFieldId = 'advertisers_id';
objAdvertisersList.feedUrl = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_banners_advertisers','action' => 'list','feed' => 'json'), $this);?>
';
objAdvertisersList.writeForm();

objEventsList = Shelby_Backend.ListHelper.cloneObject('objEventsList');
objEventsList.columns = new Array(new Array('id', 'Id'), new Array('date_from', 'Дата'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objEventsList.returnFieldId = 'events_id';
objEventsList.feedUrl = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_brandsevents','action' => 'list','feed' => 'json','sort' => "date_from:DESC"), $this);?>
';
objEventsList.writeForm();

<?php echo '
objEventsList.callbackUser = function(json) {
	$("#events_id_name").html(json.date_from + ": " + json.name + " (" + json.country_name + "/" + json.city_name + ")");
}
'; ?>

</SCRIPT>

<FORM method="post" action="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'update'), $this);?>
" enctype="multipart/form-data">

<h4>Редактируем баннер</h4>

<TABLE border="0" width="100%">
 <TR>
  <TD>Рекламодатель:</TD>
  <TD><INPUT type="text" size="5" name="advertisers_id" id="advertisers_id" value="<?php echo $this->_tpl_vars['entry']['advertisers_id']; ?>
"/> <INPUT type="button" onclick="objAdvertisersList.showPopUp();" value="Выбрать"/> <SPAN id="advertisers_id_name"></SPAN></TD>
 </TR>
 <TR>
  <TD>Тип баннера: </TD>
  <TD>
   <INPUT type="hidden" name="types_id" value="<?php echo $this->_tpl_vars['entry']['types_id']; ?>
" />
   <?php echo $this->_tpl_vars['list_types'][$this->_tpl_vars['entry']['types_id']]['name']; ?>

  </TD>
 </TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="80" name="name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['entry']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"></TD>
 </TR>
 <TR>
  <TD>Описание:</TD>
  <TD><TEXTAREA name="description" style="width:95%; height:100px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['entry']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</TEXTAREA></TD>
 </TR>
</TABLE>

<?php $this->assign('banner_type', $this->_tpl_vars['list_types'][$this->_tpl_vars['entry']['types_id']]['media']); ?>

<table border="0" align="center">
<?php if ($this->_tpl_vars['banner_type'] == 'image' || $this->_tpl_vars['banner_type'] == 'flash'): ?>

  <tr><td>Файл: </td><td><input type="file" name="file"/></td></tr>
  <tr><td>Alt: </td><td><input type="text" name="file_alt" size="20" value="<?php echo $this->_tpl_vars['entry']['file_alt']; ?>
" /></td></tr>
  <tr><td colspan="2">
  <?php if ($this->_tpl_vars['banner_type'] == 'flash'): ?>

<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
			 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
			 WIDTH="200" HEIGHT="20" id="fkash_top">
			 <PARAM NAME=movie VALUE="/data/images/banners/<?php echo $this->_tpl_vars['entry']['file_name']; ?>
"> <PARAM NAME=menu VALUE=false> <PARAM NAME=quality VALUE=high> <EMBED src="/data/images/banners/<?php echo $this->_tpl_vars['entry']['file_name']; ?>
" menu="false" quality="high" WIDTH="200" HEIGHT="200" NAME="fkash_top" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
</OBJECT>

  <?php else: ?>
   <IMG src="/data/images/banners/<?php echo $this->_tpl_vars['entry']['file_name']; ?>
" />
  <?php endif; ?>
  </td></tr>

<?php elseif ($this->_tpl_vars['banner_type'] == 'text'): ?>

  <tr><td>Текст: </td><td><textarea style="width:300px; height:100px;" name="text_content"><?php echo ((is_array($_tmp=$this->_tpl_vars['entry']['text_content'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea></td></tr>

<?php elseif ($this->_tpl_vars['banner_type'] == 'pline'): ?>

  <tr><td>Выставка: </td>
  <td>

   <SCRIPT type="text/javascript">
   var url_event = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_events','action' => 'view','id' => $this->_tpl_vars['entry']['pline_events_id'],'feed' => 'json'), $this);?>
';
   <?php echo '
    $(document).ready(function(){
    	$.getJSON(url_event, function(json) {
    		$("#events_id_name").html(json.entry.date_from + ": " + json.entry.brand_name + " (" + json.entry.city_name + ")");
    	});
    });
   '; ?>

   </SCRIPT>

   <input type="text" name="pline_events_id" id="events_id" size="5" value="<?php echo $this->_tpl_vars['entry']['pline_events_id']; ?>
"/> <INPUT type="button" onclick="objEventsList.showPopUp();" value="Выбрать"/> <SPAN id="events_id_name"></SPAN>
  </td>
  </tr>

<?php endif; ?>
</TABLE>

<br />

<CENTER><INPUT type="submit" value="Изменить"></CENTER>

</FORM>

<P><a href="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'list','del' => 'id'), $this);?>
">Вернуться к списку</a></P>