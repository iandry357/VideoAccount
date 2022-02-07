-- => Create table `User` (int id, varchar name, varchar pwd)
-- => Create table Video (int id, varchar name, varchar link, int id_user )
-- => Create table UserVideoAction (int id, int id_user, int id_video, int typeAction, varchar value)

DROP TABLE IF EXISTS Users;
CREATE TABLE IF NOT EXISTS Users (
  userId int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  hashed_password varchar(100) NOT NULL,
  PRIMARY KEY (userId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS Videos;
CREATE TABLE IF NOT EXISTS Videos (
  videoId int(11) NOT NULL AUTO_INCREMENT,
  userId int(11) NOT NULL,
  name varchar(100) NOT NULL,
  link varchar(100) NOT NULL,
  PRIMARY KEY (videoId),
  FOREIGN KEY (userId) REFERENCES Users(`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create table UserVideoAction (int id, int id_user, int id_video, int typeAction, varchar value)
DROP TABLE IF EXISTS UserVideoAction;
CREATE TABLE IF NOT EXISTS UserVideoAction (
  id int(11) NOT NULL AUTO_INCREMENT,
  userId int(11) NOT NULL,
  videoId int(11) NOT NULL,
  typeActionId int(11) NOT NULL,
  `value` text, 
  PRIMARY KEY (id),
  FOREIGN KEY (typeActionId) REFERENCES `Action`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Action`;
CREATE TABLE IF NOT EXISTS `Action` (
  id int(11) NOT NULL AUTO_INCREMENT,
  typeAction varchar(255),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into `Action` (typeAction) values 
("like"), ("dislike"), ("comment");


