--
-- odtimetracker-php-lib 
--
-- @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
-- @author Ondřej Doněk, <ondrejd@gmail.com>
-- @link https://github.com/odTimeTracker/odtimetracker-php-lib
--

CREATE TABLE IF NOT EXISTS `Projects` (
    `ProjectId` INTEGER PRIMARY KEY AUTOINCREMENT, 
    `Name` TEXT,
    `Description` TEXT,
    `Created` TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS `Activities` (
    `ActivityId` INTEGER PRIMARY KEY AUTOINCREMENT, 
    `ProjectId` INTEGER NOT NULL,
    `Name` TEXT,
    `Description` TEXT,
    `Tags` TEXT,
    `Started` TEXT NOT NULL,
    `Stopped` TEXT NOT NULL DEFAULT '',
    FOREIGN KEY(`ProjectId`) REFERENCES `Projects`(`ProjectId`)
);
