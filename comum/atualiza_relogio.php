<?php
include_once $_SERVER["DOCUMENT_ROOT"].'/keycloak_api.php';

session_start();

checkKeycloakAuth();

refreshTokenIfNeeded();
$userData = getUserData();

echo json_encode(['remaining_time' => $userData['token_expires_in_seconds']]);
?>