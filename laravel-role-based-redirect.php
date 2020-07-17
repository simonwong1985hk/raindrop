<?php

# app/Http/Controllers/Auth/LoginController.php

/**
 * Role based redirect
 */
protected function redirectTo() {
    if (auth()->user()->role_id == 3) {
        return 'tutor/index';
    }

    if (auth()->user()->role_id == 4) {
        return 'student/index';
    }
}
