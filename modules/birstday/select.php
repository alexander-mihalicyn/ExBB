<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

$select_birstday = '';
if ($requirepass === FALSE && $fm->exbb['birstday'] === TRUE){
	$fm->_LoadModuleLang('birstday');
	$select_birstday = ($fm->input['action'] == 'agreed') ? select():select($fm->user);
}

function select($info='') {
         global $fm;
         if (!empty($info) && isset($info['birstday']) && preg_match("#(\d{1,2}):(\d{1,2}):(\d{4})#is",$info['birstday'],$date)){
         	$d = $date[1];
			$m = $date[2];
			$y = $date[3];
            $show_no = (!$info['showyear']) ? 'checked' : '';
            $show_yes = ($info['showyear']) ? 'checked' : '';
         } else {
                $d = FALSE;
                $m = FALSE;
                $y = FALSE;
                $show_no =  '';
				$show_yes = 'checked';
         }
         $dayselecthtml ='';
         for($i=0; $i<=31;$i++){
             $day = $i;
             if($day==0) $day = $fm->LANG['Day'];
             if ($day == $d) {
                 $dayselecthtml .= '<option value="'.$day.'" selected>'.$day."</option>\n";
             } else {
                     $dayselecthtml .= '<option value="'.$day.'">'.$day."</option>\n";
               }
         }

         $monthselect = $fm->LANG['MonthArray'];
         $monthselecthtml = '';
         foreach ($monthselect as $id => $month) {
                  if ($id == $m) {
                      $monthselecthtml .= '<option value="'.$id.'" selected>'.$month."</option>\n";
                  } else {
                          $monthselecthtml .= '<option value="'.$id.'">'.$month."</option>\n";
                    }
         }
         $yearselecthtml = '';
         $MaxYear = date("Y", time())-9;
         for($i	= $MaxYear; $i >= 1920; $i--){
             $year=$i;
             if($year==$MaxYear) $year = $fm->LANG['Year'];
             if ($year == $y) {
                 $yearselecthtml .= '<option value="'.$year.'" selected>'.$year."</option>\n";
             } else {
                     $yearselecthtml .= '<option value="'.$year.'">'.$year."</option>\n";
               }
         }
         include ('templates/'.DEF_SKIN.'/modules/birstday/select.tpl');
         return $select_birstday;
}
?>
