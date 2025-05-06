<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <!-- Top header with logo and signin -->
            <div class="top-header">
                <a href="index.php" class="logo">Tripla</a>
                <?php
                if (isset($_SESSION["user"])){
                    echo '<div class="acc-dropdown">
                             <span class="sign-in">' . $_SESSION["user"]["username"] . '</span>
                             <div class="acc-dropdown-content">
                                <a href="?page=account" class="sign-in">Account</a>
                                <a href="?page=myItinerary" class="sign-in">My Itineraries</a>
                                <form method="post">
                                    <button type="submit" class="sign-in" name="logout" value="logout">Log Out</button>
                                </form>
                             </div>
                          </div>';
                }else{
                        echo '<a href="?page=login" class="sign-in">Sign-in</a>';
                    }
                ?>
                <!--<a href="?page=account" class="sign-in"></a>-->
            </div>
            
            <!-- Bottom header with navigation buttons -->
            <div class="nav-container">
                <ul class="nav-links">
                    <li><a href="?page=recommendations"></i>Recommendations</a></li>
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