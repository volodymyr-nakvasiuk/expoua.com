<?PHP

abstract class Sab_ListModelAbstract extends ModelAbstract {

  /**
  * Экземпляр объекта провадера данных списка
  *
  * @var List_Abstract
  */
  protected $_DP_obj = null;

  /**
  * Название провайдера данных. Должен быть установлен в каждом классе, который наследует данный
  *
  * @var string
  */
  protected $_DP_name = null;

  /**
  * Набор ограничивающих параметров для действий с провайдерами данных.
  * Можно применять для ограничения по категории или по id пользователя.
  * Учитывается при всех выборках и удалениях
  * При добавлении записи, содержимое этого массива добавляется к вставляемым данным
  *
  * @var array
  */
  protected $_DP_limit_params = array();

  public $forceListResults = null;

  /**
  * Создаем экземпляр объекта провайдера данных
  *
  */
  public function init() {
    //Сперва вызываем функцию инициализации родителя
    parent::init();

    if (!is_null($this->_DP_name)) {
      $this->_DP_obj = $this->_DP($this->_DP_name);
    }

    $this->_DP_limit_params = array_merge($this->_DP_limit_params, array('languages_id' => self::$_user_language_id));
  }

  /**
  * Возвращает запись с номером $id из БД
  *
  * @param int $id
  * @return array
  */
  public function getEntry($id) {
    return $this->_DP_obj->getEntry($id, $this->_DP_limit_params);
  }

  /**
  * Возвращает массив, содержащий список запрашиваемых данных
  * Кроме этого, возвращается общее количество элементов
  * Для установки определенной сортировки вывода, необходимо передать третьим параметром массив,
  * содержащий парамеры сортировки.
  * Ключом выступает название столбца для сортировки, значением - неправление сортировки (ASC, DESC)
  * Для осуществления поиска по списку можно передавать массив, состоящий из ключа (столбца для поиска) и значения (непосредственно поиск)
  *
  * @param int $page
  * @param int $parent
  * @param array $sort
  * @param array $search
  * @return array
  */
  public function getList($page = null, $sort = null, $search = null) {

    $search_save = $search;

    $extraParams = $this->_DP_limit_params;

    if (is_array($sort)) {
      $sortOrder = $sort;
    } else {
      $sortOrder = array();
    }

    //Подготавливаем параметры поиска
    if (!is_null($search)) {
      $search = $this->_prepareSearchConditions($search);
      $extraParams = array_merge($search, $extraParams);
    }

    if (is_null($this->forceListResults)) {
      $this->forceListResults = $this->getConfigValue("GENERAL_ELEMENTS_PER_PAGE");
    }

    return array_merge(array('search' => $search_save), $this->_DP_obj->getList($this->forceListResults, $page, $extraParams, $sortOrder));

  }

  /**
  * Обновляет указанную в первом параметре запись
  * Данные для обновления передаются во втором параметре
  * Функция возвращает количество фактически обновленных записей
  *
  * @param int $id
  * @param array $data
  * @return int
  */
  public function updateEntry($id, Array $data) {
    $data = array_merge($data, $this->_DP_limit_params);
    return $this->_DP_obj->updateEntry($id, $data, $this->_DP_limit_params);
  }

  /**
  * Добавлет новую запись в БД
  * Первым параметром передается массив данных, добавляемый в БД
  * Если во втором параметре передано true, запись будет добавлена во все языковые версии.
  * Если этот параметр передать для модуля, в котором не предусмотрена мультиязычность будет добавлено несколько записей одинаковых (в соответсвии с количеством языков)
  * Возвращает количество добавленных записей
  *
  * @param array $data
  * @return int
  */
  public function insertEntry(Array $data, $insertAllLangs = false) {
    $data = array_merge($data, $this->_DP_limit_params);

    if ($insertAllLangs) {
      $id = null;
      $langs_list = $this->_DP("List_Languages")->getList();
      foreach ($langs_list['data'] as $lang) {
        $data['languages_id'] = $lang['id'];

        if (is_null($id)) {
          $res = $this->_DP_obj->insertEntry($data);

          if ($res != 1) {
            return $res;
          }

          $id = $this->_DP_obj->getLastInsertId();
          $data['id'] = $id;
        } else {
          $res = $this->_DP_obj->insertLanguageData($data);
        }

        //Добавление не удалось, прекращаем
        if (!$res) {
          return $res;
        }
      }
    } else {
      $res = $this->_DP_obj->insertEntry($data);
    }

    return $res;
  }

  /**
  * Удаляет указанную запись
  * Возвращает количество удаленных записей
  *
  * @param int $id
  * @return int
  */
  public function deleteEntry($id) {
    if (!is_array($id)) {
      $id = array($id);
    }

    return $this->_DP_obj->deleteEntry($id, $this->_DP_limit_params);
  }

}