<?php
namespace Admin\Models;

use ExBB\Base\Model;

/**
 * Class CountersModel
 * @package Admin\Models
 */
class CountersModel extends Model {
	/**
	 * @var null|string используется для поддержания совместимости. Временное решение
	 */
	protected $_Counters = null;

	/**
	 * Возвращает код счётчиков
	 *
	 * @return string
	 */
	public function getCountersCode() {
		include EXBB_DATA_COUNTERS;

		return $this->_Counters;
	}

	/**
	 * Сохраняет код счётчиков в файл
	 *
	 * @param string $code Новый код счётчиков
	 */
	public function saveCountersCode($code) {
		$content = '<?php defined(\'IN_EXBB\') or die;'.PHP_EOL.'$this->_Counters = <<<CNT'.PHP_EOL;
		$content .= $code;
		$content .= PHP_EOL.'CNT;'.PHP_EOL;

		file_put_contents(EXBB_DATA_COUNTERS, $content, LOCK_EX);
	}
}