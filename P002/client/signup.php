<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/ico" href="img/logo.ico">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    include ('navbar.html');
    ?>

    <div class="container" id="message">
        <div class="alert alert-success text-center" role="alert">Are you a student? Sign UP</div>
        <div class="alert alert-warning text-center" role="alert">Are you a teacher or a technician? Don't warry, you've been already registered <br> You should have your password to <span><a href="login.php">Log in</a></span></span></div>
    </div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header border-primary">
                        <h4 class="text-center text-primary display-4 title">Sign Up</h4>
                    </div>
                    <div class="card-body">
                        <form id="signup-form">
                            <div class="mb-3">
                                <label for="name">
                                    <h6 class="text-info">Name</h6>
                                </label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Name">
                            </div>
                            <div class="mb-3">
                                <label for="surname">
                                    <h6 class="text-info">Surname</h6>
                                </label>
                                <input type="text" class="form-control" name="surname" id="surname"
                                    placeholder="Surname">
                            </div>
                            <div class="mb-3">
                                <label for="email">
                                    <h6 class="text-info">Email</h6>
                                </label>
                                <input type="text" class="form-control" name="email" id="email"
                                    placeholder="name.surname@itiszuccante.edu.it">
                            </div>
                            <div class="mb-3">
                                <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    <h6 class="text-info">Choose your role</h6>
                                </label>
                                <select id="role" name="role" class="form-select" aria-label="Default select example"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    onchange="disableOption()">
                                    <option selected value="student">Student</option>
                                    <option disabled value="teacher" hidden>Teacher</option>
                                    <option disabled value="technician" hidden>Technician</option>
                                </select>
                            </div>
                            <div class="mb-3" id="select-co">
                                <label for="class" placeholder="class">
                                    <h6 class="text-info">Choose your class</h6>
                                </label>
                                <select id="class" name="class" class="form-select" aria-label="Default select example">
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="password">
                                    <h6 class="text-info">Insert your password</h6>
                                </label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Password">
                            </div>
                            <div>
                                <p>
                                    Already have an account?
                                    <a href="login.php">Log In</a>
                                </p>
                            </div>
                            <br>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5" id="id1"></div>


    <?php
    include ('footer.html')
        ?>
    <script>
        getClass();
        submitForm()
        function disableOption() {
            let typeSelect = document.getElementById('role');
            let classSelect = document.getElementById('class');
            if (typeSelect.value == "student") {
                classSelect.disabled = false;
            } else {
                classSelect.disabled = true;
            }
        }

        function submitForm() {
            const form = document.querySelector('#signup-form');
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(form);

                fetch('../server/user/signup', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data)
                        if (data['message']) {
                            const el = document.querySelector('#id1');
                            el.innerHTML = `<div class="alert alert-danger text-center" role="alert">${data['message']}!</div>`
                        } else if (data['success']) {
                            window.location.href = 'login.php';
                        }
                    })
                    .catch((error) => {
                        console.log('Errore: ', error);
                    });
            })
        }

        function getClass() {
            const selectEl = document.querySelector('#class');
            // const title = document.querySelector('#title');
            fetch('../server/class', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    if (data['message']) {

                        // title.innerHTML = `<p class="display-1 text-center text-danger">${data['message']}</p>`;
                        // selectEl.innerHTML = '';
                    } else {
                        data.forEach(element => {
                            let option = `<option value="${element.id}">${element.name}</option>`;
                            selectEl.innerHTML += option;
                        });
                    }
                })
                .catch((error) => {
                    console.log('Errore: ', error);
                });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>