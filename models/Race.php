<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/models/Pony.php';

class Race {
   public $id;
   public $location;
   public $date;
   public $ponies;
}

function getPDORace(){
    $user="root";
    $pass='root';
    $database="littlepponey";
    try {
       return $PDO = new PDO('mysql:host=localhost:3306;dbname='.$database.';charset=utf8', $user, $pass);
        //ini_set('max_execution_time', 300);
    } catch (PDOException $e) {
        //header('http/1.1 500'.$e->getMessage());
        throw $e;
    }
}
function getAllRaces(){
    $query = getPDORace()->prepare("select * from races");
    $query->execute();
    $datas = $query->fetchALL();
    $res = array();
    foreach ($datas as $d) {
        $new = new Race();
        $new->id = $d['id_race'];
        $new->location = $d['location'];
        $new->date = $d['date'];
        $new->ponies = [];
        $query2 = getPDORace()->prepare("select * from poniesinraces where id_race=:id_r");
        $query2->bindValue(":id_r", $new->id);
        $query2->execute();
        $ps = $query2->fetchALL();
        foreach ($ps as $p) {
            array_push($new->ponies, getPony($p['id_poney']));
        }
        array_push($res, $new);
    }
    return $res;
}

function getRace($id){
    $query = getPDORace()->prepare("select * from races where id_race=:id");
    $query->bindValue(":id", $id);
    $query->execute();
    $d = $query->fetch();
    $new = new Race();
    $new->id = $d['id_race'];
    $new->location = $d['location'];
    $new->date = $d['date'];
    $new->ponies = [];
    $query2 = getPDO()->prepare("select * from poniesinraces where id_race=:id_r");
    $query2->bindValue(":id_r", $new->id);
    $query2->execute();
    $ps = $query2->fetchALL();
    foreach ($ps as $p) {
        array_push($new->ponies, getPony($p['id_poney']));
    }
    return $new;
}

function addRace($race){
    $PDO = getPDORace();
    $query = $PDO->prepare("insert into races(location,date) values(:l,:d)");
    $query->bindValue(":l", $race->location);
    $query->bindValue(":d", $race->date);
    $query->execute();
    $race->id = $PDO->lastInsertId();
    foreach($race->ponies as $p){
        $query2 = $PDO->prepare("insert into poniesinraces(id_poney,id_race) values(:p,:r)");
        $query2->bindValue(":r", $race->id);
        $query2->bindValue(":p", $p->id);
        $query2->execute();
    }
}
    
function updateRace($race){ 
    $query = getPDORace()->prepare("update races SET location=:l, date=:d where id_race=:id");
    $query->bindValue(":id", $race->id);
    $query->bindValue(":l", $race->location);
    $query->bindValue(":d", $race->date);
    $query->execute();    
    $query2 = getPDORace()->prepare("delete from poniesinraces(id_poney,id_race) where id_race=:id");
    $query2->bindValue(":id", $race->id);
    $query2->execute();  
    foreach($race->ponies as $p){
        try{
            $query2 = getPDORace()->prepare("insert into poniesinraces(id_poney,id_race) values(:p,:r)");
            $query2->bindValue(":r", $race->id);
            $query2->bindValue(":p", $p->id);
            $query2->execute();
        } catch (Exception $e){}
    }
}