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
		$stmt = $this->db->getPdo()->prepare(<<<EOD
CREATE TABLE IF NOT EXISTS `$table` (
	ProjectId INTEGER PRIMARY KEY, 
	Name TEXT,
	Description TEXT,
	Created TEXT NOT NULL
)
EOD
		);

		return $stmt->execute();
	} // end createSchema()

	/**
	 * Insert new record.
	 *
	 * @param ProjectEntity $data
	 * @return mixed
	 */
	function insert(ProjectEntity $entity)
	{
		$name = is_array($entity) ? $entity['Name'] : $entity->getName();
		$desc = is_array($entity) ? $entity['Description'] : $entity->getDescription();
		$created = is_array($entity) ? (array_key_exists('Created', $entity) ? $entity['Created'] : null) : $entity->getCreated();
		$createdObj = is_null($created) ? new \DateTime() : (($created instanceof \DateTime) ? $created : new \DateTime($created));
		$createdStr = $createdObj->format(\DateTime::RFC3339);

		$table = self::TABLE_NAME;
		$stmt = $this->db->getPdo()->prepare(<<<EOD
INSERT INTO `$table` (`Name`, `Description`, `Created`) 
VALUES ( :name , :desc , :created );
EOD
		);
		$stmt->bindParam(':name', $name, \PDO::PARAM_STR);
		$stmt->bindParam(':desc', $desc, \PDO::PARAM_STR);
		$stmt->bindParam(':created', $createdStr, \PDO::PARAM_STR);
		$res = $stmt->execute();
		//var_dump($stmt->debugDumpParams());exit();

		if ($res === false) {
			return false;
		}

		return new ProjectEntity(array(
			'ProjectId' => $this->db->getPdo()->lastInsertId(),
			'Name' => $name,
			'Description' => empty($description) ? null : $description,
			'Created' => $created
		));
	} // end insert($entity)

	/**
	 * Update record.
	 *
	 * @param ProjectEntity $entity
	 * @return mixed
	 * @todo Implement `ProjectMapper.update`!
	 */
	function update(ProjectEntity $entity)
	{
		// ...
	} // end update($entity)

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
		$stmt = $this->db->getPdo()->prepare("SELECT * FROM `$table` WHERE 1 $limit;");
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
