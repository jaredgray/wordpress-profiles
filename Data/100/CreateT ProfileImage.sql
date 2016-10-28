CREATE TABLE IF NOT EXISTS `ProfileImage` (
  `ProfileImageID` INT NOT NULL AUTO_INCREMENT ,
  `ProfileID` INT NOT NULL ,
  `ImageType` INT NOT NULL ,
  `ImagePath` VARCHAR(400) NOT NULL ,
  `ThumbnailPath` VARCHAR(400) NOT NULL ,
  `ImageSize` INT NOT NULL ,
  `Order` INT NOT NULL ,
  PRIMARY KEY (`ProfileImageID`) ,
  UNIQUE INDEX `ProfileImageID_UNIQUE` (`ProfileImageID` ASC) ,
  INDEX `FK_ProfileImage_Profile_idx` (`ProfileID` ASC) ,
  CONSTRAINT `FK_ProfileImage_Profile`
    FOREIGN KEY (`ProfileID` )
    REFERENCES `profile` (`ProfileID` )

    ON DELETE NO ACTION

    ON UPDATE NO ACTION);