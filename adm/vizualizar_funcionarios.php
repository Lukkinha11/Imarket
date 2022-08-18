<?php

ob_start();
ini_set('default_charset','utf-8');
require_once '../class/conexao2.php';
global $pdo;

$idFuncionarios = filter_input(INPUT_GET, "idFuncionarios", FILTER_SANITIZE_NUMBER_INT);
//$idCategoria = "1000";

if(!empty($idFuncionarios))
{
   $query_funcionarios = "SELECT idFuncionarios, Nome, Sobrenome, Data_nasc, Cargo_idCargo, Login, Senha, CPF, Email, Telefone, DDD, Endereco_idEndereco, Cep, Logradouro, Bairro, Cidade, Estado, Complemento, Status, date_format(Admissao, '%d/%m/%Y') AS Admissao, Acesso_idAcesso FROM funcionarios
                         INNER JOIN cargo
                         ON funcionarios.Cargo_idCargo = cargo.idCargo
                         INNER JOIN endereco
                         ON funcionarios.Endereco_idEndereco = endereco.idEndereco
                         INNER JOIN acesso
                         ON funcionarios.Acesso_idAcesso = acesso.idAcesso
                         WHERE idFuncionarios=:idFuncionarios LIMIT 1";

   $result_funcionarios = $pdo->prepare($query_funcionarios); 
   $result_funcionarios->bindValue(':idFuncionarios', $idFuncionarios, PDO::PARAM_INT);
   $result_funcionarios->execute();

   if(($result_funcionarios) AND ($result_funcionarios->rowCount() !=0))
   {
        $row_funcionarios = $result_funcionarios->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_funcionarios];
   }
   else
   {
        $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Funcionário encontrado</div>"];
   }
}
else
{
    $retorna = ['status' => false, 'msg' =>  "<div class='alert alert-danger' role='alert'>Erro: Nenhum Funcionário encontrado</div>"];
}

echo json_encode($retorna);