<?PHP

class Frontend_View extends ViewAbstract {

	/**
	 * Экземпляр класса провадера данных для получения шаблонов
	 *
	 * @var DataProviderView
	 */
	private $_DP_view;

	protected function init() {
		$this->_smarty->compile_id = "Frontend";
		//$this->_smarty->force_compile = true;

		$this->_smarty->register_resource("db", array(&$this,
													"dbGetTemplate",
                                       "dbGetTimestamp",
                                       "dbGetSecure",
                                       "dbGetTrusted"));

		$this->_smarty->default_resource_type = "db";

		Zend_Loader::loadClass("DataProviderView", PATH_DATAPROVIDERS);
		$this->_DP_view = new DataProviderView();
	}

	public function render($name) {
		$this->_smarty->compile_id = "Frontend_" . Zend_Registry::get("language_id");

		if (!is_null($this->_template)) {
			$this->_checkMeta();
			$this->_smarty->display($this->_template);
		}
	}

	/**
	 * Проверяем, установлены ли мета-теги
	 * Если нет, устанавливаем теги по-умолчанию
	 *
	 */
	private function _checkMeta() {
		$meta = $this->meta;
		if (is_null($meta)) {
			$meta = array();
		}

		if (empty($meta['title'])) {
			$meta['title'] = Zend_Registry::get("Shelby_ModelObj")->getConfigValue('DEFAULT_META_TITLE');
		}
		if (empty($meta['keywords'])) {
			$meta['keywords'] = Zend_Registry::get("Shelby_ModelObj")->getConfigValue('DEFAULT_META_KEYWORDS');
		}
		if (empty($meta['description'])) {
			$meta['description'] = Zend_Registry::get("Shelby_ModelObj")->getConfigValue('DEFAULT_META_DESCRIPTION');
		}

		$this->meta = $meta;
	}

	/**
	 * Возвращает запрашиваемый шаблон в $tpl_source
	 *
	 * @param string $tpl_name
	 * @param string $tpl_source
	 * @param Smarty $smarty_obj
	 */
	public function dbGetTemplate ($tpl_name, &$tpl_source, &$smarty_obj) {
		//echo " GetTemplate: " . $tpl_name;

		$tpl_source = $this->_DP_view->getTemplate($tpl_name);

		if ($tpl_source === false) {
			return false;
		} elseif (empty($tpl_source)) {
			//Чтобы не ругался что шаблон не найден если пустой
			$tpl_source = ' ';
		}

		return $tpl_source;
	}

	/**
	 * Возвращает отметку времени последней модификации шаблона
	 *
	 * @param string $tpl_name
	 * @param int $tpl_timestamp
	 * @param Smarty $smarty_obj
	 */
	public function dbGetTimestamp($tpl_name, &$tpl_timestamp, &$smarty_obj) {

		//echo " dbGetTimestamp: " . $tpl_name;

		$tpl_timestamp = $this->_DP_view->getTemplateTimestamp($tpl_name);

		if ($tpl_timestamp === false) {
			return false;
		}

		return true;
	}

	public function dbGetSecure($tpl_name, &$smarty_obj) {
		// предполагаем, что наши шаблоны совершенно безопасны
		return true;
	}

	public function dbGetTrusted($tpl_name, &$smarty_obj) {
		// не используется для шаблонов
	}

	/**
	 * Добавлем дополнительные вспомогательные классы для
	 * получения данных из пользовательской части
	 *
	 */
	protected function _attachHelpers() {
		parent::_attachHelpers();

		//Различные функции работы с контентом
		Zend_Loader::loadClass("ViewHelpers_Cms", PATH_VIEWS);
		$cmsObj = new ViewHelpers_Cms();
		$this->_smarty->assign_by_ref("HCms", $cmsObj);

		//Различные функции работы с новостями
		Zend_Loader::loadClass("ViewHelpers_News", PATH_VIEWS);
		$newsObj = new ViewHelpers_News();
		$this->_smarty->assign_by_ref("HNews", $newsObj);

		//Различные функции работы с галлереями
		Zend_Loader::loadClass("ViewHelpers_Gallery", PATH_VIEWS);
		$galleryObj = new ViewHelpers_Gallery();
		$this->_smarty->assign_by_ref("HGallery", $galleryObj);

		//Различные функции работы с голосованиями
		Zend_Loader::loadClass("ViewHelpers_Vote", PATH_VIEWS);
		$voteObj = new ViewHelpers_Vote();
		$this->_smarty->assign_by_ref("HVote", $voteObj);
	}
}