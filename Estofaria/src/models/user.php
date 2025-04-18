<?php
require_once __DIR__ . '/../../database/connect.php';

function getUserById(int $id): ?array {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, nome, email FROM users WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user ?: null;
}
