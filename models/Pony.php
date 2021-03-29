<?php

class Pony {
   public $id;
   public $name;
   public $color;
   public $age;
}

function getPDO(){
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

function getAllPonies(){
    $query = getPDO()->prepare("select * from ponies");
    $query->execute();
    $datas = $query->fetchALL();
    $res = array();
    foreach ($datas as $d) {
        $new = new Pony();
        $new->id = $d['id_poney'];
        $new->name = $d['name'];
        $new->color = $d['color'];
        $new->age = $d['age'];
        array_push($res, $new);
    }
    return $res;
}

function getPony($id_pony){
    $query = getPDO()->prepare("select * from ponies where id_poney=:id");
    $query->bindValue(":id", $id_pony);
    $query->execute();
    $d = $query->fetch();
    $new = new Pony();
    $new->id = $d['id_poney'];
    $new->name = $d['name'];
    $new->color = $d['color'];
    $new->age = $d['age'];
    return $new;
}

function addPony($Pony){
    $PDO = getPDO();
    $query = $PDO->prepare("insert into ponies(name,color,age) values(:n,:c,:a)");
    $query->bindValue(":n", $Pony->name);
    $query->bindValue(":c", $Pony->color);
    $query->bindValue(":a", intval($Pony->age));
    $query->execute();
}
    
function updatePony($pony){ 
    $query = getPDO()->prepare("update ponies SET name=:n, color=:c, age=:a where id_poney=:id_poney");
    $query->bindValue(":id_poney", $pony->id);
    $query->bindValue(":n", $pony->name);
    $query->bindValue(":c", $pony->color);
    $query->bindValue(":a", $pony->age);
    $query->execute();    
}