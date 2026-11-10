<?php
class AlertHandler {
    public static function setAlert($message, $type = 'success') {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['alert'] = [
            'message' => $message,
            'type' => $type
        ];
    }

    public static function displayAlert() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['alert'])) {
            $alert = $_SESSION['alert'];
            echo '<div class="alert alert-' . htmlspecialchars($alert['type']) . '">' . htmlspecialchars($alert['message']) . '</div>';
            unset($_SESSION['alert']);
        }
    }
}
?>