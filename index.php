<?php

// Inclui o arquivo que contem a classe Crud
require_once('classes/Crud.php');

// Inclui o arquivo que configura a conexao com o banco de dados
require_once('conexao/conexao.php'); 

// Cria uma instancia da classe Database para obter a conexao com o banco de dados
$database = new Database();
$db = $database->getConnection();

// Cria uma instancia da classe Crud, passando a conexao como argumento
$crud = new Crud($db);

// Verifica se a acao esta definida na URL (por meio do metodo GET)
if(isset($_GET['action'])){
    // Dependendo da acao especificada, executara uma operacao CRUD
    switch($_GET['action']){
        case 'create':
            // Se a acao for 'create', chama o metodo 'create' da classe Crud, passando os dados do formulario via POST
            $crud->create($_POST);
            // Em seguida, le todos os registros
            $rows = $crud->read();
            break;
        case 'read':
            // Se a acao for 'read', apenas le todos os registros
            $rows = $crud->read();
            break;
        case 'update':
            // Se a acao for 'update', verifica se o ID do registro a ser atualizado esta definido no POST
            if(isset($_POST['id'])){
                // Se o ID estiver definido, chama o metodo 'update' da classe Crud para atualizar o registro
                $crud->update($_POST);
            }
            // Em seguida, le todos os registros
            $rows = $crud->read();
            break;
        case 'delete':
            // Se a acao for 'delete', verifica se o ID do registro a ser excluido esta definido na URL (via GET)
            $crud->delete($_GET['id']);
            // Em seguida, le todos os registros
            $rows = $crud->read();
            break;
        default:
            // Se a acao nao for reconhecida, le todos os registros por padrao
            $rows = $crud->read();
            break;
    }
}else{
    // Se nenhuma acao estiver definida na URL, le todos os registros por padrao
    $rows = $crud->read();
}
?>

<!-- Aqui comeca a parte HTML do codigo -->

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Cabecalho HTML com metadados e estilos CSS incorporados -->
    <style> 
        form{
            max-width:500px;
            margin: 0 auto;
        }
        label{
            display: flex;
            margin-top:10px;
        }
        input[type=text]{
            width:100%;
            padding: 12px 20px;
            margin: 8px 0;
            display:inline-block;
            border: 1px solid #ccc;
            border-radius:4px;
            box-sizing:border-box;
        }
        input[type=submit]{
            background-color:#4caf50;
            color:white;
            padding:12px 20px;
            border:none;
            border-radius:4px;
            cursor:pointer;
            float:right;
        }
        input[type=submit]:hover{
            background-color:#45a049;
        }
        table{
            border-collapse:collapse;
            width:100%;
            font-family:Arial, sans-serif;
            font-size:14px;
            color:#333;
        }
        th, td{
            text-align:left;
            padding:8px;
            border: 1px solid #ddd;
        }
        th{
            background-color:#f2f2f2;
            font-weight:bold;
        }
        a{
            display:inline-block;
            padding:4px 8px;
            background-color: #007bff;
            color:#fff;
            text-decoration:none;
            border-radius:4px;
        }
          a:hover{
            background-color:#0069d9;
        }

        a.delete{
            background-color: #dc3545;
        }
        a.delete:hover{
            background-color:#c82333;
        }
    </style>
</head>
<body>
    
<?php
// Verifica se a acao e 'update' e se um ID foi fornecido na URL
if(isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id'])){
    $id = $_GET['id'];
    // Le os detalhes do registro com o ID especificado
    $result = $crud->readOne($id);

    // Se o registro nao for encontrado, exibe uma mensagem de erro e encerra a execucao
    if(!$result){
        echo "Registro nao encontrado.";
        exit();
    }

    // Extrai os dados do registro para uso no formulario de atualizacao
    $modelo = $result['modelo'];
    $marca = $result['marca'];
    $placa = $result['placa'];
    $cor = $result['cor'];
    $ano = $result['ano'];
?>

    <!-- Formulario de atualizacao com os campos pre-preenchidos com os dados do registro -->
    <form action="?action=update" method="POST">
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <!-- Campos de entrada para os atributos do registro -->
        <!-- ... -->
        <input type="submit" value="Atualizar" name="enviar" onclick="return confirm('Certeza que deseja atualizar?')">
    </form>

<?php
}else{
?>

    <!-- Formulario de criacao de um novo registro -->
    <!-- ... -->
    <form action="?action=create" method="POST">
        <!-- Campos de entrada para os atributos do registro -->
        <!-- ... -->
        <input type="submit" value="Cadastrar" name="enviar">
    </form>

<?php
}
?>

<!-- Tabela HTML para exibir os registros de veiculos -->
<table>
    <tr>
        <!-- Cabecalhos da tabela -->
        <td>Id</td>
        <td>Modelo</td>
        <td>Marca</td>
        <td>Placa</td>
        <td>Cor</td>
        <td>Ano</td>
        <td>Acoes</td>
    </tr>

    <?php
    // Loop para exibir os registros na tabela
    if(isset($rows)){
       foreach($rows as $row){
            echo "<tr>";
            // Exibe os dados do registro em cada coluna
            echo "<td>". $row['id']."</td>";
            echo "<td>". $row['modelo']."</td>";
            echo "<td>". $row['marca']."</td>";
            echo "<td>". $row['placa']."</td>";
            echo "<td>". $row['cor']."</td>";
            echo "<td>". $row['ano']."</td>";
            echo "<td>";
            // Links para editar e excluir o registro
            echo "<a href='?action=update&id=".$row['id']."'>Editar</a>";
            echo "<a href='?action=delete&id=".$row['id']."' onclick='return confirm(\"Tem certeza que deseja deletar esse registro?\")' class='delete'>Deletar</a>";
            echo "</td>";
            echo "</tr>";
        }
    }else{
        echo "Nao ha registros a serem exibidos";
    }
    ?>
</table>
</body>
</html>
