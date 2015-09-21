<?php
/**
 * odtimetracker-php-lib
 *
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @link https://github.com/odTimeTracker/odtimetracker-php-lib
 */

namespace odTimeTracker\Model;

/**
 * Mapper for projects.
 */
class ProjectMapper extends AbstractMapper
{
	/**
	 * @const string Database table name.
	 */
	const TABLE_NAME = 'Projects';

	/**
	 * @const string Primary key column name.
	 */
	const PK_COL_NAME = 'ProjectId';

	/**
	 * Create schema.
	 *
	 * @return boolean
	 */
	function createSchema()
	{
		$table = self::TABLE_NAME;
		$sql = <<<EOD
CREATE TABLE IF NOT EXISTS `$table` (
	ProjectId INTEGER PRIMARY KEY AUTOINCREMENT, 
	Name TEXT,
	Description TEXT,
	Created TEXT NOT NULL
);
EOD;

		return $this->pdo->prepare($sql)->execute();
	} // end createSchema()

	/**
	 * Insert new record.
	 *
	 * @param EntityInterface $data
	 * @return mixed Returns either `FALSE` or {@see \odTimeTracker\Model\ProjectEntity}.
	 */
	function insert(EntityInterface $entity)
	{
		$table = self::TABLE_NAME;
		$sql = <<<EOD
INSERT INTO `$table` (`Name`, `Description`, `Created`) 
VALUES ( :name , :desc , :created )
EOD;

		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':name', $entity->getName(), \PDO::PARAM_STR);
		$stmt->bindParam(':desc', $entity->getDescription(), \PDO::PARAM_STR);
		$stmt->bindParam(':created', $entity->getCreatedRfc3339(), \PDO::PARAM_STR);
		$res = $stmt->execute();

		if ($res === false) {
			return false;
		}

		$entity->setId($this->pdo->lastInsertId());

		return $entity;
	} // end insert(EntityInterface $entity)

	/**
	 * Update record.
	 *
	 * @param EntityInterface $entity
	 * @return mixed Returns either `FALSE` or {@see \odTimeTracker\Model\ProjectEntity}.
	 */
	function update(EntityInterface $entity)
	{
		$table = self::TABLE_NAME;
		$sql = <<<EOD
UPDATE `$table` 
SET `Name` = :name , `Description` = :desc , `Created` = :created  
WHERE `ProjectId` = :id 
EOD;

		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':id', $entity->getId(), \PDO::PARAM_INT);
		$stmt->bindParam(':name', $entity->getName(), \PDO::PARAM_STR);
		$stmt->bindParam(':desc', $entity->getDescription(), \PDO::PARAM_STR);
		$stmt->bindParam(':created', $entity->getCreatedRfc3339(), \PDO::PARAM_STR);
		$res = $stmt->execute();

		if ($res === false) {
			return false;
		}

		return $entity;
	} // end update(EntityInterface $entity)

	/**
	 * Select all records.
	 *
	 * @param integer $limit (Optional.)
	 * @return array Returns array of {@see \odTimeTracker\Model\ProjectEntity}.
	 */
	function selectAll($limit = 0)
	{
		$table = self::TABLE_NAME;
		$limit = ($limit === 0 || is_null($limit)) ? '' : 'LIMIT ' . $limit;
		$sql = "SELECT * FROM `$table` WHERE 1 $limit;";
		$stmt = $this->pdo->prepare($sql);
		$res = $stmt->execute();

		if ($res === false) {
			return array();
		}

		$rows = $stmt->fetchAll();
		$ret = array();

		foreach ($rows as $row) {
			array_push($ret, new ProjectEntity($row));
		}

		return $ret;
	} // end selectAll($limit = 0)
} // End of ProjectMapper
