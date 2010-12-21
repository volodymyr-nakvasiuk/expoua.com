<?PHP

/**
 * Интерфейс вспомогательных классов вида для упорядочивания API различных классов
 *
 */
interface ViewHelpers_ListInterface {

	public function getEntry($id);

	public function getList($num = null, $page = 1, $sort = null);

	public function getElementsList($id = null, $num = null, $page = 1, $sort = null);

}