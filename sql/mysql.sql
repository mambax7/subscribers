CREATE TABLE `subscribers_user` (
  `user_id`      INT(11)      NOT NULL AUTO_INCREMENT,
  `user_email`   VARCHAR(100) NOT NULL DEFAULT '',
  `user_name`    VARCHAR(100) NOT NULL DEFAULT '',
  `user_country` VARCHAR(100) NOT NULL DEFAULT '',
  `user_phone`   VARCHAR(100) NOT NULL DEFAULT '',
  `user_created` INT(11)      NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
)
  ENGINE = MyISAM;

CREATE TABLE `subscribers_waiting` (
  `wt_id`       INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `wt_subject`  VARCHAR(150)     NOT NULL DEFAULT '',
  `wt_body`     TEXT             NOT NULL,
  `wt_toemail`  VARCHAR(100)     NOT NULL DEFAULT '',
  `wt_toname`   VARCHAR(100)     NOT NULL DEFAULT '',
  `wt_created`  INT(11)          NOT NULL DEFAULT '0',
  `wt_priority` INT(5)           NOT NULL DEFAULT '0',
  PRIMARY KEY (`wt_id`)
)
  ENGINE = MyISAM;
