/*
Navicat SQLite Data Transfer

Source Server         : application
Source Server Version : 30706
Source Host           : :0

Target Server Type    : SQLite
Target Server Version : 30706
File Encoding         : 65001

Date: 2012-11-20 09:46:20
*/

PRAGMA foreign_keys = OFF;

-- ----------------------------
-- Table structure for "main"."content"
-- ----------------------------
DROP TABLE "main"."content";
CREATE TABLE "content" (
"id"  INTEGER(10) NOT NULL,
"url"  TEXT(255) NOT NULL,
"title"  TEXT(255),
"content"  TEXT,
"meta_title"  TEXT(255),
"meta_keywords"  TEXT,
"meta_description"  TEXT,
"active"  INTEGER(1) NOT NULL,
"date_created"  TEXT NOT NULL,
"last_updated"  TEXT NOT NULL,
"can_delete"  INTEGER(1) NOT NULL,
"edit_url"  INTEGER(1) NOT NULL,
"full_page"  INTEGER(1) NOT NULL,
PRIMARY KEY ("id")
);

-- ----------------------------
-- Records of content
-- ----------------------------
INSERT INTO "main"."content" VALUES (1, 'about/us', 'About Us', '<p>
  This is the about us page.
</p>', null, null, null, 1, '2012-11-08 23:54:05', '2012-11-09 09:46:37', 1, 1, 1);
INSERT INTO "main"."content" VALUES (2, 'contact-us', 'Contact Us', '<p>
  This is the contact page.
</p>', null, null, null, 1, '2012-11-19 21:18:09', '2012-11-19 21:18:09', 1, 1, 1);

-- ----------------------------
-- Table structure for "main"."migration_version"
-- ----------------------------
DROP TABLE "main"."migration_version";
CREATE TABLE "migration_version" (
"version"  INTEGER(11)
);

-- ----------------------------
-- Records of migration_version
-- ----------------------------
INSERT INTO "main"."migration_version" VALUES (3);

-- ----------------------------
-- Table structure for "main"."sqlite_stat1"
-- ----------------------------
DROP TABLE "main"."sqlite_stat1";
CREATE TABLE sqlite_stat1(tbl,idx,stat);

-- ----------------------------
-- Records of sqlite_stat1
-- ----------------------------
INSERT INTO "main"."sqlite_stat1" VALUES ('users', 'sqlite_autoindex_users_1', '2 1');
INSERT INTO "main"."sqlite_stat1" VALUES ('migration_version', null, 1);
INSERT INTO "main"."sqlite_stat1" VALUES ('content', 'sqlite_autoindex_content_1', '2 1');

-- ----------------------------
-- Table structure for "main"."users"
-- ----------------------------
DROP TABLE "main"."users";
CREATE TABLE "users" (
"id"  INTEGER(10) NOT NULL,
"username"  TEXT(25) NOT NULL,
"password"  TEXT(60),
"firstname"  TEXT(32) NOT NULL,
"lastname"  TEXT(64) NOT NULL,
"email"  TEXT(127) NOT NULL,
"active"  INTEGER(1) NOT NULL,
"date_created"  TEXT NOT NULL,
"last_login"  TEXT,
"accesslevel"  TEXT(50) NOT NULL,
"token"  TEXT(32),
"token_date"  TEXT,
PRIMARY KEY ("id")
);

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO "main"."users" VALUES (1, 'admin', '$2a$12$axpUYoY9uAR.yTw0R9jQL.KuXx41GDTiz6cK96ph575tax7U8Grhy', 'Administrator', 'Administrator', 'admin@example.com', 1, '2012-09-17 22:52:52', '2012-11-20 09:25:18', 'superadmin', null, null);
INSERT INTO "main"."users" VALUES (2, 'testadmin', '$2a$12$TzZ.v/WoAOGdlBXdJ9gbQu9NPq2.piTRsX5iQWDOdYcKbmDDlm8ga', 'Firstname', 'Lastname', 'testadmin@example.com', 1, '2012-11-17 12:50:27', null, 'admin', null, null);
