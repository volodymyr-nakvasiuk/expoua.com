<?PHP

Zend_Loader::loadClass("Admin_ModelAbstract", PATH_MODELS);

abstract class Admin_Files_ModelAbstract extends Admin_ModelAbstract {

	/**
	 * Экземпляр класса провайдера данный фалов
	 *
	 * @var Filesystem_Abstract
	 */
	protected $_DP_obj;

	/**
	 * Имя класса провайдера данных
	 *
	 * @var string
	 */
	protected $_DP_name;

	/**
	 * Базовый путь к каталогу, с которым производится работа (cage)
	 *
	 * @var string
	 */
	protected $_FS_Base_Path;

	public function init() {
		parent::init();

		$this->_DP_obj = $this->_DP($this->_DP_name);
	}

	/**
	 * Возвращает список файлов каталога $parent
	 * TODO: пейджинг, сортировки
	 *
	 * @param int $page
	 * @param string $parent
	 * @param array $sort
	 * @return array
	 */
	public function getList($page = null, $parent = '', $sort = null) {

		$extraParams['basePath'] = $this->_FS_Base_Path . "/";
		$extraParams['path'] = (is_null($parent) ? "":str_replace(":", "/", $parent) . "/");
		
		//Автоматически создается каталог при его отсутствии
		$this->_DP("Filesystem_Images")->createRecursive($extraParams);

		return $this->_DP_obj->getList(null, null, $extraParams);
	}

	public function getEntry($name) {
		$name = str_replace(":", "/", $name);
		$extraParams['basePath'] = $this->_FS_Base_Path . "/";

		return $this->_DP_obj->getEntry($name, $extraParams);
	}

	/**
	 * Создает каталог или файл
	 * Возвращает код завершения действия
	 *
	 * @param array $data
	 * @return int
	 */
	public function insertEntry(Array $data) {

		//Zend_Debug::dump($data);

		$data['basePath'] = $this->_FS_Base_Path . "/";
		$data['path'] = (is_null($data['parent_id']) ? "":str_replace(":", "/", $data['parent_id']) . "/");

		return $this->_DP_obj->insertEntry($data);
	}

	/**
	 * Удаляет указанный файл или каталог. Передается полный путь относительно текущей "клетки"
	 *
	 * @param string $name
	 * @return int
	 */
	public function deleteEntry($name, $parent) {
		$result = 0;

		$extraParams['basePath'] = $this->_FS_Base_Path . "/";

		if (is_string($parent)) {
			$name = str_replace(":", "/", $parent) . "/" . $name;
		}

		//$name = str_replace(":", "/", $name);
		$result = $this->_DP_obj->deleteRecursive(array($name), $extraParams);

		return $result;
	}

	public function updateEntry($name, $data) {
		Zend_Debug::dump($data);
		echo $name;
	}

	/**
	 * Возвращает массив, содержащий каталоги
	 *
	 * @param string $path
	 * @return array
	 */
	public function getTrail($path) {
		$result = array();

		if (is_string($path)) {
			$result = explode(":", $path);
		}

		return $result;
	}
}