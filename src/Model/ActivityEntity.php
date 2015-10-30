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
 * Activity entity.
 */
class ActivityEntity implements \odTimeTracker\Model\EntityInterface
{
	/**
	 * Activity's identifier.
	 *
	 * @var integer $activityId
	 */
	protected $activityId;

	/**
	 * Identifier of activity's project.
	 *
	 * @var integer $projectId
	 */
	protected $projectId;

	/**
	 * Activity's name.
	 *
	 * @var string $name
	 */
	protected $name;

	/**
	 * Activity's description.
	 *
	 * @var string $description
	 */
	protected $description;

	/**
	 * Comma-separated tags attached to the activity.
	 *
	 * @var string $tags
	 */
	protected $tags;

	/**
	 * Date time when was activity started (RFC3339).
	 *
	 * @var \DateTime $started
	 */
	protected $started;

	/**
	 * Date time when was activity started (RFC3339).
	 *
	 * @var \DateTime $stopped
	 */
	protected $stopped;

	/**
	 * Referenced project entity.
	 *
	 * @var \odTimeTracker\Model\ProjectEntity
	 */
	protected $project;

	/**
	 * Constructor.
	 *
	 * @param array $data (Optional). Data to initialize entity with.
	 * @return void
	 */
	public function __construct($data = array())
	{
		$this->exchangeArray($data);
	} // end __construct($data = array())

	/**
	 * Set entity with given data.
	 *
	 * @param array $data
	 * @return void
	 */
	public function exchangeArray(array $data)
	{
		$this->setActivityId(isset($data['ActivityId']) ? $data['ActivityId'] : null);
		$this->setProjectId(isset($data['ProjectId']) ? $data['ProjectId'] : null);
		$this->setName(isset($data['Name']) ? $data['Name'] : null);
		$this->setDescription(isset($data['Description']) ? $data['Description'] : null);
		$this->setTags(isset($data['Tags']) ? $data['Tags'] : null);
		$this->setStarted(isset($data['Started']) ? $data['Started'] : null);
		$this->setStopped(isset($data['Stopped']) ? $data['Stopped'] : null);

		if (array_key_exists('Project.ProjectId', $data)) {
			$this->project = new ProjectEntity(array(
				'ProjectId' => $data['Project.ProjectId'],
				'Name' => $data['Project.Name'],
				'Description' => $data['Project.Description'],
				'Created' => $data['Project.Created']
			));
		}
	} // end exchangeArray(array $data)

	/**
	 * Retrieve entity as array.
	 *
	 * @return array
	 */
	public function getArrayCopy()
	{
		return array(
			'ActivityId' => $this->activityId,
			'ProjectId' => $this->projectId,
			'Name' => $this->name,
			'Description' => $this->description,
			'Tags' => $this->tags,
			'Started' => $this->started,
			'Stopped' => $this->stopped,
			// *Extra* properties
			'StartedFormatted' => $this->getStartedFormatted(),
			'StoppedFormatted' => $this->getStoppedFormatted(),
			'Duration' => $this->getDuration(),
			'DurationFormatted' => $this->getDurationFormatted(),
			'IsWithinOneDay' => $this->isWithinOneDay()
		);
	} // end getArrayCopy()

	/**
	 * Retrieve numeric identifier of the entity (usually primary key).
	 *
	 * @return integer|null
	 */
	public function getId()
	{
		return $this->getActivityId();
	} // end getId()

	/**
	 * Retrieve activity's identifier.
	 *
	 * @return integer|null
	 */
	public function getActivityId()
	{
		return $this->activityId;
	} // end getActivityId()

	/**
	 * Set activity's identifier.
	 *
	 * @param integer $val
	 * @return \odTimeTracker\Model\ActivityEntity
	 */
	public function setActivityId($val)
	{
		$this->activityId = $val ? (int) $val : null;
	} // end setActivityId($val)

	/**
	 * Retrieve entity of the project to which activity belongs.
	 *
	 * @return \odTimeTracker\Model\ProjectEntity|null
	 */
	public function getProject()
	{
		return $this->project;
	} // end getProject()

	/**
	 * Retrieve identifier of activity's project.
	 *
	 * @return integer|null
	 */
	public function getProjectId()
	{
		return $this->projectId;
	} // end getProjectId()

	/**
	 * Set identifier of activity's project.
	 *
	 * @param integer $val
	 * @return \odTimeTracker\Model\ActivityEntity
	 */
	public function setProjectId($val)
	{
		$this->projectId = $val ? (int) $val : null;
	} // end setProjectId($val)

	/**
	 * Retrieve activity's name.
	 *
	 * @return string|null
	 */
	public function getName()
	{
		return $this->name;
	} // end getName()

	/**
	 * Set activity's name.
	 *
	 * @param string $val
	 * @return \odTimeTracker\Model\ActivityEntity
	 */
	public function setName($val)
	{
		$this->name = $val ? (string) $val : null;
	} // end setName($val)

	/**
	 * Retrieve activity's description.
	 *
	 * @return string|null
	 */
	public function getDescription()
	{
		return $this->description;
	} // end getDescription()

	/**
	 * Set activity's description.
	 *
	 * @param string $val
	 * @return \odTimeTracker\Model\ActivityEntity
	 */
	public function setDescription($val)
	{
		$this->description = $val ? (string) $val : null;
	} // end setDescription($val)

	/**
	 * Retrieve tags attached to activity.
	 *
	 * @return string|null
	 */
	public function getTags()
	{
		return $this->tags;
	} // end getTags()

