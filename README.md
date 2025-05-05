<a name="readme-top"></a>

<br />
<div align="center">
  <a href="https://github.com/KenDorni/tripla">
    <img src="https://imgur.com/2K65gUp.png" alt="TriPla Logo" width="300" height="200">
  </a>

  <h2 align="center">TriPla Project</h2>
  <p align="center">Your travel companion for finding the perfect trip destination</p>
</div>

<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#xampp">XAMPP</a></li>
        <li><a href="#database">Database</a></li>
      </ul>
    </li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgments">Acknowledgments</a></li>
  </ol>
</details>

## About The Project

This is a school project created by four students from Lycée des Arts et Métiers. Our goal is to help users who are uncertain about their travel destination by providing tailored suggestions. We host the project locally using XAMPP and manage our data with MySQL. Additionally, we integrate various APIs to fetch the necessary information.

<img src="https://i.imgur.com/u0KTK7f.png" alt="TriPla Website Example">

<p align="right">(<a href="#readme-top">back to top</a>)</p>

### Built With

* [![HTML][HTML.js]][HTML-url]
* [![CSS][CSS.js]][CSS-url]
* [![JavaScript][JavaScript.js]][JavaScript-url]
* [![PHP][PHP.io]][PHP-url]
* [![SQL][SQL.io]][SQL-url]
* [![JQuery][JQuery.com]][JQuery-url]


<p align="right">(<a href="#readme-top">back to top</a>)</p>

## Getting Started

To set up the project locally, follow these steps:

### XAMPP (Local Hosting)

Download and install [XAMPP](https://sourceforge.net/projects/xampp/files/). Once installed:

* Start both **Apache** and **MySQL**.
* Access the site locally at: [http://localhost/TriPla/](http://localhost/TriPla/).

<img src="https://i.imgur.com/zrP2Ve7.png" alt="XAMPP Control Panel" width="500" height="300">

### Database (phpMyAdmin)

* Open your browser and navigate to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
* If you do not see the SQL interface, click on **New** on the left sidebar.
* You should see a screen similar to this:

<img src="https://i.imgur.com/Qo4oBiB.png" alt="phpMyAdmin Interface" width="500" height="300">

* To import the SQL data, click on the **Import** tab and select the [TriPla SQL file](https://github.com/KenDorni/tripla/tree/main/assets/php/database/tripla.sql) or copy its contents into the SQL query field, then click **Go**.
* If you wish to change the database credentials, refer to the [db_credentials.php](https://github.com/KenDorni/tripla/tree/main/assets/php/database/db_credentials.php) file. The default credentials are:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tripla');
?>
```
<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- CONTRIBUTING -->
## Contributing

Contributions make the open source community an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

To contribute:

1. Fork the repository.
2. Create your feature branch (`git checkout -b feature/TriPla`).
3. Commit your changes (`git commit -m 'Add some TriPla improvements'`).
4. Push to the branch (`git push origin feature/TriPla`).
5. Open a pull request.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- CONTACT -->
## Contact

* Kenneth Dornseiffer - [dorke108@school.lu](mailto:dorke108@school.lu)
* Thibaut Friederici - [frith033@school.lu](mailto:frith033@school.lu)
* Tony Wang - [wanto443@school.lu](mailto:wanto443@school.lu)
* Raphael Scherer - [schra239@school.lu](mailto:schra239@school.lu)

Project Link: [https://github.com/KenDorni/tripla](https://github.com/KenDorni/tripla)

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- ACKNOWLEDGMENTS -->
## Acknowledgments

* [ReadMe Template](https://github.com/othneildrew/Best-README-Template)
* [w3schools](https://www.w3schools.com)
* [LAM](https://www.artsetmetiers.lu)
* [XAMPP](https://www.apachefriends.org/de/index.html)

<p align="right">(<a href="#readme-top">back to top</a>)</p>

[HTML.js]: https://img.shields.io/badge/HTML-withe?style=for-the-badge&logo=html&color=orange
[HTML-url]: https://html.com
[CSS.js]: https://img.shields.io/badge/CSS-withe?style=for-the-badge&logo=CSS&color=blue
[CSS-url]: https://wiki.selfhtml.org/wiki/CSS
[JavaScript.js]: https://img.shields.io/badge/JavaScript-withe?style=for-the-badge&logo=javascript
[JavaScript-url]: https://www.javascript.com
[PHP.io]: https://img.shields.io/badge/PHP-withe?style=for-the-badge&logo=PHP&color=grey
[PHP-url]: https://www.php.net
[SQL.io]: https://img.shields.io/badge/SQL-withe?style=for-the-badge&logo=SQL&color=grey
[SQL-url]: https://sql.sh
[JQuery.com]: https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white
[JQuery-url]: https://jquery.com 
