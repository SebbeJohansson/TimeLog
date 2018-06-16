<?php


?>


<section class="container-fluid">
    <section class="row justify-content-center">
        <section class="col-lg-4 col-sm-6 col-12">
            <form method='post'  id='loginform' enctype='multipart/form-data'>
                <section class="form-group">
                    <label for="username">Username:</label>
                    <input type='text' name='username' id='username' placeholder='Username' class="form-control">
                </section>

                <section class="form-group">
                    <label for="password">Password:</label>
                    <input type='password' name='password' id='password' placeholder='Password' class="form-control">
                </section>

                <input type='hidden' name='action' value='login'>

                <button type='submit' name='login' class="btn btn-primary">login</button>
            </form>
        </section>
    </section>
</section>