	/**
	 * Retrieve tags as an array.
	 *
	 * @return array
	 */
	public function getTagsAsArray()
	{
		$tags = $this->getTags();

		if (empty($tags)) {
			return array();
		}

		return explode(',', $tags);
	} // end getTagsAsArray()

	/**
	 * Set tags attached to activity.
	 *
	 * @param string $val
	 * @return \odTimeTracker\Model\ActivityEntity
	 */
	public function setTags($val)
	{
		$this->tags = $val ? (string) $val : null;
	} // end setTags($val)

	/**
	 * Retrieve date time when was the activity started.
	 *
	 * @return \DateTime|null
	 */
	public function getStarted()
	{
		return $this->started;
	} // end getStarted()

	/**
	 * Retrieve formatted date time when was activity started.
	 *
	 * @return string|null
	 */
	public function getStartedFormatted()
	{
		$started = $this->getStarted();
		return (is_null($started)) ? null : $started->format('j.n.Y G:i');
	} // end getStartedFormatted()

	/**
	 * @return string|null
	 */
	public function getStartedRfc3339()
	{
		$started = $this->getStarted();
		return (is_null($started)) ? null : $started->format(\DateTime::RFC3339);
	} // end getStartedRfc3339()

	/**
	 * Set date time when was the activity started.
	 *
	 * @param DateTime|string $val Date time of creation.
	 * @return \odTimeTracker\Model\ActivityEntity
	 */
	public function setStarted($val)
	{
		if (($val instanceof \DateTime)) {
			$this->started = $val;
		}
		else if (is_string($val) && !empty($val)) {
			try {
				// There can be accidentaly exeption thrown whenever the given
				// string is not valid date.
				$this->started = new \DateTime($val);
			} catch(\Exception $e) {
				$this->started = null;
			}
		}
		else {
			$this->started = null;
		}

		return $this;
	} // end setStarted($val)

	/**
	 * Retrieve date time when was the activity stopped.
	 *
	 * @return \DateTime|null
	 */
	public function getStopped()
	{
		return $this->stopped;
	} // end getStopped()

	/**
	 * Retrieve formatted date time when was activity stopped.
	 *
	 * @return string|null
	 */
	public function getStoppedFormatted()
	{
		$stopped = $this->getStopped();
		return (is_null($stopped)) ? null : $stopped->format('j.n.Y G:i');
	} // end getStoppedFormatted()

	/**
	 * @return string|null
	 */
	public function getStoppedRfc3339()
	{
		$stopped = $this->getStopped();
		return (is_null($stopped)) ? null : $stopped->format(\DateTime::RFC3339);
	} // end getStoppedRfc3339()

	/**
	 * Set date time when was the activity stopped.
	 *
	 * @param DateTime|string $val Date time of creation.
	 * @return \odTimeTracker\Model\ActivityEntity
	 */
	public function setStopped($val)
	{
		if (($val instanceof \DateTime)) {
			$this->stopped = $val;
		}
		else if (is_string($val) && !empty($val)) {
			try {
				// There can be accidentaly exeption thrown whenever the given
				// string is not valid date.
				$this->stopped = new \DateTime($val);
			} catch(\Exception $e) {
				$this->stopped = null;
			}
		}
		else {
			$this->stopped = null;
		}

		return $this;
	} // end setStopped($val)

	/**
	 * Retrieve duration between started and stopped. If stopped is `NULL`
	 * calculates duration up to now.
	 *
	 * @return \DateInterval
	 */
	public function getDuration()
	{
		$started = (is_null($this->started)) ? new \DateTime('now') : $this->started;
		$stopped = (is_null($this->stopped)) ? new \DateTime('now') : $this->stopped;

		return $started->diff($stopped);
	} // end getDuration()

	/**
	 * Retrieve duration as formatted string.
	 *
	 * Note: Currently we are displaying just hours and minutes and we does not expect 
	 * activities that takes more than day.
	 *
	 * @return string
	 * @todo Finish this (works only for short activities).
	 */
	public function getDurationFormatted()
	{
		$duration = $this->getDuration();

		if ($duration->d == 0 && $duration->h == 0 && $duration->i == 0) {
			return 'Less than minute';
		}

		$ret = '';

		if ($duration->d == 1) {
			$ret .= 'One day';
		} else if ($duration->d > 1) {
			$ret .= $duration->d . ' days';
		}

		if ($duration->h == 1) {
			if (!empty($ret)) {
				$ret .= ', one hour';
			} else {
				$ret .= 'One hour';
			}
		} else if ($duration->h > 1) {
			if (!empty($ret)) {
				$ret .= ', ' . $duration->h . ' hours';
			} else {
				$ret .= $duration->h . ' hours';
			}
		}

		if ($duration->i == 1) {
			if (!empty($ret)) {
				$ret .= ', one minute';
			} else {
				$ret .= 'One minute';
			}
		} else if ($duration->i > 1) {
			if (!empty($ret)) {
				$ret .= ', ' . $duration->i . ' minutes';
			} else {
				$ret .= $duration->i . ' minutes';
			}
		}

		return $ret;
	} // end getDurationFormatted()

	/**
	 * Returns `TRUE` if activity is started and stopped within one day.
	 *
	 * @return boolean
	 */
	public function isWithinOneDay()
	{
		$started = (is_null($this->started)) ? new \DateTime('now') : $this->started;
		$stopped = (is_null($this->stopped)) ? new \DateTime('now') : $this->stopped;

		return ($started->format('Y-m-d') === $stopped->format('Y-m-d'));
	} // end isWithinOneDay()

	/**
	 * @return boolean
	 */
	public function isRunning()
	{
		return empty($this->stopped);
	} // end isRunning()
} // End of ActivityEntity