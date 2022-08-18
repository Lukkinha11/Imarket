<?php

    ini_set('default_charset', 'utf-8');
    require_once '../class/conexao2.php';
    global $pdo;

    $id_filtro = $_GET['id_filtro'];

    if($id_filtro == 1)
    {
        $query_formapag = "SELECT idDesc_pag, Forma_pag, Quant FROM desc_pag LIMIT 1";

    }elseif($id_filtro == 2)
    {
        $query_formapag = "SELECT idDesc_pag, Forma_pag, Quant FROM desc_pag";
    }
    elseif($id_filtro == 3)
    {
        $query_formapag = "SELECT idDesc_pag, Forma_pag, Quant FROM desc_pag LIMIT 1";
    }
    elseif($id_filtro == 4)
    {
        $query_formapag = "SELECT idDesc_pag, Forma_pag, Quant FROM desc_pag";
    }
    elseif($id_filtro == 5)
    {
        $query_formapag = "SELECT idDesc_pag, Forma_pag, Quant FROM desc_pag LIMIT 1";
    }

    $result_pag = $pdo->prepare($query_formapag);
    $result_pag->execute();

    while($dados = $result_pag->fetch(PDO::FETCH_ASSOC))
    {
        extract($dados);
        $opcaoSalva = $data['quant_parcelas'];

        ?>
            
            <option value="<?php echo $idDesc_pag ?>" <?php echo ($idDesc_pag == $opcaoSalva) ? 'selected' : 'Selecione' ?>><?php echo $Quant . "x" ?></option>
                                    
        <?php
    }

    die;

    