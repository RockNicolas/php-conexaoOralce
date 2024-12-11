<?php
class Conexao
{
    private $db = "";
    private $user = "";
    private $pass = "";
    private ?PDO $base = null;

    public function Conectar(): ?PDO
    {
        try {
            $this->base = new PDO($this->db, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            echo "Conexão com a base de dados Oracle estabelecida com sucesso!";

            $sql = "SELECT * FROM ALL_TABLES WHERE OWNER = 'TASY' AND TABLE_NAME = 'ORDEM_COMPRA'";  
            $stmt = $this->base->query($sql); 

            
            if ($stmt) {
                while ($row = $stmt->fetch()) {
                    print_r($row);  
                }
            }
            
            return $this->base;

        } catch (PDOException $e) {
            error_log($e->getMessage(), 3, '/path/to/your/logfile.log');
            echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
            return null;
        }
    }
    public function FecharConexao()
    {
        if ($this->base !== null) {
            $this->base = null;
            echo "Conexão fechada.";
        }
    }
}

$conexao = new Conexao();
$db = $conexao->Conectar();

$conexao->FecharConexao();
?>
