<?php
function alerta_sucesso($texto) {
    echo("  <div class='alert alert-success' role='alert'>
                $texto
            </div>");
}

function alerta_erro($texto) {
    echo("  <div class='alert alert-danger' role='alert'>
                $texto
            </div>");
}

function alerta_atencao($texto) {
    echo("  <div class='alert alert-warning' role='alert'>
                $texto
            </div>");
}
?>