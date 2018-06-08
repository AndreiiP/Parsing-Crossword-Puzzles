<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'application/lib/Db.php';
require 'application/lib/phpQuery.php';
require 'vendor/autoload.php';
require 'Model.php';


$url = 'https://www.kreuzwort-raetsel.net/'.$argv['1'];
$file = file_get_contents($url);
$doc = phpQuery::newDocument($file);
$lastPage = $doc->find('.Pagination')->find('.Digit:last')->find('a')->text();

$count = 0;
$mes = 1;
for($i =0; $i < $lastPage; $i++) {
    $count++;
    if ($count == $lastPage) {
        $loop->cancelTimer($timer);
    }

    $file = file_get_contents($url);
    $doc = phpQuery::newDocument($file);
    $tb2 = $doc->find('table tbody tr');

    foreach ($tb2 as $element) {
        $pq = pq($element);
        $frage = $pq->find('.Question')->text();
        $hreffQuestion = $pq->find('.Question a')->attr('href');

        $answer = $pq->find('.AnswerFull')->text();
        $hreffAnswer = $pq->find('.AnswerFull a')->attr('href');
        $answerLen = strlen($answer);

        // check the table of questions on the existence of the question
        $model = new Model();
        $result = $model->checkQuestions($frage);

        if ($result[0]->rowCount() > 0) {
            $a = 'There are in the database';
            $questionsId = $result[1];
        } else {
            $a = 'There is no value in the database';
            $result = $model->inseertQuestions($frage, $hreffQuestion);
            $questionsId = $result[1];
        }

        // check the table of answers to the existence
        $result = $model->checkAntword($answer);

        if ($result[0]->rowCount() > 0) {
            $a = 'There are in the database';
            $antwordId = $result[1];
        } else {
            $a = 'There is no value in the database';
            $result = $model->inseertAnswer($answer, $hreffAnswer, $answerLen);
            $antwordId = $result[1];
        }

        $model->insertRelations($antwordId, $questionsId);

    }
    $url = $doc->find('.Pagination')->find('.Next')->find('a')->attr('href');
    $url = 'https://www.kreuzwort-raetsel.net/' . $url;
    echo "Page:($argv[1]) " . round(100 * $mes / $lastPage, 2) . "%\n\r";
    $mes++;
}


