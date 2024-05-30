<?php
require 'vendor/autoload.php';
$f3 = \Base::instance();

require ('db.php');

// prende gli attributi dall'utente loggato
$f3->route(
    'GET /user',
    function ($f3) {
        global $db;
        $id = $f3->get('SESSION.id');
        $is_logged = $f3->get('SESSION.logged');
        if ($is_logged) {
            $res = $db->exec(
                'SELECT * FROM user WHERE id = :id',
                [':id' => $id]
            );

            if ($res[0]['type'] === 'student') {
                $result = $db->exec(
                    'SELECT u.id, u.name, u.surname, u.mail, u.type, c.name AS class 
                    FROM user AS u 
                    JOIN user_in_class AS uc ON u.id = uc.id_user 
                    JOIN class AS c ON uc.id_class = c.id 
                    WHERE u.id = :id AND c.type = "class"',
                    [':id' => $id]
                );
            } else if ($res[0]['type'] === 'teacher') {
                $result = $db->exec(
                    'SELECT u.id, u.name, u.surname, u.type, u.mail, GROUP_CONCAT(DISTINCT " ", c.name) AS classes, GROUP_CONCAT(DISTINCT " ", s.subject) AS subject 
                    FROM user AS u 
                    JOIN schedule AS s ON u.id = s.id_teacher
                    JOIN class AS c ON s.id_class = c.id
                    WHERE s.id_teacher = :id AND s.id_class <> 1',
                    [':id' => $id]
                );
            }else{
                $result = $db->exec(
                    'SELECT u.id, u.name, u.surname, u.type, u.mail, 
                    FROM user AS u 
                    JOIN schedule AS s ON u.id = s.id_technician
                    JOIN class AS c ON s.id_class = c.id
                    WHERE s.id_technician = :id
                    GROUP BY u.id',
                    [':id' => $id]
                );
            }
            $response = $result;
        } else {
            $response['message'] = 'User Not Logged';
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
);

// registrazione dell'utente
$f3->route(
    'POST /user/signup',
    function ($f3) {
        global $db;

        $mail = $f3->get('POST.email');

        if (isset ($mail)) {
            $res = $db->exec(
                'SELECT * FROM user WHERE mail = :mail',
                [':mail' => $mail]
            );
            if ($res) {
                // mail già utilizzata
                $response['message'] = 'Email già utilizzata';
            } else {
                $type = $f3->get('POST.role');
                $id_class = $f3->get('POST.class');
                $password = $f3->get('POST.password');

                if ($password !== '') {
                    // inserimento nella tabella user
    
                    $suffisso = "@itiszuccante.edu.it";

                    if (substr($mail, -strlen($suffisso)) === $suffisso) {
                        // mail @itiszuccante.edu.it
                        $password_hash = hash('sha256', $password);
                        $db->exec(
                            'INSERT INTO user (name, surname, mail, token, type) VALUES (:name, :surname, :mail, :token, :type)',
                            // [':name' => $f3->get('POST.name'), ':surname' => $f3->get('POST.surname'), ':mail' => $f3->get('POST.email'), ':token' => $password, ':type' => $type]
                            [':name' => $f3->get('POST.name'), ':surname' => $f3->get('POST.surname'), ':mail' => $f3->get('POST.email'), ':token' => $password_hash, ':type' => $type]
                        );

                        if ($type === 'student') {
                            if ($id_class !== '') {
                                $id_user = $db->pdo()->lastInsertId();
                                $db->exec(
                                    'INSERT INTO user_in_class (id_class, id_user) VALUES (:id_class, :id_user)',
                                    [':id_class' => $id_class, ':id_user' => $id_user,]
                                );
                                $response['success'] = true;

                            } else {
                                $response['message'] = 'Classe non inserita';
                            }
                        }
                    } else {
                        $response['message'] = 'Mail non scolastica';
                    }


                } else {
                    $response['message'] = 'Password non inserita';
                }

                // $f3->reroute('../../P002/client/login.php');
            }
        } else {
            $response['message'] = 'Email non inserita';
        }


        header('Content-Type: application/json');
        echo json_encode($response);

    }
);

// flutter signup
$f3->route(
    'POST /user/signup2',
    function ($f3) {
        global $db;

        $mail = $f3->get('POST.email');
        $res = $db->exec(
            'SELECT * FROM user WHERE mail = :mail',
            [':mail' => $mail]
        );
        if ($res) {
            // mail già utilizzata
            $response = ['message' => 'email già utilizzata'];
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            $name = $f3->get('POST.name');
            $surname = $f3->get('POST.surname');
            $type = $f3->get('POST.role');
            $password = $f3->get('POST.password');
            $name_class = $f3->get('POST.class');

            $res2 = $db->exec(
                'SELECT * FROM class WHERE name = :name',
                [':name' => $name_class]
            );

            $id_class = $res2[0]['id'];

            // $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            // inserimento nella tabella user
            $db->exec(
                'INSERT INTO user (name, surname, mail, token, type) VALUES (:name, :surname, :mail, :token, :type)',
                [':name' => $name, ':surname' => $surname, ':mail' => $mail, ':token' => $password, ':type' => $type]
                // [':name' => $f3->get('POST.name'), ':surname' => $f3->get('POST.surname'), ':mail' => $f3->get('POST.email'), ':token' => $pass_hash, ':type' => $type]
            );

            if ($type === 'student') {
                $id_user = $db->pdo()->lastInsertId();
                $db->exec(
                    'INSERT INTO user_in_class (id_class, id_user) VALUES (:id_class, :id_user)',
                    [':id_class' => $id_class, ':id_user' => $id_user,]
                );
            }
        }
    }
);

// login utente
$f3->route(
    'POST /user/login',
    function ($f3) {
        global $db;
        $password = $_POST['password'];
        header('Content-Type: application/json');
        $res = $db->exec(
            'SELECT * FROM user WHERE mail = :mail',
            [':mail' => $_POST['email']]
        );

        if ($res) {
            $stored_pass = $res[0]['token'];
            $pass_hash = hash('sha256', $password);
            if($pass_hash === $stored_pass){
            // if ($password === $stored_pass) {
                session_start();
                $f3->set('SESSION.logged', true);
                $f3->set('SESSION.id', $res[0]['id']);
                echo json_encode(['success' => true]); // Indica il successo al client
            } else {
                echo json_encode(['message' => 'Password errata']);
            }

        } else {
            echo json_encode(["message" => 'Mail errata']);
        }

    }
);


// flutter login
$f3->route(
    'POST /user/login2',
    function ($f3) {
        // if ($f3->get('SESSION.id') == null) {
        global $db;
        $password = $_POST['password'];
        $email = $_POST['email'];
        // verifica con la password hash
        $res = $db->exec(
            'SELECT * FROM user WHERE mail = :mail AND token = :token',
            [':mail' => $email, ':token' => $password]
        );

        if ($res) {
            // session_start();
            // $f3->set('SESSION.logged', true);
            // $f3->set('SESSION.id', $res[0]['id']);
            $response['id'] = $res[0]['id'];
            $response['message'] = ['Login successful'];
        } else {
            $response = ['message' => 'Wrong credentials'];
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
);

// ricerca del docente in base a ora e giorno
$f3->route(
    'POST /user/teacher/search',
    function ($f3) {
        global $db;
        $result = $db->exec(
            'SELECT u.name AS name, u.surname AS surname, s.id_class AS class, r.name AS room, s.hour AS hour, s.day AS day
             FROM schedule AS s JOIN room AS r ON s.id_room = r.id
            JOIN user AS u ON u.id = s.id_teacher WHERE LOWER(u.name) = LOWER(:name) AND LOWER(surname) = LOWER(:surname) AND day = :day AND hour = :hour',
            [':name' => $f3->get('POST.name'), ':surname' => $f3->get('POST.surname'), ':day' => $f3->get('POST.day'), ':hour' => $f3->get('POST.hour')]
        );
        header('Content-Type: application/json');
        echo json_encode($result);
    }
);

// autocompletamento dei docenti
$f3->route(
    'GET /user/teacher/autocomplete',
    function ($f3) {
        global $db;
        $is_logged = $f3->get('SESSION.logged');
        $response = array ();

        if ($is_logged) {
            $input = $f3->get('GET.term');
            if ($input === null || $input === '') {
                $res = $db->exec(
                    'WITH class_query AS (
                        SELECT 
                            c.name AS entity_name,
                            c.type AS entity_type,
                            NULL AS surname,
                            c.id AS id,
                            NULL AS subjects
                        FROM class AS c
                        WHERE c.id <> 1
                    ),
                    room_query AS (
                        SELECT 
                            r.name AS entity_name, 
                            "room" AS entity_type, 
                            NULL AS surname,
                            r.id AS id,
                            GROUP_CONCAT(DISTINCT s.subject ORDER BY s.subject SEPARATOR " ") AS subjects
                        FROM room AS r
                        JOIN schedule AS s ON r.id = s.id_room
                        WHERE r.id <> 1
                        GROUP BY r.id
                    ),
                    user_query AS (
                        SELECT 
                            u.name AS entity_name, 
                            "user" AS entity_type, 
                            u.surname AS surname,
                            u.id AS id,
                            GROUP_CONCAT(DISTINCT s.subject ORDER BY s.subject SEPARATOR " ") AS subjects
                        FROM user AS u
                        JOIN schedule AS s ON u.id = s.id_teacher
                        WHERE s.subject <> "R"
                        GROUP BY s.id_teacher
                    )
                    SELECT *
                    FROM user_query
                    UNION ALL
                    SELECT *
                    FROM room_query
                    UNION ALL
                    SELECT *
                    FROM class_query;'
                );

                $response = $res;
            } else {
                // dividere nome e cognome
                if (strpos($input, ' ')) {
                    // dividi nome e cognome
                    $parts = explode(" ", $input);
                    $name1 = $parts[0] . '%';
                    $surname1 = $parts[1] . '%';
                } else {
                    $name1 = '';
                    $surname1 = '';
                }

                // $result = $db->exec(
                //     'SELECT u.id, u.name, u.surname, u.mail, GROUP_CONCAT(DISTINCT " ", s.subject) AS subjects
                //     FROM user as u
                //     JOIN schedule AS s ON u.id = s.id_teacher
                //     WHERE s.subject <> "R" AND (u.name LIKE :name OR u.surname LIKE :surname OR (u.name LIKE :name1 AND u.surname LIKE :surname1))
                //     GROUP BY s.id_teacher',
                //     [':name' => $input . '%', ':surname' => $input . '%', ':name1' => $name1, ':surname1' => $surname1]
                // );

                
                // if($result){
                //     // se trova risultati la ricerca è per un professore
                //     $response = $result;
                // }else{
                //     // se no
                //     // ricerca per aule
                //     $res1 = $db->exec(
                //         'SELECT r.id, r.name, GROUP_CONCAT(DISTINCT " ", s.subject) AS subjects
                //         FROM room AS r JOIN schedule AS s ON r.id = s.id_room
                //         WHERE r.id <> 1 AND r.name LIKE :name
                //         GROUP BY r.id',
                //         [':name' => $input . '%']
                //     );

                //     if($res1){
                //         // se trova risultati la ricerca è per aule
                //         $response = $res1;
                //     }else{
                //         // se no
                //         // ricerca per classi
                //         $res2 = $db->exec(
                //             'SELECT c.name, c.type
                //             FROM class AS c
                //             WHERE c.name LIKE :name',
                //             [':name' => $input . '%']
                //         );
                //         $response = $res2;
                //     }

                // }

                $result = $db->exec(
                    'WITH class_query AS (
                        SELECT 
                            c.name AS entity_name, 
                            c.type AS entity_type, 
                            NULL AS surname, 
                            NULL AS subjects,
                            c.id AS id
                        FROM class AS c
                        WHERE c.name LIKE :name
                    ),
                    room_query AS (
                        SELECT 
                            r.name AS entity_name, 
                            "room" AS entity_type, 
                            NULL AS surname, 
                            GROUP_CONCAT(DISTINCT s.subject ORDER BY s.subject SEPARATOR " ") AS subjects,
                            r.id AS id
                        FROM room AS r
                        JOIN schedule AS s ON r.id = s.id_room
                        WHERE r.id <> 1 AND r.name LIKE :name
                        GROUP BY r.id
                    ),
                    user_query AS (
                        SELECT 
                            u.name AS entity_name, 
                            "user" AS entity_type, 
                            u.surname AS surname, 
                            GROUP_CONCAT(DISTINCT s.subject ORDER BY s.subject SEPARATOR " ") AS subjects,
                            u.id AS id
                        FROM user AS u
                        JOIN schedule AS s ON u.id = s.id_teacher
                        WHERE s.subject <> "R" AND (u.name LIKE :name OR u.surname LIKE :surname OR (u.name LIKE :name1 AND u.surname LIKE :surname1))
                        GROUP BY s.id_teacher
                    )
                    SELECT *
                    FROM user_query
                    UNION ALL
                    SELECT *
                    FROM room_query
                    UNION ALL
                    SELECT *
                    FROM class_query;',
                    [':name' => $input . '%', ':surname' => $input . '%', ':name1' => $name1 . '%', ':surname1' => $surname1]
                );
                $response = $result;
            }
        } else {
            $response = ['message' => 'User Not Logged'];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
);


// autocomplete per flutter
$f3->route(
    'GET /user/teacher/autocomplete2',
    function ($f3) {
        global $db;
        $response = array ();

        $input = $f3->get('GET.term');
        if ($input === null || $input === '') {
            $res = $db->exec(
                'SELECT u.id, u.name, u.surname, u.mail, GROUP_CONCAT(DISTINCT " ", s.subject) AS subjects 
                    FROM user as u 
                    JOIN schedule AS s ON u.id = s.id_teacher 
                    GROUP BY s.id_teacher'
            );
            $response = $res;
        } else {
            // dividere nome e cognome
            if (strpos($input, ' ')) {
                // dividi nome e cognome
                $parts = explode(" ", $input);
                $name1 = $parts[0] . '%';
                $surname1 = $parts[1] . '%';
            } else {
                $name1 = '';
                $surname1 = '';
            }

            $result = $db->exec(
                'SELECT u.id, u.name, u.surname, u.mail, GROUP_CONCAT(DISTINCT " ", s.subject) AS subjects 
                FROM user as u 
                JOIN schedule AS s ON u.id = s.id_teacher
                WHERE u.name LIKE :name OR u.surname LIKE :surname OR (u.name LIKE :name1 AND u.surname LIKE :surname1)
                GROUP BY s.id_teacher',
                [':name' => $input . '%', ':surname' => $input . '%', ':name1' => $name1, ':surname1' => $surname1]
            );

            $response = $result;
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
);

// autocomplete studenti
$f3->route(
    'GET /user/student/autocomplete',
    function ($f3) {
        global $db;
        $is_logged = $f3->get('SESSION.logged');
        $response = array ();

        if ($is_logged) {
            $input = $f3->get('GET.term');
            if ($input == '0') {
                $res = $db->exec(
                    'SELECT u.id, u.name, u.surname, u.mail, u.type, c.name AS class
                    FROM user AS u 
                    JOIN user_in_class AS uc ON uc.id_user = u.id
                    JOIN class AS c ON uc.id_class = c.id
                    WHERE u.type = "student" AND c.type = "class"
                    GROUP BY u.id'
                );
                $response = $res;
            } else {
                // se c'è uno spazio
                // dividere nome e cognome
                if (strpos($input, ' ')) {
                    // dividi nome e cognome
                    $parts = explode(" ", $input);
                    $name1 = $parts[0] . '%';
                    $surname1 = $parts[1] . '%';
                } else {
                    $name1 = '';
                    $surname1 = '';
                }

                $result = $db->exec(
                    'SELECT u.id, u.name, u.surname, u.mail, u.type, c.name AS class 
                    FROM user as u 
                    JOIN user_in_class AS uc ON uc.id_user = u.id
                    JOIN class AS c ON uc.id_class = c.id
                    WHERE u.name LIKE :name OR u.surname LIKE :surname OR (u.name LIKE :name1 AND u.surname LIKE :surname1) AND u.type = "student" AND c.type = "class"
                    GROUP BY u.id',
                    [':name' => $input . '%', ':surname' => $input . '%', ':name1' => $name1, ':surname1' => $surname1]
                );

                // se ha risultati mandala
                if ($result) {
                    $response = $result;
                } else {
                    // ricerca per classi
                    $result1 = $db->exec(
                        'SELECT *
                        FROM class WHERE name LIKE :name
                        ORDER BY id',
                        [':name' => $input . '%']
                    );

                    $response = $result1;
                }


                // ricerca per classi
    
            }
        } else {
            $response = ['message' => 'User Not Logged'];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
);

// prende l'orario dell'utente loggato
$f3->route(
    'GET /user/schedule',
    function ($f3) {
        global $db;
        $is_logged = $f3->get('SESSION.logged');
        // se l'utente è loggato
        if ($is_logged) {
            $id_user = $f3->get('SESSION.id');
            $isFull = $f3->get('GET.full');
            $data = date("Y-m-d");
            $day = date("N", strtotime($data));
            $response = array ();
            $res1 = $db->exec('SELECT * FROM user WHERE id = :id', [':id' => $id_user]);
            $type = $res1[0]['type'];
            $response['type'] = $type;

            if ($type === 'student') {
                // orario completo
                if ($isFull === '1') {
                    // echo "studentfull";
                    $res2 = $db->exec(
                        'SELECT s.day, s.hour, s.subject, GROUP_CONCAT(DISTINCT " ", u.surname) AS professors, r.name AS room
                        FROM schedule AS s
                        JOIN user_in_class AS uc ON s.id_class = uc.id_class 
                        JOIN user as u ON s.id_teacher = u.id 
                        JOIN room as r ON s.id_room = r.id
                        JOIN class AS c ON uc.id_class = c.id 
                        WHERE uc.id_user = :id_user
                        GROUP BY s.day, s.hour
                        ORDER BY s.day, s.hour',
                        [':id_user' => $id_user]
                    );

                    $response['schedule'] = $res2;
                } else {
                    // echo "studentnotfull";
                    $res3 = $db->exec(
                        'SELECT s.hour, s.subject, GROUP_CONCAT(DISTINCT " ", u.surname) AS professors, r.name AS room
                        FROM schedule AS s 
                        JOIN user_in_class AS uc ON s.id_class = uc.id_class 
                        JOIN user as u ON s.id_teacher = u.id 
                        JOIN room as r ON s.id_room = r.id 
                        WHERE uc.id_user = :id_user AND s.day = :day
                        GROUP BY s.hour
                        ORDER BY s.hour',
                        // [':id_user' => 126, ':day' => 1]
                        [':id_user' => $id_user, ':day' => $day]
                    );
                    $response['schedule'] = $res3;
                }

            } else if ($type === 'teacher') {
                // orario completo
                if ($isFull === '1') {
                    $res4 = $db->exec(
                        "SELECT s.day, s.hour, GROUP_CONCAT(DISTINCT ' ', c.name) AS class, r.name AS room, u.name AS name, u.surname AS surname, s.subject
                        FROM schedule AS s 
                        JOIN user AS u ON s.id_teacher = u.id
                        JOIN room AS r ON s.id_room = r.id
                        JOIN class AS c ON s.id_class = c.id
                        WHERE u.id = :id_user
                        GROUP BY s.day, s.hour
                        ORDER BY s.day, s.hour",
                        [':id_user' => $id_user]
                        // [':id_teacher' => 1]
                    );
                    $response['schedule'] = $res4;
                } else {
                    $res5 = $db->exec(
                        'SELECT s.hour, s.subject, u.id, GROUP_CONCAT(DISTINCT " ", c.name) AS class, r.name AS room
                        FROM schedule AS s 
                        JOIN user AS u ON s.id_teacher = u.id
                        JOIN room AS r ON s.id_room = r.id
                        JOIN class AS c ON s.id_class = c.id
                        WHERE u.id = :id_user AND s.day = :day
                        GROUP BY s.day, s.hour
                        ORDER BY s.day, s.hour',
                        [':id_user' => $id_user, ':day' => $day]
                        // [':id_user' => 1, ':day' => 2]
                    );
                    $response['schedule'] = $res5;
                }

            } else if ($type === 'technician') {
                //query per i technician
                $res = $db->exec(
                    'SELECT s.hour, s.subject, u.id, GROUP_CONCAT(DISTINCT " ", c.name) AS class, r.name AS room
                    FROM schedule AS s 
                    JOIN user AS u ON s.id_technician = u.id
                    JOIN room AS r ON s.id_room = r.id
                    JOIN class AS c ON s.id_class = c.id
                    WHERE u.id = :id_user AND s.day = :day
                    GROUP BY s.day, s.hour
                    ORDER BY s.day, s.hour '
                );
                $response = $res;
            } else {
                $response['message'] = 'No type allowed';
            }


        } else {
            // $f3->reroute('../../P002/client/login.php');
            // exit;
            $response = ['message' => 'User Not Logged'];
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
);

// flutter schedule
$f3->route(
    'GET /user/schedule2',
    function ($f3) {
        global $db;
        // se l'utente è loggato
        $id_user = $f3->get('GET.id');
        $data = date("Y-m-d");
        $day = date("N", strtotime($data));
        $response = array ();

        $res3 = $db->exec(
            'SELECT s.hour, s.subject, GROUP_CONCAT(DISTINCT " ", u.surname) AS professors, r.name AS room
            FROM schedule AS s 
            JOIN user_in_class AS uc ON s.id_class = uc.id_class 
            JOIN user as u ON s.id_teacher = u.id 
            JOIN room as r ON s.id_room = r.id 
            WHERE uc.id_user = :id_user AND s.day = :day
            GROUP BY s.hour
            ORDER BY s.hour',
            // [':id_user' => $id_user, ':day' => 1]
            [':id_user' => $id_user, ':day' => $day]
        );

        if ($res3) {
            $response['schedule'] = $res3;
        } else {
            $response['message'] = 'Oggi è domenica';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

);

// prende tutti i docenti
$f3->route(
    'POST /user/teacher',
    function ($f3) {
        global $db;

        $res = $db->exec(
            "SELECT u.name, u.surname, u.mail, GROUP_CONCAT(DISTINCT s.subject) AS subjects 
            FROM user as u 
            JOIN schedule AS s ON u.id = s.id_teacher 
            GROUP BY s.id_teacher;"
        );
        header('Content-Type: application/json');
        echo json_encode($res);
    }
);

// prende l'orario del docente selezionato
$f3->route(
    'GET /schedule',
    function ($f3) {
        global $db;
        $id = $f3->get('GET.id');
        $type = $f3->get('GET.type');

        if($type == 'user'){
            $res = $db->exec(
                "SELECT s.day, s.hour, GROUP_CONCAT(DISTINCT ' ', c.name) AS class, r.name AS room, u.name AS name, u.surname AS surname, s.subject
                FROM schedule AS s 
                JOIN user AS u ON s.id_teacher = u.id
                JOIN room AS r ON s.id_room = r.id
                JOIN class AS c ON s.id_class = c.id
                WHERE s.id_teacher = :id_teacher
                GROUP BY s.day, s.hour
                ORDER BY s.day, s.hour",
                [':id_teacher' => $id]
                // [':id_teacher' => 1]
            );
        }else if ($type == 'class'){
            $res = $db->exec(
                'SELECT s.day, s.hour, s.subject, GROUP_CONCAT(DISTINCT " ",  u.surname) AS teachers, r.name AS room, c.name AS class
                FROM schedule AS s JOIN class AS c ON s.id_class = c.id
                JOIN user AS u ON s.id_teacher = u.id
                JOIN room AS r ON s.id_room = r.id
                WHERE c.id = :id
                GROUP BY s.day, s.hour
                ORDER BY s.day, s.hour',
                [':id' => $id]
            );
        }else{
            // room
            $res = $db->exec(
                'SELECT s.day, s.hour, s.subject, GROUP_CONCAT(DISTINCT " ",  u.surname) AS teachers, c.name AS class, r.name AS room, us.surname AS technician
                FROM schedule AS s JOIN class AS c ON s.id_class = c.id
                JOIN user AS u ON s.id_teacher = u.id
                JOIN room AS r ON s.id_room = r.id
                JOIN user AS us ON s.id_technician = us.id
                WHERE r.id = :id
                GROUP BY s.day, s.hour
                ORDER BY s.day, s.hour',
                [':id' => $id]
            );
        }
        
        header('Content-Type: application/json');
        $response['schedule'] = $res;
        $response['type'] = $type;
        echo json_encode($response);
    }
);

// logout dell'utente
$f3->route(
    'GET /user/logout',
    function ($f3) {
        if ($f3->get('SESSION.logged')) {
            // logout
            $f3->set('SESSION.logged', false);
            $f3->set('SESSION.id', null);
            // $response['success'] = true;
            $f3->reroute('../../P002/client/login.php');
        } else {
            $response['message'] = ['message' => 'User not logged'];
        }
        // exit;
        // header('Content-Type: application/json');
        // echo json_encode($response);
    }
);

// UPDATE user
$f3->route(
    'POST /user/update',
    function ($f3) {
        global $db;
        $id = $f3->get('SESSION.id');
        $token = $f3->get('POST.token');

        $res = $db->exec(
            'SELECT * FROM user WHERE id = :id',
            [':id' => $id]
        );

        $hash_token = hash('sha256', $token);

        if ($res[0]['type'] === 'student') {
            $class = $f3->get('POST.class');
            if ($class != '' || $token != '') {
                if ($class != '' && $token != '') {
                    // update class e token
                    $db->exec(
                        'UPDATE user
                        SET token = :token
                        WHERE id = :id_student;
                        UPDATE user_in_class
                        SET id_class = :class
                        WHERE id_user = :id_user',
                        [':token' => $hash_token, ':id_student' => $id, ':class' => $class, ':id_user' => $id]
                    );
                    
                    $response['success'] = true;
                } else if ($class != '') {
                    // update solo class
                    $db->exec(
                        'UPDATE user_in_class
                        SET id_class = :class
                        WHERE id_user = :id_user',
                        [':class' => $class, ':id_user' => $id]
                    );
                    
                    $response['success'] = true;
                } else if ($token != '') {
                    // update solo token
                    $db->exec(
                        'UPDATE user
                        SET token = :token
                        WHERE id = :id_student',
                        [ ':token' => $hash_token, ':id_student' => $id]
                    );
                    
                    $response['success'] = true;
                }

            } else {
                $response['message'] = 'Dati non inviati';
            }

        } else if ($res[0]['type'] === 'teacher') {
            if ($token != '') {
                $db->exec(
                    'UPDATE user
                    SET token = :token
                    WHERE id = :id',
                    [':token' => $hash_token, ':id' => $id]
                );
                
                $response['message'] = true;
            }
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
);

// user update flutter
$f3->route(
    'POST /user/update2',
    function ($f3) {
        global $db;
        $id = $f3->get('POST.id');
        $token = $f3->get('POST.password');

        $res = $db->exec(
            'SELECT * FROM user WHERE id = :id',
            [':id' => $id]
        );

        if ($res) {
            $db->exec(
                'UPDATE user SET token = :token WHERE id = :id',
                [':token' => $token, ':id' => $id]
            );
            $response['message'] = 'Update successful';
            echo json_encode($response);
        } else {
            $response['message'] = 'Not found id';
        }
    }
);

// delete user
$f3->route(
    'GET /user/delete',
    function ($f3) {
        global $db;
        // eliminare l'utente
        $id_user = $f3->get('SESSION.id');
        $db->exec(
            'DELETE FROM user WHERE id = :id',
            [':id' => $id_user]
        );
        $f3->set('SESSION.logged', false);
        $f3->set('SESSION.id', null);

        $response['message'] = 'User deleted successfully';
        $response['success'] = true;
        header('Content-Type: application/json');
        echo json_encode($response);
    }
);

// delete flutter
$f3->route(
    'POST /user/delete2',
    function ($f3) {
        global $db;
        // eliminare l'utente
        $id_user = $f3->get('POST.id');
        // $id_user = intval($id);
        if (isset ($id_user)) {
            $db->exec(
                'DELETE FROM user WHERE id = :id',
                [':id' => $id_user]
            );
            $response['message'] = 'Delete successful';
            header('Content-Type: application/json');
            echo json_encode($response);
        }

    }
);

// prende tutti gli studenti
$f3->route(
    'GET /user/students',
    function ($f3) {
        global $db;
        $is_logged = $f3->get('SESSION.logged');
        if ($is_logged) {

            $res = $db->exec('SELECT * FROM user WHERE type = "student"');

            if ($res) {
                $response = $res;
            } else {
                $response['message'] = 'No student there';
            }
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
);

// aggiunge un asisgnment per l'utente loggato
$f3->route(
    'POST /user/add/assignment',
    function ($f3) {
        global $db;

        $is_logged = $f3->get('SESSION.logged');
        if ($is_logged) {
            $id = $f3->get('SESSION.id');

            $title = $f3->get('POST.title');
            $description = $f3->get('POST.description');
            $deadline = $f3->get('POST.date');
            $hour = $f3->get('POST.hour');

            // query per prendere il type dall'id
            $check = $db->exec(
                'SELECT * FROM user WHERE id = :id',
                [':id' => $id]
            );

            $type = $check[0]['type'];

            // controllo per vedere se è uno studente
            if ($type === 'student') {
                // controllo per vedere se c'è già un compito nello stesso giorno alla stessa ora
                $res = $db->exec(
                    'SELECT * 
                    FROM assignment
                    WHERE deadline = :deadline AND hour = :hour AND id_student = :id',
                    [':deadline' => $deadline, ':hour' => $hour, ':id' => $id]
                );

                if ($res) {
                    // c'è un assignment con lo stesso giorno e ora
                    $response['message'] = 'Assignment gia\' assegnato per questo giorno e quest\'ora';
                } else {
                    // echo 'no risultati';
                    $day = date("N", strtotime($deadline));
                    // vedere se lo studente è a scuola durante l'ora selezionata
                    $checkHour = $db->exec(
                        'SELECT s.day, s.hour
                    FROM schedule AS s 
                    JOIN user_in_class AS uc ON s.id_class = uc.id_class
                    JOIN user AS u ON uc.id_user = u.id
                    WHERE u.id = :id AND s.day = :day AND s.hour = :hour',
                        [':id' => $id, ':day' => $day, 'hour' => $hour]
                    );

                    if ($checkHour) {
                        $db->exec(
                            'INSERT INTO assignment (deadline, hour, title, description, id_student)
                            VALUES (:deadline, :hour, :title, :description, :id)',
                            [':deadline' => $deadline, ':hour' => $hour, ':title' => $title, ':description' => $description, ':id' => $id]
                        );
                        $response['success'] = true;
                        // $f3->reroute('../../P002/client/schedule.php');
                    } else {
                        $response['message'] = 'L\'utente non ha lezione in quest\'ora';
                    }

                }
            } else {
                $response['message'] = 'L\'utente non è uno studente';
            }
        } else {
            $response['message'] = 'User Not Logged';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
);

// prende gli assignment dell'utente loggato
$f3->route(
    'GET /user/get/assignments',
    function ($f3) {
        global $db;
        $is_logged = $f3->get('SESSION.logged');
        if ($is_logged) {
            $id = $f3->get('SESSION.id');

            $check = $db->exec(
                'SELECT * FROM user WHERE id = :id',
                [':id' => $id]
            );

            $type = $check[0]['type'];

            if ($type == 'student') {
                $res = $db->exec(
                    'SELECT *
                    FROM assignment
                    WHERE id_student = :id',
                    [':id' => $id]
                );

                $response = $res;
            } else {
                $response['message'] = 'Utente non è studente';
            }
        }


        header('Content-Type: application/json');
        echo json_encode($response);
    }
);

// aggiungere un'attività
$f3->route(
    'POST /class/add/activity',
    function ($f3) {
        global $db;
        // $id = $f3->get('SESSION.id');
        $name = $f3->get('POST.name');

        $res = $db->exec(
            'SELECT *
            FROM class 
            WHERE name = :name',
            [':name' => $name]
        );

        if ($res) {
            $response['message'] = 'Nome gia utilizzato';
        } else {
            $db->exec(
                'INSERT INTO class (name, type)
                VALUES (:name, "activity")',
                [':name' => $name]
            );

            $selectedStudents = $f3->get('POST.selectedStudents');

            if (!empty ($selectedStudents)) {
                $id_class = $db->pdo()->lastInsertId();
                foreach ($selectedStudents as $id_student) {
                    $db->exec(
                        'INSERT INTO user_in_class (id_class, id_user)
                         VALUES (:id_class, :id_user)',
                        [':id_class' => $id_class, ':id_user' => $id_student]
                    );
                }
                $response['success'] = true;
            } else {
                $response['message'] =  "Nessuno studente selezionato";
            }

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
);

// prendere tutte le attività
$f3->route(
    'GET /class/activity',
    function ($f3) {
        global $db;
        // $id = $f3->get('SESSION.id');
    
        $res = $db->exec(
            'SELECT *
            FROM class
            WHERE type = "activity"'
        );

        header('Content-Type: application/json');
        echo json_encode($res);
    }
);

// parametro: giorno
// ritorna laboratori e ore
$f3->route(
    'GET /room/schedule',
    function ($f3) {
        global $db;
        // $id = $f3->get('SESSION.id');
        $is_logged = $f3->get('SESSION.logged');
        $response = array ();
        if ($is_logged) {
            $day = $f3->get('GET.day');

            $res1 = $db->exec(
                'SELECT ls.hour, r.id AS room_id, r.name AS room_name
                FROM lab_schedule AS ls
                JOIN room AS r ON ls.id = r.id
                JOIN class AS c ON ls.id_class = c.id
                WHERE ls.day = :day
                ORDER BY r.id',
                [':day' => $day]
            );
            $response = $res1;
        } else {
            $response = ['message' => 'User Not Logged'];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
);

// prende tutte le stanze
$f3->route(
    'GET /room',
    function ($f3) {
        // $class = $f3->get('GET.class');
        global $db;
        $is_logged = $f3->get('SESSION.logged');
        $response = array ();

        if ($is_logged) {
            $res = $db->exec(
                'SELECT * FROM room WHERE id <> 1 ORDER BY id'
            );
            $response = $res;
        } else {
            $response = ['message' => 'User Not Logged'];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
);

$f3->route(
    'GET /room/id',
    function ($f3) {
        // $class = $f3->get('GET.class');
        global $db;
        $is_logged = $f3->get('SESSION.logged');
        $response = array ();

        if ($is_logged) {
            $res = $db->exec(
                'SELECT * FROM room WHERE id <> 1'
            );
            $response = $res;
        } else {
            $response = ['message' => 'User Not Logged'];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
);

// prende tutte le classi
$f3->route(
    'GET /class',
    function ($f3) {
        global $db;

        $res = $db->exec(
            // 'SELECT * FROM class WHERE type = "class" AND id <> "0"'
            'SELECT * FROM class WHERE type = "class" AND id <> 1'
        );

        header('Content-Type: application/json');
        echo json_encode($res);
    }
);

// eliminazione assignment
$f3->route(
    'GET /assignment/delete',
    function ($f3) {
        global $db;
        $id = $f3->get('GET.id');

        $db->exec(
            'DELETE FROM assignment WHERE id = :id',
            [':id' => $id]
        );
        // $response['message'] = 'Delete successful';
    
        // header('Content-Type: application/json');
        // echo json_encode($response);
        $f3->reroute('../../P002/client/schedule.php');

    }
);

// update assignment
$f3->route(
    'PUT /assignment/@id/@date',
    function ($f3) {
        global $db;
        $id = $f3->get('PARAMS.id');
        $new_date = $f3->get('PARAMS.date');

        if ($new_date != '') {
            $db->exec(
                'UPDATE assignment SET deadline = :deadline WHERE id = :id',
                [':deadline' => $new_date, ':id' => $id]
            );

            // $f3->reroute('../../P002/client/schedule.php');

            $response['success'] = true;
            $response['date'] = $new_date;
        } else {
            $response['message'] = 'New date is null';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
);

// aggiungere una prenotazione
$f3->route(
    'POST /booking/add',
    function ($f3) {
        global $db;

        $id = $f3->get('SESSION.id');
        $day = $f3->get('POST.day');
        $room = $f3->get('POST.room');
        $hour = $f3->get('POST.hour');
        $class = $f3->get('POST.class');
        $activity = $f3->get('POST.activity');
        $date_start = $f3->get('POST.date_start');
        $date_end = $f3->get('POST.date_end');
        $title = $f3->get('POST.title');

        $day = '2024-05-15';

        $result1 = $db->exec(
            'SELECT * FROM room WHERE name = :name',
            [':name' => $room]
        );

        $room_id = $result1[0]['id'];

        // check se ci sono altre prenotazioni già fatte con gli stesse c aratteristiche
        $check = $db->exec(
            'SELECT * FROM booking
            JOIN book ON booking.id = book.id_booking
            WHERE book.day = :day AND book.id_room = :room AND book.hour_start = :hour_start AND book.hour_end = :hour_end',
            [':day' => $day, ':room' => $room_id, ':hour_start' => $hour, ':hour_end' => $hour + 1]
        );

        if ($check) {
            // ci sono altre prenotazioni
            $response['message'] = 'Esiste una prenotazione uguale';
        } else {
            $db->exec(
                'INSERT INTO booking (title, date_start, date_end)
                VALUES (:title, :date_start, :date_end)',
                [':title' => $title, ':date_start' => $date_start, ':date_end' => $date_end]
            );

            $id_booking = $db->pdo()->lastInsertId();

            $id_class = (
                $activity != '' ? $activity : $class
            );

            $db->exec(
                'INSERT INTO book
                VALUES (:id_teacher, :id_class, :id_room, :id_booking, :day, :hour_start, :hour_end)',
                [':id_teacher' => $id, 'id_class' => $id_class, ':id_room' => $room_id, ':id_booking' => $id_booking, ':day' => $day, ':hour_start' => $hour, ':hour_end' => $hour + 1]
            );

            $response['success'] = true;
        }

        header('Content-Type: application/json');
        echo json_encode($response);

    }
);

// ritorna tuti i booking
$f3->route(
    'GET /bookings',
    function ($f3) {
        global $db;
        
        $res = $db->exec(
            'SELECT bk.title, u.name, u.surname, b.day, b.hour_start, b.hour_end, c.name AS class, r.name AS room
            FROM book AS b
            JOIN user AS u ON b.id_teacher = u.id
            JOIN room AS r ON b.id_room = r.id
            JOIN class AS c ON b.id_class = c.id
            JOIN booking AS bk ON bk.id = b.id_booking'
        );

        if($res){
            $response = $res;
        }else{
            $response['message'] = 'No bookings';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
);

// route di prova
$f3->route(
    'GET /user/prova',
    function ($f3) {
        global $db;
        $pass = '6f49cdbd80e1b95d5e6427e1501fc217790daee87055fa5b4e71064288bddede';
        $pass_hash = hash('sha256', $pass);
        echo $pass_hash;
    
        // $int = intval($pass);
        // echo gettype($int);
        // echo gettype($pass);
        // if(password_verify($pass, $pass_hash)){
            // echo 'si';
        // }
    
        // $stringa = "a.a@gmail.com";

        // echo 'INSERT INTO user (name, surname, mail, token, type) VALUES';
        // $res = $db->exec('SELECT * FROM user WHERE type = "teacher" OR type = "technician"');
        // foreach ($res as $row) {
        //     $hash = hash('sha256', $row['token']);
        //     echo "('" . $row['name']  . "', '" . $row['surname'] . "', '" . $row['mail'] . "', '" . $hash . "', 'teacher'),<br>";
        // }
        // echo ";";

        // header('Content-Type: application/json');

        // echo json_encode($res);

    }
);

$f3->run();
?>