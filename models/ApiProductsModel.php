<?php

namespace App\models;

use app\models\DB;
use app\models\RatingModel;
use \app\controllers\ApiController;

class ApiProductsModel
{
    private $conn = null;
    public $ratingDB;

    public function __construct()
    {
        date_default_timezone_set("Asia/kolkata");
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
        $this->ratingDB = new RatingModel();
    }

    public function userRating($pid){
        $userRating = $this->ratingDB->userProductRating(ApiController::$decodedData->data->id, $pid);
        if($userRating){
            return $userRating;
        }else{
            return 0;
        }
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

    public function getSearchProduct($table, $q, $pincode, $page){
        $page = $page === "" ? 0 : $page;
        $table = $this->conn->real_escape_string($table);
        $q = $this->conn->real_escape_string($q);
        $page = $this->conn->real_escape_string($page);

        if($page == 0 || $page == 1){
            $query = "SELECT * FROM $table WHERE pname LIKE '%$q%' and pincode=$pincode and " . sprintf('((convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s") ', date("H:i"), date("H:i")) ."or (minTime=\"00:00\" or maxTime=\"00:00\")) LIMIT 10";
        }else{
            $query = "SELECT * FROM $table WHERE pname LIKE '%$q%' and pincode=$pincode and " . sprintf('((convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s") ', date("H:i"), date("H:i")) ."or (minTime=\"00:00\" or maxTime=\"00:00\")) LIMIT " . (($page * 10) - 10) . ", 10";
        }
        $result = $this->conn->query($query);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $rating = $this->getProductRating($row['id']);
                $userRating = $this->userRating($row['id']);
                $row = array_merge($row, array("rating"=>$rating), array("userRating"=>$userRating));
                array_push($array, $row);
            }

            $query = "SELECT count(*) FROM $table WHERE pname LIKE '%$q%' and pincode=$pincode and " . sprintf('((convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s") ', date("H:i"), date("H:i")) ."or (minTime=\"00:00\" or maxTime=\"00:00\"))";
            $result = $this->conn->query($query);
            $row = $result->fetch_assoc();
            $isMore = $row['count(*)'] - (($page === 0 || $page === 1 ? 1 : $page) * 10);
            $isMore = $isMore > 0 ? True : False;
            return array("data"=>$array, "isMore"=>$isMore);
        }else{
            return false;
        }
    }
    public function getFoodItems($pincode){
        $query = "SELECT * FROM product where cid = 3 and pincode=$pincode and " . sprintf('((convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s") ', date("H:i"), date("H:i")) ."or (minTime=\"00:00\" or maxTime=\"00:00\"))";
        $result = $this->conn->query($query);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $rating = $this->getProductRating($row['id']);
                $userRating = $this->userRating($row['id']);
                $row = array_merge($row, array("rating"=>$rating), array("userRating"=>$userRating));
                array_push($array, $row);
            }
            return json_encode($array);
        }else{
            return false;
        }
    }

    public function readByCid($table, $cid, $pincode, $page){
        $page = $page === "" ? 0 : $page;
        $table = $this->conn->real_escape_string($table);
        $cid = $this->conn->real_escape_string($cid);
        $page = $this->conn->real_escape_string($page);

        if($page == 0 || $page == 1){
            $query = "select p.*, category.catname, subcategory.name from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id WHERE cid=$cid and pincode=$pincode and " . sprintf('((convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s") ', date("H:i"), date("H:i")) ."or (minTime=\"00:00\" or maxTime=\"00:00\")) order by id desc LIMIT 10";
        }else{
            $query = "select p.*, category.catname, subcategory.name from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id WHERE cid=$cid and pincode=$pincode and " . sprintf('((convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s") ', date("H:i"), date("H:i")) ."or (minTime=\"00:00\" or maxTime=\"00:00\")) order by id desc LIMIT " . (($page * 10) - 10) . ", 10";
        }
        $result = $this->conn->query($query);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $rating = $this->getProductRating($row['id']);
                $userRating = $this->userRating($row['id']);
                $row = array_merge($row, array("rating"=>$rating), array("userRating"=>$userRating));
                array_push($array, $row);
            }
            shuffle($array);
            $query = "select p.*, category.catname, subcategory.name from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id WHERE cid=$cid and pincode=$pincode and " . sprintf('((convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s") ', date("H:i"), date("H:i")) ."or (minTime=\"00:00\" or maxTime=\"00:00\"))";
            $result = $this->conn->query($query);
            $row = $result->num_rows;
            $isMore = $row - (($page === 0 || $page === 1 ? 1 : $page) * 10);
            $isMore = $isMore > 0 ? True : False;
            return array("data"=>$array, "isMore"=>$isMore);
        }else{
            return false;
        }
    }

    public function readBySid($table, $sid, $pincode, $page){
        $page = $page === "" ? 0 : $page;
        $table = $this->conn->real_escape_string($table);
        $sid = $this->conn->real_escape_string($sid);
        $page = $this->conn->real_escape_string($page);

        if($page == 0 || $page == 1){
            $query = "select p.*, category.catname, subcategory.name from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id WHERE sid=$sid and pincode=$pincode " . sprintf('((convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s") ', date("H:i"), date("H:i")) ."or (minTime=\"00:00\" or maxTime=\"00:00\")) order by id desc LIMIT 10";
        }else{
            $query = "select p.*, category.catname, subcategory.name from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id WHERE sid=$sid and pincode=$pincode " . sprintf('((convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s") ', date("H:i"), date("H:i")) ."or (minTime=\"00:00\" or maxTime=\"00:00\")) order by id desc LIMIT " . (($page * 10) - 10) . ", 10";
        }

        $result = $this->conn->query($query);
        $array = [];

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $rating = $this->getProductRating($row['id']);
                $userRating = $this->userRating($row['id']);
                $row = array_merge($row, array("rating"=>$rating), array("userRating"=>$userRating));
                array_push($array, $row);
            }
            shuffle($array);
            $query = "select p.*, category.catname, subcategory.name from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id WHERE sid=$sid and pincode=$pincode and " . sprintf('((convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s") ', date("H:i"), date("H:i")) ."or (minTime=\"00:00\" or maxTime=\"00:00\"))";
            $result = $this->conn->query($query);
            $row = $result->num_rows;
            $isMore = $row - (($page === 0 || $page === 1 ? 1 : $page) * 10);
            $isMore = $isMore > 0 ? True : False;
            return array("data"=>$array, "isMore"=>$isMore);
        }else{
            return false;
        }
    }

    public function read($table, $pincode, $page)
    {
        $page = $page === "" ? 0 : $page;
        $table = $this->conn->real_escape_string($table);

        if($page == 0 || $page == 1){
            $query = "select p.*, category.catname, subcategory.name from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id where pincode=$pincode and " . sprintf('((convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s") ', date("H:i"), date("H:i")) ."or (minTime=\"00:00\" or maxTime=\"00:00\")) order by id desc limit 10";
        }else{
            $query = "select p.*, category.catname, subcategory.name from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id where pincode=$pincode and " . sprintf('convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s"', date("H:i"), date("H:i")) ."or minTime=\"00:00\" or maxTime=\"00:00\" order by id desc limit " . (($page * 10) - 10) . ", 10";
        }
        $result = $this->conn->query($query);
        $array = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rating = $this->getProductRating($row['id']);
                $userRating = $this->userRating($row['id']);
                $row = array_merge($row, array("rating"=>$rating), array("userRating"=>$userRating));
                array_push($array, $row);
            }
            $array = mb_convert_encoding($array, 'UTF-8', 'UTF-8');
            $query = "select p.*, category.catname, subcategory.name from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id where pincode=$pincode and " . sprintf('((convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s") ', date("H:i"), date("H:i")) ."or (minTime=\"00:00\" or maxTime=\"00:00\"))";
            $result = $this->conn->query($query);
            $row = $result->num_rows;
            $isMore = $row - (($page === 0 || $page === 1 ? 1 : $page) * 10);
            $isMore = $isMore > 0 ? True : False;
            return array("data"=>$array, "isMore"=>$isMore);
        } else {
            return false;
        }
    }

    public function getProductById($table, $id, $pincode, $page)
    {
        $table = $this->conn->real_escape_string($table);
        $id = htmlspecialchars($id);
        $page = $page === "" ? 0 : $page;
        $page = $this->conn->real_escape_string($page);

        if($page == 0 || $page == 1){
            $query = "select p.*, category.catname, subcategory.name, category.id cid, subcategory.id sid, subcategory.name subname from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id where p.id = $id and pincode=$pincode " . sprintf('((convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s") ', date("H:i"), date("H:i")) ."or (minTime=\"00:00\" or maxTime=\"00:00\")) LIMIT 10";
        }else{
            $query = "select p.*, category.catname, subcategory.name, category.id cid, subcategory.id sid, subcategory.name subname from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id where p.id = $id and pincode=$pincode " . sprintf('((convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s") ', date("H:i"), date("H:i")) ."or (minTime=\"00:00\" or maxTime=\"00:00\")) limit " . (($page * 10) - 10) . ", 10";
        }
        $array = [];
        $result = $this->conn->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rating = $this->getProductRating($row['id']);
                $userRating = $this->userRating($row['id']);
                $row = array_merge($row, array("rating"=>$rating), array("userRating"=>$userRating));
                array_push($array, $row);
            }

            $query = "select p.*, category.catname, subcategory.name, category.id cid, subcategory.id sid, subcategory.name subname from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id where p.id = $id and pincode=$pincode and " . sprintf('((convert(minTime, time) <= "%s" and convert(maxTime, time) >= "%s") ', date("H:i"), date("H:i")) ."or (minTime=\"00:00\" or maxTime=\"00:00\"))";
            $result = $this->conn->query($query);
            $row = $result->num_rows;
            $isMore = $row - (($page === 0 || $page === 1 ? 1 : $page) * 10);
            $isMore = $isMore > 0 ? True : False;
            return array("data"=>$array, "isMore"=>$isMore);
        } else {
            return false;
        }
    }

    public function create($table, $productName, $productImage, $sellerName, $category, $subCategory, $stock, $publish, $description, $unit, $price, $discount, $popular)
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

    public function update($table, $id, $productName, $sellerName, $category, $subCategory, $stock, $publish, $description, $range, $price, $discount, $popular, $image = "")
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
        $range = $this->conn->real_escape_string($range);
        $price = $this->conn->real_escape_string($price);
        $discount = $this->conn->real_escape_string($discount);
        $popular = $this->conn->real_escape_string($popular);
        $image = $this->conn->real_escape_string($image);

        if (isset($image) && $image != "") {
            $query = "UPDATE $table set pname='$productName', sname='$sellerName', cid='$category', sid='$subCategory', stock='$stock', status='$publish', psdesc='$description', pgms='$range', pprice='$price', discount='$discount', popular='$popular', pimg='$image' where id=$id";
        } else {
            $query = "UPDATE $table set pname='$productName', sname='$sellerName', cid='$category', sid='$subCategory', stock='$stock', status='$publish', psdesc='$description', pgms='$range', pprice='$price', discount='$discount', popular='$popular' where id=$id";
        }
        $result = $this->conn->query($query);
        if ($this->conn->affected_rows > 0) {
            return true;
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
