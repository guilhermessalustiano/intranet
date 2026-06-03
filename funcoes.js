$(document).ready(function () {

    const expirySpan = document.getElementById('session-expiry-timer');

    // Inicializa o tempo restante com o valor do atributo "value"
    // let remainingTime = parseInt(expirySpan.getAttribute('value'));

    // Só executa o script se o elemento existir
    if (!expirySpan) {
        console.warn("Elemento #session-expiry-timer não encontrado. A função de contagem regressiva não será executada.");
        return;
    }


    // Inicializa o tempo restante com o valor do atributo "value"
    let remainingTime = parseInt(expirySpan.getAttribute('value')) || 0;

    
    // Formata o tempo no formato "mm:ss"
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60); 
        const secs = seconds % 60;
        return `${minutes}:${secs < 10 ? '0' : ''}${secs}s`;
    }

    // Atualiza o timer na tela
    function updateTimer() {
        if (remainingTime > 0) {
            remainingTime--;
            expirySpan.textContent = formatTime(remainingTime);
        } else {
            // alert("Sua sessão expirou!");
            toastr.info('Sessão expirada, desconectando...');
            setTimeout(() => {
                window.location.href = '../../encerra.php';
                
            }, 3000);
            
        }
    }

    // Função para buscar o tempo restante do backend
    function fetchSessionVariable() {
        $.ajax({
            url: '/comum/atualiza_relogio.php', // Endpoint PHP
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                if (data.remaining_time) {
                    remainingTime = parseInt(data.remaining_time);
                    expirySpan.textContent = formatTime(remainingTime);
                    
                } else {
                    console.error("Resposta inválida do servidor:", data);
                }
            },
            error: function(xhr, status, error) {
                console.error("Erro na requisição:", error);
            }
        });
    }

    // Inicializa o timer com o valor formatado
    expirySpan.textContent = formatTime(remainingTime);

    // Atualiza o contador a cada segundo
    setInterval(updateTimer, 1000);

    // Sincroniza o tempo com o backend a cada 30 segundos
    setInterval(fetchSessionVariable, 30000);
});
