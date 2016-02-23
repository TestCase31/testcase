<?php
/*
	Date create: 23.02.2016
	Author: AD
	Description: TestCase site MySQL connection functions
				 Must be rewrited to mysqli_* or PDO before go on PHP 7.0.0
*/
$wstc_conn = false;
	/*
		Author: AD
		Name: GetConn
		Params: null
		Description: depricate view any connection parameters (like a singletone =] for connect once per script)
		Returns: fill resource connection link to database and returns (GV) connection
	*/
	function GetConn()
    {
		global $wstc_conn;
		if($wstc_conn) {
			return $wstc_conn;
		}
		$wstc_database = "ws_testcase";
		$wstc_user = "testcase_user";
		$wstc_pass = "superpass";
		$wstc_conn = mysql_connect('127.0.0.1', $wstc_user, $wstc_pass);
		if (!$wstc_conn) {
			die("2001, Ресурс не доступен");
		}
		mysql_select_db($wstc_database, $wstc_conn) or die('2002, Ресурс не доступен полностью');
		mysql_set_charset('cp1251');
		return $wstc_conn;
	}

?>