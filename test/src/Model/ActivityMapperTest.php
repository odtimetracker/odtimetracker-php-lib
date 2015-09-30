<?php
/**
 * odtimetracker-php-cgi
 *
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 */

namespace odTimeTrackerTest\Model;

/**
 * @covers \odTimeTracker\Model\ActivityMapper
 */
class ActivityMapperTest extends \odTimeTrackerTest\AbstractModelTestCase
{
	/**
	 * @covers \odTimeTracker\Model\ActivityMapper::insert
	 */
	public function testInsert()
	{
		$mapper = new \odTimeTracker\Model\ActivityMapper(self::$pdo);

		foreach (self::getDataActivities() as $data) {
			$activity = $mapper->insert(new \odTimeTracker\Model\ActivityEntity($data));
			$this->assertInstanceOf('\odTimeTracker\Model\ActivityEntity', $activity);
		}

		$results = $mapper->selectAll();
		$this->assertEquals(7, count($results));

		// Test failure
		$res = $mapper->insert(new \odTimeTracker\Model\ActivityEntity(array('Name' => 'Test name')));
		$this->assertFalse($res);

		$results = $mapper->selectAll();
		$this->assertEquals(7, count($results));
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityMapper::update
	 */
	public function testUpdate()
	{
		$mapper = new \odTimeTracker\Model\ActivityMapper(self::$pdo);
		$activities = self::getDataActivities();

		$activity1 = new \odTimeTracker\Model\ActivityEntity($activities[0]);
		$activity1->setName('(Updated) '.$activity1->getName());
		$activity1->setDescription('(Updated) '.$activity1->getDescription());

		$res1 = $mapper->update($activity1);
		$this->assertInstanceOf('\odTimeTracker\Model\ActivityEntity', $res1);
		$this->assertEquals('(Updated) '.$activities[0]['Name'], $res1->getName());
		$this->assertEquals('(Updated) '.$activities[0]['Description'], $res1->getDescription());

		$activity2 = new \odTimeTracker\Model\ActivityEntity();
		$activity2->setName('Test activity');

		$res2 = $mapper->update($activity2);
		$this->assertFalse($res2);
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityMapper::delete
	 */
	public function testDelete()
	{
		$activities = self::getDataActivities();
		$activity = new \odTimeTracker\Model\ActivityEntity($activities[6]);
		$mapper = new \odTimeTracker\Model\ActivityMapper(self::$pdo);

		$res = $mapper->delete($activity);
		$this->assertTrue($res);

		$results = $mapper->selectAll();
		$this->assertEquals(6, count($results));
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityMapper::deleteById
	 */
	public function testDeleteById()
	{
		$mapper = new \odTimeTracker\Model\ActivityMapper(self::$pdo);

		$res = $mapper->deleteById(6);
		$this->assertTrue($res);

		$results = $mapper->selectAll();
		$this->assertEquals(5, count($results));
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityMapper::selectAll
	 */
	public function testSelectAll()
	{
		$mapper = new \odTimeTracker\Model\ActivityMapper(self::$pdo);
		$activities = $mapper->selectAll();
		$this->assertEquals(5, count($activities));

		$count1 = $count2 = $count3 = 0;

		foreach ($activities as $activity) {
			$this->assertInstanceOf('\odTimeTracker\Model\ActivityEntity', $activity);
			$this->assertInstanceOf('\odTimeTracker\Model\ProjectEntity', $activity->getProject());

			switch ($activity->getProjectId()) {
				case 1: $count1++; break;
				case 2: $count2++; break;
			}

			if (is_null($activity->isRunning())) {
				$count3++;
			}
		}

		$this->assertEquals(4, $count1);
		$this->assertEquals(1, $count2);

		// Test "random" activity
		$activity = $activities[1];
		$this->assertGreaterThanOrEqual(4, $activity->getId());
		$this->assertGreaterThanOrEqual(4, $activity->getActivityId());
		$this->assertEquals('Activity #4', $activity->getName());
		$this->assertEquals('The fourth test activity.', $activity->getDescription());
		$this->assertEquals(null, $activity->getTags());
		$this->assertEquals(new \DateTime('2011-10-07 09:15:00.0000+1:00'), $activity->getStarted());
		$this->assertEquals('7.10.2011 9:15', $activity->getStartedFormatted());
		$this->assertEquals(new \DateTime('2011-10-07 09:19:00.0000+1:00'), $activity->getStopped());
		$this->assertEquals('7.10.2011 9:19', $activity->getStoppedFormatted());
		$this->assertEquals('P0Y0M0DT0H4M0S', $activity->getDuration()->format('P%yY%mM%dDT%hH%iM%sS'));
		$this->assertEquals('4 minutes', $activity->getDurationFormatted());
		$this->assertTrue($activity->isWithinOneDay());
		$this->assertEquals(1, $activity->getProjectId());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityMapper::selectById
	 */
	public function testSelectById()
	{
		$mapper = new \odTimeTracker\Model\ActivityMapper(self::$pdo);
		$activity = $mapper->selectById(5);

		$this->assertEquals(5, $activity->getId());
		$this->assertEquals(5, $activity->getActivityId());
		$this->assertEquals('Activity #5', $activity->getName());
		$this->assertNull($activity->getDescription());
		$this->assertEquals('', $activity->getTags());
		$this->assertEquals(new \DateTime('2011-10-07 10:01:10.00+1:00'), $activity->getStarted());
		$this->assertEquals('7.10.2011 10:01', $activity->getStartedFormatted());
		$this->assertEquals(new \DateTime('2011-10-07 10:31:06.00+1:00'), $activity->getStopped());
		$this->assertEquals('7.10.2011 10:31', $activity->getStoppedFormatted());
		$this->assertEquals('P0Y0M0DT0H29M56S', $activity->getDuration()->format('P%yY%mM%dDT%hH%iM%sS'));
		$this->assertEquals('29 minutes', $activity->getDurationFormatted());
		$this->assertTrue($activity->isWithinOneDay());
		$this->assertEquals(2, $activity->getProjectId());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityMapper::selectRecentActivities
	 */
	public function testSelectRecentActivities()
	{
		$mapper = new \odTimeTracker\Model\ActivityMapper(self::$pdo);
		$activities = $mapper->selectRecentActivities(2);
		$this->assertEquals(2, count($activities));

		foreach ($activities as $activity) {
			$this->assertInstanceOf('\odTimeTracker\Model\ActivityEntity', $activity);
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityMapper::selectRunningActivity
	 */
	public function testSelectRunningActivity()
	{
		$mapper = new \odTimeTracker\Model\ActivityMapper(self::$pdo);

		// 1) There is no running activity
		$activity1 = $mapper->selectRunningActivity();
		$this->assertNull($activity1);

		// 2) Create new running activity
		$activity2 = $mapper->insert(new \odTimeTracker\Model\ActivityEntity(array(
			'ProjectId' => 2,
			'Name' => 'Test running #1',
			'Started' => new \DateTime()
		)));
		$this->assertInstanceOf('\odTimeTracker\Model\ActivityEntity', $activity2);
		$this->assertEquals(8, $activity2->getActivityId());

		// 3) And try to select it
		$activity3 = $mapper->selectRunningActivity();
		$this->assertInstanceOf('\odTimeTracker\Model\ActivityEntity', $activity3);
		$this->assertEquals($activity2->getName(), $activity3->getName());

		// 4) Now we should have six activities
		$activities = $mapper->selectAll();
		$this->assertEquals(6, count($activities)); 
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityMapper::selectActivitiesForInterval
	 */
	public function testSelectActivitiesForInterval()
	{
		$mapper = new \odTimeTracker\Model\ActivityMapper(self::$pdo);

		$activities = $mapper->selectActivitiesForInterval('2011-10-05', '2011-10-06');
		$this->assertEquals(2, count($activities));

		foreach ($activities as $activity) {
			$this->assertInstanceOf('\odTimeTracker\Model\ActivityEntity', $activity);
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityMapper::startActivity
	 */
	public function testStartActivity()
	{
		$mapper = new \odTimeTracker\Model\ActivityMapper(self::$pdo);

		// 1) We can not just start new activity - there is one running from `testSelectRunningActivity`
		$res1 = $mapper->startActivity('New test activity', 2);
		$this->assertFalse($res1);

		// 2) Select running activity and delete it
		$activity = $mapper->selectRunningActivity();
		$this->assertInstanceOf('\odTimeTracker\Model\ActivityEntity', $activity);
		$res2 = $mapper->delete($activity);
		$this->assertTrue($res2);

		// 3) Start new activity again
		$res3 = $mapper->startActivity('New test activity', 2);
		$this->assertInstanceOf('\odTimeTracker\Model\ActivityEntity', $res3);

		// 4) Now we should have still six activities
		$activities = $mapper->selectAll();
		$this->assertEquals(6, count($activities)); 

		// 5) Start activity again - this should ends in failure
		$res4 = $mapper->startActivity('Another new activity', 2);
		$this->assertFalse($res4);

		// 6) Start activity again - this should fails as the one before
		//    (because it has no project).
		$res5 = $mapper->startActivity('Yet another new activity', null);
		$this->assertFalse($res5);
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityMapper::stopRunningActivity
	 */
	public function testStopRunningActivity()
	{
		$mapper = new \odTimeTracker\Model\ActivityMapper(self::$pdo);

		// 1) Stop running activity (from `testStartActivity`)
		$res1 = $mapper->stopRunningActivity();
		$this->assertTrue($res1);

		// 2) Now is there nothing to stop
		$res2 = $mapper->stopRunningActivity();
		$this->assertFalse($res2);
	}
}
