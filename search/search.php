<?php

/**
 *           RiSearch PHP
 *
 * web search engine, version 0.1.03
 * (c) Sergej Tarasov, 2000-2002
 *
 * Homepage: http://risearch.org/
 * email: risearch@risearch.org
 * Last modified: 13.01.2003
 */

#===================================================================
#
#         Set variables below
#
#===================================================================

# site size
# 1 - Tiny    ~1Mb
# 2 - Medium  ~10Mb
# 3 - Big     ~50Mb
# 4 - Large   >100Mb
$site_size = 3;

# Indexing scheme
# Whole word - 1
# Beginning of the word - 2
# Every substring - 3
$_SEARCH['INDEXING_SCHEME'] = 2;

switch ($site_size) {
   case 1: $_SEARCH['HASHSIZE'] = 20001; break;
   case 2: $_SEARCH['HASHSIZE'] = 50001; break;
   case 3: $_SEARCH['HASHSIZE'] = 100001; break;
   default: $_SEARCH['HASHSIZE'] = 300001;
}

#=====================================================================

function get_query(&$wholeword,&$querymode,&$query_arr) {
		global $_SEARCH, $fm;

		$query = $_SEARCH['search_keywords'];
		$stype = $_SEARCH['stype'];
		$query = mb_strtolower($query);
		$query = preg_replace('/\b([a-zA-Zа-яА-ЯёЁ\-\+\!]{1,3})\b/', "", $query);
		$_SEARCH['search_keywords'] = $query;
		$query_arr = preg_split("/\s+/",$query);
		$query_arr = array_unique($query_arr);
		$k = count($query_arr);

		for ($i=0; $i<$k; $i++) {
			if (preg_match("/\!/", $query_arr[$i]))   {
				$wholeword[$i] = 1;
			} # WholeWord

			$query_arr[$i] = preg_replace("/[\! ]/",'',$query_arr[$i]);
			if ($stype == 'AND') {
				$querymode[$i] = 2;
			} # AND

			if (preg_match ("/^\-/", $query_arr[$i])) {
				$querymode[$i] = 1;
			} # NOT
			if (preg_match ("/^\+/", $query_arr[$i])){
				$querymode[$i] = 2;
			} # AND
			$query_arr[$i] = preg_replace("/^[\+\- ]/",'',$query_arr[$i]);
		}
}
#=====================================================================

function get_results($inforum,$wholeword,$querymode,$query_arr,&$allres) {
		global $_SEARCH;

		$HASH      = EXBB_DATA_DIR_SEARCH . '/db/'.$inforum.'_hash';
		$HASHWORDS = EXBB_DATA_DIR_SEARCH . '/db/'.$inforum.'_hashwords';
		$SITEWORDS = EXBB_DATA_DIR_SEARCH . '/db/'.$inforum.'_sitewords';
		$WORD_IND  = EXBB_DATA_DIR_SEARCH . '/db/'.$inforum.'_word_ind';

		if (!file_exists($HASH) || !file_exists($HASHWORDS) || !file_exists($SITEWORDS) || !file_exists($WORD_IND)) return;

		$fp_HASH		= fopen($HASH, "rb");
		$fp_HASHWORDS	= fopen($HASHWORDS, "rb");
		$fp_SITEWORDS	= fopen($SITEWORDS, "rb");
		$fp_WORD_IND	= fopen($WORD_IND, "rb");

       	for ($j=0; $j < count($query_arr); $j++) {
			$query = $query_arr[$j];
			$allres[$j] = array();

			if ($_SEARCH['INDEXING_SCHEME'] == 1) {
				$substring_length = mb_strlen($query);
			} else {
					$substring_length = 4;
			}

			$hash_value = abs(exbb_hash(mb_substr($query,0,$substring_length)) % $_SEARCH['HASHSIZE']);

			fseek($fp_HASH,$hash_value*4,0);
			$dum = fread($fp_HASH,4);
			$dum = unpack("Ndum", $dum);
			fseek($fp_HASHWORDS,$dum['dum'],0);
			$dum = fread($fp_HASHWORDS,4);
			$dum1 = unpack("Ndum", $dum);

			for ($i=0; $i<$dum1['dum']; $i++) {
				$dum = fread($fp_HASHWORDS,8);
				$arr_dum = unpack("Nwordpos/Nfilepos",$dum);
				fseek($fp_SITEWORDS,$arr_dum['wordpos'],0);
				$word = fgets($fp_SITEWORDS,1024);
				$word = preg_replace("/\x0A/","",$word);
				$word = preg_replace("/\x0D/","",$word);

				if (array_key_exists($j,$wholeword) && ($wholeword[$j]==1) && ($word != $query)) {
					$word = '';
				}

				$pos = mb_strpos($word, $query);

				if ($pos !== false) {
					fseek($fp_WORD_IND,$arr_dum['filepos'],0);
					$dum = fread($fp_WORD_IND,4);
					$dum2 = unpack("Ndum",$dum);
					$dum = fread($fp_WORD_IND,$dum2['dum']*4);

					for($k=0; $k < $dum2['dum']; $k++){
						$zzz = unpack("Ndum",mb_substr($dum,$k*4,4));
						$allres[$j][$zzz['dum']] = 1;
					}
				}
			}

		}
		fclose($fp_HASH);
		fclose($fp_HASHWORDS);
		fclose($fp_SITEWORDS);
		fclose($fp_WORD_IND);
}
#=====================================================================

