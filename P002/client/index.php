<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>P002 demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/ico" href="img/logo.ico">
    <link rel="stylesheet" href="style.css">
</head>
<?php
include ('navbar.html')
    ?>

<style>
    .row {
        margin-bottom: 10px;
    }

    .home-icon {
        margin-top: 10px;
    }

    #cookie-banner {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #343a40;
        color: #fff;
        padding: 15px;
        text-align: center;
        z-index: 1000;
        display: none;
    }
</style>

<div id="cookie-banner" class="alert alert-dark alert-dismissible fade show" role="alert">
    Questo sito utilizza solo cookie tecnici per migliorare la tua esperienza.
    <button type="button" class="btn btn-success" id="accept-cookies">Ho capito</button>
</div>

<div class="container text-center">
    <div class="row">
        <div class="col">
            <p class="text-center text-info display-1 title">Welcome to ZCONNECT <br></p>
            <div class="alert alert-info text-center" role="alert">The site to search teachers, to book rooms and have a friendly school interaction.
            </div>
            <img src="img/zconnect_logo1.jpeg" alt="ZConnect" height="300px">
        </div>
    </div>
    <br>
    <hr class="border border-primary border-3 opacity-75"><br>
    <div class="row">
        <h1 class="text-center display-3 text-info title">Features</h1>
        <div class="col">
            <div class="card" style="width: 18rem;">
                <a href="signup.php">
                    <div class="card-img-top d-flex justify-content-center align-items-center" style="height: 10rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor"
                            class="text-primary home-icon" class="bi bi-person-add" viewBox="0 0 16 16">
                            <path
                                d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                            <path
                                d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z" />
                        </svg>
                    </div>
                </a>
                <div class="card-body">
                    <!-- <span class="badge text-bg-success"> -->
                    <h5 class="card-title">Sign Up</h5>
                    <p class="card-text">Registration enables users to create a new account on our site, providing
                        access to exclusive features and personalized benefits
                    </p>
                </div>
                <div class="card-body text-center">
                    <a href="signup.php" class="card-link link-underline-light">
                        Sign Up <i class="bi bi-box-arrow-up-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card" style="width: 18rem;">
                <a href="login.php">
                    <div class="card-img-top d-flex justify-content-center align-items-center" style="height: 10rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor"
                            class="text-primary home-icon" class="bi bi-box-arrow-in-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0z" />
                            <path fill-rule="evenodd"
                                d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                        </svg>
                    </div>
                </a>
                <div class="card-body">
                    <h5 class="card-title">Log in</h5>
                    <p class="card-text">Sign in to your existing account to gain access to site features, manage your
                        personal settings, and interact with content in a personalized way</p>
                </div>
                <div class="card-body text-center">
                    <a href="login.php" class="card-link link-underline-light">Log in <i
                            class="bi bi-box-arrow-up-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card" style="width: 18rem;">
                <a href="search.php">
                    <div class="card-img-top d-flex justify-content-center align-items-center" style="height: 10rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor"
                            class="text-primary home-icon" class="bi bi-calendar" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                        </svg>
                    </div>
                </a>
                <div class="card-body">
                    <h5 class="card-title">Search</h5>
                    <p class="card-text">Utilize the search function to discover teachers and their schedule, in order
                        to help you in the scolastic environment</p>
                </div>
                <div class="card-body text-center">
                    <a href="search.php" class="card-link link-underline-light">Search <i
                            class="bi bi-box-arrow-up-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card" style="width: 18rem;">
                <a href="booking.php">
                    <div class="card-img-top d-flex justify-content-center align-items-center" style="height: 10rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor"
                            class="bi bi-clipboard-check text-primary home-icon" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                            <path
                                d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z" />
                            <path
                                d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z" />
                        </svg>
                    </div>
                </a>
                <div class="card-body">
                    <h5 class="card-title">Booking</h5>
                    <p class="card-text">Secure your spot in our laboratories by scheduling your appointments in
                        advance. Enjoy hassle-free access to our facilities and ensure you make the most of your time
                        with us.
                    </p>
                </div>
                <div class="card-body text-center">
                    <a href="booking.php" class="card-link link-underline-light">
                        Booking <i class="bi bi-box-arrow-up-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card" style="width: 18rem;">
                <a href="assignment.php">
                    <div class="card-img-top d-flex justify-content-center align-items-center" style="height: 10rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor"
                            class="bi bi-list-check" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3.854 2.146a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 3.293l1.146-1.147a.5.5 0 0 1 .708 0m0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 7.293l1.146-1.147a.5.5 0 0 1 .708 0m0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0" />
                        </svg>
                    </div>
                </a>
                <div class="card-body">
                    <h5 class="card-title">Assignment</h5>
                    <p class="card-text">Insert or book your work and school assignments for greater organization
                    </p>
                </div>
                <div class="card-body text-center">
                    <a href="assignment.php" class="card-link link-underline-light">
                        Assignment <i class="bi bi-box-arrow-up-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card" style="width: 18rem;">
                <a href="schedule.php">
                    <div class="card-img-top d-flex justify-content-center align-items-center" style="height: 10rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor"
                            class="text-primary home-icon bi bi-calendar-day" viewBox="0 0 16 16">
                            <path
                                d="M4.684 11.523v-2.3h2.261v-.61H4.684V6.801h2.464v-.61H4v5.332zm3.296 0h.676V8.98c0-.554.227-1.007.953-1.007.125 0 .258.004.329.015v-.613a2 2 0 0 0-.254-.02c-.582 0-.891.32-1.012.567h-.02v-.504H7.98zm2.805-5.093c0 .238.192.425.43.425a.428.428 0 1 0 0-.855.426.426 0 0 0-.43.43m.094 5.093h.672V7.418h-.672z" />
                            <path
                                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                        </svg>
                    </div>
                </a>
                <div class="card-body">
                    <h5 class="card-title">Schedule</h5>
                    <p class="card-text">Check your school schedule to organize your homework and assignment in order to
                        have school benefits
                    </p>
                </div>
                <div class="card-body text-center">
                    <a href="schedule.php" class="card-link link-underline-light">
                        Schedule <i class="bi bi-box-arrow-up-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include ('footer.html')
    ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var banner = document.getElementById("cookie-banner");
        var button = document.getElementById("accept-cookies");
        var cookieName = "cookies_accepted";

        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length === 2) return parts.pop().split(";").shift();
        }

        if (!getCookie(cookieName)) {
            banner.style.display = "block";
        }

        button.addEventListener("click", function () {
            banner.style.display = "none";
            var date = new Date();
            date.setTime(date.getTime() + (90 * 24 * 60 * 60 * 1000)); // 3 mesi di durata
            var expires = "expires=" + date.toUTCString();
            document.cookie = cookieName + "=true;" + expires + ";path=/";
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>


</html>