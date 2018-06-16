<?php
    /**
     * Created by Sebbans.
     * Date: 2018-04-18
     * Time: 21:18
     */

    $page = "";
    if(isset($_GET['page'])) {
        $page = $_GET['page'];
    }

?>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark row">
    <ul class="navbar-nav col-8 justify-content-center ml-2">
        <li class="nav-item <?php if ($page == "") echo "active"; ?>">
            <a class="nav-link" href="?">Home</a>
        </li>
        <li class="nav-item <?php if ($page == "logs") echo "active"; ?>">
            <a class="nav-link" href="?page=logs">Logs</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" id='logout' onclick='logoutUser()'>Logout</a>
        </li>
    </ul>
</nav>