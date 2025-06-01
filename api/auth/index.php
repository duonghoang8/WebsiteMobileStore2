<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once '../../models/user.php';

$user = new User();
$request_method = $_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(isset($_GET['action'])) {
            switch($_GET['action']) {
                case 'login':
                    handleLogin($data, $user);
                    break;
                case 'register':
                    handleRegister($data, $user);
                    break;
                case 'logout':
                    handleLogout();
                    break;
                default:
                    http_response_code(400);
                    echo json_encode(array("message" => "Invalid action"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Action not specified"));
        }
        break;
    
    case 'GET':
        if(isset($_GET['action']) && $_GET['action'] == 'check_session') {
            checkSession();
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid request"));
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed"));
        break;
}

function handleLogin($data, $user) {
    if(empty($data->username) || empty($data->password)) {
        http_response_code(400);
        echo json_encode(array("message" => "Username và password không được để trống"));
        return;
    }

    if($user->login($data->username, $data->password)) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role'] = $user->role;
        $_SESSION['full_name'] = $user->full_name;
        
        http_response_code(200);
        echo json_encode(array(
            "message" => "Đăng nhập thành công",
            "user" => array(
                "id" => $user->id,
                "username" => $user->username,
                "full_name" => $user->full_name,
                "role" => $user->role
            )
        ));
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Tên đăng nhập hoặc mật khẩu không đúng"));
    }
}

function handleRegister($data, $user) {
    if(empty($data->username) || empty($data->email) || empty($data->password) || empty($data->full_name)) {
        http_response_code(400);
        echo json_encode(array("message" => "Vui lòng điền đầy đủ thông tin"));
        return;
    }

    // Validate email
    if(!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(array("message" => "Email không hợp lệ"));
        return;
    }

    // Validate password length
    if(strlen($data->password) < 6) {
        http_response_code(400);
        echo json_encode(array("message" => "Mật khẩu phải có ít nhất 6 ký tự"));
        return;
    }

    $user->username = $data->username;
    $user->email = $data->email;
    $user->password = $data->password;
    $user->full_name = $data->full_name;

    // Check if username exists
    if($user->usernameExists()) {
        http_response_code(400);
        echo json_encode(array("message" => "Tên đăng nhập đã tồn tại"));
        return;
    }

    // Check if email exists
    if($user->emailExists()) {
        http_response_code(400);
        echo json_encode(array("message" => "Email đã được sử dụng"));
        return;
    }

    if($user->register()) {
        http_response_code(201);
        echo json_encode(array("message" => "Đăng ký thành công"));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Có lỗi xảy ra khi đăng ký"));
    }
}

function handleLogout() {
    session_destroy();
    http_response_code(200);
    echo json_encode(array("message" => "Đăng xuất thành công"));
}

function checkSession() {
    if(isset($_SESSION['user_id'])) {
        http_response_code(200);
        echo json_encode(array(
            "logged_in" => true,
            "user" => array(
                "id" => $_SESSION['user_id'],
                "username" => $_SESSION['username'],
                "full_name" => $_SESSION['full_name'],
                "role" => $_SESSION['role']
            )
        ));
    } else {
        http_response_code(200);
        echo json_encode(array("logged_in" => false));
    }
}
?>
