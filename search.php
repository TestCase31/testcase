<?php
/*
	Date create: 23.02.2016
	Author: AD
	Description: page for catalog parametric search
*/
//AD: requre connection
require_once 'mysql_connect.php';
//AD: requre functions
require_once 'tc_functions.php';
?>

<html><body>
<table width=50% height=100% border="0" align="center">
<tr><td align="center" height="20%" valign="top" bgcolor="#FFFFE0">
<div align="right"><a href="/admin.php"><img src="http://www.iconsearch.ru/uploads/icons/humano2/24x24/preferences-system-windows-actions.png" border="0"></a></div>
<table width="60%" height="200" border="0"><tr><td align="left">
<form name="search_form">
<?php
	//AD: process $_GET values for selected checkboxes and create two dimensions array for keep id of parametr types and id of parameters
	$ls_idprm = array();
	foreach($_GET as $cbname => $cbvalue) {
		if(strpos($cbname, "fcb_") >= 0) {
			foreach($cbvalue as $key => $value) {
				$cbid = (int) substr(mysql_real_escape_string($cbname, GetConn()), 4);
				if(!$ls_idprm[$cbid]) {
					$ls_idprm[$cbid] = array();
				}
				array_push($ls_idprm[$cbid], mysql_real_escape_string($value, GetConn()));
			}
		}
	}

	$ft_notlist  = array();
	//AD: if some checkboxes selected then fill array of excepted values
	if(count($ls_idprm) > 0) {
		$sqb = "";
		$sqw = "";
		$sqi = 0;

		foreach($ls_idprm as $key => $value) {
			$sqb = sprintf("%s JOIN tc_prm as b%d on b%d.lnk_cat = a.id_cat ", $sqb, $key, $key);
			$sqw = sprintf("%s %s b%d.lnk_prm in (%s) ", $sqw, ($sqi > 0)?"and ":"", $key, implode(',', $value));
			$sqi++;
		}

		$subquery = sprintf('where a.id_cat not in (select a.id_cat from tc_catalog as a %s where %s)', $sqb, $sqw);
		$query = sprintf('SELECT DISTINCT ab.lnk_prm
							FROM tc_catalog as a
							LEFT JOIN tc_prm as ab on ab.lnk_cat = a.id_cat
							LEFT JOIN tc_prm_type as ac on ac.id_prm_type = ab.id_prm_type
							LEFT JOIN tc_prm as ad on ad.id_prm = ab.lnk_prm
							LEFT JOIN (SELECT a.id_prm, a.v_prm, a.lnk_prm, a.lnk_cat, count(a.id_prm) as dr_cnt FROM tc_prm as a GROUP BY lnk_cat) as ae on ae.lnk_cat = ab.lnk_cat
							%s', (count($ls_idprm) > 0)?$subquery:"");

		$res = mysql_query($query, GetConn());
		if (!$res) {
		    $res = "2201, Ошибка обработки";
		    die($res);
		}
		
		while ($row = mysql_fetch_array($res)) {
			array_push($ft_notlist, $row["lnk_prm"]);
		}
	}

	//AD: select data for print filtered values
	$query = sprintf('SELECT a.id_prm_type, a.n_prm_type, b.id_prm, b.v_prm
						FROM `tc_prm_type` as a
						JOIN  `tc_prm` as b ON b.id_prm_type = a.id_prm_type
						WHERE a.f_prm = 1
						  and b.lnk_prm is null
						  and b.lnk_cat is null
						ORDER BY a.n_prm_type, b.v_prm');
	$res = mysql_query($query, GetConn());
	if (!$res) {
	    $res = "2201, Ошибка обработки";
	    die($res);
	}

	$ft_idprmtype = 0;
	//AD: print data for search case checkboxes
	while ($row = mysql_fetch_array($res)) {
		$ft_checked = "";
		if(array_key_exists($row["id_prm_type"], $ls_idprm)) {
			foreach ($ls_idprm[$row["id_prm_type"]] as $key => $value) {				
				if($value == $row["id_prm"]) {
					$ft_checked = " checked";
					break;
				}
			}
		}
		$ft_gray = '<font color="%s">%s</font>';
		//AD: set color for exclusive checkboxes if they found
		if(in_array($row["id_prm"], $ft_notlist, true)) {
			$ft_gray = sprintf($ft_gray, "#FF0000", $row["v_prm"]);
		} else {
			$ft_gray = sprintf($ft_gray, "#000000", $row["v_prm"]);
		}
		
		//AD: if there is new group then make new row and print type name otherwise proceed print checkboxes
		if($row["id_prm_type"] != $ft_idprmtype) {
			$ft_idprmtype = $row["id_prm_type"];
			printf("\r\n".'<br><b>%s</b>: <input type="checkbox" name="fcb_%s[]" value="%s"%s>%s',
				   $row["n_prm_type"],
				   $row["id_prm_type"],
				   $row["id_prm"],
				   $ft_checked,
				   $ft_gray);
		} else {
			printf('<input type="checkbox" name="fcb_%s[]" value="%s"%s>%s',					   
				   $row["id_prm_type"],
				   $row["id_prm"],
				   $ft_checked,
				   $ft_gray);
		}
	}	
?>
<br><br><input type="submit" value="Поиск">
</form>
</td></tr>
</table>
</td></tr>
<tr><td align=center height="80%" valign="top" bgcolor="#FFFAFA">
<table width="70%">
<?php
	$sqb = "";
	$sqw = "";
	$sqi = 0;
	//AD: prepare query for select filtered products or select they all
	foreach($ls_idprm as $key => $value) {
		$sqb = sprintf("\r\n%s join tc_prm as b%d on b%d.lnk_cat = a.id_cat ", $sqb, $key, $key);
		$sqw = sprintf("\r\n%s %s b%d.lnk_prm in (%s) ", $sqw, ($sqi > 0)?"and ":"", $key, implode(',', $value));
		$sqi++;
	}

	$subquery = sprintf('WHERE a.id_cat in (SELECT a.id_cat FROM tc_catalog as a %s where %s)', $sqb, $sqw);
	$query = sprintf('SELECT a.id_cat, a.id_type_cat, a.n_cat, ac.id_prm_type, ac.n_prm_type, coalesce(ac.f_prm, -1) as f_prm,
						coalesce(ad.v_prm, concat(lcase(ac.n_prm_type),\' \',ab.v_prm)) v_prm, coalesce(ab.lnk_prm, ab.v_prm) prm_val,
						coalesce(ae.dr_cnt + 1, 1) dr_cnt
					  FROM tc_catalog as a
					  LEFT JOIN tc_prm as ab on ab.lnk_cat = a.id_cat
					  LEFT JOIN tc_prm_type as ac on ac.id_prm_type = ab.id_prm_type
					  LEFT JOIN tc_prm as ad on ad.id_prm = ab.lnk_prm
					  LEFT JOIN (SELECT a.id_prm, a.v_prm, a.lnk_prm, a.lnk_cat, count(a.id_prm) as dr_cnt FROM tc_prm as a GROUP BY lnk_cat) as ae on ae.lnk_cat = ab.lnk_cat
					%s',
					(count($ls_idprm) > 0)?$subquery:"");

	$res = mysql_query($query, GetConn());
	if (!$res) {
	    $res = "2201, Ошибка обработки";
	    die($res);
	}

	//AD: print catalog products and their parameters
	$ft_idcat = 0;
	while ($row = mysql_fetch_array($res)) {			
		if($row["id_cat"] != $ft_idcat) {
			
			if($ft_idcat > 0) {
				printf("<br><br></td></tr>");
			}
			$ft_idcat = $row["id_cat"];
			printf("\r\n".'<tr><td bgcolor="#EEEEEE"><h3>%s</h3></td></tr><tr><td>%s',
				   $row["n_cat"], 
				   $row["v_prm"]);
		} else {
			printf(', %s',					   
				   $row["v_prm"]);
		}
	}
	printf("<br></td></tr>");
?>
</table>
</td></tr>
</table>

</body></html>