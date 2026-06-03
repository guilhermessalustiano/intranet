<?php

include($_SERVER["DOCUMENT_ROOT"] . "/header.php");


    controlarAcessoModulos($_SESSION['username']);


        if(checkIsAdmin($_SESSION['username'])==1){
        echo("<div><button class='btn btn-primary' style='margin:10px' onclick=\"location.href='gerenciar-pessoas/gerenciar.php'\" type='button'>
            <div class='' style='max-width: 18rem;'>
                <div class='card-body'>Gerenciamento de Pessoas
                </div>
            </div>
        </button></div>");
    }


include($_SERVER["DOCUMENT_ROOT"] . "/footer.php");

?>
