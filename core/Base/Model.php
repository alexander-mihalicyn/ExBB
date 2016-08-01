<?php
namespace ExBB\Base;

use ExBB\DataBase\FileDB;

/**
 * Класс, являющийся родителем для всех моделей в системе
 *
 * Class Model
 * @package ExBB\Base
 */
class Model {
	/**
	 * @var \ExBB\DataBase\FileDB Объект класс для работы с файловой базой данных
	 */
	protected $db;

	/**
	 *
	 */
	public function __construct() {
		$this->db = new FileDB();
	}
}