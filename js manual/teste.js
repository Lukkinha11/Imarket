const DOMStrings = 
{
    btn: '#btn-passageiro',
    adicionaPassageiro: '#adiciona-passageiro',
    tabelaPassageiro: '#tabela-passageiros',
    error: '#notice'
}

document.querySelector(DOMStrings.btn).addEventListener('click', function(e) {
    e.preventDefault();
    let form = document.querySelector(DOMStrings.adicionaPassageiro);

    if (form.inputNome.value === '' || form.inputData.value === '' || form.inputCpf.value === ''
        || form.inputNF.value === '') {
        return document.querySelector(DOMStrings.error).innerHTML = 'É necessário preencher todos os campos para cadastrar'
    }


    let passageiro = criaPassageiro(form);

    var passageiroTr = document.createElement("tr");
    var nomeTd = document.createElement("td");
    var nascimentoTd = document.createElement("td");
    var cpfTd = document.createElement("td");
    var num_nfTd = document.createElement("td");

    nomeTd.textContent = passageiro.nome;
    nascimentoTd.textContent = passageiro.dataNascimento;
    cpfTd.textContent = passageiro.cpf;
    num_nfTd.textContent = passageiro.numeroNf;


    passageiroTr.appendChild(nomeTd);
    passageiroTr.appendChild(nascimentoTd);
    passageiroTr.appendChild(cpfTd);
    passageiroTr.appendChild(num_nfTd);

    var tabelaPassageiro = document.querySelector(DOMStrings.tabelaPassageiro);
    tabelaPassageiro.appendChild(passageiroTr);

    document.querySelector(DOMStrings.error).innerHTML = '';

    form.reset();
    form.inputNome.focus();
})

function criaPassageiro(form) {
    let passageiro = {
        nome: form.inputNome.value,
        dataNascimento: dataBrasil(form.inputData.value),
        cpf: form.inputCpf.value,
        numeroNf: form.inputNF.value
    }

    return passageiro;
}

function dataBrasil(data) {
    return data.split('-').reverse().join('/');
}