<?php

        require("function2.php");


        
        // //sorting array by created_at in DESC
        // usort($posts , function($a,$b){
        //     return $b["created_at"] <=>$a["created_at"];
        // });

        // //search by title
        $search = isset($_GET['q']) ? $_GET['q']:null;
         if($search != null){
        //     $posts = array_filter($posts,function($item) use($search){
        //         return is_numeric(stripos($item['title'],$search));
        //     });
        //'q'=>
         flash(['q'=>$search]);

        }
        

        //filter by category
        $category = isset($_GET['category']) ? $_GET['category']:null;
        
        $id = isset($_GET['id'])?$_GET['id']:null;
        
        if($id){
            if(deletePost($id)){
                header("location:index.php?delete-success=true");
             }else{
                header("location:index.php?delete-success=false");
            }
        }
        
        // if($category != null){
        //     $posts = array_filter($posts,function($item) use ($category){
        //         return $item['category'] == $category;
        //     });
        // }



        $page = isset($_GET['page'])?(int) $_GET['page']:1;
        $limit = 2;

       $offset = ($page-1) * $limit;//pagination logic
       $total_result = getPostsCount($search,$category);
       
       $total_pages = ceil(($total_result/$limit));



       $posts = getPosts($search,$category,$offset,$limit);


       
?>

<?php include("includes/header.php") ?>

<div class="container-fluid">

<?php include("includes/search_form.php") ?>

    <p class="alert alert-info">
        There are <?php echo count($posts); ?> posts in total.
    </p>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Photo</th>
                <th>Category</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach($posts as $post): ?>


            <tr>
                <td>
                    <?php echo $post["id"];?>
                </td>
                <td>
                    <?php echo $post["title"]; ?>
                </td>
                <td>
                    <img src="upload/<?php echo $post["thumbnail"]; ?>" style="width:100px";/>
                </td>
                <td>
                    <?php echo $post["category"]; ?>
                </td>
                <td>
                    <?php echo $post["created_at"];?>
                </td>
                <td>
                    <a href="?id=<?php echo $post['id']; ?>" 
                    class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete this user?')">Delete</a>
                    <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-info">Edit</a>
                    <a href="view.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-info">View</a>
                </td>
            </tr>


        <?php endforeach;?>


        </tbody>
    </table>
    <hr>
    <nav aria-label="page navigation">
        <ul class="pagination">

            <?php if($page > 1 && $page <= $total_pages): ?>
                <li class="page-item"><a href="?page=<?php echo $page-1; ?>" class="page-link">prv</a></li>
            <?php endif; ?>
            <?php for($i=1;$i<=$total_pages;$i++): ?>

            <li class="page-item <?php echo ($page==$i) ? 'active':''; ?>"><a href="?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a></li>
            <?php endfor; ?>
            
            <?php if($page < $total_pages): ?>
                <li class="page-item"><a href="?page=<?php echo $page+1; ?>"class="page-link">Next</a></li>
            <?php endif; ?>
            
 
        </ul>
    </nav>

</div>

<?php include("includes/footer.php") ?>