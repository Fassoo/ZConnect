<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/ico" href="img/logo.ico">
</head>

<body>
    <?php
    include ('navbar.html')
        ?>
    <div class="container mt-5" id="insert-activity">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header border-primary">
                        <h4 class="display-4 text-center text-primary">Add activity</h4>
                    </div>
                    <div class="card-body">
                        <form id="activity-form">
                            <div class="mb-3">
                                <label for="name" class="text-info">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter the name" required>
                            </div>
                            <div class="container-fluid" id="id1">
                                <label for="search-input"><span class="badge text-bg-primary">Search: </span></label>
                                <input type="text" id="search-input"
                                    placeholder="Search student by name or surname or class by name..."
                                    class="form-control">
                                <div id="table-container"></div>
                            </div>

                            <div class="container-fluid">
                                <div id="result-schedule"></div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include ('footer.html')
        ?>

    <script>
        submitForm();

        function submitForm() {
            const form = document.querySelector('#activity-form');
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(form);

                fetch('../server/class/add/activity', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data)
                        if (data['message']) {
                            const el = document.querySelector('#insert-activity');
                            el.innerHTML = `<div class="alert alert-danger text-center" role="alert">${data['message']}!</div>`
                        } else if (data['success']) {
                            window.location.href = 'booking.php';
                        }
                    })
                    .catch((error) => {
                        console.log('Errore: ', error);
                    });
            })
        }
        // stampa i check del form per ogni studente
        function generateChecks(data) {
            console.log(data)
            let rows = '';
            data.forEach(element => {

                let row = (
                    data[0]['type'] == 'student'
                        ?
                        `<div class="form-check">
                        <input class="form-check-input" type="checkbox" name="selectedStudents[]" value="${element.id}" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">${element.name} ${element.surname} ${element.class}</label>
                    </div>`
                        :
                        ``
                );
                rows += row;
            });
            return rows;
        }

        fetchTeachers();
        autocomplete();

        // stampa tutti gli studenti all'inizio
        function fetchTeachers() {
            const searchInput = document.querySelector('#search-input');
            let searchTerm = searchInput.value.trim();
            fetch('../server/user/student/autocomplete?term', {
                method: 'GET',
                header: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    let table = generateChecks(data);
                    let tableContainer = document.querySelector('#result-schedule');
                    tableContainer.innerHTML = table;
                })
                .catch((error) => {
                    console.log('Errore: ', error);
                });
        }

        // Autocomplete function per gli studenti quando viene messo un input nel campo di ricerca
        function autocomplete() {
            const searchInput = document.querySelector('#search-input');
            const searchResults = document.querySelector('#result-schedule');

            searchInput.addEventListener('input', function () {
                let searchTerm = searchInput.value.trim();

                // if (searchTerm === '') {
                //     searchResults.innerHTML = '';
                //     return;
                // }

                fetch('../server/user/student/autocomplete?term=' + searchTerm, {
                    method: 'GET',
                    header: {
                        'Content-Type': 'application/json'
                    }
                }
                )
                    .then(response => response.json())
                    .then(data => {
                        // Visualizza i risultati dell'autocompletamento
                        // console.log(data);
                        let rows = generateChecks(data);
                        searchResults.innerHTML = rows;
                    })
                    .catch(error => {
                        console.error('Errore durante la ricerca:', error);
                    });
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>