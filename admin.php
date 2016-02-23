<?
/*
	Date create: 23.02.2016
	Author: AD
	Description: admin page for edit catalog
*/
require_once 'mysql_connect.php';
require_once 'tc_functions.php';
?>
<?php 
//AD: no comments becourse there is not in test case
?>
<html>
<body>
<script type="text/javascript">
function deletebyid(catid) {
	
	try {
		dfo = document.forms["edit_form"];
		df = dfo.elements;
		df.namedItem("do").value = "del";
		df.namedItem("n_type").value = catid;
		dfo.submit();
	} catch(e) {alert(e);}

}
</script>
<table width=50% height=100% border=1 align="center">
<tr><td height="30">
<a href="admin.php?act=edit_cat">Записи каталога</a>
</td>
<td>
<a href="admin.php?act=edit_type_cat">Типы каталога</a>
</td>
<td>
<a href="admin.php?act=edit_prm_type">Параметры каталога</a>
</td>
<td>
<a href="admin.php?act=edit_prm_cat">Значения</a>
</td></tr>
<tr><td colspan="4" valign="top" height="50">
<?php
switch($_GET["act"]) {
	
	case "edit_type_cat":
	if($_GET["do"] === "add") {
		$tc_res = ftc_add_type_cat($_GET["n_type"]);
		if(is_string($tc_res)) {
			die($tc_res);
		}
	}
if($_GET["do"] === "del") {
		$tc_res = ftc_del_type_cat($_GET["n_type"]);
		if(is_string($tc_res)) {
			die($tc_res);
		}
	}	
?>
<form method="GET" name="edit_form">
<input type="hidden" name="act" value="<?php echo $_GET["act"];?>" />
<input type="hidden" name="do" value="add" />
Название категории:<input type="text" name="n_type" value="" width="300" maxlength="2000"></input>
<br>
<input type="submit" value="Добавить"></input>
</form>
</td></tr>
<tr><td colspan="4" valign="top">
<?php
$tc_res = ftc_fetch_type_cat();
if(!is_string($tc_res)) {
	while ($row = mysql_fetch_array($tc_res)) {
	    printf("(%s) %s (<a href=# onclick=\"deletebyid(%s)\">del</a>);", $row[0], $row[1], $row[0]);
	}
} else {
	echo "<br>".$tc_res;
}
?>
<?php
	break;
	case "edit_prm_type":
	
	if($_GET["do"] === "add") {
		$tc_res = ftc_add_prm_type_cat($_GET["n_type"], $_GET["f_prm"]);
		if(is_string($tc_res)) {
			die($tc_res);
		}
	}
if($_GET["do"] === "del") {
		$tc_res = ftc_del_prm_type_cat($_GET["n_type"]);
		if(is_string($tc_res)) {
			die($tc_res);
		}
	}	
?>
<form method="GET" name="edit_form">
<input type="hidden" name="act" value="<?php echo $_GET["act"];?>" />
<input type="hidden" name="do" value="add" />
Название параметра: <input type="text" name="n_type" value="" width="300" maxlength="2000"></input>
Фильтруется: <input type="checkbox" name="f_prm"></input>
<br>
<input type="submit" value="Добавить"></input>
</form>
</td></tr>
<tr><td colspan="4" valign="top">
<?php
$tc_res = ftc_fetch_prm_type_cat();
if(!is_string($tc_res)) {
	while ($row = mysql_fetch_array($tc_res)) {
	    printf("(%s) %s (<a href=# onclick=\"deletebyid(%s)\">del</a>), [%s];&nbsp;&nbsp;&nbsp;", $row[0], $row[1], $row[0], ($row[2] == "1")?"Ф":"Н/Ф");
	}
} else {
	echo "<br>".$tc_res;
}
?>
<?php	
	break;
	case "edit_prm_cat":
	
	if($_GET["do"] === "add") {
		$tc_res = ftc_add_prm_cat($_GET["id_prm_type"], $_GET["n_type"]);
		if(is_string($tc_res)) {
			die($tc_res);
		}
	}
if($_GET["do"] === "del") {
		$tc_res = ftc_del_prm_cat($_GET["n_type"]);
		if(is_string($tc_res)) {
			die($tc_res);
		}
	}	
?>
<form method="GET" name="edit_form">
<input type="hidden" name="act" value="<?php echo $_GET["act"];?>" />
<input type="hidden" name="do" value="add" />
<select name="id_prm_type">
<?php
$res = mysql_query("SELECT id_prm_type, n_prm_type, f_prm FROM tc_prm_type WHERE f_prm = 1 ORDER BY n_prm_type", GetConn());
if($res) {	
	$rs_spanned = 0;
	$rs_text = "";
	while ($row = mysql_fetch_array($res)) {
			printf("<option value=\"%d\">%s</option>", $row["id_prm_type"], $row["n_prm_type"]);
	}
}
?>
</select>
Название параметра: <input type="text" name="n_type" value="" width="300" maxlength="2000"></input>
<br>
<input type="submit" value="Добавить"></input>
</form>
</td></tr>
<tr><td colspan="4" valign="top">
	<table border="2">
<?php

$tc_res = ftc_fetch_prm_cat();
if(!is_string($tc_res)) {
	$rs_spanned = 0;
	$rs_cntprm = 0;
	$rs_text = "";
	$rs_idprmtype = 0;
	while ($row = mysql_fetch_array($tc_res)) {
		if( ($row["id_prm_type"] != $rs_idprmtype) ) {
			$rs_idprmtype = (int) $row["id_prm_type"];
			$rs_cntprm = (int) $row["cnt_prm"];
			$rs_spanned = 0;
		}		
	    if( ($rs_spanned == 0) && ($rs_cntprm > 1) ) {	    	
	    	$rs_text = sprintf("<td rowspan=\"%s\">%s</td>", $row["cnt_prm"], $row["n_prm_type"]);
	    	$rs_spanned++;
	    } elseif ( $rs_cntprm == 1 ) {
	    	$rs_text = sprintf("<td>%s</td>", $row["n_prm_type"]);	    		    	
	    } else {
	    	$rs_text = "";
	    }
	    printf("		<tr>%s<td>%s (<a href=# onclick=\"deletebyid(%s)\">del</a>)</td></tr>\r\n", $rs_text, $row["v_prm"], $row["id_prm"]);
	    $rs_spanned++;
	}
} else {
	echo "<br>".$tc_res;
}
?>
	</table>
<?php	
	break;
	case "edit_cat":
	
	if($_GET["do"] === "add") {
		$tc_res = ftc_add_cat($_GET["id_type_cat"], $_GET["n_type"]);
		if(is_string($tc_res)) {
			die($tc_res);
		}
	}
	if($_GET["do"] === "del") {
		$tc_res = ftc_del_prm_cat($_GET["n_type"]);
		if(is_string($tc_res)) {
			die($tc_res);
		}
	}
	if($_GET["do"] === "edit_catprm") {
		$tc_res = ftc_edit_catprm($_GET["id_prm"], $_GET["prm"]);
		if(is_string($tc_res)) {
			die($tc_res);
		}
	}
	if($_GET["do"] === "add_catprm") {
		$tc_res = ftc_add_catprm($_GET["id_cat"], $_GET["prm_type"]);
		if(is_string($tc_res)) {
			die($tc_res);
		}
	}	
?>
<form method="GET" name="edit_form">
<input type="hidden" name="act" value="<?php echo $_GET["act"];?>" />
<input type="hidden" name="do" value="add" />
<select name="id_type_cat">
<?php
$res = mysql_query("SELECT id_type_cat, n_type_cat FROM tc_type_catalog ORDER BY n_type_cat", GetConn());
if($res) {	
	while ($row = mysql_fetch_array($res)) {
			printf("<option value=\"%d\">%s</option>", $row["id_type_cat"], $row["n_type_cat"]);
	}
}
?>
</select>
Название: <input type="text" name="n_type" value="" width="300" maxlength="2000"></input>
<br>
<input type="submit" value="Добавить"></input>
</form>
</td></tr>
<tr><td colspan="4" valign="top">
	<table border="2">
<?php

$tc_res = ftc_fetch_cat();
if(!is_string($tc_res)) {
	$rs_idcat = 0;
	$rs_spanned = 0;
	$rs_cntprm = 0;
	$rs_text = "";
	while ($row = mysql_fetch_array($tc_res)) {
		if( ($row["id_cat"] != $rs_idcat) ) {
			$rs_idcat = (int) $row["id_cat"];
			$rs_cntprm = (int) $row["dr_cnt"];
			$rs_spanned = 0;
		}		
	    if($rs_spanned == 0) {	    	
	    	$rs_text = sprintf("<td rowspan=\"%s\">%s</td>", $row["dr_cnt"], $row["n_cat"]);
	    	$rs_spanned++;
	    } else {
	    	$rs_text = "";
	    }
	    if($row["id_prm_type"]) {
	    	printf("		<tr>%s<td>%s: %s (<a href=# onclick=\"deletebyid(%s)\">del</a>)%s</td></tr>\r\n", $rs_text, $row["n_prm_type"], $row["prm_val"], $row["id_prm"], ftc_print_catprm_editor($_GET["act"],  $row["id_prm"], $row["id_cat"], $row["id_prm_type"]));
		} else {
			printf("		<tr>%s<td>%s: %s</td></tr>\r\n", $rs_text, $row["n_prm_type"], $row["prm_val"], $row["id_prm"]);
		}

	    if( ($rs_spanned + 1 == $rs_cntprm) ) {
?>
	<tr><td>
		<form method="GET" name="add_prm_form">
		Параметр: 
		<select name="prm_type">
<?php
$res = mysql_query("SELECT `id_prm_type`, `n_prm_type` FROM `tc_prm_type` WHERE `id_prm_type` not in (select `id_prm_type` from `tc_prm` where `lnk_cat` = ".$row["id_cat"].") ORDER BY `n_prm_type`", GetConn());
if($res) {	
	while ($sq_row = mysql_fetch_array($res)) {
			printf("<option value=\"%d\">%s</option>", $sq_row["id_prm_type"], $sq_row["n_prm_type"]);
	}
}
?>
		</select>
		<input type="hidden" name="act" value="<?php echo $_GET["act"];?>" />
		<input type="hidden" name="do" value="add_catprm" />
		<input type="hidden" name="id_cat" value="<?php echo $row["id_cat"];?>" />
		<input type="submit" value="Добавить параметр"></input>
		</form>
	</td></tr>
<?php			
	    }
	    $rs_spanned++;
	}
} else {
	echo "<br>".$tc_res;
}
?>
	</table>
<?php
	break;
	default:
		echo $_GET["act"];
	break;
}
?>

</td></tr>
</table>

</body>
</html>