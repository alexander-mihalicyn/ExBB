<?php
namespace Admin\Models;

use ExBB\Base\Model;

/**
 * Class BannersModel
 * @package Admin\Models
 */
class BannersModel extends Model {
	/**
	 * @var null|string используется для поддержания совместимости. Временное решение
	 */
	protected $_Banner = null;

	/**
	 * Возвращает код баннеров
	 *
	 * @return string
	 */
	public function getBannersCode() {
		include EXBB_DATA_BANNERS;

		return $this->_Banner;
	}

	/**
	 * Сохраняет код баннеров в файл
	 *
	 * @param string $code Новый код баннеров
	 */
	public function saveBannersCode($code) {
		$content = '<?php defined(\'IN_EXBB\') or die;'.PHP_EOL.'$this->_Banner = <<<BAN'.PHP_EOL;
		$content .= $code;
		$content .= PHP_EOL.'BAN;'.PHP_EOL;

		file_put_contents(EXBB_DATA_BANNERS, $content, LOCK_EX);
	}
}