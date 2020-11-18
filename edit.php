<?php
require("function2.php");
$errors = array();

//GET ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;//to get integer only
if(!$id){
    header("Location:index.php");
}
// if(!$id){
//     header("Location:index.php");
// }else{
//    echo pretty_print(findPostById($id));
// }

$n_post = findPostById($id);


if(isset($_POST['submit'])){

    $update_post = array();
    
    $pattern = '/[^a-zA-Z0-9\s]+/';
    preg_match($pattern, $_POST['title'],$matches );//remove regular expression $$$#$%


    if(empty($_POST['title']) || strlen($_POST['title']) < 5 || count($matches)>0) {
        $errors['title'] = "Title must be at least 5 characters in length";
    }else{
        $update_post['title'] = $_POST['title'];
    }


    //validate category

    if(empty($_POST['category']) || strlen($_POST['category']) < 4){
        $errors['category'] = "Title must be at least 5 characters in length!";
    }else{
        $update_post['category'] = $_POST['category'];
    }

    //validate description

    if(empty($_POST['description']) || strlen($_POST['description']) < 5){
        $errors['description'] = "Title must be at least 5 characters in length!";
    }else{
        $update_post['description'] = $_POST['description'];
    }



    //to upload files------------------------------------------------------
    if(!empty($_FILES['thumbnail']['name'])){
        $thumbnail = $_FILES['thumbnail'];
        $allowed_types = ["image/jpg","image/png","image/jpeg"];
        if(!in_array($thumbnail['type'],$allowed_types) || $thumbnail['size'] > 3000000 ){
            $errors['thumbnail'] = "File type must be jpeg or jpg or png image and must not be larger than 2MB.";
        }else{
            //file upload
            $filename = time().'-'.$thumbnail['name'];
            move_uploaded_file($thumbnail['tmp_name'],"upload/".$filename);
            $update_post['thumbnail'] = $filename;

        }
    }

    if(count($errors) == 0){
        //pretty_print($new_post);
        //update into db
        $id = $_POST['id'];
        if(updatePost($id,$update_post)){
            header("Location:index.php");
        }else{
            $errors['save'] = "somethng went wrong while saving the post.Please try again.";
        }

    }




}




?>



<?php include("includes/header.php") ?>
    
<div class="container">

<h2 class="py-3">Update A New Post</h2>

<?php if(isset($errors['save'])): ?>
    <p class="alert alert-danger"><?php echo $errors['save']; ?></p>
<?php endif; ?>

    <form action="" method = "POST" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?php echo $n_post['id']; ?>">

        <div class="form_group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo $n_post['title']; ?>"> 
            <?php if(isset($errors['title'])): ?>
                <p class="text-danger"><?php echo $errors['title']; ?></p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Photo</label>
            <input type="file" name = "thumbnail" class="form-control">
            <?php if(isset($errors['thumbnail'])): ?>
                <p class="text-danger"><?php echo $errors['thumbnail']; ?></p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" class="form-control" value="<?php echo $n_post['category']; ?>">
            <?php if(isset($errors['category'])): ?>
                <p class="text-danger"><?php echo $errors['category']; ?></p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="">Description</label>
            <?php if(isset($errors['description'])): ?>
                <p class="text-danger">description must be any 15characters length</p>
            <?php endif; ?>
            <textarea name="description" class="form-control"><?php echo $n_post['description']; ?></textarea>
        </div>

        <div class="form-group">
            <button name="submit" class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-default"></a>
        </div>
    </form>
</div>



<?php include("includes/footer.php") ?>