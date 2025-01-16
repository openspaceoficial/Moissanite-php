function alterarEstoque() {
    const pedra = document.getElementById("pedra").value;
    const formato = document.getElementById("formato").value;
    const mm = document.getElementById("mm").value;
    const estoqueUnidade = document.getElementById("estoqueUnidade").value;
    const estoqueQuilate = document.getElementById("estoqueQuilate").value;

    // Validação de campos
    if (!pedra || !formato || !mm || !estoqueUnidade || !estoqueQuilate) {
        alert("Por favor, preencha todos os campos antes de enviar.");
        return;
    }

    // Montar os dados da requisição com encodeURIComponent
    const bodyData = `pedra=${encodeURIComponent(pedra)}&formato=${encodeURIComponent(formato)}&mm=${encodeURIComponent(mm)}&estoqueUnidade=${encodeURIComponent(estoqueUnidade)}&estoqueQuilate=${encodeURIComponent(estoqueQuilate)}`;

    // Enviar a requisição
    fetch("alterar_estoque.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: bodyData,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status}`);
            }
            return response.text();
        })
        .then(data => {
            alert(`Resposta do servidor: ${data}`);
            location.reload(); // Recarregar a página após sucesso
        })
        .catch(error => {
            console.error("Erro na requisição:", error);
            alert("Ocorreu um erro ao alterar o estoque. Tente novamente mais tarde.");
        });
}
