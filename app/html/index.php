<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LAMP STACK</title>
        <link rel="stylesheet" href="/html/assets/css/bulma.min.css">
    </head>
    <body>
        <section class="hero is-medium is-info is-bold">
            <div class="hero-body">
                <div class="container has-text-centered">
                    <h1 class="title">
                        LAMP STACK
                    </h1>
                    <h2 class="subtitle">
                        Your local development environment
                    </h2>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="columns">
                    <div class="column">
                        <h3 class="title is-3 has-text-centered">Environment</h3>
                        <hr>
                        <div class="content">
                            <ul>
                                <li><?= apache_get_version(); ?></li>
                                <li>PHP <?= phpversion(); ?></li>
                                <li>
                                    <?php
                                    $link = mysqli_connect("localhost", "root", "", null);

/* check connection */
                                    if (mysqli_connect_errno()) {
                                        printf("MySQL connecttion failed: %s", mysqli_connect_error());
                                    } else {
                                        /* print server version */
                                        printf("MySQL Server %s", mysqli_get_server_info($link));
                                    }
                                    /* close connection */
                                    mysqli_close($link);
                                    ?>
                                </li>

                                <li><a href="/html/phpinfo.php">phpinfo()</a></li>
                                <li><a href="/html/test_db.php">Test DB Connection with mysqli</a></li>
                                <li><a href="/html/test_db_pdo.php">Test DB Connection with PDO</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="column">
                        <h3 class="title is-3 has-text-centered">Quick Links</h3>
                        <hr>
                        <div class="content">
                            <ul>
                                <li><a href="http://localhost/phpmyadmin" >phpMyAdmin</a></li>
                                <li><a style="cursor: copy" title="Author: Trần Văn Nghĩa" href="/html/phpmyadmin.php">phpMyAdmin - Updated MySql</a></li>
                                <li><a href="/html/upload">Upload Databases</a></li>
                                <li><a href="/html/contact.html">Contact Author</a> ----------------</li>
                                <li><a style="cursor: copy" title="Author: Trần Văn Nghĩa" href="http://localhost:<? print $_ENV['KETOAN_ACN_PORT']; ?>" target="_blank">ACN - Kế toán Doanh Nghiệp</a></li>
                                <li><a style="cursor: copy" title="Author: Trần Văn Nghĩa" href="http://localhost:<? print $_ENV['KETOAN_DOC_PORT']; ?>" target="_blank">DOCKER - Kế toán Doanh Nghiệp</a></li>
                                <li><a style="cursor: copy" title="Author: Trần Văn Nghĩa" href="http://nghiatv.googlepages.com" target="_blank">VFP - Kế toán Doanh Nghiệp</a></li>
                        </ul>
                        </div>
                    </div>
                    <div class="column">
                        <h3 class="title is-3 has-text-centered">Powered by</h3>
                        <hr>
                        <div class="content">
                        <!-- <a style="cursor: copy" title="Author: Trần Văn Nghĩa" href="">Email: nghiatv@gmail.com</a> -->

                        <div style="color: darkolivegreen">
                            <img
                                src="nghia.png"
                                name="aboutme"
                                width="200"
                                height="200"
                                class="avatar-ecommerce"
                                style="border-radius: 50%"
                            />
                                                
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>

<!-- body {
    padding-bottom: 6rem;
    padding-top: 6rem;
} -->