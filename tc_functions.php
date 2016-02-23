<?

/*
	Date create: 23.02.2016
	Author: AD
	Description: TestCase site functions
*/

require_once 'mysql_connect.php';

/*
	Author: AD
	Name: ftc_fetch_type_cat
	Params: null
	Description: prepare fetch for catalog types
	Returns: fill resource query result or return string exception with id
*/
function ftc_fetch_type_cat()
{	
		$query = sprintf("SELECT `id_type_cat`, `n_type_cat` FROM `tc_type_catalog` ORDER BY `id_type_cat`");
		$res = mysql_query($query, GetConn());
		if (!$res) {
		    $res = "3101, Ошибка обработки";
		    return $res;
		}
		return $res;
}

/*
	Author: AD
	Name: ftc_add_type_cat
	Params: (string)n_type_cat - name for new catalog type
	Description: prepare resource of modifyed data for add catalog type
	Returns: fill resource query result or return string exception with id
*/
function ftc_add_type_cat($n_type_cat)
{
		$query = sprintf("SELECT `tc_getnextseq`() AS `nextseq`");
		$res = mysql_query($query, GetConn());		
		if (!$res) {
			$res = '3201, Ошибка обработки';
		    return $res;
		}
		$row = mysql_fetch_array($res);
		$nextseq = $row["nextseq"];
		mysql_free_result($res);

		$query = sprintf("INSERT INTO `tc_type_catalog`(id_type_cat, n_type_cat) VALUES (%d, '%s')",
    		              $nextseq,
    		              mysql_real_escape_string($n_type_cat, GetConn()));
		$res = mysql_query($query, GetConn());
		if (!$res) {
			$res = "3202, Ошибка обработки";
		    return $res;
		}
		if(mysql_affected_rows(GetConn()) != 1) {
			$res = "3203, Ошибка добавления";
			return $res;
		}
		return $res;
}

/*
	Author: AD
	Name: ftc_del_type_cat
	Params: (int)id_type_cat - id for catalog record
	Description: prepare resource of modifyed data for delete catalog record
	Returns: fill resource query result or return string exception with id
*/
function ftc_del_type_cat($id_type_cat)
{
		$query = sprintf("DELETE FROM `tc_type_catalog` WHERE id_type_cat = %d",
						  mysql_real_escape_string($id_type_cat), GetConn());
		$res = mysql_query($query, GetConn());
		if (!$res) {
		    $res = "3301, Ошибка обработки";
		    return $res;
		}
		if(mysql_affected_rows(GetConn()) != 1) {
			$res = "3302, Ошибка удаления";
			return $res;
		}		
		return $res;
}

/*
	Author: AD
	Name: ftc_fetch_prm_type_cat
	Params: null
	Description: prepare fetch for catalog parameters types
	Returns: fill resource query result or return string exception with id
*/
function ftc_fetch_prm_type_cat()
{
		$query = sprintf("SELECT `id_prm_type`, `n_prm_type`, `f_prm` FROM `tc_prm_type` ORDER BY `id_prm_type`");
		$res = mysql_query($query, GetConn());
		if (!$res) {
		    $res = "3401, Ошибка обработки";
		    return $res;
		}
		return $res;
}

/*
	Author: AD
	Name: ftc_add_prm_type_cat
	Params: (string)n_prm_type - name for new parameter type
			(int)f_prm - switch of parameter data dype 
	Description: prepare resource of modifyed data for add catalog parameter
	Returns: fill resource query result or return string exception with id
*/
function ftc_add_prm_type_cat($n_prm_type, $f_prm)
{
		$query = sprintf("SELECT `tc_getnextseq`() AS `nextseq`");
		$res = mysql_query($query, GetConn());		
		if (!$res) {
			$res = "3501, Ошибка обработки";
		    return $res;
		}
		$row = mysql_fetch_array($res);
		$nextseq = $row["nextseq"];
		mysql_free_result($res);

		$query = sprintf("INSERT INTO `tc_prm_type`(id_prm_type, n_prm_type, f_prm) VALUES (%d, '%s', %d)",
    		              $nextseq,
    		              mysql_real_escape_string($n_prm_type, GetConn()),
    		              ($f_prm && strlen($f_prm) > 0)?"1":"0");
		$res = mysql_query($query, GetConn());
		if (!$res) {
			$res = "3502, Ошибка обработки";
		    return $res;
		}
		if(mysql_affected_rows(GetConn()) != 1) {
			$res = "3503, Ошибка добавления ";
			return $res;
		}
		return $res;
}

