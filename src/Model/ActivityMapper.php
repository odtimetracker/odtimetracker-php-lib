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
 * Mapper for activities.
 */
class ActivityMapper extends AbstractMapper
{
	/**
	 * @const string Database table name.
	 */
	const TABLE_NAME = 'Activities';

	/**
	 * @const string Primary key column name.
	 */
	const PK_COL_NAME = 'ActivityId';

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
	 * @return mixed Returns `false` or {@see \odTimeTracker\Model\ActivityEntity}.
	 */
	public function insert(EntityInterface $entity)
	{
		$projectId = $entity->getProjectId();
		$name = $entity->getName();
		$desc = $entity->getDescription();
		$tags = $entity->getTags();
		$started = $entity->getStarted();
		$startedObj = is_null($started) ? new \DateTime() : $started;
		$startedStr = $startedObj->format(\DateTime::RFC3339);
		$stopped = $entity->getStopped();
		$stoppedObj = is_null($stopped) ? null : (($stopped instanceof \DateTime) ? $stopped : new \DateTime($stopped));
		$stoppedStr = ($stopped instanceof \DateTime) ? $stoppedObj->format(\DateTime::RFC3339) : '';

		$table = self::TABLE_NAME;
		$stmt = $this->pdo->prepare(<<<EOT
INSERT INTO `$this->tableName` (ProjectId, Name, Description, Tags, Started, Stopped) 
VALUES ( :projectId, :name , :desc , :tags , :started , :stopped );
EOT
		);
		$stmt->bindParam(':projectId', $projectId, \PDO::PARAM_INT);
		$stmt->bindParam(':name', $name, \PDO::PARAM_STR);
		$stmt->bindParam(':desc', $desc, \PDO::PARAM_STR);
		$stmt->bindParam(':tags', $tags, \PDO::PARAM_STR);
		$stmt->bindParam(':started', $startedStr, \PDO::PARAM_STR);
		$stmt->bindParam(':stopped', $stoppedStr);
		$res = $stmt->execute();

		if ($res === false || $stmt->rowCount() !== 1) {
			return false;
		}

		return $this->prepareEntity(array(
			'ActivityId' => $this->pdo->lastInsertId(),
			'ProjectId' => $projectId,
			'Name' => $name,
			'Description' => empty($description) ? null : $description,
			'Tags' => empty($tags) ? null : $tags,
			'Started' => $started,
			'Stopped' => $stopped
		));
	} // end insert(EntityInterface $entity)

