<?PHP

/**
 * Абстрактный класс стеммера. Наследуется всеми стеммерами для каждого языка
 * Определяет общий для всех интерфейс и функции
 *
 */
abstract class Fulltext_Stemmer_Abstract extends DataProviderAbstract {

	/**
	 * Функция, которая возвращает нормализованную форму слова.
	 * Данная функция реализована в каждом языковом стеммере
	 *
	 * @param string $word
	 */
	abstract public function stem($word);
}