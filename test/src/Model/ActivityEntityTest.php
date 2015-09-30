<?php
/**
 * odtimetracker-php-cgi
 *
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 */

namespace odTimeTrackerTest\Model;

/**
 * @covers \odTimeTracker\Model\ActivityEntity
 */
class ActivityEntityTest extends \odTimeTrackerTest\AbstractModelTestCase
{
	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::__construct
	 */
	public function testConstructWithNulls()
	{
		$entity = new \odTimeTracker\Model\ActivityEntity();
		$this->assertNull($entity->getId());
		$this->assertNull($entity->getActivityId());
		$this->assertNull($entity->getProjectId());
		$this->assertNull($entity->getName());
		$this->assertNull($entity->getDescription());
		$this->assertNull($entity->getTags());
		$this->assertNull($entity->getStarted());
		$this->assertNull($entity->getStopped());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::__construct
	 */
	public function testConstructWithValues()
	{
		$activities = self::getDataActivities();

		foreach ($activities as $data) {
			$entity = new \odTimeTracker\Model\ActivityEntity($data);
			$this->assertEquals($data['ActivityId'], $entity->getId());
			$this->assertEquals($data['ActivityId'], $entity->getActivityId());
			$this->assertEquals($data['ProjectId'], $entity->getProjectId());
			$this->assertEquals($data['Name'], $entity->getName());
			$this->assertEquals($data['Description'], $entity->getDescription());
			$this->assertEquals($data['Tags'], $entity->getTags());
			$this->assertEquals($data['Started'], $entity->getStarted());

			if (is_null($data['Stopped'])) {
				$this->assertNull($entity->getStopped());
			} else {
				$this->assertEquals($data['Stopped'], $entity->getStopped());
			}
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::exchangeArray
	 */
	public function testExchangeArrayWithNulls()
	{
		$activities = self::getDataActivities();

		foreach ($activities as $data) {
			$entity = new \odTimeTracker\Model\ActivityEntity();
			$entity->exchangeArray($data);
			$entity->exchangeArray(array());
			$this->assertNull($entity->getId());
			$this->assertNull($entity->getActivityId());
			$this->assertNull($entity->getProjectId());
			$this->assertNull($entity->getName());
			$this->assertNull($entity->getDescription());
			$this->assertNull($entity->getTags());
			$this->assertNull($entity->getStarted());
			$this->assertNull($entity->getStopped());
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::exchangeArray
	 */
	public function testExchangeArrayWithValues()
	{
		$activities = self::getDataActivities();

		foreach ($activities as $data) {
			$entity = new \odTimeTracker\Model\ActivityEntity();
			$entity->exchangeArray($data);
			$this->assertEquals($data['ActivityId'], $entity->getId());
			$this->assertEquals($data['ActivityId'], $entity->getActivityId());
			$this->assertEquals($data['ProjectId'], $entity->getProjectId());
			$this->assertEquals($data['Name'], $entity->getName());
			$this->assertEquals($data['Description'], $entity->getDescription());
			$this->assertEquals($data['Tags'], $entity->getTags());
			$this->assertEquals($data['Started'], $entity->getStarted());

			if (is_null($data['Stopped'])) {
				$this->assertNull($entity->getStopped());
			} else {
				$this->assertEquals($data['Stopped'], $entity->getStopped());
			}
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getArrayCopy
	 */
	public function testGetArrayCopyWithNulls()
	{
		$entity = new \odTimeTracker\Model\ActivityEntity();
		$copy = $entity->getArrayCopy();
		$this->assertNull($copy['ActivityId']);
		$this->assertNull($copy['ProjectId']);
		$this->assertNull($copy['Name']);
		$this->assertNull($copy['Description']);
		$this->assertNull($copy['Tags']);
		$this->assertNull($copy['Started']);
		$this->assertNull($copy['Stopped']);
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getArrayCopy
	 */
	public function testGetArrayCopyWithValues()
	{
		$activities = self::getDataActivities();

		foreach ($activities as $data) {
			$entity = new \odTimeTracker\Model\ActivityEntity($data);
			$copy = $entity->getArrayCopy();
			$this->assertEquals($data['ActivityId'], $copy['ActivityId']);
			$this->assertEquals($data['ProjectId'], $copy['ProjectId']);
			$this->assertEquals($data['Name'], $copy['Name']);
			$this->assertEquals($data['Description'], $copy['Description']);
			$this->assertEquals($data['Tags'], $copy['Tags']);
			$this->assertEquals($data['Started'], $copy['Started']);

			if (is_null($data['Stopped'])) {
				$this->assertNull($entity->getStopped());
			} else {
				$this->assertEquals($data['Stopped'], $copy['Stopped']);
			}
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getArrayCopy
	 */
	public function testGetArrayCopyExtraValues()
	{
		$activities = self::getDataActivities();
		$activity = new \odTimeTracker\Model\ActivityEntity($activities[0]);
		$data = $activity->getArrayCopy();

		$this->assertEquals('5.10.2011 10:00', $data['StartedFormatted']);
		$this->assertEquals('5.10.2011 13:00', $data['StoppedFormatted']);
		$this->assertEquals(3, $data['Duration']->h);
		$this->assertEquals(0, $data['Duration']->i);
		$this->assertEquals(0, $data['Duration']->s);
		$this->assertEquals('3 hours', $data['DurationFormatted']);
		$this->assertEquals(true, $data['IsWithinOneDay']);
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getDuration
	 */
	public function testGetDuration()
	{
		$activities = self::getDataActivities();

		$activity1 = new \odTimeTracker\Model\ActivityEntity($activities[0]);
		$duration1 = $activity1->getDuration();
		$this->assertEquals(3, $duration1->h);
		$this->assertEquals(0, $duration1->i);
		$this->assertEquals(0, $duration1->s);

		$activity2 = new \odTimeTracker\Model\ActivityEntity($activities[1]);
		$duration2 = $activity2->getDuration();
		$this->assertEquals(7, $duration2->h);
		$this->assertEquals(5, $duration2->i);
		$this->assertEquals(30, $duration2->s);

		$activity3 = new \odTimeTracker\Model\ActivityEntity($activities[2]);
		$duration3 = $activity3->getDuration();
		$this->assertEquals(12, $duration3->h);
		$this->assertEquals(30, $duration3->i);
		$this->assertEquals(0, $duration3->s);

		$activity4 = new \odTimeTracker\Model\ActivityEntity($activities[3]);
		$duration4 = $activity4->getDuration();
		$this->assertEquals(0, $duration4->h);
		$this->assertEquals(4, $duration4->i);
		$this->assertEquals(0, $duration4->s);

		$activity5 = new \odTimeTracker\Model\ActivityEntity(array(
			'Name' => 'Test activity',
			'Started' => '2015-06-01 10:00:00+01:00',
			'Stopped' => '2015-06-01 11:01:01+01:00'
		));
		$duration5 = $activity5->getDuration();
		$this->assertEquals(1, $duration5->h);
		$this->assertEquals(1, $duration5->i);
		$this->assertEquals(1, $duration5->s);

		$activity6 = new \odTimeTracker\Model\ActivityEntity(array(
			'Name' => 'Test activity',
			'Started' => '2015-06-02 10:00:00+01:00',
			'Stopped' => '2015-06-03 11:01:01+01:00'
		));
		$duration6 = $activity6->getDuration();
		$this->assertEquals(1, $duration6->d);
		$this->assertEquals(1, $duration6->h);
		$this->assertEquals(1, $duration6->i);
		$this->assertEquals(1, $duration6->s);
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getDurationFormatted
	 */
	public function testGetDurationFormatted()
	{
		$activities = self::getDataActivities();

		$activity1 = new \odTimeTracker\Model\ActivityEntity($activities[0]);
		$this->assertEquals('3 hours', $activity1->getDurationFormatted());

		$activity2 = new \odTimeTracker\Model\ActivityEntity($activities[1]);
		$this->assertEquals('7 hours, 5 minutes', $activity2->getDurationFormatted());

		$activity3 = new \odTimeTracker\Model\ActivityEntity($activities[2]);
		$this->assertEquals('12 hours, 30 minutes', $activity3->getDurationFormatted());

		$activity4 = new \odTimeTracker\Model\ActivityEntity($activities[3]);
		$this->assertEquals('4 minutes', $activity4->getDurationFormatted());

		$activity5 = new \odTimeTracker\Model\ActivityEntity(array(
			'Name' => 'Test activity',
			'Started' => '2015-06-01 10:00:00+01:00',
			'Stopped' => '2015-06-01 11:01:01+01:00'
		));
		$this->assertEquals('One hour, one minute', $activity5->getDurationFormatted());

		$activity6 = new \odTimeTracker\Model\ActivityEntity(array(
			'Name' => 'Test activity',
			'Started' => '2015-06-02 10:00:00+01:00',
			'Stopped' => '2015-06-03 11:01:01+01:00'
		));
		$this->assertEquals('One day, one hour, one minute', $activity6->getDurationFormatted());

		$activity7 = new \odTimeTracker\Model\ActivityEntity(array(
			'Name' => 'Test activity',
			'Started' => '2015-07-01 10:00:00+01:00',
			'Stopped' => '2015-07-01 10:01:01+01:00'
		));
		$this->assertEquals('One minute', $activity7->getDurationFormatted());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getTagsAsArray
	 */
	public function testGetTagsAsArray()
	{
		$activities = self::getDataActivities();

		$activity1 = new \odTimeTracker\Model\ActivityEntity($activities[0]);
		$this->assertEquals(array('tag1', 'tag2', 'tag3'), $activity1->getTagsAsArray());

		$activity2 = new \odTimeTracker\Model\ActivityEntity($activities[1]);
		$this->assertEquals(array('tag2', 'tag3'), $activity2->getTagsAsArray());

		$activity3 = new \odTimeTracker\Model\ActivityEntity($activities[2]);
		$this->assertEquals(array('tag3', 'tag5'), $activity3->getTagsAsArray());

		$activity4 = new \odTimeTracker\Model\ActivityEntity($activities[3]);
		$this->assertEquals(array(), $activity4->getTagsAsArray());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::isWithinOneDay
	 */
	public function testIsWithinOneDay()
	{
		$activities = self::getDataActivities();

		$activity1 = new \odTimeTracker\Model\ActivityEntity($activities[0]);
		$this->assertEquals(true, $activity1->isWithinOneDay());

		$activity2 = new \odTimeTracker\Model\ActivityEntity($activities[1]);
		$this->assertEquals(true, $activity2->isWithinOneDay());

		$activity3 = new \odTimeTracker\Model\ActivityEntity($activities[2]);
		$this->assertEquals(true, $activity3->isWithinOneDay());

		// TODO Check false!
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getStartedFormatted
	 */
	public function testGetStartedFormatted()
	{
		$activities = self::getDataActivities();

		$activity1 = new \odTimeTracker\Model\ActivityEntity($activities[0]);
		$this->assertEquals('5.10.2011 10:00', $activity1->getStartedFormatted());

		$activity2 = new \odTimeTracker\Model\ActivityEntity($activities[1]);
		$this->assertEquals('5.10.2011 14:10', $activity2->getStartedFormatted());

		$activity3 = new \odTimeTracker\Model\ActivityEntity($activities[2]);
		$this->assertEquals('6.10.2011 7:15', $activity3->getStartedFormatted());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getStartedFormatted
	 */
	public function testGetStartedRfc3339()
	{
		$activities = self::getDataActivities();

		$activity1 = new \odTimeTracker\Model\ActivityEntity($activities[0]);
		$this->assertEquals('2011-10-05T10:00:00+01:00', $activity1->getStartedRfc3339());

		$activity2 = new \odTimeTracker\Model\ActivityEntity($activities[1]);
		$this->assertEquals('2011-10-05T14:10:00+01:00', $activity2->getStartedRfc3339());

		$activity3 = new \odTimeTracker\Model\ActivityEntity($activities[2]);
		$this->assertEquals('2011-10-06T07:15:00+01:00', $activity3->getStartedRfc3339());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getStoppedFormatted
	 */
	public function testGetStoppedFormatted()
	{
		$activities = self::getDataActivities();

		$activity1 = new \odTimeTracker\Model\ActivityEntity($activities[0]);
		$this->assertEquals('5.10.2011 13:00', $activity1->getStoppedFormatted());

		$activity2 = new \odTimeTracker\Model\ActivityEntity($activities[1]);
		$this->assertEquals('5.10.2011 21:15', $activity2->getStoppedFormatted());

		$activity3 = new \odTimeTracker\Model\ActivityEntity($activities[2]);
		$this->assertEquals('6.10.2011 19:45', $activity3->getStoppedFormatted());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getStoppedRfc3339
	 */
	public function testGetStoppedRfc3339()
	{
		$activities = self::getDataActivities();

		$activity1 = new \odTimeTracker\Model\ActivityEntity($activities[0]);
		$this->assertEquals('2011-10-05T13:00:00+01:00', $activity1->getStoppedRfc3339());

		$activity2 = new \odTimeTracker\Model\ActivityEntity($activities[1]);
		$this->assertEquals('2011-10-05T21:15:30+01:00', $activity2->getStoppedRfc3339());

		$activity3 = new \odTimeTracker\Model\ActivityEntity($activities[2]);
		$this->assertEquals('2011-10-06T19:45:00+01:00', $activity3->getStoppedRfc3339());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::setStarted
	 */
	public function testSetStarted()
	{
		$activity = new \odTimeTracker\Model\ActivityEntity(array('Name' => 'Test activity'));

		$activity->setStarted('2011-10-10T10:00:00+01:00');
		$this->assertEquals(new \DateTime('2011-10-10T10:00:00+01:00'), $activity->getStarted());

		$activity->setStarted(new \DateTime('2011-10-10T10:00:00+01:00'));
		$this->assertEquals(new \DateTime('2011-10-10T10:00:00+01:00'), $activity->getStarted());

		$activity->setStarted(null);
		$this->assertNull($activity->getStarted());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::__construct
	 */
	public function testConstructWithProject()
	{
		$activity = new \odTimeTracker\Model\ActivityEntity(array(
			'ActivityId' => 1,
			'ProjectId' => 1,
			'Name' => 'Test activity',
			'Description' => 'Description of the test activity.',
			'Tags' => 'tag1,tag3',
			'Started' => '2011-10-06T17:00:00+01:00',
			'Stopped' => '2011-10-06T19:00:00+01:00',
			'Project.ProjectId' => 1,
			'Project.Name' => 'Test project #1',
			'Project.Description' => 'The first test project.',
			'Project.Created' => '2011-10-06T16:45:00+01:00'
		));

		$this->assertInstanceOf('\odTimeTracker\Model\ActivityEntity', $activity);
		$this->assertEquals('Test activity', $activity->getName());
		$this->assertInstanceOf('\odTimeTracker\Model\ProjectEntity', $activity->getProject());
		$this->assertEquals('Test project #1', $activity->getProject()->getName());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::isRunning
	 */
	public function testIsRunning()
	{
		$activity1 = new \odTimeTracker\Model\ActivityEntity(array(
			'Name' => 'Test activity',
			'Started' => '2015-06-02 10:00:00+01:00',
			'Stopped' => '2015-06-03 11:01:01+01:00'
		));
		$this->assertFalse($activity1->isRunning());

		$activity2 = new \odTimeTracker\Model\ActivityEntity(array(
			'Name' => 'Test activity',
			'Started' => '2015-06-03 10:00:00+01:00',
			'Stopped' => null
		));
		$this->assertTrue($activity2->isRunning());
	}
}