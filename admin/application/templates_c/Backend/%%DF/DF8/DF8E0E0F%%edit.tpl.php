<?php /* Smarty version 2.6.18, created on 2010-12-21 13:10:51
         compiled from controllers/admin_ep_ads_participants/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'controllers/admin_ep_ads_participants/edit.tpl', 11, false),array('modifier', 'escape', 'controllers/admin_ep_ads_participants/edit.tpl', 68, false),)), $this); ?>
<SCRIPT type="text/javascript" language="javascript" src="<?php echo $this->_tpl_vars['document_root']; ?>
js/adminListHelper.js"></SCRIPT>

<SCRIPT>

objBrandsEventsList = Shelby_Backend.ListHelper.cloneObject('objBrandsEventsList');
objServiceCoList = Shelby_Backend.ListHelper.cloneObject('objServiceCoList');
objParticipantList = Shelby_Backend.ListHelper.cloneObject('objParticipantList');

objBrandsEventsList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название бренда'), new Array('date_from', 'С даты'));
objBrandsEventsList.returnFieldId = 'events_id';
objBrandsEventsList.feedUrl = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_brandsevents','action' => 'list','feed' => 'json'), $this);?>
';
objBrandsEventsList.writeForm();

objServiceCoList.columns = new Array(new Array('id', 'Id'), new Array('active', 'A'), new Array('name', 'Название'));
objServiceCoList.returnFieldId = 'service_companies_id';
objServiceCoList.feedUrl = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_servicecomp','action' => 'list','feed' => 'json'), $this);?>
';
objServiceCoList.writeForm();

objParticipantList.columns = new Array(new Array('id', 'Id'), new Array('name', 'Название'));
objParticipantList.returnFieldId = 'events_participants_id';
objParticipantList.feedUrl = '<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('controller' => 'admin_ep_eventsparticipants','action' => 'list','feed' => 'json'), $this);?>
';
objParticipantList.writeForm();

</SCRIPT>

<h4>Редактируем данные об участнике выставки</h4>

<FORM method="post" action="<?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'update'), $this);?>
">

<TABLE border="1" width="100%" style="border-collapse:collapse;">
 <TR>
  <TD width="70">Выставка: </TD>
  <TD>Id: <INPUT type="text" name="events_id" id="events_id" value="<?php echo $this->_tpl_vars['entry']['events_id']; ?>
" size="5"> <INPUT type="button" onclick="objBrandsEventsList.showPopUp();" value="Выбрать"> <span id="events_id_name"><?php echo $this->_tpl_vars['entry']['brand_name']; ?>
 (с <?php echo $this->_tpl_vars['entry']['event_date_from']; ?>
 по <?php echo $this->_tpl_vars['entry']['event_date_to']; ?>
) </span></TD>
 </TR>
 <TR>
  <TD>Сервисник:</TD>
  <TD>Id: <INPUT type="text" name="service_companies_id" id="service_companies_id" value="<?php echo $this->_tpl_vars['entry']['service_companies_id']; ?>
" size="5"> <INPUT type="button" onclick="objServiceCoList.showPopUp();" value="Выбрать"> <span id="service_companies_id_name"><?php echo $this->_tpl_vars['entry']['service_company_name']; ?>
</span></TD>
 </TR>
 <TR>
  <TD>Участник:</TD>
  <TD>Id: <INPUT type="text" name="events_participants_id" id="events_participants_id" value="<?php echo $this->_tpl_vars['entry']['events_participants_id']; ?>
" size="5"> <INPUT type="button" onclick="objParticipantList.showPopUp();" value="Выбрать"> <span id="events_participants_id_name"><?php echo $this->_tpl_vars['entry']['event_participant_name']; ?>
</span></TD>
 </TR>
 <TR>
  <TD>Email:</TD>
  <TD><INPUT type="text" name="email" value="<?php echo $this->_tpl_vars['entry']['email']; ?>
"></TD>
 </TR>
 <TR>
  <TD>PIN:</TD>
  <TD><?php echo $this->_tpl_vars['entry']['pin']; ?>
</TD>
 </TR>
 <TR>
  <TD>Название: </TD>
  <TD><INPUT type="text" size="20" name="name" value="<?php echo $this->_tpl_vars['entry']['name']; ?>
"></TD>
 </TR>
 <TR>
  <TD>Тип:</TD>
  <TD>
   <SELECT name="type">
    <OPTION value="participant" <?php if ($this->_tpl_vars['entry']['type'] == 'participant'): ?>selected<?php endif; ?>>Участник</OPTION>
    <OPTION value="tour" <?php if ($this->_tpl_vars['entry']['type'] == 'tour'): ?>selected<?php endif; ?>>Тур</OPTION>
    <OPTION value="ad" <?php if ($this->_tpl_vars['entry']['type'] == 'ad'): ?>selected<?php endif; ?>>Объявление</OPTION>
   </SELECT>
  </TD>
 </TR>
 <TR>
  <TD colspan="2">
  Краткое описание:<br />
  <TEXTAREA style="width:95%; height:200px;" name="description_short"><?php echo ((is_array($_tmp=$this->_tpl_vars['entry']['description_short'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</TEXTAREA>
  </TD>
 </TR>
 <TR>
  <TD colspan="2">
  Полное описание:<br />
  <TEXTAREA style="width:95%; height:150px;" name="description"><?php echo ((is_array($_tmp=$this->_tpl_vars['entry']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</TEXTAREA>
  </TD>
 </TR>
</TABLE>

<CENTER><INPUT type="submit" value="Обновить"></CENTER>

</FORM>