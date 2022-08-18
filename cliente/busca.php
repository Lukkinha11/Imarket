<?php

	ini_set('default_charset', 'utf-8');
	require_once '../class/conexao2.php';
	global $pdo;

	$prod = trim($_POST['palavra']);

	$prod = "SELECT id_Produto, Nome_prod, Desc_prod, Image, Categoria_diretorio, Valor_prod, Valor_novo FROM produto
								INNER JOIN preco
								ON preco.Produto_Id_Produto = produto.id_Produto
								INNER JOIN categoria_prod
								ON produto.Categoria_Prod_idCategoria = categoria_prod.idCategoria
								WHERE Nome_prod LIKE '%$prod%'";

	$prod_result = $pdo->prepare($prod);
	$prod_result->execute();


	if($prod_result->rowCount() <= 0)
	{
?>
	<div class="text-muted">
		<div class="text-center mt-5">
			<BR></BR>
			<p>
				<h1>Nenhum produto encontrado</h1>
			</p>
		</div>	
	</div>
<?php
	}
	else
	{
		echo"<div class='row g-3'>";
				while($rows = $prod_result->fetch(PDO::FETCH_ASSOC))
				{
					extract($rows);
				?>	   	
					<div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
						<div class="card text-center bg-light">
							<img src='<?php echo "../img_prod/$Categoria_diretorio/$Image"; ?>' class="card-img-top" alt="...">
							<div class="card-body">
								<h5 class="card-title"><?php echo "$Nome_prod <br>"; ?></h5>
								<div class="card-text"> R$ <?php echo number_format($Valor_novo, 2, ",", "."); ?></div>
							</div>
							<div class="card-footer">
								<form action="index.php?page=cart&action=add" method="POST" class="d-block">
									<div class="d-grid gap-2 ">
										<input type="hidden" name="id" value="<?php echo $id_Produto; ?>" />
										<button  type="submit" name="addcart" class="btn btn-outline-secondary add" title="Adicionar ao Carrinho" ><strong>ADICIONAR</strong> <img src="../icons/add-to-cart.png" alt=""></button>
										<a href="index.php?page=det&id=<?php echo $id_Produto; ?>" class="btn btn-outline-secondary"><strong>DETALHES</strong>  👀</a>
									</div>
								</form>
							</div>
						</div>
					</div>
				<?php		
				}
		echo"</div>";
	}
?>