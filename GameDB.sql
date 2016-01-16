<?sql


CREATE TABLE IF NOT EXISTS Players(
  PId  INT(11) UNSIGNED AUTO_INCREMENT NOT NULL,      
  Name   VARCHAR(35) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,     
  PRIMARY KEY ( PId ),
  )ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_swedish_ci;   


CREATE TABLE IF NOT EXISTS Game (
  GId  int(8) UNSIGNED AUTO_INCREMENT NOT NULL,      
  GameName VARCHAR( 35 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,     
  MaxPlayers INT(3) UNSIGNED NOT NULL,     
  PRIMARY KEY ( GId )
  )ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_swedish_ci;   

/*  This table stores Tournament/Game Playthrough information */
/*  The Playthrough Id is semi unique and will be generated outside of the table.  */
/*  For the game of War it is expected that one record will be created for each player that will each use the same Tournament/Playthrough Id   */
/*  Each separate playthrough for each pair of players will have a unique playthrough Id */
CREATE TABLE IF NOT EXISTS Playthrough (
  PId  int(11) UNSIGNED NOT NULL,      
  PlayerId INT(11) UNSIGNED NOT NULL,     
  GameId INT(8) UNSIGNED NOT NULL,
  Win BOOLEAN NOT NULL DEFAULT false,  /*Indicates if the player won the complete Playthrough  */
  beginTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  endTime TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  INDEX  `gameIndex`  ( PId, PlayerId, GameId ),  /*  useful for retrieving information for Ranking different players  */
  PRIMARY KEY ( PId, PlayerId, GameId),
  FOREIGN KEY (PlayerId) REFERENCES Players(PId),
  FOREIGN KEY (GameId) REFERENCES Game(GId)
  )ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_swedish_ci;   
  

/*  This table stores the records for individual plays within each Playthrough/tournament.  */
/*  These records are expected to be the most numerous so certain non-normalized fields are included to facilitate data access rather than storage  */

/*  The Challenge Id is semi unique and will be generated outside of the table based upon the unique PlaythroughId that it is used in.  */
/*  For the game of War it is expected that one record will be created for each separate player that will both use the same challenge Id   */
/*  Each separate challenge for each pair of players will have a unique playthrough Id */

CREATE TABLE IF NOT EXISTS Challenge (
 chId  int(11) UNSIGNED NOT NULL,      
/*  ProductSKU   VARCHAR(35) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,  */ 
  Score TINYINT(3) UNSIGNED NOT NULL,
  Win BOOLEAN NOT NULL DEFAULT false,  /*  Indicates whether the player won the individual challenge / match  */
  opvTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PlayerId INT(11) UNSIGNED NOT NULL,     /*  This foreign key is included to facilitate single table selects which will make things easier when the table grows really big  */ 
  PlaythroughId  INT(11) UNSIGNED NOT NULL,      
  UNIQUE KEY (chId, UserId),
  UNIQUE KEY (chId, PlaythroughId),  /* challenges and playthroughs are a unique combination  */
  FOREIGN KEY (PlayerId) REFERENCES Players(PId),
  FOREIGN KEY (PlaythroughId) REFERENCES Playthrough(PId),
  FOREIGN KEY (ProductSKU) REFERENCES Products(SKU),
  INDEX  `inPlayIndex`  ( chId, UserId, PlaythroughId ),  /*  useful if you need to access data about a challenge while in the middle of or at the end of a challenge  */
  INDEX  `LeaderboardStatisticIndex`  ( chId, UserId, PlaythroughId, Score Win, opvTime )  /*  useful for retrieving information for Ranking different players  */
) ; 
