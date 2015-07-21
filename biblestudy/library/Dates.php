<?php
class Dates {
	public $rightNow;
	public $nextStudy;
	public $nextRegStudy; //next regular study night
	public $nextNonRegStudy; //one week ahead - may not be regular study night

	public function showNextStudy() {
		$this->rightNow = new DateTime();
		$db = Database::getInstance();
		$sql = "SELECT * FROM meetings WHERE descrip=? LIMIT 1";
		$result = $db->queryOne($sql, 'study');

		if (self::isValidMysqlDatetime($result['starttime'])) {
			$this->nextStudy = new DateTime($result['starttime']);
			if ($this->rightNow > $this->nextStudy) {
				$grabHour = $this->nextStudy->format('H');
				$grabMinute = $this->nextStudy->format('i');
				$this->nextStudy = clone $this->rightNow;
				$this->nextStudy->setTime($grabHour, $grabMinute);
				$this->setNextRegStudy($this->nextStudy);
				$MySQLDate = $this->nextStudy->format('Y-m-d H:i:00');
				$sql2 = "UPDATE meetings SET starttime=? WHERE id=?";
				$result2 = $db->queryOne($sql2, array($MySQLDate, 1));
			}
			return true;
		}
		return false;
	}

	public function setNextRegStudy($dateToSet) {
		$grabHour = $dateToSet->format('H');
		$grabMinute = $dateToSet->format('i');
		$dateToSet->modify('next Tuesday');
		$dateToSet->setTime($grabHour, $grabMinute);
		$dayOfWeek = $dateToSet->format('j');
		switch ($dayOfWeek) {
			case $dayOfWeek>0 && $dayOfWeek<8:
			case $dayOfWeek>14 && $dayOfWeek<22:
			case $dayOfWeek>28 && $dayOfWeek<32:
				break;
			default:
				$dateToSet->modify('+7 days');
		}
		return $dateToSet;
	}

	public static function isValidMysqlDatetime($theDatetime) {
		if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $theDatetime, $matches)) {
			if (checkdate($matches[2], $matches[3], $matches[1])) {
				return true;
			}
		}
		return false;
	}
}
