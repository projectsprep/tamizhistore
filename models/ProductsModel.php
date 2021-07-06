<?php

namespace App\models;

use app\models\DB;
use app\models\RatingModel;
use \app\controllers\ApiController;

class ProductsModel
{
    private $conn = null;
    public $ratingDB;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
        $this->ratingDB = new RatingModel();
    }

    public function getProductRating($pid){
        $pid = $this->conn->real_escape_string($pid);

        $query = "SELECT rating FROM productrating where pid=$pid";
        $result = $this->conn->query($query);
        $sum = 0;
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $sum += $row['rating'];
            }
            $result = $sum/$result->num_rows;
            return $result;
        }else{
            return 0;
        }
    }

    public function getSearchProduct($table, $q){
        $table = $this->conn->real_escape_string($table);
        $q = $this->conn->real_escape_string($q);
        $query = "SELECT * FROM $table WHERE pname LIKE '%$q%'";
        $result = $this->conn->query($query);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            return $array;
        }else{
            return false;
        }
    }
    public function getFoodItems(){
        $query = "SELECT p.*, s.name FROM product p inner join subcategory s on s.id=p.sid where cid = 3";
        $result = $this->conn->query($query);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            return json_encode($array);
        }else{
            return json_encode("No results found");
        }
    }

    public function readByCid($table, $cid){
        $table = $this->conn->real_escape_string($table);
        $cid = $this->conn->real_escape_string($cid);

        $query = "select p.*, category.catname, subcategory.name from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id WHERE cid=$cid order by id desc";
        $result = $this->conn->query($query);
        $array = [];

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            shuffle($array);
            return $array;
        }else{
            return false;
        }
    }

    public function readBySid($table, $sid){
        $table = $this->conn->real_escape_string($table);
        $sid = $this->conn->real_escape_string($sid);

        $query = "select p.*, category.catname, subcategory.name from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id WHERE sid=$sid order by id desc";
        $result = $this->conn->query($query);
        $array = [];

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            shuffle($array);
            return $array;
        }else{
            return false;
        }
    }

    public function read($table)
    {
        $table = $this->conn->real_escape_string($table);

        $query = "select p.*, category.catname, subcategory.name from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id order by id desc";
        $result = $this->conn->query($query);
        $array = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($array, $row);
            }
            $array = mb_convert_encoding($array, 'UTF-8', 'UTF-8');
            return json_encode($array);
        } else {
            return false;
        }
    }

    public function getProductById($table, $id)
    {
        $table = $this->conn->real_escape_string($table);
        $id = htmlspecialchars($id);

        $query = "select p.*, category.catname, subcategory.name, category.id cid, subcategory.id sid, subcategory.name subname from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id where p.id = $id";
        $array = [];
        $result = $this->conn->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($array, $row);
            }

            return json_encode($array);
        } else {
            return json_encode("No results found");
        }
    }

    public function create($table, $productName, $productImage, $sellerName, $category, $subCategory, $stock, $publish, $description, $unit, $price, $discount, $popular, $pincode, $minTime="", $maxTime="")
    {
        $table = $this->conn->real_escape_string($table);
        $productName = $this->conn->real_escape_string($productName);
        $productImage = $this->conn->real_escape_string($productImage);
        $sellerName = $this->conn->real_escape_string($sellerName);
        $category = $this->conn->real_escape_string($category);
        $subCategory = $this->conn->real_escape_string($subCategory);
        $stock = $this->conn->real_escape_string($stock);
        $publish = $this->conn->real_escape_string($publish);
        $description = $this->conn->real_escape_string($description);
        $unit = $this->conn->real_escape_string($unit);
        $price = $this->conn->real_escape_string($price);
        $discount = $this->conn->real_escape_string($discount);
        $popular = $this->conn->real_escape_string($popular);
        $pincode = $this->conn->real_escape_string($pincode);
        $minTime = $this->conn->real_escape_string($minTime);
        $maxTime = $this->conn->real_escape_string($maxTime);

        $stock = strtolower($stock) == "no" ? 0 : 1;
        $popular = strtolower($popular) == "yes" ? 0 : 1;

        $query = "INSERT INTO $table SET 
        pname='$productName',
        pimg='$productImage',
        sname='$sellerName',
        cid=$category,
        sid=$subCategory,
        date=NOW(),
        psdesc='$description',
        pgms='$unit',
        pprice=$price,
        status='$publish',
        stock=$stock,
        discount=$discount,
        popular=$popular
        " . ($pincode == "" ? '' : sprintf(", pincode=%s", $pincode)) . "
        ". ($minTime == "" ? '' : sprintf(",minTime= '%s'", $minTime)) . "
        " . ($maxTime == "" ? '' : sprintf(",maxTime= '%s'", $maxTime)) ."
        ";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($table, $id)
    {
        $table = $this->conn->real_escape_string($table);
        $id = $this->conn->real_escape_string($id);

        $query = "DELETE FROM $table where id=$id";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update($table, $id, $productName, $sellerName, $category, $subCategory, $stock, $publish, $description, $unit, $price, $discount, $popular, $pincode, $minTime, $maxTime, $image = "")
    {
        $table = $this->conn->real_escape_string($table);
        $id = $this->conn->real_escape_string($id);
        $productName = $this->conn->real_escape_string($productName);
        $sellerName = $this->conn->real_escape_string($sellerName);
        $category = $this->conn->real_escape_string($category);
        $subCategory = $this->conn->real_escape_string($subCategory);
        $stock = $this->conn->real_escape_string($stock);
        $publish = $this->conn->real_escape_string($publish);
        $description = $this->conn->real_escape_string($description);
        $unit = $this->conn->real_escape_string($unit);
        $price = $this->conn->real_escape_string($price);
        $discount = $this->conn->real_escape_string($discount);
        $popular = $this->conn->real_escape_string($popular);
        $pincode = $this->conn->real_escape_string($pincode);
        $image = $this->conn->real_escape_string($image);

        if (isset($image) && $image != "") {
            $query = "UPDATE $table set pname='$productName', sname='$sellerName', cid='$category', sid='$subCategory', pincode=$pincode, stock='$stock', status='$publish', psdesc='$description', pgms='$unit', pprice=$price, discount='$discount', popular='$popular', pimg='$image'". ($minTime == "" ? '' : sprintf(",minTime= '%s'", $minTime)) . "
            " . ($maxTime == "" ? '' : sprintf(",maxTime= '%s'", $maxTime)) ." where id=$id";
        } else {
            $query = "UPDATE $table set pname='$productName', sname='$sellerName', cid='$category', sid='$subCategory', pincode=$pincode, stock='$stock', status='$publish', psdesc='$description', pgms='$unit', pprice=$price, discount='$discount', popular='$popular'         ". ($minTime == "" ? '' : sprintf(",minTime= '%s'", $minTime)) . "
            " . ($maxTime == "" ? '' : sprintf(",maxTime= '%s'", $maxTime)) ." where id=$id";
        }
        $result = $this->conn->query($query);
        if ($this->conn->affected_rows > 0) {
            return true;
            // header("Location: /categorylist")
        } else {
            return false;
        }
    }

    public function edit($table, $id)
    {
        $table = $this->conn->real_escape_string($table);
        $id = $this->conn->real_escape_string($id);

        $query = "SELECT * FROM $table where id=$id";
        $result = $this->conn->query($query);
        $array = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($array, $row);
            }
            return json_encode($array);
        } else {
            return false;
        }
    }

    public function getCategoryNames($table)
    {
        $table = $this->conn->real_escape_string($table);

        $query = "SELECT id, catname from $table";
        $result = $this->conn->query($query);
        $output = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $output .= "<option value='" . $row['id'] . "'>" . $row['catname'] . "</option>";
            }
            return json_encode($output);
        }
    }

    public function getSubcategoryNames($table, $id)
    {
        $table = $this->conn->real_escape_string($table);
        $id = htmlspecialchars($id);

        $query = "SELECT id, `name` from $table where cat_id=$id";
        $result = $this->conn->query($query);
        $output = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $output .= "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
            return json_encode($output);
        }
    }
}
