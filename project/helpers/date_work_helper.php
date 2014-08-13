<?php
define('MONTH', 2592000);
define('DAY', 86400);
define('HOUR', 3600);
define('MINUTE', 60);

	function get_month($lang){
		switch ($lang) {
			case 'ru':return $aMonth = array('00'=>'00', '01'=>'Января', '02'=>'Февраля', '03'=>'Марта', '04'=>'Апреля', '05'=>'Мая', '06'=>'Июня', '07'=>'Июля', '08'=>'Августа', '09'=>'Сентября', '10'=>'Октября', '11'=>'Ноября', '12'=>'Декабря');
			case 'en':return $aMonth = array('00'=>'00', '01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June', '07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December');
			case 'ru_short':return $aMonth = array('00'=>'00', '01'=>'Янв.', '02'=>'Фев.', '03'=>'Мар.', '04'=>'Апр.', '05'=>'Мая', '06'=>'Июн.', '07'=>'Июл.', '08'=>'Авг.', '09'=>'Сен.', '10'=>'Окт.', '11'=>'Ноя.', '12'=>'Дек.');
			case 'en_short':return $aMonth = array('00'=>'00', '01'=>'Jan.', '02'=>'Feb.', '03'=>'Mar.', '04'=>'Apr.', '05'=>'May', '06'=>'Jun.', '07'=>'Jul.', '08'=>'Aug.', '09'=>'Sep.', '10'=>'Oct.', '11'=>'Nov.', '12'=>'Dec.');
			default:
		    return $aMonth = array('00'=>'00', '01'=>'Января', '02'=>'Февраля', '03'=>'Марта', '04'=>'Апреля', '05'=>'Мая', '06'=>'Июня', '07'=>'Июля', '08'=>'Августа', '09'=>'Сентября', '10'=>'Октября', '11'=>'Ноября', '12'=>'Декабря');
		};

	}

	function dd_month_yyyy($d, $lang='ru'){ //2011-04-03 00:00:00
       	$aMonth = get_month($lang);

        $year = $d[0].$d[1].$d[2].$d[3];
        if($year == date("Y"))
        {
            $year = '';
        }
	    return $d[8].$d[9].' '.$aMonth[$d[5].$d[6]].' '.$year;
    }

    function dd_mm_yyyy($d){

	    return (strlen($d)>9) ? $d[8].$d[9].'.'.$d[5].$d[6].'.'.$d[0].$d[1].$d[2].$d[3] : '';
    }
    function time_ago($time_from = null ,$time, $lang='ru')
	{
	    $oCI = & get_instance();
	    $oCI->load->helper('date');

        if(!isSet($time_from))
        {
            $time_from = now();
        }

	    $interval = mysql_to_unix($time_from) - mysql_to_unix($time); // надо проверить возвращаемую дату у меня она на час всегда больше
	    if($interval>MONTH){
	    	return dd_month_yyyy($time, $lang);
	    }else if($interval>DAY){
	    	return floor($interval/DAY).$oCI->lang->line('d');
	    }else if($interval>HOUR){
	        return floor($interval/HOUR).$oCI->lang->line('h');
	    }else if($interval>MINUTE){
	        return floor($interval/MINUTE).$oCI->lang->line('m');
	    }else{
	    	return $interval.$oCI->lang->line('s');
	    }
	}

    function getAge($d,$m,$y)
    {
        return (int)((date('Ymd') - date('Ymd', strtotime($d.'.'.$m.'.'.$y))) / 10000);
    }

	function interval_timestamp($time, $lang='ru')
	{
        $oCI = & get_instance();
        $oCI->load->helper('date');

		$interval = mysql_to_unix($time) - time();

        if($interval>MONTH){
	    	return $oCI->lang->line('to').' '.dd_month_yyyy($time, $lang.'_short');
	    }else if($interval>DAY){
	    	return floor($interval/DAY).$oCI->lang->line('d');
	    }else if($interval>HOUR){
	        return floor($interval/HOUR).$oCI->lang->line('h');
	    }else if($interval>MINUTE){
	        return floor($interval/MINUTE).$oCI->lang->line('m');
	    }else if($interval>0){
	    	return $interval.$oCI->lang->line('s');
	    }else{
            return $oCI->lang->line('completed');
        }
	}

	function time_event($time_s, $time_po)
	{
		if(($time_s != "00:00:00")&&($time_po != "00:00:00")){
			return "c ".mb_substr($time_s,0,5)." до ".mb_substr($time_po,0,5);
		}else{
			return "не указано";
		}
    }

    // return yyyy-mm-dd
    function date_to_mysql($date,$format="dd/mm/yyyy")
    {
        if($format == "dd/mm/yyyy")
        {
            $aDate = explode("/",$date);

            if(count($aDate) == 3)
            {
                $date = $aDate[2].'-'.$aDate[1].'-'.$aDate[0].' 00:00:00';
            }

            return $date;
        }

        return false;
    }

    function dd_mm_yyyy_time($d,$with_time = TRUE){ //2011-04-03 00:00:00 | 20 июля 2012 в 13:13
        $aMonth = get_month('ru');

        $day = $d[8].$d[9];
        $month = mb_strtolower($aMonth[$d[5].$d[6]]);
        $year = $d[0].$d[1].$d[2].$d[3];

        $in_time = '';
        if($with_time)
        {
            $time = $d[11].$d[12].$d[13].$d[14].$d[15];
            $in_time = ' в '.$d[11].$d[12].$d[13].$d[14].$d[15];
        }

        if($year == date("Y"))
        {
            $year = '';
        }

        return (int)$day.' '.$month.' '.$year.$in_time;
    }

    function dd_mm_yyyy_time_from_to($d,$d2,$with_count = TRUE){ // c 20 июля 2012 по 21 августа 2013 (20 дней)
        $aMonth = get_month('ru');

        $day = $d[8].$d[9];
        $month = mb_strtolower($aMonth[$d[5].$d[6]]);
        $year = $d[0].$d[1].$d[2].$d[3];

        $day2 = $d2[8].$d2[9];
        $month2 = mb_strtolower($aMonth[$d2[5].$d2[6]]);
        $year2 = $d2[0].$d2[1].$d2[2].$d2[3];

        if($year == $year2)
        {
            $year = '';

            if($month == $month2)
            {
                $month = '';
            }
        }

        if($with_count)
        {
            $str = dd_diff($d,$d2);
            $with_count = '('.trim($str).')';
        }

        return 'c '.$day.' '.$month.' '.$year.' по '.$day2.' '.$month2.' '.$year2.' '.$with_count;
    }

    function dd_diff($d,$d2)
    {
        $datetime1 = new DateTime($d);
        $datetime2 = new DateTime($d2);
        $diff = $datetime1->diff($datetime2);

        $str = (($diff->y>0)?$diff->y.' '.CountToRightStr($diff->y,'лет','год','года').' ':'');
        $str .= (($diff->m>0)?$diff->m.' '.CountToRightStr($diff->m,'месяцев','месяц','месяца').' ':'');
        $str .= (($diff->d>0)?$diff->d.' '.CountToRightStr($diff->d,'дней','день','дня').' ':'');

        if(empty($str))
        {
            $str = (($diff->h>0)?$diff->h.' '.CountToRightStr($diff->h,'часов','час','часа').' ':'');
            $str .= (($diff->i>0)?$diff->i.' '.CountToRightStr($diff->i,'минут','минута','минуты').' ':'');

            if(empty($str))
            {
                $str = '1 vbyene';
            }
        }

        return $str;
    }

    function data_convert_sql_to_calendar($sql_date)
    {
        $year = substr($sql_date, 0, 4);
        $month = substr($sql_date, 5, 2);
        $day = substr($sql_date, 8, 2);
        return $day.'-'.$month.'-'.$year;
    }
