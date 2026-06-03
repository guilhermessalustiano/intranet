<?php
global $_GET, $_POST;

if (!isset($_SESSION)) session_start();

require("header.php");
     
?>

<div class="container iel-container" style="max-width:100%;padding-left:25px;padding-right:40px">

  <div class="row">

  <!-- BLOCO ESQUERDO -->

    <div class="col-2">
      <div id='mini_agenda'></div>
      <br>
      <br>
      <div id='checkbox-salas'>
      
        <?php
          require("componentes_agenda.php");
        ?>
      </div>
    </div>

  <!-- BLOCO ESQUERDO -->



  <!-- BLOCO DA AGENDA -->
    <div class="col-10">
      <div id='agenda'></div>
    </div>
  <!-- BLOCO DA AGENDA -->


  </div>

</div>

<?php
require("modais/modal_mostrar.php");
require("modais/modal_criar.php");
require("modais/modal_editar.php");
require("modais/modal_remover.php");
require("footer.php");
?>

<script src='agenda.js'></script>
<script src='modais/modal_criar.js'></script>
<script src='modais/modal_mostrar.js'></script>
<script src='modais/modal_editar.js'></script>
<script src='modais/modal_remover.js'></script>
<script> moment.locale('pt-br');</script>
