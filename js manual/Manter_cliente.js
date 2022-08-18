$(document).ready(function() {
    $('#listar_cliente').DataTable( {
        scrollX: true,
        "processing": true,
        "serverSide": true,
        "ajax": "list_cliente.php",
        "language": 
        {
            "url": "../json_manual/Data_tables_brazilian.json"
        }
    } );
} );