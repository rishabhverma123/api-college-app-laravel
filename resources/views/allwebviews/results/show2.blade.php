<?php
require_once(public_path() . \App\Library\ConstantPaths::$PATH_WEB_ASSETS . 'simpletest/browser.php');
require_once(public_path() . \App\Library\ConstantPaths::$PATH_WEB_ASSETS . 'simplehtmldom/simple_html_dom.php');

$browser = new SimpleBrowser();
$results = array();

$session = '2015-2016';
$semester = '4';
$resultCategory = 'R';
$rollNoPrefix = '14043100';
$i = 10;

$marksTextId = $semester % 2 == 0 ? 'ctl00_ContentPlaceHolder1_emk' : 'ctl00_ContentPlaceHolder1_omk';

for (; $i < 13; $i++)
{
    $browser->get('http://bietjhs.ac.in/studentresultdisplay/frmprintreport.aspx');

    $rollNo = $i < 10 ? $rollNoPrefix . '0' . $i : $rollNoPrefix . $i;

    $browser->setFieldById('ctl00_ContentPlaceHolder1_ddlAcademicSession', $session);
    $browser->setFieldById('ctl00_ContentPlaceHolder1_ddlSem', $semester);
    $browser->setFieldById('ctl00_ContentPlaceHolder1_ddlResultCategory', $resultCategory);
    $browser->setFieldById('ctl00_ContentPlaceHolder1_txtRollno', $rollNo);

    $browser->click('View');
    $html = new simple_html_dom();



    //die($browser->getContent());
    $html->load($browser->getContent());



    $marks = explode('/', $html->find('#'.$marksTextId,0));


    if (!isset($marks[1]) || !isset($marks[0]))
        continue;

    $results[$i] = [
            'rollno' => $rollNo,
            'name' => $html->find('#ctl00_ContentPlaceHolder1_sName',0),
            'per' => ((float)$marks[0]) / ((float)$marks[1]),
    ];

    echo $results[$i]['rollno'] . "    " . $results[$i]['name'] . "   " . $results[$i]['per'] . "<br>";
//$page2 = $browser->click('statistics');
//$page = $browser->click('PHP 5 bugs only');
//preg_match('/status=Open.*?by=Any.*?(\d+)<\/a>/', $page, $matches);
}


?>