function boolean($inforum,&$query_arr,&$querymode,&$allres) {
		global $_SEARCH, $fm;

		$_SEARCH['res'][$inforum] = '';
        if (count($allres) === 0) return;
		
		if (count($query_arr) == 1) {
			foreach ($allres[0] as $k => $v) {
					if ($k && _check($inforum, $k)) {
						$_SEARCH['res'][$inforum] .= pack("N",$k);
					}
			}

			$_SEARCH['rescount'][$inforum] = intval(mb_strlen($_SEARCH['res'][$inforum])/4);
			unset($allres);
			return;
		} else {
				$kk = count($query_arr);
				if ($_SEARCH['stype'] == "AND") {
					for ($i=0; $i<$kk; $i++) {
						if ($querymode[$i] == 2) {
							$min = $i;
							break;
						}
					}

					for ($i=$min+1; $i<$kk; $i++) {
						if (count($allres[$i]) < count($allres[$min]) && $querymode[$i] == 2) {
							$min = $i;
						}
					}

					for ($i=0; $i<$kk; $i++) {
						if ($i == $min) {
							continue;
						}

						if ($querymode[$i] == 2) {
							foreach ($allres[$min] as $k => $v) {
									if (array_key_exists($k,$allres[$i])) {
									} else {
											unset($allres[$min][$k]);
									}
							}
						} else {
								foreach ($allres[$min] as $k => $v) {
										if (array_key_exists($k,$allres[$i])) {
											unset($allres[$min][$k]);
										}
								}
						}
					}

					foreach ($allres[$min] as $k => $v) {
							if ($k && _check($inforum, $k)) {
								$_SEARCH['res'][$inforum] .= pack("N",$k);
							}
					}

					$_SEARCH['rescount'][$inforum] = intval(mb_strlen($_SEARCH['res'][$inforum])/4);
					return;
				}

				if ($_SEARCH['stype'] == "OR") {
					for ($i=0; $i<$kk; $i++) {
						if (!array_key_exists($i,$querymode) || $querymode[$i] != 1) {
						//if ($querymode[$i] != 1) {
							$max = $i;
							break;
						}
					}

					for ($i=$max+1; $i<$kk; $i++) {
						if (isset($querymode[$i]) && count($allres[$i]) > count($allres[$max]) && $querymode[$i] != 1) {
							$max = $i;
						}
					}

					for ($i=0; $i<$kk; $i++) {
						if ($i == $max) {
							continue;
						}
                        if (!array_key_exists($i,$querymode) || $querymode[$i] != 1) {
						//if ($querymode[$i] != 1) {
							foreach ($allres[$i] as $k => $v) {
									$allres[$max][$k] = 1;
							}
						} else {
								foreach ($allres[$i] as $k => $v) {
										if (array_key_exists($k,$allres[$max])) {
											unset($allres[$max][$k]);
										}
								}
						}
					}

					foreach ($allres[$max] as $k => $v) {
							if ($k && _check($inforum, $k)) {
								$_SEARCH['res'][$inforum] .= pack("N",$k);
							}
					}

					$_SEARCH['rescount'][$inforum] = intval(mb_strlen($_SEARCH['res'][$inforum])/4);
					return;
				}
		}
}

// Проверка темы на существование
$finfo = array();
function _check($inforum, $k) {
	global $finfo;
	
	if (!isset($finfo[$inforum])) {
		$fp = fopen(EXBB_DATA_DIR_SEARCH . '/db/'.$inforum.'_finfo', 'r');
		flock($fp, 1);
		$finfo[$inforum] = fread($fp, filesize(EXBB_DATA_DIR_SEARCH . '/db/'.$inforum.'_finfo'));
		flock($fp, 3);
		fclose($fp);
	}
	$_finfo = $finfo[$inforum];
	$size = mb_strlen($_finfo);
	
	$row = mb_substr($_finfo, $k, mb_strpos(mb_substr($_finfo, $k, $size - $k), "\n"));
	
	list($f, $t) = explode('::', $row);
	if (file_exists(EXBB_DATA_DIR_FORUMS.'/'.$f.'/'.$t.'-thd.php')) return TRUE;
	return FALSE;
}

#=====================================================================

function getmicrotime(){
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
}

#=====================================================================

function exbb_hash($key) {
		$chars = preg_split("//",$key);

		for ($i=1;$i<count($chars)-1;$i++) {
			$chars2[$i] = ord($chars[$i]);
		}

		$h = hexdec("00000000");
		$f = hexdec("F0000000");

		for ($i=1;$i<count($chars)-1;$i++) {
			$h = ($h << 4) + $chars2[$i];
			if ($g = $h & $f) {
				$h ^= $g >> 24;
			};
			$h &= ~$g;
		}
		return $h;
}

