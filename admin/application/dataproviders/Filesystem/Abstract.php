<?PHP

Zend_Loader::loadClass("List_Interface", PATH_DATAPROVIDERS);

abstract class Filesystem_Abstract implements List_Interface {

	/**
	 * Возвращает список файлов и папок каталога
	 *
	 * @param int $results_num
	 * @param int $page
	 * @param array $extraParams
	 * @param array $sortBy
	 * @return array
	 */
	public function getList($results_num = null, $page = null, Array $extraParams = array(), Array $sortBy = array()) {

		$result = array('page' => 1, 'pages' => 1, 'rows' => 0, 'sort_by' => array('type' => 'DESC'));
		$data = array();

		$path = $extraParams['basePath'] . $extraParams['path'];

		try {
			$dirObj = new DirectoryIterator($path);
		} catch (Exception $e) {
			echo $e->getMessage();
			return false;
		}

		foreach ($dirObj as $dirElement) {
			if (!$dirElement->isDot()) {

				$result['rows']++;

				$id = $dirElement->getFilename();
				$parent = trim($extraParams['path'], "/");

				$data[] = array(
					'id' => $id,
					'name' => $dirElement->getFilename(),
					'type' => $dirElement->getType(),
					'size' => $dirElement->getSize(),
					'date' => $dirElement->getMTime(),
					'writeable' => $dirElement->isWritable()
				);
			}
		}

		//array_multisort($data);

		$result['data'] = $data;

		return $result;
	}

	/**
	 * Загружает файл или создает каталог
	 * В качестве параметра передается массив
	 *
	 * @param array $data
	 * @return int
	 */
	public function insertEntry(Array $data) {

		$res = 0;

		switch ($data['type']) {
			case "dir":
				$dirname = $data['basePath'] . $data['path'] . $this->_prepareFilename($data['name']);
				$res = mkdir($dirname);
				if ($res) {
					chmod($dirname, 0777);
				}
				break;
			case "file":
				//Zend_Debug::dump($_FILES);
				if (isset($_FILES) && sizeof($_FILES) > 0) {
					$files = array_pop($_FILES);
					if (isset($files['error']) && $files['error']==0) {

						$filename = $data['basePath'] . $data['path'];
						if (isset($data['name']) && is_string($data['name']) && strlen($data['name'])>0) {
							$filename .= $this->_prepareFilename($data['name']);
						} else {
							$filename .= $this->_prepareFilename($files['name']);
						}

						//echo $filename;
						$res = move_uploaded_file($files['tmp_name'], $filename);
						if ($res) {
							chmod($filename, 0777);
						}
					}
				}
				break;
			default:
				throw new Exception("Unknown type", 1);
		}

		return $res;
	}

	/**
	 * Рекурсивное создание каталога. Можно указать полный путь к целевому каталогу для создания,
	 * все промежуточные каталоги создаются автоматически при необходимости
	 *
	 * @param array $data
	 */
	public function createRecursive(Array $data) {
		$path_array = explode("/", $data['path']);
		if (empty($path_array)) {
			$path_array = array($data['path']);
		}

		$path = $data['basePath'];

		foreach ($path_array as $el) {
			$el = $this->_prepareFilename($el);
			if (!empty($el)) {
				$path .= $el . "/";
				if (!file_exists($path)) {
					mkdir($path, 0777, true);
					chmod($path, 0777);
				}
			}
		}
	}

	/**
	 * Переименование/перемещение файлов и каталогов
	 *
	 * @param string $id
	 * @param array $data
	 * @param array $extraParams
	 */
	public function updateEntry($id = null, Array $data, Array $extraParams = array()) {

	}

	/**
	 * Удаляет файл или каталог
	 * Имя передается в массиве. Если нужно удалить несколько файлов, передается массив имен
	 * Имя каталога передается в первом параметре, путь в массиве во втором
	 *
	 * @param string $name
	 * @param array $extraParams
	 * @return int
	 */
	public function deleteEntry(Array $name = array(), Array $extraParams = array()) {
		$result = 0;

		$name = array_pop($name);
		$name = $extraParams['basePath'] . $name;
		//echo $name . "<br />";

		if (is_writeable($name)) {
			if (is_file($name)) {
				$result = unlink($name);
			} else if (is_dir($name)) {
				$result = @rmdir($name);
				if ($result===false) {
					$result = -4;
				}
			}
		} else {
			$result = -3;
		}

		return $result;
	}

	/**
	 * Удаляет структуру каталогов рекурсивно
	 *
	 * @param array $name
	 * @param array $extraParams
	 * @return int
	 */
	public function deleteRecursive(Array $name, Array $extraParams = array()) {

		$name = array_pop($name);

		if (is_file($extraParams['basePath'] . $name)) {
			$result = $this->deleteEntry(array($name), $extraParams);
			return $result;
		} elseif (!is_dir($extraParams['basePath'] . $name)) {
			return 0;
		}

		$extraParams['path'] = $name . "/";

		$list = $this->getList(null, null, $extraParams);
		//Zend_Debug::dump($list);
		foreach ($list['data'] as $el) {
			$this->deleteRecursive(array($name . "/" . $el['name']), $extraParams);
			if ($el['type'] == 'dir') {
				$result = $this->deleteEntry(array($name . "/" . $el['name']), $extraParams);
			}
		}

		$result = $this->deleteEntry(array($name), $extraParams);

		return 1;
	}

	/**
	 * Возвращает информацию о выбранном файле или каталоге
	 *
	 * @param string $id
	 * @param array $extraParams
	 * @return array
	 */
	public function getEntry($name, Array $extraParams = array()) {
		$result = array();

		$filename = $extraParams['basePath'] . $name;

		if (is_readable($filename)) {

			$result['name'] = basename($name);
			$result['parent'] = str_replace("/", ":", dirname($name));
			$result['basePath'] = $extraParams['basePath'];

			if (is_file($filename)) {
				$result['type'] = 'file';
			} else if (is_dir($filename)) {
				$result['type'] = 'dir';
			} else if (is_link($filename)) {
				$result['type'] = 'link';
			} else {
				$result['type'] = 'unknown';
			}

			$result['writeable'] = is_writeable($filename);
			$result['size'] = filesize($filename);
		}

		return $result;
	}

	/**
	 * Проверяет путь на предмет наличия запрещенных знаков - / в начале .. и т.д.
	 * Путь должен быть относительный
	 *
	 * @param string $path
	 * @return boolean
	 */
	public function validateRelativePath($path) {
		return true;
	}

	/**
	 * Подготавливает имя файла или каталога к сохранению в файловой системе
	 * Заменяет недопустимые символы.
	 *
	 * @param string $name
	 * @return string
	 */
	protected function _prepareFilename($name) {

		$name = str_replace(array(" ", ":", "/", "\\"), "_", $name);

		return $name;
	}
}