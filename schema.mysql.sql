--
-- odtimetracker-php-lib 
--
-- @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
-- @author Ondřej Doněk, <ondrejd@gmail.com>
-- @link https://github.com/odTimeTracker/odtimetracker-php-lib
--

CREATE TABLE IF NOT EXISTS `Projects` (
    `ProjectId` INTEGER NOT NULL AUTO_INCREMENT, 
    `Name` TEXT,
    `Description` TEXT,
    `Created` TEXT NOT NULL,
    PRIMARY KEY (`ProjectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `Activities` (
    `ActivityId` INTEGER NOT NULL AUTO_INCREMENT, 
    `ProjectId` INTEGER NOT NULL,
    `Name` TEXT,
    `Description` TEXT,
    `Tags` TEXT,
    `Started` TEXT NOT NULL ,
    `Stopped` TEXT,
    PRIMARY KEY (`ActivityId`),
    FOREIGN KEY(`ProjectId`) REFERENCES `Projects`(`ProjectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