/*
    function dd_mm_yyyy3($d){

	    return (strlen($d)>9) ? $d[8].$d[9].'/'.$d[5].$d[6].'/'.$d[0].$d[1].$d[2].$d[3] : '';
    }



    function month_dd_yyyy($d){ //2011-04-03 00:00:00 June 13th, 2011
	    $aMonth = array('01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June',
					    '07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December');
	    return $aMonth[$d[5].$d[6]].' '.$d[8].$d[9].', '.$d[0].$d[1].$d[2].$d[3];
    }

    function dd_mm_yyyy2($date){ //на входе: 17 November 2011 |на выходе: 2008/07/31
	    $aMonth = array('01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June',
					    '07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December');
	    $date = explode(' ', $date);

	    if(!empty($date)){
        	return $date[0].'/'.array_search($date[1], $aMonth).'/'.$date[2];
	    }
    }
    function dd_mm_yyyy4($date){ //на входе: 17 November 2011 |на выходе: 2008-07-31
	    $aMonth = array('01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June',
					    '07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December');
	    $date = explode(' ', $date);

	    if(!empty($date)){
        	return $date[2].'-'.array_search($date[1], $aMonth).'-'.$date[0];
	    }
    }
    function dd_month($d) // на входе: 2012-11-30 00:00:00 на выходе 30 November
    {
     	$aMonth = array('01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June',
					    '07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December');
        return $d[8].$d[9].' '.$aMonth[$d[5].$d[6]];
    }

    function getDateItem($sDate)
    {
	    $aNoTimeDate = explode(" ",$sDate);
	    $aDate = explode("-",$aNoTimeDate[0]);
	    if(is_array($aDate)&&(count($aDate)==3))
	    return array($aDate[0],$aDate[1],$aDate[2]);
    }

    function get_date($str,$format=true)
    {
	    $aStr = explode("-",$str);

	    if(count($aStr))
		    if($format)
		    {
			    return $aStr[2]."-".$aStr[1]."-".$aStr[0];
		    }
		    else
		    {
			    return array("year"=>$aStr[2],"month"=>$aStr[1],"day"=>$aStr[0]);
		    }
    }

  */
?>