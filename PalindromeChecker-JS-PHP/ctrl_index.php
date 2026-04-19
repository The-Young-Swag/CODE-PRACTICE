<?php
header("Content-Type: application/json");

// Early exit if wrong method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$input = $_POST['input'] ?? '';
$clean = "";

for ($i = 0; $i < strlen($input); $i++) {
    $char = $input[$i];

    // check if letter (a-z or A-Z)
    if (
        ($char >= 'a' && $char <= 'z') ||
        ($char >= 'A' && $char <= 'Z') ||
        ($char >= '0' && $char <= '9')
    ) {
        $clean .= strtolower($char);
    }
}
$result  = ($clean === strrev($clean)) ? "✅ Palindrome" : "❌ Not a palindrome";

echo json_encode(["result" => $result]);