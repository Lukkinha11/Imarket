<!DOCTYPE html>
<html>
<head>
    <title>Teste otávio</title>
    <meta charset="utf-8">
    <script
    src="https://code.jquery.com/jquery-3.3.1.js"
    integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
    crossorigin="anonymous"></script>
</head>
<body>
    <select id="select">
        <option value="" selected>Selecione</option>
        <option value="selectA">Select A</option>
        <option value="selectB">Select B</option>
        <option value="selectC">Select C</option>
    </select>
    <select id="selectA" class="opicionais" style="display: none;">
        <option value="opA1">Opção A1</option>
        <option value="opA2">Opção A2</option>
        <option value="opA3">Opção A3</option>
    </select>
    <select id="selectB" class="opicionais" style="display: none;">
        <option value="opB1">Opção B1</option>
        <option value="opB2">Opção B2</option>
        <option value="opB3">Opção B3</option>
    </select>
    <select id="selectC" class="opicionais" >
        <option value="opC1">Opção C1</option>
        <option value="opC2">Opção C2</option>
        <option value="opC3">Opção C3</option>
    </select>

</body>
</html>
    <script type="text/javascript">
        $("#select").change(function(){
            $(".opicionais").each(function(){
                $(this).hide();
            });
            var valor = $(this).val();
                $("#"+valor).show();
        });
    </script>