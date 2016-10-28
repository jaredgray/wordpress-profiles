CREATE TABLE IF NOT EXISTS `Profile` (
  `ProfileID` INT NOT NULL AUTO_INCREMENT ,
  `UserID` INT NOT NULL ,
  `IsActive` BIT NOT NULL ,
  `DisplayOrder` INT NOT NULL ,
  `DisplayName` VARCHAR(100) NULL ,
  `Blurb` VARCHAR(4000) NULL ,
  `Biography` VARCHAR(8000) NULL ,
  UNIQUE INDEX `ProfilePluginID_UNIQUE` (`ProfileID` ASC) ,
  PRIMARY KEY (`ProfileID`) );