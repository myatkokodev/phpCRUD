<?php
require("function2.php");
$errors = array();

if(isset($_POST['submit'])){

    $new_post = array();

    //save in session
    flash($_POST);

    $pattern = '/[^a-zA-Z0-9\s]+/';
    preg_match($pattern, $_POST['title'],$matches );//remove regular expression $$$#$%

    if(empty($_POST['title']) || strlen($_POST['title']) < 5 || count($matches)>0) {
        $errors['title'] = "Title must be at least 5 characters in length";
    }else{
        $new_post['title'] = $_POST['title'];
    }


    //validate category

    if(empty($_POST['category']) || strlen($_POST['category']) < 4){
        $errors['category'] = "Title must be at least 5 characters in length!";
    }else{
        $new_post['category'] = $_POST['category'];
    }

    //validate description

    if(empty($_POST['description']) || strlen($_POST['description']) < 15){
        $errors['description'] = "Title must be at least 5 characters in length!";
    }else{
        $new_post['description'] = $_POST['description'];
    }



    if(!empty($_FILES['thumbnail']['name'])){
        $thumbnail = $_FILES['thumbnail'];
        $allowed_types = ["image/jpg","image/png","image/jpeg"];
        if(!in_array($thumbnail['type'],$allowed_types) || $thumbnail['size'] > 3000000 ){
            $errors['thumbnail'] = "File type must be jpeg or jpg or png image and must not be larger than 3MB.";
        }else{
            //file upload
            $filename = time().'-'.$thumbnail['name'];
            move_uploaded_file($thumbnail['tmp_name'],"upload/".$filename);
            $new_post['thumbnail'] = $filename;

        }
    }

    if(count($errors) == 0){
        //pretty_print($new_post);
        //insert into db
        if(insertPost($new_post)){
            header("Location:index.php");
        }else{
            $errors['save'] = "somethng went wrong while saving the post.Please try again.";
        }

    }




}




?>



<?php include("includes/header.php") ?>
    
<div class="container">

<h2 class="py-3">Create a New Blog Post</h2>

<?php if(isset($errors['save'])): ?>
    <p class="alert alert-danger"><?php echo $errors['save']; ?></p>
<?php endif; ?>

    <form action="" method = "POST" enctype="multipart/form-data">
        <div class="form_group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo old('title'); ?>"> 
            <?php if(isset($errors['title'])): ?>
                <p class="text-danger">Title must be any 5characters length and no symbols allowed</p>
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
            <input type="text" name="category" class="form-control" value="<?php echo old('category'); ?>">
            <?php if(isset($errors['category'])): ?>
                <p class="text-danger">category must be any 4characters length</p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="">Description</label>
            <?php if(isset($errors['description'])): ?>
                <p class="text-danger">description must be any 15characters length</p>
            <?php endif; ?>
            <textarea name="description" class="form-control"><?php echo old('description'); ?></textarea>
        </div>

        <div class="form-group">
            <button name="submit" class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-default"></a>
        </div>
    </form>
</div>



<?php include("includes/footer.php") ?>