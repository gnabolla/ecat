<?php
// Main entry point file: login.php

$title = "ECAT System Login";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 30px;
            width: 400px;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .login-options {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .login-option {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px 0;
            width: 48%;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .login-option:hover {
            background-color: #45a049;
        }
        .login-option.admin {
            background-color: #2196F3;
        }
        .login-option.admin:hover {
            background-color: #0b7dda;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to ECAT System</h1>
        <p>Please select your login type:</p>
        
        <div class="login-options">
            <a href="/student/login.php" class="login-option">Student Login</a>
            <a href="/admin/login.php" class="login-option admin">Admin Login</a>
        </div>
    </div>
</body>
</html>