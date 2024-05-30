<?php

require 'vendor/autoload.php';
$f3 = \Base::instance();

class LoginController {
    public function showLoginPage($f3) {
        // Logica per mostrare la pagina 1
        echo "Questa è la pagina 1";
    }
}

class Searchcontroller {
    public function showSearchPage($f3) {
        // Logica per mostrare la pagina 2
        echo "Questa è la pagina 2";
    }
}

?>
