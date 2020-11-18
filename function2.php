<?php
//start session

session_start();


function pretty_print($data){
     echo "<pre>";
     print_r($data);
     echo "</pre>";
 }

$dbname = "helloworld";
$servername = "localhost";
$username = "phpdev";
$password = "123456";

//

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);//where the connection 
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}



//SELECT Data
function getPosts($search,$category,$offset=0,$limit=2,$count=false){
    global $conn;
    $query ="SELECT * FROM `posts` ";
    if($search){
        $query.="WHERE `title` LIKE :search ";
    }
    if($category){
        $query.= $search ? " AND " : " WHERE ";
        $query.=" `category` = :category ";
    }

    $query .= "ORDER BY `created_at` DESC LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($query);

    if($search){
        $search = "%".$search."%";
        $stmt->bindParam(":search", $search);
    }
    if($category){
        $stmt->bindParam(":category",$category);
    }
    $stmt->bindParam(":limit",$limit,PDO::PARAM_INT);
    $stmt->bindParam(":offset",$offset,PDO::PARAM_INT);

    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); //for associated arrray
    return $stmt->fetchAll();

} 
//SELECT Data count
function getPostsCount($search,$category){
    global $conn;
    $query ="SELECT COUNT(*) as total FROM `posts` ";
    if($search){
        $query.="WHERE `title` LIKE :search ";
    }
    if($category){
        $query.= $search ? " AND " : " WHERE ";
        $query.=" `category` = :category ";
    }

    $query .= "ORDER BY `created_at` DESC";

    $stmt = $conn->prepare($query);

    if($search){
        $search = "%".$search."%";
        $stmt->bindParam(":search", $search);
    }
    if($category){
        $stmt->bindParam(":category",$category);
    }

    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); //for associated arrray
    return $stmt->fetchColumn();

}

/**
 * INSERT Data
 */

 function insertPost($new_post){
     global $conn;
     $query="INSERT INTO `posts`( `title`, `thumbnail`, `description`, `category`) 
     VALUES (:title, :thumbnail, :description, :category)";
     $stmt = $conn->prepare($query);
     $stmt->bindParam(":title",$new_post['title']);
     $stmt->bindValue(":thumbnail",$new_post['thumbnail']);
     $stmt->bindParam(":description",$new_post['description']);
     $stmt->bindParam(":category",$new_post['category']);
     return $stmt->execute();
 }


 function deletePost($id){
     global $conn;
     $query = "DELETE FROM `posts` WHERE `id` = :id ";
     $stmt = $conn->prepare($query);
     $stmt->bindParam(":id",$id);
     return $stmt->execute();
 }

 function findPostById($id){
     global $conn;
     try{
        $query = "SELECT * FROM `posts` WHERE `id`=:id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id",$id);

        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch();
     }
     catch(PDOExpression $e){
        print($e->getMessage());
    }
 }

 function updatePost($id,$update_post){

    global $conn;
    try{
    $query = "UPDATE `posts` SET `title`=:title,`description`=:description,`category`=:category ";
    if(!empty($update_post['thumbnail'])){
        $query .= " ,`thumbnail`=:thumbnail ";
    }
    $query .=" WHERE `id`=:id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":title",$update_post['title']);
    $stmt->bindParam(":description",$update_post['description']);
    $stmt->bindParam(":category",$update_post['category']);
    if(!empty($update_post['thumbnail'])){
        $stmt->bindValue(":thumbnail",$update_post['thumbnail']);
    }
    $stmt->bindParam(":id",$id);
    return $stmt->execute();
    } catch(PDOExpression $e){
        die($e->getMessage());
    }
 }


 /*
 save into session for formdata (temporary)
 */

 function flash($array){
    $_SESSION ['formdata']= $array;
 }
 /** 
  * output form value stored in session (just one time)
  */
 function old($key){
     $value = isset($_SESSION['formdata'][$key]) ? $_SESSION['formdata'][$key]:null;
     if($value){
         unset($_SESSION['formdata'][$key]);
     }
     return $value;
 }




 /*
 for view funciton
 */

 function getInformation($id){
    global $conn;
    $query ="SELECT * FROM `posts` WHERE `id` = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id",$id);
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); //for associated arrray
    return $stmt->fetch();
    
 }
/*
$posts = [
            [
                "id"=>1,
                "title"=>"Bunny With Yellow Background",
                "thumbnail"=>"https://cdn.pixabay.com/photo/2016/12/04/21/58/rabbit-1882699__340.jpg",
                "description"=>"Description 1",
                "created_at"=>"2020-01-13 15:25:42",
                "category"=>"sports"
            ],
            [
                "id"=>2,
                "title"=>"Girl with Rubbit",
                "thumbnail"=>"https://cdn.pixabay.com/photo/2018/09/07/13/28/rabbit-3660673_960_720.jpg",
                "description"=>"Description 2",
                "created_at"=>"2020-01-13 17:25:42",
                "category"=>"health"
            ],
            [
                "id"=>3,
                "title"=>"Cute RB on the grass",
                "thumbnail"=>"https://cdn.pixabay.com/photo/2016/12/13/00/13/rabbit-1903016_960_720.jpg",
                "description"=>"Description 3",
                "created_at"=>"2020-01-13 19:25:42",
                "category"=>"sports"
            ]
        ];

        */

