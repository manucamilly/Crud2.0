<?php
// Inclui o arquivo de configuracao de conexao com o banco de dados
include_once('conexao/conexao.php');

// Cria uma instancia da classe Database para obter a conexao com o banco de dados
$db = new Database();

// Define uma classe chamada Crud para realizar operacoes CRUD no banco de dados
    class Crud{
    private $conn; // Armazena a conexao com o banco de dados
    private $table_name = "carros"; // Nome da tabela no banco de dados

    // Construtor da classe, recebe a conexao como argumento
    public function __construct($db){
        $this->conn = $db;
    }

    // Funcao para criar um novo registro no banco de dados
    public function create($postValues){
        // Obtem os valores enviados via POST
        $modelo = $postValues['modelo'];
        $marca = $postValues['marca'];
        $placa = $postValues['placa'];
        $cor = $postValues['cor'];
        $ano = $postValues['ano'];

        // Prepara uma consulta SQL para inserir os valores na tabela
        $query = "INSERT INTO ". $this->table_name . " (modelo, marca, placa, cor, ano) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $modelo);
        $stmt->bindParam(2, $marca);
        $stmt->bindParam(3, $placa);
        $stmt->bindParam(4, $cor);
        $stmt->bindParam(5, $ano);

        // Executa a consulta SQL
        if($stmt->execute()){
            // Exibe uma mensagem de sucesso e redireciona para a pagina de leitura
            print "<script>alert('Cadastro Ok!')</script>";
            print "<script> location.href='?action=read'; </script>";
            return true;
        }else{
            return false;
        }
    }

    // Funcao para ler todos os registros da tabela
    public function read(){
        $query = "SELECT * FROM ". $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Retorna o resultado da consulta
    }

    // Funcao para atualizar um registro no banco de dados
    public function update($postValues){
        // Obtem os valores enviados via POST
        $id = $postValues['id'];
        $modelo = $postValues['modelo'];
        $marca = $postValues['marca'];
        $placa = $postValues['placa'];
        $cor = $postValues['cor'];
        $ano = $postValues['ano'];

        // Verifica se algum dos campos obrigatorios esta vazio
        if(empty($id) || empty($modelo) || empty($marca) || empty($placa) || empty($cor) || empty($ano)){
            return false;
        }

        // Prepara uma consulta SQL para atualizar os valores na tabela
        $query = "UPDATE ". $this->table_name . " SET modelo = ?, marca = ?, placa = ?, cor = ?, ano = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $modelo);
        $stmt->bindParam(2, $marca);
        $stmt->bindParam(3, $placa);
        $stmt->bindParam(4, $cor);
        $stmt->bindParam(5, $ano);
        $stmt->bindParam(6, $id);

        // Executa a consulta SQL
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    // Funcao para ler um unico registro com base em um ID fornecido
    public function readOne($id){
        $query = "SELECT * FROM ". $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna o registro como um array associativo
    }

    // Funcao para excluir um registro com base em um ID fornecido
    public function delete($id){
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
}
?>
