<?php

require("function2.php");
$information = $_GET['id'];
$views = getInformation($information);

?>
<?php include("includes/header.php"); ?>

<div class="container">
    <table class="table table-dark table-striped">
        <thead class="table-dark">
            <tr>
                <th>Object</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>ID</td>
                <td><?php echo $views['id']; ?></td>
            </tr>
            <tr>
                <td>Title</td>
                <td><?php echo $views['title']; ?></td>
            </tr>
            <tr>
                <td>Image</td>
                <td><img src="upload/<?php echo $views["thumbnail"]; ?>" style="width:200px";/></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?php echo $views['description']; ?></td>
            </tr>
            <tr>
                <td>Category</td>
                <td><?php echo $views['category']; ?></td>
            </tr>
            <tr>
                <td>Created At</td>
                <td><?php echo $views['created_at']; ?></td>
            </tr>
            
        </tbody>
    </table>
</div>


<?php include("includes/footer.php") ?>