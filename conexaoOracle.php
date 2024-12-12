<?php

require_once 'vendor/autoload.php';  
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env');
$dotenv->load();  

class Conexao
{
    private ?PDO $base = null;

    public function conectar(): ?PDO
    {
        
        $dbHost = $_ENV['DB_HOST'];      
        $dbPort = $_ENV['DB_PORT'];        
        $dbService = $_ENV['DB_SERVICE']; 
        $dbUser = $_ENV['DB_USER'];       
        $dbPass = $_ENV['DB_PASS']; 
        
        
        if (!$dbHost || !$dbPort || !$dbService || !$dbUser || !$dbPass) {
            echo "Uma ou mais variáveis de ambiente estão ausentes!";
            return null;
        }

        $db = "oci:dbname=//$dbHost:$dbPort/$dbService";

        try {
            
            $this->base = new PDO($db, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            echo "Conexão com a base de dados Oracle estabelecida com sucesso!";

            $sql = "SELECT * FROM ALL_TABLES WHERE OWNER = 'TASY' AND TABLE_NAME = 'ORDEM_COMPRA'";  
            $stmt = $this->base->query($sql); 

            if ($stmt) {
                while ($linha = $stmt->fetch()) {
                    print_r($linha);  
                }
            }

            return $this->base;

        } catch (PDOException $e) {
            
            error_log($e->getMessage(), 3, '/caminho/para/seu/logfile.log');
            echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
            return null;
        }
    }

    public function fecharConexao()
    {
        
        if ($this->base !== null) {
            $this->base = null;
            echo "Conexão fechada.";
        }
    }
}

$conexao = new Conexao();
$db = $conexao->conectar();

$conexao->fecharConexao();
?>
