/**
 * Author:  chistinetaguan
 * Created: Nov 30, 2024
 */

-- Run the following sql commands in mysqlworkbench while logged in as root
-- pre-requisites:
--     create user using the AlbumSharing app with userId=admin1 password=AdminPa$$w0rd1
--     create user using AlbumSharing app with userId=ctaguan password=Pa$$w0rdTaguan

USE cst8257project;

ALTER TABLE user
ADD COLUMN isAdmin BOOL DEFAULT 0;

-- set isAdmin to true for admin1
UPDATE user
SET isAdmin=1
WHERE UserId='admin1';

-- create user ADMINSCRIPTUSER in mysql
CREATE USER 'ADMINSCRIPTUSER'@'localhost' IDENTIFIED BY 'password1234';
GRANT ALL ON cst8257project TO 'ADMINSCRIPTUSER'@'localhost';
FLUSH PRIVILEGES;

-- create user ctaguan in mysql
CREATE USER 'ctaguan'@'localhost' IDENTIFIED BY 'password1234';
GRANT ALL ON cst8257project TO 'ADMINSCRIPTUSER'@'localhost';
FLUSH PRIVILEGES;

-- create albumsAudit log table for monitoring of album delete action
CREATE TABLE albumsAudit(
auditId int(10) PRIMARY KEY AUTO_INCREMENT,
whoDeleted varchar(255),
whenDeleted datetime,
whatDeleted varchar(255),
whoOwnsDeleted varchar(255)
);

-- create trigger for album delete action
DROP TRIGGER IF EXISTS cst8257project.albumsAuditTrigger;
CREATE TRIGGER albumsAuditTrigger AFTER DELETE on album
FOR EACH ROW
	INSERT INTO albumsAudit VALUES(auditId,user(),current_time(),concat('album id:',old.Album_Id, ' album title:',old.Title),old.Owner_Id);


