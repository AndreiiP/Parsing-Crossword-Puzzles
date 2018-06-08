<?php
use application\lib\Db as Db;

class Model
{
    public function __construct()
    {
        $this->db = new Db();
    }
    function checkQuestions($frage){
        $q = "SELECT id,frage  FROM questions WHERE frage = '$frage'";
        $result = $this->db->query($q);
        $result->execute();
        $result2 = $this->db->column($q);
        $result = [$result,$result2];
        return $result;
    }
    function inseertQuestions($frage,$hreffQuestion){
        $q = "INSERT INTO questions (`frage`,`frage_link`)VALUES ('$frage','$hreffQuestion')";
        $result = $this->db->query($q);
        $stmt = $this->db->query("SELECT LAST_INSERT_ID()");
        $questionsId = $stmt->fetchColumn();
        $result = [$result, $questionsId];
        return $result;
    }
    function checkAntword($answer){
        $q = "SELECT id, antword FROM answer WHERE antword = '$answer'";
        $result = $this->db->query($q);
        $result->execute();
        $result1 = $this->db->column($q);
        $result = [$result,$result1];
        return $result;
    }
    function inseertAnswer($answer,$hreffAnswer,$answerLen){
        $q = "INSERT INTO answer (`antword`,`ant_link`,`ant_len`) VALUES('$answer', '$hreffAnswer', '$answerLen')";
        $result = $this->db->query($q);
        $stmt = $this->db->query("SELECT LAST_INSERT_ID()");
        $stmt = $stmt->fetchColumn();
        $result = [$result,$stmt];
        return $result;
    }
    function insertRelations($antwordId,$questionsId){
        $q = "INSERT INTO relations (`answer_id`,`question_id`) VALUES('$antwordId', '$questionsId')";
        $this->db->query($q);
    }
}

