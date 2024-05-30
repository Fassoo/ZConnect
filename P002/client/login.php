<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
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

    <div id="title" class="container">
        <div class="alert alert-info text-center" role="alert">Log in with your email and your password</div>
    </div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header border-primary">
                        <h4 class="display-4 text-center text-primary title">Login</h4>
                    </div>
                    <div class="card-body">
                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="email" class="text-info">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter your email">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="text-info">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Enter password">
                            </div>
                            <div>
                                <p>
                                    Don't have an account?
                                    <a href="signup.php">Sign Up</a>
                                </p>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include ('footer.html');
    ?>

    <script>
        const loginForm = document.getElementById('loginForm');
        loginForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(loginForm);
            fetch('../server/user/login', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data['success']) {
                        // Reindirizza l'utente alla pagina di area privata
                        window.location.href = '../../P002/client/privatearea.php';
                    } else if (data['message']) {
                        const title = document.querySelector('#title');
                        title.innerHTML = `<div class="alert alert-danger text-center" role="alert">${data['message']}</div>`;
                    }
                })
                .catch((error) => {
                    console.log('Errore: ', error);
                });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>