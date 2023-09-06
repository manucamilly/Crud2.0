<?php

// Define uma classe chamada Database
class Database{
    // Define propriedades privadas para armazenar as informacoes de conexao com o banco de dados
    private $host = "localhost"; // Endereco do servidor de banco de dados
    private $db_name = "aula3crud"; // Nome do banco de dados
    private $username = "root"; // Nome de usuario do banco de dados
    private $senha = ""; // Senha do banco de dados
    private $conn; // Armazena a conexao PDO

    // Metodo publico para obter uma conexao com o banco de dados
    public function getConnection(){
        $this->conn = null; // Inicializa a conexao como nula

        try{
            // Tenta criar uma nova instancia da classe PDO para estabelecer a conexao com o banco de dados
            $this->conn = new PDO("mysql:host=". $this->host.";dbname=".$this->db_name, $this->username, $this->senha);
            
            // Define o modo de erro para excecao, o que significa que o PDO lancara excecoes em caso de erros
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            // Em caso de erro na conexao, exibe uma mensagem de erro
            echo "Erro na conexao: ". $e->getMessage();
        }
        
        // Retorna a conexao estabelecida ou nula em caso de erro
        return $this->conn;
    }
}
?>
