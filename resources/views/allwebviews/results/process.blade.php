<?php
require_once(public_path() . \App\Library\ConstantPaths::$PATH_WEB_ASSETS . 'simpletest/browser.php');
require_once(public_path() . \App\Library\ConstantPaths::$PATH_WEB_ASSETS . 'simplehtmldom/simple_html_dom.php');

$browser = new SimpleBrowser();
$browser->get('http://bietjhs.ac.in/studentresultdisplay/frmprintreport.aspx');

$browser->setFieldById('ctl00_ContentPlaceHolder1_ddlAcademicSession', $session);
$browser->setFieldById('ctl00_ContentPlaceHolder1_ddlSem', $semester);
$browser->setFieldById('ctl00_ContentPlaceHolder1_ddlResultCategory', $resultCategory);
$browser->setFieldById('ctl00_ContentPlaceHolder1_txtRollno', $rollNo);

$browser->click('View');
//$html = new simple_html_dom();

//die($browser->getContent());
$dom = new DOMDocument();

@$dom->loadHTML($browser->getContent());

$name = $dom->getElementById('ctl00_ContentPlaceHolder1_sName')->textContent;
$marks = explode('/', $dom->getElementById('ctl00_ContentPlaceHolder1_emk')->textContent);

$percentage = $marks[0] / $marks[1] * 100;

$result = [
        'rollno' => $rollNo,
        'name' => $name,
        'percentage' => $percentage,
];

//print_r($result);
return response()->json($result);
//$regex = '/<span id="ctl00_ContentPlaceHolder1_emk">( .+?)</span>/';
//
//$name = '';//$html->find('#ctl00_ContentPlaceHolder1_sName',0);
//$marks = preg_match($regex,$html,$match);
//
//var_dump($marks);

?>