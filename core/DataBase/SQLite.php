<?php
namespace ExBB\DataBase;

class SQLite extends \SQLite3 {
	public function fetchAssoc($result) {
		return $result->fetchArray(SQLITE3_ASSOC);
	}
}