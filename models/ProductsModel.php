<?php

namespace App\models;

use mysqli;
use app\models\DB;

class ProductsModel{
    private $conn = null;

    public function __construct(){
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function read($table, $min='', $max=''){
        if(($min == "") && ($max=="")){
            $query = "select p.id, p.pname, p.pimg, p.sname, category.catname, subcategory.name, p.pgms, p.pprice, p.stock, p.status from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id order by id desc";
        }else{
            $query = "select p.id, p.pname, p.pimg, p.sname, category.catname, subcategory.name, p.pgms, p.pprice, p.stock, p.status from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id order by id desc limit $min, $max";
        }
        $result = $this->conn->query($query);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            return json_encode($array);
        }else{
            return false;
        }
    }

    public function create($table , $productName, $productImage, $sellerName, $category, $subCategory, $stock, $publish, $description, $unit, $price, $discount, $popular){
        $query = "INSERT INTO $table SET 
        pname='$productName',
        pimg='$productImage',
        sname='$sellerName',
        cid='$category',
        sid='$subCategory',
        date=NOW(),
        psdesc='$description',
        pgms='$unit',
        pprice='$price',
        status='$publish',
        stock='$stock',
        discount='$discount',
        popular='$popular'
        ";

        $result = $this->conn->query($query);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function delete($table, $id){
        $query = "DELETE FROM $table where id=$id";
        $result = $this->conn->query($query);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    // public function update($table , $productName, $sellerName, $category, $subCategory, $stock, $publish, $description, $unit, $price, $discount){
    //     // $query = "UPDATE $table set catname='$catname', catimg='$catimage' where id=$id";
    //     $result = $this->conn->query($query);
    //     if($result){
    //         return true;
    //         // header("Location: /categorylist")
    //     }else{
    //         return false;
    //     }
    // }

    public function edit($table, $id){
        $query = "SELECT * FROM $table where id=$id";
        $result = $this->conn->query($query);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            return json_encode($array);
        }else{
            return false;
        }
    }
}