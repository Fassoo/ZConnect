<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario con FullCalendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/ico" href="img/logo.ico">
    <style>
        .custom-calendar {
            max-width: 100%;
            margin: 50px 50px 50px 50px;
            padding: 20px;
            background-color: #f9f9f9;
            /* border-radius: 5px; */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #calendar .fc-daygrid-day.fc-day .fc-daygrid-day-number,
        #calendar .fc-daygrid-day.fc-day .fc-daygrid-day-top .fc-daygrid-day-number {
            color: black !important;
            /* Imposta il colore del testo dei numeri e dei nomi dei giorni del calendario */
            text-decoration: none !important;
            /* Rimuovi eventuali decorazioni del testo (come il sottolineato) */
        }
    </style>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: function (fetchInfo, successCallback, failureCallback) {
                    getEvents()
                        .then(events => {
                            successCallback(events);
                        })
                        .catch(error => {
                            console.error('Errore durante il recupero degli eventi:', error);
                            failureCallback(error.message);
                        });
                },
                eventClick: function (info) {
                    alert('Titolo: ' + info.event.title + '\n' +
                        'Inizio: ' + info.event.start + '\n' +
                        'Descrizione: ' + info.event.description  );
                }
            });
            calendar.render();
        });

        function getEvents() {
            return fetch('../server/user', {
                method: 'GET'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Errore durante il recupero degli eventi');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data)
                    if (data['message']) {
                        const div = document.querySelector('#message');
                        div.innerHTML = `<div class="alert alert-danger text-center" role="alert">${data['message']}!</div>`;
                        return [];
                    } else {
                        if (data[0]['type'] == 'student') {
                            return getStudentEvents();
                        } else if (data[0]['type'] == 'teacher') {
                            return getTeacherEvents();
                        } else {
                            console.log('technician');
                            return [];
                        }
                    }
                });
        }

        // stampa nel calendario gli assignment
        function getStudentEvents() {
            return fetch('../server/user/get/assignments', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Errore durante il recupero degli eventi');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data['message']) {
                        return [];
                    } else {
                        return data.map(element => ({
                            'id': element.id,
                            'title': element.title,
                            'start': element.deadline, // Aggiungi l'ora di inizio
                            // 'end': element.end_datetime // Aggiungi l'ora di fine
                        }));
                    }
                });
        }

        // stampa nel calendario i booking
        function getTeacherEvents() {
            return fetch('../server/bookings', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Errore durante il recupero degli eventi');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data['message']) {
                        return [];
                    } else {
                        return data.map(element => ({
                            'id': element.surname,
                            'title': element.title,
                            'start': element.day,
                            'description': element.name + ' ' + element.surname + ' ' + element.room + ' ' + element.class
                            // 'end': element.hour_end // Aggiungi l'ora di fine
                        }));
                    }
                });
        }
    </script>
</head>

<body>
    <?php
    include ('navbar.html');
    ?>
    <div class="container" id="message"></div>
    <div id='calendar' class="custom-calendar"></div>
    <?php
    include ('footer.html');
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