/*
	Author: AD
	Name: ftc_del_prm_type_cat
	Params: (int)id_prm_type - id for parameter record
	Description: prepare resource of modifyed data for delete parameter
	Returns: fill resource query result or return string exception with id
*/
function ftc_del_prm_type_cat($id_prm_type)
{
		$query = sprintf("DELETE FROM `tc_prm_type` WHERE `id_prm_type` = %d",
						  mysql_real_escape_string($id_prm_type), GetConn());
		$res = mysql_query($query, GetConn());
		if (!$res) {
		    $res = "3601, Ошибка обработки";
		    return $res;
		}
		if(mysql_affected_rows(GetConn()) != 1) {
			$res = "3602, Ошибка удаления";
			return $res;
		}	
		return $res;
}

/*
	Author: AD
	Name: ftc_fetch_prm_cat
	Params: null
	Description: prepare fetch for catalog parameters
	Returns: fill resource query result or return string exception with id
*/
function ftc_fetch_prm_cat()
{
		$query = sprintf('SELECT a.id_prm, a.id_prm_type, a.v_prm, b.n_prm_type, c.cnt_prm
							FROM `tc_prm` as a
							JOIN `tc_prm_type` as b ON b.id_prm_type = a.id_prm_type
							JOIN (SELECT a.id_prm_type, b.n_prm_type,count(a.id_prm) as cnt_prm
									FROM `tc_prm` as a
									JOIN `tc_prm_type` as b ON b.id_prm_type = a.id_prm_type
									WHERE a.lnk_prm is null
									AND a.lnk_cat is null
									GROUP by a.id_prm_type) as c ON c.id_prm_type = a.id_prm_type
							WHERE a.lnk_prm is null
							AND a.lnk_cat is null
							ORDER BY n_prm_type, v_prm');
		$res = mysql_query($query, GetConn());
		if (!$res) {
		    $res = "3701, Ошибка обработки";
		    return $res;
		}
		return $res;
}

/*
	Author: AD
	Name: ftc_add_prm_cat
	Params: (int)id_prm_type - type of new parameter
			(string)v_prm - name for new parameter
	Description: prepare resource of modifyed data for add parameter value
	Returns: fill resource query result or return string exception with id
*/
function ftc_add_prm_cat($id_prm_type, $v_prm)
{
		$query = sprintf("SELECT `tc_getnextseq`() AS `nextseq`");
		$res = mysql_query($query, GetConn());		
		if (!$res) {
			$res = "3801, Ошибка обработки";
		    return $res;
		}
		$row = mysql_fetch_array($res);
		$nextseq = $row["nextseq"];
		mysql_free_result($res);

		$query = sprintf("INSERT INTO `tc_prm`(id_prm, id_prm_type, v_prm) VALUES (%d, %d, '%s')",
    		              $nextseq,
    		              mysql_real_escape_string($id_prm_type, GetConn()),
    		              mysql_real_escape_string($v_prm, GetConn()));
		$res = mysql_query($query, GetConn());
		if (!$res) {
			$res = "3802, Ошибка обработки";
		    return $res;
		}
		if(mysql_affected_rows(GetConn()) != 1) {
			$res = "3803, Ошибка добавления ";
			return $res;
		}
		return $res;
}

/*
	Author: AD
	Name: ftc_del_prm_cat
	Params: (int)id_prm - id for parameter value record
	Description: prepare resource of modifyed data for delete parameter value
	Returns: fill resource query result or return string exception with id
*/
function ftc_del_prm_cat($id_prm)
{
		$query = sprintf("DELETE FROM `tc_prm` WHERE `id_prm` = %d",
						  mysql_real_escape_string($id_prm), GetConn());
		$res = mysql_query($query, GetConn());
		if (!$res) {
		    $res = "3901, Ошибка обработки";
		    return $res;
		}
		if(mysql_affected_rows(GetConn()) != 1) {
			$res = "3902, Ошибка удаления";
			return $res;
		}	
		return $res;
}

/*
	Author: AD
	Name: ftc_fetch_cat
	Params: null
	Description: prepare fetch for records of catalog and parameters
	Returns: fill resource query result or return string exception with id
*/
function ftc_fetch_cat()
{
		$query = sprintf('SELECT a.id_cat, a.id_type_cat, a.n_cat, ac.id_prm_type, ac.n_prm_type, ab.id_prm, coalesce(ac.f_prm, -1) as f_prm, ad.v_prm, coalesce(ad.v_prm, ab.v_prm) prm_val,
							     coalesce(ae.dr_cnt + 1, 2) dr_cnt
							FROM tc_catalog as a
							LEFT JOIN tc_prm as ab on ab.lnk_cat = a.id_cat
							LEFT JOIN tc_prm_type as ac on ac.id_prm_type = ab.id_prm_type
							LEFT JOIN tc_prm as ad on ad.id_prm = ab.lnk_prm
							LEFT JOIN (SELECT a.id_prm, a.v_prm, a.lnk_prm, a.lnk_cat, count(a.id_prm) as dr_cnt FROM tc_prm as a GROUP BY lnk_cat) as ae on ae.lnk_cat = ab.lnk_cat');
		$res = mysql_query($query, GetConn());
		if (!$res) {
		    $res = "2101, Ошибка обработки";
		    return $res;
		}
		return $res;
}

/*
	Author: AD
	Name: ftc_print_catprm_editor
	Params: (string)act - callback variable for stand at selected page
			(int)id_prm - id of current parameter value
			(int)id_cat - id of catalog record item
			(int)id_prm_type - id of parameter type
	Description: print editor for selected parameter of catalog record
	Returns: return string contained html code
*/
function ftc_print_catprm_editor($act, $id_prm, $id_cat, $id_prm_type)
{
		$query = sprintf('select f_prm
                        	from `tc_prm_type` as a                        	
                        	where a.id_prm_type = %s',					
					mysql_real_escape_string($id_prm_type, GetConn()));
		$res = mysql_query($query, GetConn());
		if (!$res) {
		    $res = "2200, Ошибка обработки";
		    return $res;
		}
		$row = mysql_fetch_array($res);
		$f_prm = $row["f_prm"];
		mysql_free_result($res);

		$restext = "";

		$query = sprintf('SELECT a.id_prm, a.id_prm_type, coalesce(a.lnk_prm, a.v_prm) prm, coalesce((SELECT 1 FROM `tc_prm` WHERE `lnk_cat` = %s AND `lnk_prm` = a.id_prm), 0) as is_def
                        	FROM `tc_prm` as a                        	
                        	WHERE a.id_prm_type = %2$s
                        	%3$s 
                        	%4$s',
					mysql_real_escape_string($id_cat, GetConn()),
					mysql_real_escape_string($id_prm_type, GetConn()),
					($f_prm == 1)?"and a.lnk_prm is null	and a.lnk_cat is null ":"",
					($f_prm == 0)?"and a.id_prm = ".mysql_real_escape_string($id_prm, GetConn()):"");
		$res = mysql_query($query, GetConn());
		if (!$res) {
		    $res = "2201, Ошибка обработки";
		    return $res;
		}
		$restext = '<form method="GET" id="edit_catprm_form">';

		

		if($f_prm == 1) {
			$restext = $restext.'<select name="prm">';
				while ($row = mysql_fetch_array($res)) {
					$restext = $restext.sprintf("<option value=\"%d\"%s>%s</option>", $row["id_prm"], ($row["is_def"]==1)?" selected":"", $row["prm"]);
				}
			$restext = $restext.'</select>';
		} else {
			$row = mysql_fetch_array($res);
			$restext = $restext.'<input type="text" name="prm" value="'.$row["prm"].'" />';
		}
		$restext = $restext.'<input type="hidden" name="id_cat" value="'.$id_cat.'" />
			<input type="hidden" name="act" value="'.$act.'" />
			<input type="hidden" name="do" value="edit_catprm" />
			<input type="hidden" name="id_prm" value="'.$id_prm.'" />
			<input type="submit" value="Изменить" />
		';
		$restext = $restext.'</form>';
		return $restext;
}

/*
	Author: AD
	Name: ftc_edit_catprm
	Params: (int)id_prm - id of changed parameter
			(string)prm - id or value of parameter
	Description: edit parameter value
	Returns: fill resource query result or return string exception with id
*/
function ftc_edit_catprm($id_prm, $prm)
{

		$query = sprintf('SELECT b.f_prm
                        	FROM `tc_prm` as a
                        	JOIN `tc_prm_type` as b ON b.id_prm_type = a.id_prm_type                       	
                        	WHERE a.id_prm = %s',
					mysql_real_escape_string($id_prm, GetConn()));
		$res = mysql_query($query, GetConn());
		if (!$res) {
		    $res = "2200, Ошибка обработки";
		    return $res;
		}
		$row = mysql_fetch_array($res);
		$f_prm = $row["f_prm"];

		mysql_free_result($res);

		if($f_prm == 1) {
			$query = sprintf("UPDATE `tc_prm` SET lnk_prm = %d WHERE `id_prm` = %d",
	    		              mysql_real_escape_string($prm, GetConn()),
	    		              mysql_real_escape_string($id_prm, GetConn()));
		} else {
			$query = sprintf("UPDATE `tc_prm` SET v_prm = '%s' WHERE `id_prm` = %d",
	    		              mysql_real_escape_string($prm, GetConn()),
	    		              mysql_real_escape_string($id_prm, GetConn()));
		}
		$res = mysql_query($query, GetConn());
		if (!$res) {
			$res = "2301, Ошибка обработки";
		    return $res;
		}
		if(mysql_affected_rows(GetConn()) != 1) {
			$res = "2302, Ошибка изменения".$query;
			return $res;
		}
		return $res;
}

/*
	Author: AD
	Name: ftc_add_catprm
	Params: (int)id_cat - id of catalog record
			(string)prm_type - id of new parameter type
	Description: add new parametr value for catalog parameter
	Returns: fill resource query result or return string exception with id
*/
function ftc_add_catprm($id_cat, $prm_type)
{
		$query = sprintf("SELECT `tc_getnextseq`() AS `nextseq`");
		$res = mysql_query($query, GetConn());		
		if (!$res) {
			$res = "2401, Ошибка обработки";
		    return $res;
		}
		$row = mysql_fetch_array($res);
		$nextseq = $row["nextseq"];
		mysql_free_result($res);

		$query = sprintf("INSERT INTO `tc_prm`(id_prm, id_prm_type, lnk_cat) VALUES (%d, %d, %d)",
    		              $nextseq,
    		              mysql_real_escape_string($prm_type, GetConn()),
    		              mysql_real_escape_string($id_cat, GetConn()));
		$res = mysql_query($query, GetConn());
		if (!$res) {
			$res = "2402, Ошибка обработки";
		    return $res;
		}
		if(mysql_affected_rows(GetConn()) != 1) {
			$res = "2403, Ошибка добавления ";
			return $res;
		}
		return $res;
}

/*
	Author: AD
	Name: ftc_add_cat
	Params: (int)id_type_cat - id of catalog type
			(string)n_cat - name for catalog type
	Description: add new catalog type
	Returns: fill resource query result or return string exception with id
*/
function ftc_add_cat($id_type_cat, $n_cat)
{
		$query = sprintf("SELECT `tc_getnextseq`() AS `nextseq`");
		$res = mysql_query($query, GetConn());		
		if (!$res) {
			$res = "2501, Ошибка обработки";
		    return $res;
		}
		$row = mysql_fetch_array($res);
		$nextseq = $row["nextseq"];
		mysql_free_result($res);

		$query = sprintf("INSERT INTO `tc_catalog`(id_cat, id_type_cat, n_cat) VALUES (%d, %d, '%s')",
    		              $nextseq,
    		              mysql_real_escape_string($id_type_cat, GetConn()),
    		              mysql_real_escape_string($n_cat, GetConn()));
		$res = mysql_query($query, GetConn());
		if (!$res) {
			$res = "2502, Ошибка обработки";
		    return $res;
		}
		if(mysql_affected_rows(GetConn()) != 1) {
			$res = "2503, Ошибка добавления";
			return $res;
		}
		return $res;
}

?>