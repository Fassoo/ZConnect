<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/ico" href="img/logo.ico">
</head>

<body>
    <style>
        #delete :hover {
            cursor: pointer;
        }
    </style>
    <?php
    include ('navbar.html')
        ?>

    <div id="response" class="container">
    </div>
    <div class="container" id="result">
    </div>

    <script>
        fetchUserData();

        function fetchUserData() {
            const div = document.querySelector('#response')
            fetch('../server/user')
                .then(response => response.json())
                .then(data => {
                    // console.log(data);
                    if (data['message']) {
                        div.innerHTML = `<div class="alert alert-danger text-center" role="alert">${data['message']}!</div>
                        <div class="container d-flex justify-content-center align-items-center">
                            <a class="btn btn-primary" href="login.php">Login</a>
                        <div>`
                    } else {
                        const result = document.querySelector('#result');
                        // result.innerHTML = '';
                        div.innerHTML = `<div class="alert alert-info text-center" role="alert">Update your password</div>`;
                        let is =
                            `
            <div class="container mt-5" id="id1">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header border-primary">
                        <h4 class="text-center text-primary display-4">Update</h4>
                    </div>
                    <div class="card-body">
                        <form id="edit-form">
                        <div class="mb-3">
                                <span class="text-info">Name</span>: ${data[0].name}
                            </div>
                            <div class="mb-3">
                                <span class="text-info">Surname</span>: ${data[0].surname}
                            </div>
                            <div class="mb-3">
                            <span class="text-info">Email</span>: ${data[0].mail}
                            </div>
                            <div class="mb-3">
                                <span class="text-info">Role</span>: ${data[0].type}
                            </div>`;
                        if (data[0].type === 'student') {
                            is +=
                                `
                            <div class="mb-3">
                            <span class="text-info">Current class</span>: ${data[0].class}
                            </div>
                            <div class="mb-3" id="select-co">
                                <label for="class">
                                    <h6 class="text-warning">Modify your class</h6>
                                </label>
                                <select id="class" name="class" class="form-select" aria-label="Default select example">
                                </select>
                            </div>`;
                            div.innerHTML = `
    <div class="d-flex justify-content-center align-items-center">
        <div class="alert alert-info text-center flex-grow-1 me-3" role="alert">
            Update your password and/or your class
        </div>
        <span class="alert alert-danger text-center" id="delete" role="alert" style="flex: 0 0 20%;">
            <a onclick="showAlert()">Delete your account</a>
        </span>
    </div>`;
                        }
                        is += `
                            <div class="mb-3">
                                <label for="token">
                                    <h6 class="text-warning">Update your password</h6>
                                </label>
                                <input type="password" class="form-control" name="token" id="token" placeholder="*******">
                            </div>
                            <br>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
                        result.innerHTML = is;
                        getClass();
                        submitForm();
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }

        function getClass() {
            const selectEl = document.querySelector('#class');
            const div = document.querySelector('#result');
            fetch('../server/class', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    // console.log(data)
                    if (data['message']) {

                    } else {
                        let emptyOption = '<option value="" disabled selected>Select a class</option>';
                        selectEl.innerHTML = emptyOption;
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

        function submitForm() {
            const form = document.querySelector('#edit-form');
            // let a =document.querySelector('#class')
            // console.log(a.value)
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(form);
                // console.log(formData)
                fetch('../server/user/update', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        // console.log(data)
                        if (data['message']) {
                            const el = document.querySelector('#response');
                            el.innerHTML = `<div class="alert alert-danger text-center" role="alert">${data['message']}!</div>`
                        } else if (data['success']) {
                            window.location.href = 'privatearea.php';
                        }
                    })
                    .catch((error) => {
                        console.log('Errore: ', error);
                    });
            })
        }

        function showAlert() {
            if(confirm('Are you sure?')){
                fetch('../server/user/delete', {
                    method: 'GET', // o 'POST', 'PUT', 'DELETE', ecc. a seconda delle necessità
                    headers: {
                        'Content-Type': 'application/json'
                        // altre intestazioni se necessarie
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if(data['success']){
                            window.location.href = 'signup.php';
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            }

        }
    </script>

    <?php
    include ('footer.html')
        ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>