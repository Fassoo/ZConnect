<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment</title>
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

    <div class="container" id="id1">
        <h1 class="display-2 text-center text-info title">Insert a new assignment</h1>
        <div class="alert alert-info text-center" role="alert">Here you can insert a new assignment<br><span><a href="schedule.php">Here</a> you can view the assignments</span></div>
    </div>

    <div class="container mt-5" id="insert">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header border-primary">
                        <h2 class="display-4 text-center text-primary title">Assignment</h2>
                    </div>
                    <div class="card-body">
                        <form id="assignmentForm">
                            <div class="mb-3">
                                <label for="title" class="text-info">Name</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Title"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="text-info">Description</label>
                                <input type="text" class="form-control" id="description" name="description"
                                    placeholder="Description of the assignment">
                            </div>
                            <div class="mb-3">
                                <label for="day" class="text-info">Day</label>
                                <input type="date" class="form-control" name="date" id="date" placeholder="Deadline">
                            </div>
                            <div class="mb-3">
                                <label for="hour" class="text-info">Hour</label>
                                <select id="hour" name="hour" class="form-select" aria-label="Default select example"
                                    class="bg-gray-50 border boder-gray-300 text-gray-900 text-sm rounded-lg
                                focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700
                                dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500
                                dark:focus:border-blue-500">
                                    <option selected value="1">Prima ora (8.00 - 9.00)</option>
                                    <option value="2">Seconda ora (9.00 - 9.55)</option>
                                    <option value="3">Terza ora (9.55 - 10.50)</option>
                                    <option value="4">Quarta ora (11.05 - 12.05)</option>
                                    <option value="5">Quinta ora (12.05 - 13.00)</option>
                                    <option value="6">Sesta ora (13.00 - 13.50)</option>
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">Submit</button>
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
        verifiyPermission()
        submitForm()
        function submitForm() {
            const form = document.querySelector('#assignmentForm');
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(form);

                fetch('../server/user/add/assignment', {
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
                            window.location.href = 'schedule.php';
                        }
                    })
                    .catch((error) => {
                        console.log('Errore: ', error);
                    });
            })
        }

        // verifica se l'utente è un docente o uno studente
        function verifiyPermission() {
            const div_insert =document.querySelector('#insert');
            const title =document.querySelector('#id1');
            fetch('../server/user', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    // console.log(data)
                    if (data['message']) {
                        title.innerHTML = `<div class="alert alert-danger text-center" role="alert">${data['message']}!</div>
                        <div class="container d-flex justify-content-center align-items-center">
                            <a class="btn btn-primary" href="login.php">Login</a>
                        <div>`;
                        div_insert.innerHTML = '';
                    } else if (data[0]['type'] == 'teacher') {
                        title.innerHTML = `
                        <div class="alert alert-danger text-center" role="alert">You have not the permission for this feature! Only students allowed for now</div>`;
                        div_insert.innerHTML = '';
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