	/**
	 * Update record.
	 *
	 * @param EntityInterface $entity
	 * @return mixed Returns either `FALSE` or {@see \odTimeTracker\Model\ActivityEntity}.
	 */
	public function update(EntityInterface $entity)
	{
		$sql = <<<EOT
UPDATE `$this->tableName` 
SET 
	`ProjectId` = :projectId , 
	`Name` = :name , 
	`Description` = :description , 
	`Tags` = :tags , 
	`Started` = :started , 
	`Stopped` = :stopped 
WHERE `ActivityId` = :id 
EOT;

		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':id', $entity->getActivityId(), \PDO::PARAM_INT);
		$stmt->bindParam(':projectId', $entity->getProjectId(), \PDO::PARAM_INT);
		$stmt->bindParam(':name', $entity->getName(), \PDO::PARAM_STR);
		$stmt->bindParam(':description', $entity->getDescription(), \PDO::PARAM_STR);
		$stmt->bindParam(':tags', $entity->getTags(), \PDO::PARAM_STR);
		$stmt->bindParam(':started', $entity->getStartedRfc3339(), \PDO::PARAM_STR);
		$stmt->bindParam(':stopped', $entity->getStoppedRfc3339(), \PDO::PARAM_STR);
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
	 * @return array Returns array of {@see \odTimeTracker\Model\ActivityEntity}.
	 */
	public function selectAll($limit = 0)
	{
		$stmt = $this->pdo->prepare(<<<EOT
SELECT 
	t1.*,
	t2.ProjectId AS "Project.ProjectId",
	t2.Name AS "Project.Name",
	t2.Description AS "Project.Description",
	t2.Created AS "Project.Created"   
FROM `$this->tableName` AS t1 
LEFT JOIN Projects AS t2 ON t1.ProjectId = t2.ProjectId 
ORDER BY t1.Started DESC ;
EOT
		);
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
	 * Select recent activities.
	 *
	 * @param integer $limit
	 * @return array
	 */
	public function selectRecentActivities($limit)
	{
		$stmt = $this->pdo->prepare(<<<EOT
SELECT 
	t1.*,
	t2.ProjectId AS "Project.ProjectId",
	t2.Name AS "Project.Name",
	t2.Description AS "Project.Description",
	t2.Created AS "Project.Created"
FROM `$this->tableName` AS t1 
LEFT JOIN Projects AS t2 ON t1.ProjectId = t2.ProjectId 
ORDER BY t1.Started DESC 
LIMIT :limit ;
EOT
		);
		$stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
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
	} // end selectRecentActivities($limit)

	/**
	 * Select currently running activity.
	 *
	 * @return mixed Returns either `NULL` or {@see \odTimeTracker\Model\ActivityEntity}.
	 */
	public function selectRunningActivity()
	{
		$stmt = $this->pdo->prepare(<<<EOT
SELECT 
	t1.*,
	t2.ProjectId AS "Project.ProjectId",
	t2.Name AS "Project.Name",
	t2.Description AS "Project.Description",
	t2.Created AS "Project.Created"
FROM `$this->tableName` AS t1 
LEFT JOIN Projects AS t2 ON t1.ProjectId = t2.ProjectId 
WHERE t1.Stopped IS NULL OR t1.Stopped = '' 
LIMIT 1 ;
EOT
		);
		$res = $stmt->execute();

		if ($res === false) {
			return null;
		}

		$row = $stmt->fetch();

		if (!is_array($row)) {
			return null;
		}

		return $this->prepareEntity($row);
	} // end selectRunningActivity()

	/**
	 * Select activities which was started in given interval.
	 *
	 * @param string $dateFrom
	 * @param string $dateTo
	 * @return array
	 */
	public function selectActivitiesForInterval($dateFrom, $dateTo)
	{
		$stmt = $this->pdo->prepare(<<<EOT
SELECT 
	t1.*,
	t2.ProjectId AS "Project.ProjectId",
	t2.Name AS "Project.Name",
	t2.Description AS "Project.Description",
	t2.Created AS "Project.Created"
FROM `$this->tableName` AS t1 
LEFT JOIN Projects AS t2 ON t1.ProjectId = t2.ProjectId 
WHERE t1.Started > :dateFrom AND t1.Started < :dateTo  
ORDER BY t1.Started DESC ;
EOT
		);
		$stmt->bindParam(':dateFrom', $dateFrom, \PDO::PARAM_STR);
		$stmt->bindParam(':dateTo', $dateTo, \PDO::PARAM_STR);
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
	} // end selectActivitiesForInterval($dateFrom, $dateTo)

	/**
	 * Starts new activity. Returns FALSE when inserting failed (usually
	 * when another activity is running). Otherwise returns instance of
	 * {@see \odTimeTracker\Model\ActivityEntity}.
	 *
	 * @param string $name
	 * @param integer $projectId
	 * @param string $description (Optional.)
	 * @param string $tags (Optional.)
	 * @return mixed Returns either `FALSE` or {@see \odTimeTracker\Model\ActivityEntity}.
	 */
	public function startActivity($name, $projectId, $description = '', $tags = '')
	{
		// Only one activity can be executed at once!
		if (($this->selectRunningActivity() instanceof ActivityEntity)) {
			return false;
		}

		$started = new \DateTime();
		$startedStr = $started->format(\DateTime::RFC3339);

		$stmt = $this->pdo->prepare(<<<EOT
INSERT INTO `$this->tableName` (ProjectId, Name, Description, Tags, Started) 
VALUES ( :projectId , :name , :description , :tags, :started );
EOT
		);
		$stmt->bindParam(':projectId', $projectId, \PDO::PARAM_INT);
		$stmt->bindParam(':name', $name, \PDO::PARAM_STR);
		$stmt->bindParam(':description', $description, \PDO::PARAM_STR);
		$stmt->bindParam(':tags', $tags, \PDO::PARAM_STR);
		$stmt->bindParam(':started', $startedStr, \PDO::PARAM_STR);
		$res = $stmt->execute();

		if ($res === false || $stmt->rowCount() !== 1) {
			return false;
		}

		return $this->prepareEntity(array(
			'ActivityId' => $this->pdo->lastInsertId(),
			'ProjectId' => $projectId,
			'Name' => $name,
			'Description' => empty($description) ? null : $description,
			'Tags' => empty($tags) ? null : $tags,
			'Started' => $started
		));
	} // end startActivity($name, $projectId, $description = '', $tags = '')

	/**
	 * Stops currently running activity.
	 *
	 * @return boolean
	 */
	public function stopRunningActivity()
	{
		$runningActivity = $this->selectRunningActivity();

		// There is no running activity...
		if (!($runningActivity instanceof ActivityEntity)) {
			return false;
		}

		$nowObj = new \DateTime('now');
		$nowStr = $nowObj->format(\DateTime::RFC3339);
		$activityId = $runningActivity->getActivityId();

		$stmt = $this->pdo->prepare(
			"UPDATE `$this->tableName` SET `Stopped` = :stopped WHERE `ActivityId` = :activityId "
		);
		$stmt->bindParam(':stopped', $nowStr, \PDO::PARAM_STR);
		$stmt->bindParam(':activityId', $activityId, \PDO::PARAM_INT);
		$res = $stmt->execute();

		return $res;
	} // end stopRunningActivity()

	/**
	 * @internal
	 * @param array $data
	 * @return ActivityEntity
	 */
	protected function prepareEntity($data)
	{
		return new ActivityEntity($data);
	} // end prepareEntity($data)
} // End of ActivityMapper
