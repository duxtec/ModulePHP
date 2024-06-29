// Seleciona todos os contêineres de 'tabs'
document.querySelectorAll(".tabs").forEach((container) => {
    // Adiciona um ouvinte de evento de clique ao contêiner
    container.addEventListener("click", (event) => {
        // Verifica se o alvo do evento tem o atributo 'opentab'
        const opentab = event.target.closest("[opentab]");
        if (opentab) {
            // Remove 'active-tab' de todos os '.tab' dentro do contêiner
            container.querySelectorAll(".tab").forEach((tab) => {
                tab.classList.remove('active-tab');
            });

            // Log do ID do tab a ser aberto
            console.log(`#${opentab.getAttribute('opentab')}`);

            // Adiciona 'active-tab' ao '.tab' correspondente
            container.querySelector(`.tab#${opentab.getAttribute('opentab')}`).classList.add('active-tab');
        }
    });
});
