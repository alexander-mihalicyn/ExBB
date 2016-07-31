<?php
namespace ExBB\DataBase;

/**
 * Class SQLite
 * @package ExBB\DataBase
 */
class SQLite extends \SQLite3 {
	/**
	 * Возвращает ассоциативный массив, полученный в результате SQLite запроса
	 *
	 * @param \SQLite3Result $result результат запроса
	 *
	 * @return mixed
	 */
	public function fetchAssoc($result) {
		return $result->fetchArray(SQLITE3_ASSOC);
	}
}