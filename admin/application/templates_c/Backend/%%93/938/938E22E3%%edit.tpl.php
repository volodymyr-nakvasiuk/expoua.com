<?php /* Smarty version 2.6.18, created on 2010-12-21 13:08:54
         compiled from controllers/admin_ep_news/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'controllers/admin_ep_news/edit.tpl', 15, false),array('modifier', 'escape', 'controllers/admin_ep_news/edit.tpl', 104, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/contentVisualEdit.tpl", 'smarty_include_vars' => array('textarea' => 'content','imagesDefaultParent' => "news:".($this->_tpl_vars['entry']['id']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<SCRIPT type="text/javascript" language="javascript" src="<?php echo $this->_tpl_vars['document_root']; ?>
js/adminListHelper.js"></SCRIPT>

<SCRIPT>

objBrandsList = Shelby_Backend.ListHelper.cloneObject('objBrandsList');
objOrganizersList = Shelby_Backend.ListHelper.cloneObject('objOrganizersList');
objExpocentersList = Shelby_Backend.ListHelper.cloneObject('objExpocentersList');
objServiceCoList = Shelby_Backend.ListHelper.cloneObject('objServiceCoList');
objParticipantList = Shelby_Backend.ListHelper.cloneObject('objParticipantList');

objBrandsList.columns = new Array(new Array('id', 'Id'), new Array('name', 'Название'), new Array('organizer_name', 'Организатор'));
objBrandsList.returnFieldId = 'brands_id';
objBrandsList.feedUrl = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_brands','action' => 'list','feed' => 'json'), $this);?>
';
objBrandsList.writeForm();

objOrganizersList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
objOrganizersList.returnFieldId = 'organizers_id';
objOrganizersList.feedUrl = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_organizers','action' => 'list','feed' => 'json'), $this);?>
';
objOrganizersList.writeForm();

objExpocentersList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'), new Array('city_name', 'Город'));
objExpocentersList.returnFieldId = 'expocenters_id';
objExpocentersList.feedUrl = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_expocenters','action' => 'list','feed' => 'json'), $this);?>
';
objExpocentersList.writeForm();

objServiceCoList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
objServiceCoList.returnFieldId = 'service_companies_id';
objServiceCoList.feedUrl = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_servicecomp','action' => 'list','feed' => 'json'), $this);?>
';
objServiceCoList.writeForm();

objParticipantList.columns = new Array(new Array('id', 'Id'),  new Array('active', 'A'), new Array('name', 'Заголовок'));
objParticipantList.returnFieldId = 'events_pariticipants_id';
objParticipantList.feedUrl = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_eventsparticipants','action' => 'list','feed' => 'json'), $this);?>
';
objParticipantList.writeForm();

<?php echo '
$(document).ready(function() {

	$(\'#date_public\').datepicker();

});
'; ?>

</SCRIPT>

<FORM method="post" action="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'update'), $this);?>
">

<h4>Редактируем новость</h4>

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD>Бренд:</TD>
  <TD><INPUT type="text" size="5" name="brands_id" id="brands_id" value="<?php echo $this->_tpl_vars['entry']['brands_id']; ?>
"> <INPUT type="button" onclick="objBrandsList.showPopUp();" value="Выбрать"> <SPAN id="brands_id_name"><?php echo $this->_tpl_vars['entry']['brand_name']; ?>
</SPAN></TD>
 </TR>
 <TR>
  <TD>Категория брендов:</TD>
  <TD>
   <SELECT name="brands_categories_id">
    <OPTION value="">(Не выбрана)</OPTION>
    <?php $_from = $this->_tpl_vars['list_brand_categories']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['el']):
?>
     <OPTION value="<?php echo $this->_tpl_vars['el']['id']; ?>
"<?php if ($this->_tpl_vars['entry']['brands_categories_id'] == $this->_tpl_vars['el']['id']): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['el']['name']; ?>
</OPTION>
    <?php endforeach; endif; unset($_from); ?>
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Страна:</TD>
  <TD>
   <SELECT name="countries_id">
    <OPTION value="">(Не выбрана)</OPTION>
    <?php $_from = $this->_tpl_vars['list_countries']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['el']):
?>
     <OPTION value="<?php echo $this->_tpl_vars['el']['id']; ?>
"<?php if ($this->_tpl_vars['entry']['countries_id'] == $this->_tpl_vars['el']['id']): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['el']['name']; ?>
</OPTION>
    <?php endforeach; endif; unset($_from); ?>
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD>Организатор:</TD>
  <TD><INPUT type="text" size="5" name="organizers_id" id="organizers_id" value="<?php echo $this->_tpl_vars['entry']['organizers_id']; ?>
"> <INPUT type="button" onclick="objOrganizersList.showPopUp();" value="Выбрать"> <SPAN id="organizers_id_name"><?php echo $this->_tpl_vars['entry']['organizer_name']; ?>
</SPAN></TD>
 </TR>
 <TR>
  <TD>Выставочный центр:</TD>
  <TD><INPUT type="text" size="5" name="expocenters_id" id="expocenters_id" value="<?php echo $this->_tpl_vars['entry']['expocenters_id']; ?>
"> <INPUT type="button" onclick="objExpocentersList.showPopUp();" value="Выбрать"> <SPAN id="expocenters_id_name"><?php echo $this->_tpl_vars['entry']['expocenter_name']; ?>
</SPAN></TD>
 </TR>
 <TR>
  <TD>Сервисная компания:</TD>
  <TD><INPUT type="text" size="5" name="service_companies_id" id="service_companies_id" value="<?php echo $this->_tpl_vars['entry']['service_companies_id']; ?>
"> <INPUT type="button" onclick="objServiceCoList.showPopUp();" value="Выбрать"> <SPAN id="service_companies_id_name"><?php echo $this->_tpl_vars['entry']['service_company_name']; ?>
</SPAN></TD>
 </TR>
 <TR>
  <TD>Участник выставки:</TD>
  <TD><INPUT type="text" size="5" name="events_pariticipants_id" id="events_pariticipants_id" value="<?php echo $this->_tpl_vars['entry']['events_pariticipants_id']; ?>
"> <INPUT type="button" onclick="objParticipantList.showPopUp();" value="Выбрать"> <SPAN id="events_pariticipants_id_name"><?php echo $this->_tpl_vars['entry']['event_participant_name']; ?>
</SPAN></TD>
 </TR>
 <TR>
  <TD>Название:</TD>
  <TD><INPUT type="text" size="50" name="name" value="<?php echo $this->_tpl_vars['entry']['name']; ?>
"></TD>
 </TR>
 <TR>
  <TD>Дата новости:</TD>
  <TD><INPUT type="text" size="12" name="date_public" id="date_public" value="<?php echo $this->_tpl_vars['entry']['date_public']; ?>
"></TD>
 </TR>
 <TR>
  <TD colspan="2">Преамбула:<BR />
   <TEXTAREA name="preambula" id="preambula" style="width:95%; height:100px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['entry']['preambula'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</TEXTAREA>
  </TD>
 </TR>
 <TR>
  <TD colspan="2">Полный текст:<BR />
   <TEXTAREA name="content" id="content" style="width:95%; height:500px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['entry']['content'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</TEXTAREA>
  </TD>
 </TR>
 <TR><TD align="center" colspan="2"><INPUT type="submit" value="Обновить"></TD></TR>
</TABLE>

</FORM>