<!DOCTYPE html>
<html lang="pt-br">
<title>Teste</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" crossorigin="anonymous"></script>


<body>
    <header>
        <div class="container">
            <h1>Shared Run</h1>
            <hr>
        </div>
    </header>
    <main>
        
            <div class="container">
                <button type="button" class="btn btn-primary btn-lg" data-toggle="collapse" data-target="#teste">Cadastrar Passageiro!</button>
                <div id="teste" class="collapse">
                    <form id="adiciona-passageiro" class="hidden-lg-down ">
                        <h2 class="h2 ">Cadastro de Passageiro</h2>
                        <div class="form-row ">
                            <div class="form-group col-md-6 ">
                                <label for="inputNF">Número da NF:</label>
                                <input name="num_nf" type="text" class="form-control" id="inputNF" placeholder="Digite o nº da NF">
                            </div>
                                <label for="inputNome">Nome:</label>
                                <input name="nome" type="text" class="form-control" id="inputNome" placeholder="Digite seu nome">
                            <div class="form-group col-md-6 ">
                                <label for="inputSerie">Serie da NF:</label>
                                <input name="serie_nf" type="text" class="form-control" id="inputSerie" placeholder="Digite a serie da NF">
                            </div>
                            <div class="form-group col-md-6 ">
                                <label for="inputValorNF">Valor da NF:</label>
                                <input name="valor_nf" type="text" class="form-control" id="inputValorNF" placeholder="Digite o valor da NF">
                            </div>
                            <div class="form-group col-md-6 ">
                                <label for="inputDesc">Descrição da NF:</label>
                                <input name="desc_nf" type="text" class="form-control" id="inputDesc" placeholder="Digite a descrição da NF">
                            </div>
                            <div class="form-group col-md-6 ">
                                <label for="inputData">Data de Nascimento:</label>
                                <input name="nascimento" type="date" class="form-control" id="inputData">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputCpf">CPF:</label>
                            <input name="cpf" type="text" class="form-control" id="inputCpf" placeholder="000.000.000-00">
                        </div>
                        <button id="btn-passageiro" class="btn btn-success">Cadastrar</button>
                    </form>
                    <p id="notice"></p>
                </div>

                <table class="table table-hover passageiro-lista">
                    <thead class="thead-dark">
                        <h2 class="h2 ">Passageiro</h2>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Data de Nascimento</th>
                            <th scope="col">CPF</th>
                            <th scope="col">Nº NF</th>
                        </tr>
                    </thead>

                    <tbody id="tabela-passageiros">
                    </tbody>

                </table>

            </div>
        
    </main>
</body>
</html>
<script src="../js manual/teste.js"></script>