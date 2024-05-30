<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
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

    <div class="container-fluid" id="id1">
        <h1 class="display-1 text-center text-primary title">Search</h1>
        <label for="search-input"><span class="badge text-bg-primary">Search :</span></label>
        <input type="text" id="search-input" placeholder="Search teacher, class or room by name or surname..." class="form-control">
        <div id="table-container"></div>
    </div>

    <div class="container-fluid">
        <div id="result-schedule"></div>
    </div>

    <?php
    include ('footer.html');
    ?>
    <script>

        // fetchTeachers();
        document.addEventListener('DOMContentLoaded', function () {
            loadTeachers();
        })

        function fetchTeachers() {
            const searchInput = document.querySelector('#search-input');
            const tableContainer = document.querySelector('#table-container');
            const a = document.querySelector('#id1');

            searchInput.addEventListener('input', function () {
                let searchTerm = searchInput.value.trim();

                // if (searchTerm === '') {
                //     tableContainer.innerHTML = '';
                //     return;
                // }

                fetch('../server/user/teacher/autocomplete?term=' + searchTerm, {
                    method: 'GET',
                    header: {
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data)
                        if (data['message']) {
                            // console.log('a');
                            a.innerHTML = `<div class="alert alert-danger text-center" role="alert">${data['message']}!</div>
                            <div class="container d-flex justify-content-center align-items-center">
                            <a class="btn btn-primary" href="login.php">Login</a>
                        <div>`;
                        } else {
                            tableContainer.innerHTML = '';
                            // console.log('Dati ricevuti: ', data);
                            let table = `
            <table class="table table-hover table-striped" id="teacher">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Subjects</th>
                    <th>Type</th>
                </tr>
                </thead>
                <tbody>
                ${generateRows(data)}
                </tbody>
            </table>
            `;
                            tableContainer.innerHTML = table;
                        }
                    })
                    .catch((error) => {
                        console.log('Errore: ', error);
                    });

            })

        }

        function generateRows(data) {
            let rows = '';
            data.forEach(element => {
                let row = `
                <tr onclick="showSchedule(${element.id}, '${element.entity_type}')">
                    <td>${element.entity_name}</td>
                    <td>${element.surname}</td>
                    <td>${element.subjects}</td>
                    <td>${element.entity_type}</td>
                </tr>
                </a>  
                `;
                rows += row;
            });
            return rows;
        }

        function loadTeachers() {
            const searchInput = document.querySelector('#search-input');
            const tableContainer = document.querySelector('#table-container');
            const result_schedule =document.querySelector('#result-schedule');
            const a = document.querySelector('#id1');

            // if (searchTerm === '') {
            //     tableContainer.innerHTML = '';
            //     return;
            // }

            fetch('../server/user/teacher/autocomplete?term', {
                method: 'GET',
                header: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    // console.log(data)
                    if (data['message']) {
                        console.log('loaded');
                        a.innerHTML = `<div class="alert alert-danger text-center" role="alert">${data['message']}!</div>
                            <div class="container d-flex justify-content-center align-items-center">
                            <a class="btn btn-primary" href="login.php">Login</a>
                        <div>`;
                    } else {
                        tableContainer.innerHTML = '';
                        // console.log('Dati ricevuti: ', data);
                        let table = `
            <table class="table table-hover table-striped" id="teacher">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Subjects</th>
                    <th>Type</th>
                </tr>
                </thead>
                <tbody>
                ${generateRows(data)}
                </tbody>
            </table>
            `;
                        tableContainer.innerHTML = table;
                        // result_schedule.innerHTML = '';
                    }
                    fetchTeachers()
                })
                .catch((error) => {
                    console.log('Errore: ', error);
                });



        }

        // carica tutti i docenti all'inizio
        function showSchedule(id, type) {
            // id è l'hidden value di un form o la colonna id della riga della tabella
            // console.log(id)
            let teacher_table = document.getElementById('teacher');
            teacher_table.innerHTML = '';

            let result = document.getElementById('result-schedule');
            fetch('../server/schedule?id=' + id + '&type=' + type, {
                method: 'GET',
                header: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    let table;
                    if (data['type'] == 'user') {
                        table = `<div class="container-fluid title"><h3 class="display-5 text-center text-info">Schedule for: ${data['schedule'][0].name} ${data['schedule'][0].surname}</h3></div>`;
                    } else if (data['type'] == 'class') {
                        table = `<div class="container-fluid title"><h3 class="display-5 text-center text-info">Schedule for: ${data['schedule'][0].class} </h3></div>`;
                    } else {
                        //room
                        table = `<div class="container-fluid title"><h3 class="display-5 text-center text-info">Schedule for: ${data['schedule'][0].room} </h3></div>`;
                    }
                    table += `
                    <table class="table table-striped">
                        <thead>
                            <th class="text-center">Hour</th>
                            <th class="text-center">Monday</th>
                            <th class="text-center">Tuesday</th>
                            <th class="text-center">Wednesday</th>
                            <th class="text-center">Thursday</th>
                            <th class="text-center">Friday</th>
                            <th class="text-center">Saturday</th>
                        </thead>
                        <tbody>
                            ${generateRowsSchedule(data)}
                        </tbody>
                    </table>
                    `;
                    result.innerHTML = table;
                })
                .catch((error) => {
                    console.log('Errore: ', error);
                });
        }

        function generateRowsSchedule(data) {
            console.log(data)
            let rows = '';
            for (let i = 1; i < 7; i++) {
                let row = '<tr>';
                // Aggiungi il numero dell'ora nella prima colonna
                row += `<td class="text-center align-middle">
                ${i}
                </td>`;
                for (let j = 1; j < 7; j++) {
                    let found = false;
                    data['schedule'].forEach(element => {
                        if (element.hour == i && element.day == j) {
                            // console.log(classColors[element.class])
                            if (data['type'] == 'user') {
                                row += `
                                    <td class="text-center">
                                        <span class="badge rounded-pill text-black">${element.class}</span>
                                        <div>${element.subject}</div>
                                        <div class="text-primary">${element.room}</div>
                                    </td>`;
                            } else if (data['type'] == 'class') {
                                row += `
                                    <td class="text-center">
                                        <span class="badge rounded-pill text-black">${element.room}</span>
                                        <div>${element.subject}</div>
                                        <div class="text-primary">${element.teachers}</div>
                                        </td>`;
                            } else {
                                //room
                                row += `
                                    <td class="text-center">
                                        <span class="badge rounded-pill text-black">${element.class}</span>
                                        <div>${element.subject}</div>
                                        <div class="text-primary">${element.teachers}</div>
                                        <div class="text-info">${element.technician}</div>
                                    </td>`;
                            }

                            found = true;
                        }
                    });
                    if (!found) {
                        row += `<td class="text-center">/</td>`;
                    }
                }
                row += '</tr>';
                rows += row;
            }
            return rows;
        }


    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>