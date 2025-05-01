<!-- Header -->
<header>
    <a href="index.php"><div class="logo">Tripla</div></a>
    <nav>
        <ul class="nav-links">
            <li><a href="#" id="">Button1</a></li>
            <li><a href="#" id="">Button2</a></li>
            <li><a href="?page=recommendations">Recommended</a></li>
            <li><a href="?page=account"><?php echo (isset($_SESSION["user"])? "Profile" : "Login") ?></a></li>
        </ul>
    </nav>
</header>

<!-- Banner -->
<section class="banner">
    <h1>Explore, Enjoy, Ease up</h1>
</section>