#=====================================================================

function  scan_files($forum_dir, $forum_id) {
		global $fm, $indexed_total;

		$list = $fm->_Read($forum_dir.'/list.php');
		$d = dir($forum_dir);
		while (false !== ($file = $d->read())) {
			if (!is_dir($forum_dir.'/'.$file) && preg_match("#^(\d+)-thd.php$#is", $file, $topic_id)) {
				$topicfile 	= $forum_dir.'/'.$file;
				$topic_id 	= $topic_id[1];
				if (array_key_exists($topic_id,$list)) {
					index_file($topicfile,$forum_id,$topic_id,$list[$topic_id]['name']);
					$indexed_total++;
				}
			}
		}
		$d->close();
}
#=====================================================================

function index_file($topicfile,$forum_id,$topic_id,$topicname = '') {
		global $kbcount,$fp_FINFO,$words,$fm;

		$size = filesize($topicfile);
    	$kbcount += intval($size/1024);

    	$topic = $fm->_Read($topicfile);
    	$html_text = '';
    	foreach ($topic as $id => $infa) {
       			$html_text .= ' '.$infa['post'];
    	}
    	unset($topic);

		$html_text	= mb_strtolower($html_text.' '.$topicname);
		//$html_text 	= str_replace($str_search, $_TransTable,$html_text);

		$serach		= array('/[^a-zA-Zа-яА-ЯёЁ]/is',
							'#\b[a-zA-Zа-яА-ЯёЁ]{1,2}\b#is',
							'/\s+/s');
		$replace	= array("\040","\040", "\040");

		$html_text	= preg_replace($serach,$replace,$html_text);
		$html_text	= explode(" ", trim($html_text));
		$words_temp	= array_unique($html_text);
    	unset($html_text);

    	$pos = ftell($fp_FINFO);
    	$pos = pack("N",$pos);
    	fwrite($fp_FINFO, $forum_id.'::'.$topic_id."\n");

    	foreach ($words_temp as $word) {
        		$words[$word] = isset($words[$word]) ? $words[$word].$pos :$pos;
    	}

    	unset($words_temp);
}
#=====================================================================

function build_hash() {
    	global $fm,$_SEARCH, $words, $HASH, $HASHWORDS;

		/*for ($i=0; $i<$_SEARCH['HASHSIZE']; $i++) {
			$hash_array[$i] = "";
		}*/

    	foreach($words as $word=>$value) {
                $subbound = ($_SEARCH['INDEXING_SCHEME'] == 3) ?mb_strlen($word)-3:1;
				if (mb_strlen($word)==3) {$subbound = 1;}
        		$substring_length = 4;
        		if ($_SEARCH['INDEXING_SCHEME'] == 1) $substring_length = mb_strlen($word);

        		for ($i=0; $i<$subbound; $i++){
            		$hash_value = abs(exbb_hash(mb_substr($word,$i,$substring_length)) % $_SEARCH['HASHSIZE']);
            		$hash_array[$hash_value] = (isset($hash_array[$hash_value])) ? $hash_array[$hash_value].$value:$value;
        		}
		}

		$fp_HASH = fopen ($HASH, "wb");
    	$fp_HASHWORDS = fopen ($HASHWORDS, "wb");

    	$zzz = pack("N", 0);
    	fwrite($fp_HASHWORDS, $zzz);
    	$pos_hashwords = ftell($fp_HASHWORDS);
    	$to_print_hash = "";
    	$to_print_hashwords = "";

    	for ($i=0; $i<$_SEARCH['HASHSIZE']; $i++){
        	if (!isset($hash_array[$i])) {
        		$to_print_hash .= $zzz;
        	} else {
            		$to_print_hash .= pack("N",$pos_hashwords + mb_strlen($to_print_hashwords));
            		$to_print_hashwords .= pack("N", mb_strlen($hash_array[$i])/8).$hash_array[$i];
        	}
        	if (mb_strlen($to_print_hashwords) > 64000) {
            	fwrite($fp_HASH,$to_print_hash);
            	fwrite($fp_HASHWORDS,$to_print_hashwords);
            	$to_print_hash = "";
            	$to_print_hashwords = "";
            	$pos_hashwords  = ftell($fp_HASHWORDS);
        	}
    	}
    	fwrite($fp_HASH,$to_print_hash);
    	fwrite($fp_HASHWORDS,$to_print_hashwords);

    	fclose($fp_HASH);
    	fclose($fp_HASHWORDS);

    	@chmod($HASH,$fm->exbb['ch_files']);
    	@chmod($HASHWORDS,$fm->exbb['ch_files']);
}

function getsetsmiles() {
		global $fm;
		$allsmiles = $fm->_Read(EXBB_DATA_SMILES_LIST);
		return preg_quote(implode("|",array_keys($allsmiles['smiles'])));
}

function setsmiles1($array) {
		global $fm;
		function SmileMap($arr) {
				return ' ';
		}
		$allsmiles = $fm->_Read(EXBB_DATA_SMILES_LIST);
		return $array + array_map("SmileMap",$allsmiles['smiles']);
}
?>
