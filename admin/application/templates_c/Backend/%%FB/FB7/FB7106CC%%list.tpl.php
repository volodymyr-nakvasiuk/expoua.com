<?php /* Smarty version 2.6.18, created on 2010-12-21 16:22:00
         compiled from controllers/admin_ep_banners_plans/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getUrl', 'controllers/admin_ep_banners_plans/list.tpl', 7, false),array('function', 'cycle', 'controllers/admin_ep_banners_plans/list.tpl', 50, false),)), $this); ?>
<?php echo '<h4>Планы показов</h4>'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/generalFilterDescription.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '<SCRIPT type="text/javascript">var bp_url = "'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','del' => 'search'), $this);?><?php echo '";'; ?><?php echo '
 function chBPlaceFilter(id) {
 	if (id != "0") {
 		bp_url += "search/" + Shelby_Backend.createSearchParam("places_id", id, "=", true) + "/";
 	}
 	document.location.href = bp_url;
 }
 '; ?><?php echo '</SCRIPT><div>'; ?><?php $this->assign('sel_bp_id', 0); ?><?php echo ''; ?><?php $_from = $this->_tpl_vars['list']['search']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['el']):
?><?php echo ''; ?><?php if ($this->_tpl_vars['el']['column'] == 'places_id'): ?><?php echo ''; ?><?php $this->assign('sel_bp_id', $this->_tpl_vars['el']['value']); ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?><?php endforeach; endif; unset($_from); ?><?php echo 'Фильтр по баннероместу:<select onchange="chBPlaceFilter(this.value);"><option value="0">(Не выбрано)</option>'; ?><?php $_from = $this->_tpl_vars['list_places']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['el']):
?><?php echo '<option value="'; ?><?php echo $this->_tpl_vars['el']['id']; ?><?php echo '"'; ?><?php if ($this->_tpl_vars['sel_bp_id'] == $this->_tpl_vars['el']['id']): ?><?php echo ' selected="selected"'; ?><?php endif; ?><?php echo '>'; ?><?php echo $this->_tpl_vars['el']['name']; ?><?php echo '</option>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</select><br/><br/></div>'; ?><?php if (empty ( $this->_tpl_vars['list']['data'] )): ?><?php echo '<p>Записи отсутсвуют</p>'; ?><?php endif; ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/generalPaging.tpl", 'smarty_include_vars' => array('pages' => $this->_tpl_vars['list']['pages'],'page' => $this->_tpl_vars['list']['page'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '<TABLE border="0" width="100%" class="list"><TR><TH align="center">N</TH>'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('width' => '30','name' => 'id','descr' => 'Id')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('name' => 'name','descr' => "Название")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementAutocomplete.tpl", 'smarty_include_vars' => array('name' => 'companies_id','controller' => 'admin_ep_banners_companies','descr' => "Кампании")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo ''; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Lists/headerElementGeneral.tpl", 'smarty_include_vars' => array('name' => 'date_from','descr' => "Даты")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '<TH align="center" colspan="2">Действия</TH></TR>'; ?><?php $this->assign('npp_base', $this->_tpl_vars['HMixed']->getConfigConstValue('GENERAL_ELEMENTS_PER_PAGE')); ?><?php echo ''; ?><?php $this->assign('npp_base', ($this->_tpl_vars['npp_base']*$this->_tpl_vars['list']['page']-$this->_tpl_vars['npp_base'])); ?><?php echo ''; ?><?php $_from = $this->_tpl_vars['list']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['fe'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['el']):
        $this->_foreach['fe']['iteration']++;
?><?php echo '<TR class="'; ?><?php echo smarty_function_cycle(array('values' => "odd,even"), $this);?><?php echo '"><TD align="center">'; ?><?php $this->assign('npp', ($this->_foreach['fe']['iteration']+$this->_tpl_vars['npp_base'])); ?><?php echo ''; ?><?php echo $this->_tpl_vars['npp']; ?><?php echo '</TD><TD align="center">'; ?><?php echo $this->_tpl_vars['el']['id']; ?><?php echo '</TD><TD>'; ?><?php echo $this->_tpl_vars['el']['name']; ?><?php echo '</TD><TD>'; ?><?php echo $this->_tpl_vars['el']['company_name']; ?><?php echo '</TD><TD align="center">'; ?><?php if (! empty ( $this->_tpl_vars['el']['date_from'] )): ?><?php echo '<nobr>с '; ?><?php echo $this->_tpl_vars['el']['date_from']; ?><?php echo '</nobr>'; ?><?php endif; ?><?php echo ''; ?><?php if (! empty ( $this->_tpl_vars['el']['date_to'] )): ?><?php echo ' <nobr>по '; ?><?php echo $this->_tpl_vars['el']['date_to']; ?><?php echo '</nobr>'; ?><?php endif; ?><?php echo '</TD>'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/Actions/general.tpl", 'smarty_include_vars' => array('el' => $this->_tpl_vars['el'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '</TR>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</TABLE><b>Всего записей: </b> '; ?><?php echo $this->_tpl_vars['list']['rows']; ?><?php echo '<br /><p><a href="'; ?><?php echo $this->_plugins['function']['getUrl'][0][0]->getUrl(array('add' => '1','action' => 'add'), $this);?><?php echo '">Добавить новую запись</a></p>'; ?>