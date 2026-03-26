<?php
// Script de prueba de CSRF
session_start();

echo "<h1>Prueba de CSRF y Sesiones</h1>";
echo "<p>Servidor: " . $_SERVER['SERVER_NAME'] . "</p>";
echo "<p>Puerto: " . $_SERVER['SERVER_PORT'] . "</p>";
echo "<p>Remote Addr: " . $_SERVER['REMOTE_ADDR'] . "</p>";
echo "<p>HTTP Host: " . ($_SERVER['HTTP_HOST'] ?? 'No definido') . "</p>";

// Mostrar headers
echo "<h2>Headers HTTP:</h2>";
foreach (getallheaders() as $name => $value) {
    echo "<p><strong>$name:</strong> $value</p>";
}

// Mostrar cookies
echo "<h2>Cookies:</h2>";
foreach ($_COOKIE as $name => $value) {
    echo "<p><strong>$name:</strong> $value</p>";
}

// Probar sesión
if (!isset($_SESSION['test_count'])) {
    $_SESSION['test_count'] = 1;
} else {
    $_SESSION['test_count']++;
}

echo "<h2>Sesión:</h2>";
echo "<p>ID de sesión: " . session_id() . "</p>";
echo "<p>Contador de pruebas: " . $_SESSION['test_count'] . "</p>";

// Formulario de prueba
echo "<h2>Formulario de prueba CSRF:</h2>";
echo '<form method="POST" action="/test-csrf">';
echo '<input type="hidden" name="_token" value="' . (function_exists('csrf_token') ? csrf_token() : 'test-token') . '">';
echo '<input type="text" name="test_field" placeholder="Campo de prueba">';
echo '<button type="submit">Probar CSRF</button>';
echo '</form>';

// Enlace para probar
echo '<p><a href="/test-csrf-result">Ver resultado de prueba CSRF</a></p>';
