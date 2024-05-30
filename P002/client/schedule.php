<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Personal Schedule</title>
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
    <style>
        #button-schedule {
            margin-bottom: 10px;
            margin-top: 10px;
        }

        #assignment-container {
            margin-top: 40px;
        }

        #table-schedule{
            margin-top: 10px;
        }
    </style>

    <!-- titolo e bottoni per cambiare schedule -->
    <div class="container text-center" id="title-container">
        <h2 class="display-3 text-primary title" id="table-title">Today's schedule</h2>
        <button class="btn btn-primary" onclick="getFullSchedule()" id="single-schedule">Get Full Schedule</button>
        <button class="btn btn-primary" onclick="fetchSchedule()" id="full-schedule" hidden>Get Today's
            Schedule</button>
    </div>

    <div id="table-container" class="container text-center"></div>


    <div class="container" id="calendar">
        <hr class="border border-primary border-3 opacity-75"><br>
        <a href="calendar.php" class="btn btn-info d-flex justify-content-center">View Assignements Calendar</a>
    </div>

    <div class="container" id="assignment-container">
    </div>

    <div class="container" id="update-assignment" hidden>
        <div class="form-group">
            <form id="update-form">
                <div class="mb-3">
                    <label for="newdate" class="text-warning">Update Deadline:</label>
                    <input type="date" name="newdate" id="newdate" class="form-control">
                </div>
                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div class="container" id="id1">

    </div>

    <?php
    include ('footer.html');
    ?>
    <script>
        fetchSchedule();
        getAssignments();

        function fetchSchedule() {
            let tableContainer = document.querySelector('#table-container');
            let singleButton = document.querySelector('#single-schedule');
            let fullButton = document.querySelector('#full-schedule');
            let title = document.querySelector('#table-title');
            let title_container = document.querySelector('#title-container');
            let assignment_container = document.querySelector('#assignment-container');
            let calendar = document.querySelector('#calendar')
            fetch('../server/user/schedule', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Dati ricevuti: ', data);
                    if (data['message']) {
                        assignment_container.innerHTML = '';
                        calendar.innerHTML = '';
                        title_container.innerHTML = `<div class="alert alert-danger text-center" role="alert">User not logged!</div>
                                            <a class="btn btn-primary" href="login.php">Login</a>`;
                    } else {
                        let type = data['type'];
                        let table = `
            <table class="table table-striped" id="table-schedule">
                <thead>
                <tr>`;
                        if (type === 'student') {
                            table += `
                        <th class="text-info">Hour</th>
                        <th class="text-info">Subject</th>
                        <th class="text-info">Teacher/s</th>
                        <th class="text-info">Room</th>`;
                        } else if (type === 'teacher') {
                            table += `
                        <th class="text-info">Hour</th>
                        <th class="text-info">Subject</th>
                        <th class="text-info">Class</th>
                        <th class="text-info">Room</th>`;
                        }
                        table += `</tr>
                </thead>
                <tbody>
                ${generateRows(data['schedule'], type)}
                </tbody>
            </table>`;
                        singleButton.hidden = false;
                        fullButton.hidden = true;
                        title.innerHTML = `Today's Schedule`;
                        tableContainer.innerHTML = table;
                    }

                })
                .catch((error) => {
                    console.log('Errore: ', error);
                });
        }

        function generateRows(data, type) {
            let rows = '';
            let row = '';

            if (type === 'student') {
                for (let i = 1; i < 7; i++) {
                    let found = false;
                    data.forEach(element => {
                        if (element.hour == i) {
                            row = `<tr>
                        <td>${element.hour}</td>
                        <td><span class="badge rounded-pill text-black">${element.subject}</span></td>
                        <td class="text-primary">${element.professors}</td>
                        <td>${element.room}</td>
                    </tr>`;
                            rows += row;
                            found = true;
                        }
                    });
                    if (!found) {
                        row = `<tr>
                    <td class="text-center align-middle">${i}</td>
                    <td class="text-center align-middle">/</td>
                    <td class="text-center align-middle">/</td>
                    <td class="text-center align-middle">/</td>
                </tr>`;
                        rows += row;
                    }
                }
            } else if (type === 'teacher') {
                for (let i = 1; i < 7; i++) {
                    let found = false;
                    data.forEach(element => {
                        if (element.hour == i) {
                            row = `<tr>
                        <td>${element.hour}</td>
                        <td><span class="badge rounded-pill text-black">${element.subject}</span></td>
                        <td class="text-primary">${element.class}</td>
                        <td>${element.room}</td>
                    </tr>`;
                            rows += row;
                            found = true;
                        }
                    });
                    if (!found) {
                        row = `<tr>
                    <td class="text-center align-middle">/</td>
                    <td class="text-center align-middle">/</td>
                    <td class="text-center align-middle">/</td>
                    <td class="text-center align-middle">/</td>
                </tr>`;
                        rows += row;
                    }
                }
            } else {
                // technician
            }

            return rows;
        }



        function getFullSchedule() {
            let tableContainer = document.querySelector('#table-container table');
            let tableTitle = document.querySelector('#table-title');
            let singleButton = document.querySelector('#single-schedule');
            let fullButton = document.querySelector('#full-schedule');
            tableTitle.innerHTML = 'Your Full Schedule';
            fetch('../server/user/schedule?full=1', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Dati ricevuti: ', data);
                    let type = data['type'];
                    let table = `
            <table class="table table-striped">
                <thead>
                <tr>
                    <tr>
                    <th class="text-center text-info">Hour</th>
                    <th class="text-center text-info">Monday</th>
                    <th class="text-center text-info">Tuesday</th>
                    <th class="text-center text-info">Wednesday</th>
                    <th class="text-center text-info">Thursday</th>
                    <th class="text-center text-info">Friday</th>
                    <th class="text-center text-info">Saturday</th>
                    </tr>
                </thead>
                <tbody>
                ${generateRowsFullSchedule(data['schedule'], type)}
                </tbody>
            </table>
            `;
                    tableContainer.innerHTML = '';
                    fullButton.hidden = false;
                    singleButton.hidden = true;
                    tableContainer.innerHTML = table;
                })
                .catch((error) => {
                    console.log('Errore: ', error);
                });
        }

        function generateRowsFullSchedule(data, type) {
            let rows = '';
            for (let i = 1; i < 7; i++) {
                let row = '<tr>';
                // Aggiungi il numero dell'ora nella prima colonna
                row += `<td class="text-center align-middle">${i}</td>`;
                for (let j = 1; j < 7; j++) {
                    let found = false;
                    data.forEach(element => {
                        if (type === 'student') {
                            if (element.hour == i && element.day == j) {
                                // let color = classColors["1"] || '#ccc';
                                // console.log(classColors["1"])
                                // console.log(classColors[element.class])
                                // console.log(element.day + ", " + element.hour + ", " + element.subject + ", " + element.professors)
                                row += `
                        <td class="text-center">
                            <span class="badge rounded-pill text-black">${element.subject}</span>
                            <div class="text-primary">${element.professors}</div>
                            <div>${element.room}</div>
                        </td>
                    `;
                                found = true;
                            }
                        } else if (type === 'teacher') {
                            if (element.hour == i && element.day == j) {
                                // let color = classColors["1"] || '#ccc';
                                // console.log(classColors["1"])
                                // console.log(classColors[element.class])
                                // console.log(element.day + ", " + element.hour + ", " + element.subject + ", " + element.professors)
                                row += `
                        <td class="text-center">
                            <span class="badge rounded-pill text-black">${element.subject}</span>
                            <div class="text-primary">${element.class}</div>
                            <div>${element.room}</div>
                        </td>
                    `;
                                found = true;
                            }
                        }
                    });
                    if (!found) {
                        row += `<td class="text-center align-middle">/</td>`;
                    }
                }
                row += '</tr>';
                rows += row;
            }
            return rows;
        }

        function getAssignments() {
            const assignment_container = document.querySelector('#assignment-container');
            let assignment = '';
            let assignments = '';
            let i = 1;
            fetch('../server/user/get/assignments', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    // console.log('acqua fiji: ', data);
                    // mettere due assignement sulla stessa riga
                    assignment.innerHTML += ``;
                    if (!data['message']) {
                        // se l'utente è uno studente -> message è definito per gli utenti non studenti
                        assignments = `
                        <table class="table">
                            <thead>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Deadline</th>
                                <th>Action</th>
                            </thead>
                            <tbody>`;
                        data.forEach(element => {
                            assignments +=
                                `<tr>
                                    <td class="align-middle">${i}</td>
                                    <td class="align-middle text-primary">${element.title}</td>
                                    <td class="align-middle">${element.description}</td>
                                    <td class="align-middle text-danger">${element.deadline}</td>
                                    <td class="align-middle"><button class="btn btn-warning" onclick="updateAssignment(${element.id})"><i class="bi bi-pencil"></i></button>  <a href="../server/assignment/delete?id=${element.id}" class="btn btn-danger " tabindex="-1" role="button" aria-disabled="true"><i class="bi bi-trash3"></i></a></td>
                                </tr>`
                                ;
                            i++;
                        });
                        assignments.innerHTML += `</tbody></table>`;
                        assignment_container.innerHTML += '<p class="display-4 text-info text-center title">Assignments <a href="assignment.php" class="btn btn-success" tabindex="-1" role="button" aria-disabled="true">Add <i class="bi bi-plus-lg"></i></a></p>';
                        assignment_container.innerHTML += assignments;
                    }
                })
                .catch((error) => {
                    console.log('Errore: ', error);
                });
        }

        function updateAssignment(id) {
            const update_el = document.querySelector('#update-assignment');
            // console.log(id)
            if (update_el.hidden == true) {
                update_el.hidden = false;
            } else {
                update_el.hidden = true;
            }

            submitUpdate(id)
        }

        function submitUpdate(id) {
            let form = document.querySelector('#update-form');
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(form);
                const newdate = formData.get('newdate');
                fetch(`../server/assignment/${id}/${newdate}`, {
                    method: 'PUT',
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data)
                        if (data['message']) {
                            const el = document.querySelector('#id1');
                            el.innerHTML = `<div class="alert alert-danger text-center" role="alert">${data['message']}!</div>`
                        } else if (data['success']) {
                            // window.location.href = 'schedule.php';
                            console.log(data['date'])
                        }
                    })
                    .catch((error) => {
                        console.log('Errore: ', error);
                    });
            })
        }

        function deleteAssignment(id) {
            console.log(id)
            const url = '../server/assignment/delete';

            // Opzioni della richiesta fetch
            const options = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                // Converti l'ID in formato JSON e inseriscilo nel corpo della richiesta
                body: JSON.stringify({ id: id })
            };

            // Effettua la richiesta fetch
            fetch(url, options)
                .then(response => {
                    // Gestisci la risposta del server
                })
                .catch(error => {
                    // Gestisci gli errori se necessario
                });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>