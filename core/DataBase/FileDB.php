<?php
namespace ExBB\DataBase;

use ExBB\DataBase\FileDBTable;

/**
 * Класс для работы с файловой базой данных.
 * В таблицы (файлы) рекомендуется записывать только массивы.
 * После записи в таблицу значения false, функция чтения будет выбрасывать исключение.
 *
 * Class FileDB
 * @package ExBB\DataBase
 */
class FileDB {
	/**
	 * Флаг открытия файла для чтения
	 */
	const MODE_READ = 1;
	/**
	 * Флаг открытия файла для записи
	 */
	const MODE_WRITE = 2;
	/**
	 * Флаг открытия файла для чтения и записи
	 */
	const MODE_READWRITE = 3;

	/**
	 *
	 */
	public function __construct() {

	}

	/**
	 * Открывает файл и возвращает объект для работы с ним
	 *
	 * @param string $filename путь к файлу
	 * @param int $mode режим работы с файлом
	 *
	 * @return \ExBB\DataBase\FileDBTable
	 */
	public function open($filename, $mode) {
		$table = new FileDBTable($filename, $mode);
		$table->open();

		return $table;
	}

	/**
	 * Читает данные из файла
	 *
	 * @param string $filename путь к файлу
	 *
	 * @return array|mixed|string
	 * @throws \Exception
	 */
	public function getData($filename) {
		$table = new FileDBTable($filename, static::MODE_READ);
		$table->open();

		$data = $table->read();

		$table->close();

		return $data;
	}

	/**
	 * Записывает данные в файл
	 *
	 * @param string $filename путь к файлу
	 * @param mixed $data данные
	 */
	public function setData($filename, $data) {
		$table = new FileDBTable($filename, static::MODE_WRITE);
		$table->open();
		$table->write($data);
		$table->close();
	}
}