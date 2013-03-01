<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>Installing Comments</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<h1>Installing Comments</h1>

<p>Run the following MySQL queries as the root user:</p>

<pre>CREATE DATABASE `comments` DEFAULT CHARACTER SET `utf8`;

GRANT SELECT, INSERT, UPDATE, DELETE on `comments`.* to `commentsUser`@localhost IDENTIFIED BY 'commentsPass';

CREATE TABLE `comment` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` text,
  `user` text,
  `comment` text,
  `date_left` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`)
);
</pre>

<p>Edit the config.php file to use the database, username and password above.  Set the SCRIPT_PATH option to be the path to this directory.</p>

<p>Then add the following line to any page where you want comments: (Any page you add this to will need to be a PHP executable file.)</p>

<pre>
&lt;? include_once('comments.php'); ?&gt;
</pre>


<? include_once('comments.php'); ?>

</body>
</html>