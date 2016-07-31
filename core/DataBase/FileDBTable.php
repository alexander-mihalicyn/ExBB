<?php
namespace ExBB\DataBase;

/**
 * Класс для работы с отдельной таблицей (файлом) файловой базы данных.
 * В таблицы (файлы) рекомендуется записывать только массивы.
 * После записи в таблицу значения false, функция чтения будет выбрасывать исключение.
 *
 * Class FileDBTable
 * @package ExBB\DataBase
 */
class FileDBTable {
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
	 * Специальный флаг для внутреннего использования. Указывает на отсутствие файла
	 */
	const MODE_NOT_EXISTS = 4;

	/**
	 * @var resource указатель на открытый ранее файл
	 */
	private $filePointer = null;
	/**
	 * @var string путь к файлу
	 */
	private $filename;
	/**
	 * @var int режим работы с файлом
	 */
	private $mode;

	/**
	 * @param string $filename путь к файлу
	 * @param int $mode режим работы с файлом
	 */
	public function __construct($filename, $mode=self::MODE_READ) {
		$this->filename = $filename;
		$this->mode = $mode;
	}

	/**
	 *
	 */
	public function __destruct() {
		if ($this->filePointer !== null && is_resource($this->filePointer)) {
			$this->close();
		}
	}

	/**
	 * Открывает файл для дальнейшей работы
	 */
	public function open() {
		switch ($this->mode) {
		case static::MODE_READ:
			if (file_exists($this->filename)) {
				$this->filePointer = fopen($this->filename, 'r');
				flock($this->filePointer, LOCK_SH);
			}
			else {
				$this->mode = static::MODE_NOT_EXISTS;
			}

			break;

		case static::MODE_WRITE:
			$this->filePointer = fopen($this->filename, 'w');
			flock($this->filePointer, LOCK_EX);

			break;

		case static::MODE_READWRITE:
			$this->filePointer = fopen($this->filename, 'a+');
			flock($this->filePointer, LOCK_EX);
			break;
		}
	}

	/**
	 * Читает данные из файла
	 *
	 * @return array|mixed|string
	 * @throws \Exception
	 */
	public function read() {
		if ($this->mode == static::MODE_NOT_EXISTS) {
			return [];
		}

		if ($this->mode == static::MODE_WRITE) {
			throw new \Exception('Could not read from file "'.$this->filePointer.'"');
		}

		$filesize = filesize($this->filename);

		if ($filesize <= 8) {
			throw new \Exception('Could not read from file "'.$this->filePointer.'"');
		}

		fseek($this->filePointer, 8);

		$data = fread($this->filePointer, $filesize-8);
		$data = @unserialize($data);

		if ($data === false) {
			throw new \Exception('Invalid data in file "'.$this->filePointer.'"');
		}

		return $data;
	}

	/**
	 * Записывает данные в файл
	 *
	 * @param mixed $data
	 */
	public function write($data) {
		fseek($this->filePointer, 0);
		ftruncate($this->filePointer, 0);
		fwrite($this->filePointer, '<?die;?>' . serialize($data));
		fflush($this->filePointer);
	}

	/**
	 * Закрывает файл
	 */
	public function close() {
		if ($this->mode != static::MODE_NOT_EXISTS && $this->filePointer) {
			flock($this->filePointer, LOCK_UN);
			fclose($this->filePointer);

			$this->filePointer = null;
		}
	}
}