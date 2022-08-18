
const listarProdutos = async () =>
{
    const dados = await fetch("./list.php");
    const resposta = await dados.text();
}
    
listarProdutos();