class TraccarClient {
    
    private $api_url;
    private $auth_token;
    
    public function __construct($config) {
        $this->api_url = $config['api_url'];
        $this->auth_token = base64_encode("{$config['email']}:{$config['password']}");
    }
    
    public function block_device($imei) {
        // Implementación de bloqueo via API
    }
}