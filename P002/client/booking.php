<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
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

    <div class="container" id="title">
        <h1 class="display-1 text-center text-info title">Booking</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <p class="lead text-center">
                    Welcome to our online booking system.
                <p>
            </div>
        </div>
        <div class="row text-center">
            <p class="lead text-center">
                <b>Steps to the online booking:</b><br>
                <b>1.</b> Select the day of your boking<br>
                <b>2.</b> Select the room of your booking<br>
                <b>3.</b> Select the hour of the booking<br>
                <b>4.</b> Select the class or the activity of the booking <br>
                <b>?.</b> Want to register a new activity?
                <span class="container" id="button-activity">
                    <a href="activity.php" class="btn btn-success " tabindex="-1" role="button" aria-disabled="true"><i
                            class="bi bi-plus-lg"></i></i></a>
                </span>
            <div class="container" id="calendar">
                <a href="calendar.php" class="btn btn-info d-flex justify-content-center">View Bookings Calendar</a>
                <hr class="border border-primary border-3 opacity-75"><br>

            </div>
            </p>
        </div>
    </div>


    <div class="container" id="select-container">
        <form id="booking-form">
            <div class="form-group">
                <div class="mb-3">
                    <label for="title" class="text-info">
                        <h6>Title:</h6>
                    </label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Insert Title" required>
                </div>

                <div class="mb-3">
                    <label for="day" class="text-info">
                        <h6>Select day:</h6>
                    </label>
                    <select name="day" id="day" class="form-select" onchange="showOption()" required>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="room" class="text-info">
                        <h6>Select room:</h6>
                    </label>
                    <select name="room" id="room" class="form-select" required>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="hour" class="text-info">
                        <h6>Select hour:</h6>
                    </label>
                    <select name="hour" id="hour" class="form-select" onchange="showChoice()" required>
                    </select>
                </div>

                <div class="mb-3">
                    <div id="select-choice">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="option" id="class-option">
                            <label class="form-check-label" for="class-option">
                                Class
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="option" id="activity-option" checked>
                            <label class="form-check-label" for="activity-option">
                                Activity
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-3" id="class" hidden>
                    <label for="class-select">
                        <h6 class="text-info">Select class</h6>
                    </label>
                    <select name="class" id="class-select" class="form-select" onchange="viewDates()">
                    </select>
                </div>

                <div class="mb-3" id="activity">
                    <label for="activity-select">
                        <h6 class="text-info">Select activity:</h6>
                    </label>
                    <select name="activity" id="activity-select" class="form-select" onchange="viewDates()">
                    </select>
                </div>

                <div class="mb-3" id="div-date-start" hidden>
                    <label for="date_start" class="text-info">Date Start:</label>
                    <input type="date" class="form-control" name="date_start" id="date_start">
                </div>

                <div class="mb-3" id="div-date-end" hidden>
                    <label for="date_end" class="text-info">Date End:</label>
                    <input type="date" class="form-control" name="date_end" id="date_end">
                </div>

                <!-- submit buttom -->
                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <div id="message"></div>

    <?php
    include ('footer.html');
    ?>

    <script>
        verifiyPermission()
        getDay();
        getActivity();
        getClasses();
        viewChoice();
        submitForm()

        // select per il giorno 
        // dal giorno selezionato disabilitare le aule che non sono libere quel giorno
        // dal laboratorio disabilitare le ore che non sono libere

        // in base alla scelta nasconde o visualizza il select della classe o dell'attività
        function viewChoice() {
            document.addEventListener("DOMContentLoaded", function () {
                // Seleziona tutti gli input radio con lo stesso nome
                let radioOptions = document.querySelectorAll('input[name="option"]');

                let activity_select = document.querySelector('#activity');
                let class_select = document.querySelector('#class');


                // Aggiungi un event listener per l'evento change a ciascun input radio
                radioOptions.forEach(function (option) {
                    option.addEventListener('change', function () {
                        // Controlla se l'opzione è stata selezionata
                        if (this.checked) {
                            // Ottieni il testo della label associata all'input radio
                            let labelText = this.nextElementSibling.textContent.trim();
                            // Stampa il contenuto nella console
                            if (labelText === 'Activity') {
                                activity_select.hidden = false;
                                class_select.hidden = true;
                            } else if (labelText === 'Class') {
                                activity_select.hidden = true;
                                class_select.hidden = false;
                            } else {
                                console.log('no option')
                            }
                            console.log("Opzione selezionata: " + labelText);
                        }
                    });
                });
            });

        }

        // verifica se l'utente è un docente o uno studente
        function verifiyPermission() {
            const button_activity = document.querySelector('#button-activity');
            const select_container = document.querySelector('#select-container');
            const title_container = document.querySelector('#title');
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
                        title_container.innerHTML = `<div class="alert alert-danger text-center" role="alert">${data['message']}!</div>
                        <div class="container d-flex justify-content-center align-items-center">
                            <a class="btn btn-primary" href="login.php">Login</a>
                        <div>`;
                        button_activity.innerHTML = '';
                        select_container.innerHTML = '';
                    } else if (data[0]['type'] == 'student') {
                        title_container.innerHTML = `
                        <div class="alert alert-danger text-center" role="alert">You have not the permission for this feature! Only teachers allowed</div>`
                        button_activity.innerHTML = '';
                        select_container.innerHTML = '';
                    }
                })
                .catch((error) => {
                    console.log('Errore: ', error);
                });
        }

        // associa al giorno, i laboratori e poi le ore libere
        // tramite una map
        function showRoomByDay(day) {
            let roomScheduleMap = {}; // Mappa per memorizzare l'associazione ora, laboratorio e presenza
            const elHour = document.querySelector('#hour');
            const elRoom = document.querySelector('#room');

            fetch('../server/room/schedule?day=' + day, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Dati ricevuti: ', data);

                    // Inizializza la mappa per tutte le stanze e tutte le ore
                    data.forEach(element => {
                        const room = element.room_name;
                        // const room_id =element.room_id;
                        const hour = element.hour;

                        // Se la stanza non è ancora stata aggiunta alla mappa, crea un nuovo oggetto per la stanza
                        if (!roomScheduleMap[room]) {
                            roomScheduleMap[room] = {};

                            // Inizializza tutte le ore per la stanza corrente a false
                            for (let i = 1; i <= 6; i++) {
                                roomScheduleMap[room][i] = false;
                            }
                        }

                        // Imposta l'ora per la stanza corrente su true se è disponibile
                        roomScheduleMap[room][hour] = true;
                    });

                    console.log(roomScheduleMap);

                    // Popola elRoom con le aule
                    elRoom.innerHTML = `<option value="" disabled selected>Select a room</option>`;
                    Object.keys(roomScheduleMap).forEach(room => {
                        elRoom.innerHTML += `<option value="${room}">${room}</option>`;
                    });

                    // Popola elHour con le ore disponibili per la stanza selezionata
                    elRoom.addEventListener('change', function () {
                        const selectedRoom = this.value;
                        elHour.innerHTML = `<option value="" disabled selected>Select an hour</option>`;
                        Object.keys(roomScheduleMap[selectedRoom]).forEach(hour => {
                            if (!roomScheduleMap[selectedRoom][hour]) {
                                elHour.innerHTML += `<option value="${hour}">${convertHour(hour)}</option>`;
                            }
                        });
                    });
                })
                .catch((error) => {
                    console.log('Errore: ', error);
                });
        }


        // mostra i giorni nel select
        function getDay() {
            const dayEl = document.querySelector('#day');
            const day = {
                '1': 'Lunedì',
                '2': 'Martedì',
                '3': 'Mercoledì',
                '4': 'Giovedì',
                '5': 'Venerdì',
                '6': 'Sabato'
            };
            dayEl.innerHTML = `<option value="" disabled selected>Select a day</option>`;
            for (let i = 1; i < 7; i++) {
                let option = `<option value="${i}">${day[i]}</option>`;
                dayEl.innerHTML += option;
            }
        }

        // dal numero alla stringa
        function convertHour(hour) {
            const hours = {
                '1': '1° Ora (8:00-9:00)',
                '2': '2° Ora (9:00-9:55)',
                '3': '3° Ora (9:55-10:50)',
                '4': '4° Ora (11:05-12:05)',
                '5': '5° Ora (12:05-13:00)',
                '6': '6° Ora (13:00-13:50)'
            };

            return hours[hour];
        }


        // visualizza tutte le classi
        function getClasses() {
            const selectEl = document.querySelector('#class-select');
            fetch('../server/class', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    // console.log(data)
                    data.forEach(element => {
                        let option = `<option value="${element.id}">${element.name}</option>`;
                        selectEl.innerHTML += option;
                    });

                })
                .catch((error) => {
                    console.log('Errore: ', error);
                });
        }

        // visualizza tutte le attività
        function getActivity() {
            const selectEl = document.querySelector('#activity-select');
            fetch('../server/class/activity', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data['message']) {
                        title.innerHTML = `<p class="display-1 text-center text-danger">${data['message']}</p>`;
                        select_container.innerHTML = '';
                    } else {
                        selectEl.innerHTML = `<option value="" selected disabled>Select an activity</option>`;
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

        // mostra la scelta tra classe e attività
        function showChoice() {
            const type_choice = document.querySelector('#select-choice');
            // const type_class = document.querySelector('#type-class');

            // type_activity.hidden = false;
            type_choice.hidden = false;

        }

        // manda il form e visualizza il messaggio
        function submitForm() {
            let form = document.getElementById('booking-form');
            const message = document.querySelector('#message');
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                let formData = new FormData(form);
                fetch('../server/booking/add', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (data['success']) {
                            // Reindirizza l'utente alla pagina di area privata
                            message.innerHTML = `<div class="alert alert-success text-center" role="alert">Add successful!</div>`;
                        } else if (data['message']) {
                            message.innerHTML = `<div class="alert alert-danger text-center" role="alert">${data['message']}!</div>`;
                        }
                    })
                    .catch((error) => {
                        console.log('Errore: ', error);
                    });
            })

        }

        function showOption() {
            const elDay = document.querySelector('#day');
            const elRoom = document.querySelector('#room');
            const elHour = document.querySelector('#hour');
            const elActivity = document.querySelector('#activity');
            showRoomByDay(elDay.value);
            if (elDay.value != "") {
                // console.log('bbbbbbb')
                elRoom.hidden = false;
                elHour.hidden = false;
                // elActivity.hidden = false;
            }
        }

        // visualizza i select della data di inizio e fine
        function viewDates() {
            const start_date = document.querySelector('#div-date-start');
            const end_date = document.querySelector('#div-date-end');

            start_date.hidden = false;
            end_date.hidden = false;
        }


    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>