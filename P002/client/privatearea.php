<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private Area</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/ico" href="img/logo.ico">
    <link rel="stylesheet" href="style.css">
</head>

<body>
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
    </style>
    <div class="container" id="result"></div>

    <div class="container text-center" id="cards">
        <hr class="border border-primary border-3 opacity-75"><br>
        
    </div>
    <!-- </div> -->

    <?php
    include ('footer.html')
        ?>


    <script>
        fetchUserData();

        function fetchUserData() {
            const result = document.querySelector('#result');
            const cards = document.querySelector('#cards');

            fetch('../server/user')
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if(data['message']){
                        cards.innerHTML = `<div class="alert alert-danger text-center" role="alert">${data['message']}!</div>
                        <div class="container d-flex justify-content-center align-items-center">
                            <a class="btn btn-primary" href="login.php">Login</a>
                        <div>`
                    }else{
                    result.innerHTML = '';

                    let element = `
    <section>
        <div class="container py-5">
        <h1 class="text-center display-1 text-info title">Welcome ${data[0].name}!</h1>
            <div class="row d-flex justify-content-center align-items-center ">
                <div class="col col-lg-6 mb-4 mb-lg-0">
                    <div class="card mb-3" style="border-radius: .5rem;">
                        <div class="row g-0">
                            <div class="col-md-4 gradient-custom text-center text-black"
                                style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                <img src="img/zuc_logo.jpg" alt="Avatar" class="img-fluid my-5" style="width: 100px; height: 100px;"/>
                                <h5>${data[0].name} ${data[0].surname}</h5>
                                <p>${data[0].type}</p>
                                
                                <a href="edit.php" class="link-underline-light">Edit <i class="bi bi-pencil-square"></i></a>
                                
                            </div>
                            <div class="col-md-8">
                                <div class="card-body p-4">
                                    <h6>Information</h6>
                                    <hr class="mt-0 mb-4">
                                    <div class="row pt-1">
                                        <div class="col">
                                            <h6>Email</h6>
                                            <p class="text-muted">${data[0].mail}</p>
                                        </div>
                                    </div>
                                    <h6>Classes</h6>`;
                    if (data[0]['type'] === 'student') {
                        element += `
                                        <div class="row pt-1">
                                            <div class="col-6 mb-3">
                                                <p>${data[0].class}</p>
                                            </div>
                                        </div>`;
                        cards.innerHTML += `
        <div class="row">
            <h1 class="text-center display-2 text-info title">Features</h1>
            <div class="col">
                <div class="card" style="width: 18rem;">
                    <a href="search.php">
                        <div class="card-img-top d-flex justify-content-center align-items-center"
                            style="height: 10rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor"
                                class="text-primary home-icon" class="bi bi-calendar" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </div>
                    </a>
                    <div class="card-body text-center">
                        <h5 class="card-title">Search</h5>
                        <p class="card-text">Utilize the search function to discover teachers and their schedule, in
                            order
                            to help you in the scolastic environment</p>
                    </div>
                    <div class="card-body text-center">
                        <a href="search.php" class="card-link link-underline-light">Search <i
                                class="bi bi-box-arrow-up-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card" style="width: 18rem;">
                    <a href="schedule.php">
                        <div class="card-img-top d-flex justify-content-center align-items-center"
                            style="height: 10rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor"
                                class="text-primary home-icon bi bi-calendar-day" viewBox="0 0 16 16">
                                <path
                                    d="M4.684 11.523v-2.3h2.261v-.61H4.684V6.801h2.464v-.61H4v5.332zm3.296 0h.676V8.98c0-.554.227-1.007.953-1.007.125 0 .258.004.329.015v-.613a2 2 0 0 0-.254-.02c-.582 0-.891.32-1.012.567h-.02v-.504H7.98zm2.805-5.093c0 .238.192.425.43.425a.428.428 0 1 0 0-.855.426.426 0 0 0-.43.43m.094 5.093h.672V7.418h-.672z" />
                                <path
                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                            </svg>
                        </div>
                    </a>
                    <div class="card-body text-center">
                        <h5 class="card-title">Schedule</h5>
                        <p class="card-text">Check your school schedule to organize your homework and assignment in
                            order to
                            have school benefits
                        </p>
                    </div>
                    <div class="card-body text-center">
                        <a href="schedule.php" class="card-link link-underline-light">
                            Schedule <i class="bi bi-box-arrow-up-right"></i></a>
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
        </div>`;
                    }
                    else if (data[0]['type'] === 'teacher') {
                        element += `
                                        <div class="row pt-1">
                                            <div class="col">
                                                <p>${data[0].classes}</p>
                                            </div>
                                        </div>
                                        <h6>Subject</h6>
                                        <div class="row pt-1">
                                            <div class="col">
                                                <p>${data[0].subject}</p>
                                            </div>
                                        </div>    `;
                        cards.innerHTML += `<div class="row">
            <h1 class="text-center display-2 text-info title">Features</h1>
            <div class="col">
                <div class="card" style="width: 18rem;">
                    <a href="search.php">
                        <div class="card-img-top d-flex justify-content-center align-items-center"
                            style="height: 10rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor"
                                class="text-primary home-icon" class="bi bi-calendar" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </div>
                    </a>
                    <div class="card-body text-center">
                        <h5 class="card-title">Search</h5>
                        <p class="card-text">Utilize the search function to discover teachers and their schedule, in
                            order
                            to help you in the scolastic environment</p>
                    </div>
                    <div class="card-body text-center">
                        <a href="search.php" class="card-link link-underline-light">Search <i
                                class="bi bi-box-arrow-up-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card" style="width: 18rem;">
                    <a href="schedule.php">
                        <div class="card-img-top d-flex justify-content-center align-items-center"
                            style="height: 10rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor"
                                class="text-primary home-icon bi bi-calendar-day" viewBox="0 0 16 16">
                                <path
                                    d="M4.684 11.523v-2.3h2.261v-.61H4.684V6.801h2.464v-.61H4v5.332zm3.296 0h.676V8.98c0-.554.227-1.007.953-1.007.125 0 .258.004.329.015v-.613a2 2 0 0 0-.254-.02c-.582 0-.891.32-1.012.567h-.02v-.504H7.98zm2.805-5.093c0 .238.192.425.43.425a.428.428 0 1 0 0-.855.426.426 0 0 0-.43.43m.094 5.093h.672V7.418h-.672z" />
                                <path
                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                            </svg>
                        </div>
                    </a>
                    <div class="card-body text-center">
                        <h5 class="card-title">Schedule</h5>
                        <p class="card-text">Check your school schedule to organize your homework and assignment in
                            order to
                            have school benefits
                        </p>
                    </div>
                    <div class="card-body text-center">
                        <a href="schedule.php" class="card-link link-underline-light">
                            Schedule <i class="bi bi-box-arrow-up-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card" style="width: 18rem;">
                    <a href="booking.php">
                        <div class="card-img-top d-flex justify-content-center align-items-center"
                            style="height: 10rem;">
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
                    <div class="card-body text-center">
                        <h5 class="card-title">Booking</h5>
                        <p class="card-text">Secure your spot in our laboratories by scheduling your appointments in
                            advance. Enjoy hassle-free access to our facilities and ensure you make the most of your
                            time
                            with us.
                        </p>
                    </div>
                    <div class="card-body text-center">
                        <a href="booking.php" class="card-link link-underline-light">
                            Booking <i class="bi bi-box-arrow-up-right"></i></a>
                    </div>
                </div>
            </div>
        </div>`;
                    }else{
                        
                    }
                    element +=
                        `<hr class="mt-0 mb-4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>`;
                    result.innerHTML = element;
                    // cards.innerHTML += ``;
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>