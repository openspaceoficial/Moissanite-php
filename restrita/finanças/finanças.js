function ocultarTabelaSeparado() {
    var tabela = document.getElementById('tabela');
    tabela.style.display = 'none';
}
// Selecionando o ícone do menu e o menu
const menuToggle = document.getElementById("menu-toggle");
const menuHeader = document.querySelector(".menu-aberto-header");

// Adicionando o evento de clique
menuToggle.addEventListener("click", function () {
    menuHeader.classList.toggle("show"); // Alterna a visibilidade do menu
    menuToggle.classList.toggle("open"); // Adiciona a animação do ícone
});

//btn-cadastro
document.querySelector(".dropbtn").addEventListener("click", function () {
    const dropdown = document.querySelector(".dropdown");
    dropdown.classList.toggle("show");
});

// Fecha o dropdown se o usuário clicar fora dele
window.onclick = function (event) {
    if (!event.target.matches('.dropbtn')) {
        const dropdowns = document.querySelectorAll(".dropdown-content");
        dropdowns.forEach(function (dropdown) {
            if (dropdown.style.display === "block") {
                dropdown.style.display = "none";
            }
        });
    }
};