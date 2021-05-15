<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="templates/css/login.css">

    <title>Login</title>
    <link rel="icon" type="png" href="templates/img/logoatas.png">
</head>

<body>
    <?php
        session_start();
        if(isset($_SESSION['message'])){
    ?>

     <div>
        <div class="alert alert-<?php echo $_SESSION['info']?>" role="alert"> 
          <?php
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            ?>
        </div>
    </div>
    <?php } ?>

    <section class="grid-products">
        <div class="jumbotron jumbotron-fluid">
            <div class="row">
                <div class="col-lg-6" id="kiri">
                    <!-- <h1>WELLCOME to WARUNG KOMPUTER</h1> -->
                </div>
                <div class="col-lg-6">
                    <div class="d-grid gap-2 d-md-block">
                        <img src="templates/img/logo1.png" alt="logo" id="gambar">
                        <a href="index.php" class="btn float-end but">Back</a>
                    </div>
                    <div class="d-grid gap-2 d-md-block">
                        <form action="signin.php" method="post">
                            <h2>Sign In</h2>
                            <p >Log in with your data that you entered during your registration</p>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Username</label>
                                <input type="txt" class="form-control" name="uname">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" name="pwd">
                            </div>
                            <button type="submit" class="btn btn-primary" name="login">Log in</button>
                            <p class="bawah">DON’T HAVE AN ACCOUNT?
                            <a href="register.php">Sign Up</a>
                        </from>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
        crossorigin="anonymous"></script>

    
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
    -->
</body>

</html>