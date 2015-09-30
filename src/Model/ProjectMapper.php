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
	 * Constructor.
	 *
	 * @param \PDO $pdo
	 * @return void
	 */
	public function __construct(\PDO $pdo)
	{
		parent::__construct($pdo, self::TABLE_NAME, self::PK_COL_NAME);
	} // end __construct(\PDO $pdo)

	/**
	 * Insert new record.
	 *
	 * @param EntityInterface $data
	 * @return mixed Returns either `FALSE` or {@see \odTimeTracker\Model\ProjectEntity}.
	 */
	public function insert(EntityInterface $entity)
	{
		$sql = <<<EOT
INSERT INTO `$this->tableName` (`Name`, `Description`, `Created`) 
VALUES ( :name , :description , :created )
EOT;

		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':name', $entity->getName(), \PDO::PARAM_STR);
		$stmt->bindParam(':description', $entity->getDescription(), \PDO::PARAM_STR);
		$stmt->bindParam(':created', $entity->getCreatedRfc3339(), \PDO::PARAM_STR);
		$res = $stmt->execute();

		if ($res === false || $stmt->rowCount() !== 1) {
			return false;
		}

		$entity->setProjectId($this->pdo->lastInsertId());

		return $entity;
	} // end insert(EntityInterface $entity)

	/**
	 * Update record.
	 *
	 * @param EntityInterface $entity
	 * @return mixed Returns either `FALSE` or {@see \odTimeTracker\Model\ProjectEntity}.
	 */
	public function update(EntityInterface $entity)
	{
		$sql = <<<EOT
UPDATE `$this->tableName` 
SET 
	`Name` = :name , 
	`Description` = :description , 
	`Created` = :created  
WHERE `ProjectId` = :id 
EOT;

		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':id', $entity->getProjectId(), \PDO::PARAM_INT);
		$stmt->bindParam(':name', $entity->getName(), \PDO::PARAM_STR);
		$stmt->bindParam(':description', $entity->getDescription(), \PDO::PARAM_STR);
		$stmt->bindParam(':created', $entity->getCreatedRfc3339(), \PDO::PARAM_STR);
		$res = $stmt->execute();

		if ($res === false || $stmt->rowCount() !== 1) {
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
	public function selectAll($limit = 0)
	{
		$limit = ($limit === 0 || is_null($limit)) ? '' : 'LIMIT ' . $limit;
		$sql = "SELECT * FROM `$this->tableName` WHERE 1 %s ";
		$stmt = $this->pdo->prepare(sprintf($sql, $limit));
		$res = $stmt->execute();

		if ($res === false) {
			return array();
		}

		$rows = $stmt->fetchAll();
		$ret = array();

		foreach ($rows as $row) {
			array_push($ret, $this->prepareEntity($row));
		}

		return $ret;
	} // end selectAll($limit = 0)

	/**
	 * @internal
	 * @param array $data
	 * @return ProjectEntity
	 */
	protected function prepareEntity($data)
	{
		return new ProjectEntity($data);
	} // end prepareEntity($data)
} // End of ProjectMapper
