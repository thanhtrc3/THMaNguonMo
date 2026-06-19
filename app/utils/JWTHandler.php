<?php
require_once 'vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JWTHandler
{
    private $secret_key;

    public function __construct()
    {
        // Khóa bí mật dùng để ký token
        $this->secret_key = "NGUYENTHANHIEN_CYBERSTORE_2005_2026_THUCHANHVAPHATTRIENMANGUONMO_KMITLAB03"; 
    }

    // Tạo JWT
    public function encode($data)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // Token có hiệu lực 1 giờ

        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $data
        );
        return JWT::encode($payload, $this->secret_key, 'HS256');
    }

    // Giải mã JWT
    public function decode($jwt)
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secret_key, 'HS256'));
            return (array) $decoded->data;
        } catch (Exception $e) {
            return null; // Trả về null nếu Token sai hoặc hết hạn
        }
    }
}
?>
