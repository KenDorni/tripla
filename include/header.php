<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <!-- Top header with logo and signin -->
            <div class="top-header">
                <a href="index.php" class="logo">Tripla</a>
                <a href="?page=account" class="sign-in"><?php echo (isset($_SESSION["user"])? "Profile" : "Sign in") ?></a>
            </div>
            
            <!-- Bottom header with navigation buttons -->
            <div class="nav-container">
                <ul class="nav-links">
                    <li><a href="#"></i>Button 1</a></li>
                    <li><a href="#"></i>Button 2</a></li>
                    <li><a href="#"></i>Button 3</a></li>
                    <li><a href="#"></i>Button 4</a></li>
                    <li><a href="#"></i>Button 5</a></li>
                    <li><a href="#"></i>Button 6</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Banner -->
    <section class="banner">
        <div class="banner-container">
            <h1 class="slogan">Explore, Enjoy, Ease up</h1>
        </div>
    </section>
</body>
</html>