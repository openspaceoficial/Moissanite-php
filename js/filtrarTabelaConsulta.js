function filtrarTabelaConsulta() {
    const pedra = document.getElementById('pedraConsulta').value;
    const formato = document.getElementById('formatoConsulta').value;
    const mm = document.getElementById('mmConsulta').value;

    if (!pedra) {
        alert("Por favor, selecione um tipo de pedra.");
        return;
    }

    // Construção segura da URL com encodeURIComponent
    const queryString = `pedra=${encodeURIComponent(pedra)}&formato=${encodeURIComponent(formato)}&mm=${encodeURIComponent(mm)}`;

    fetch(`../estoque/php/consultar_pedras.php?${queryString}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erro ao carregar dados: ${response.statusText}`);
            }
            return response.text();
        })
        .then(data => {
            const tabelaConsulta = document.getElementById('tabelaConsulta');
            tabelaConsulta.innerHTML = data;
        })
        .catch(error => {
            console.error("Erro ao aplicar filtros:", error);
            alert("Ocorreu um erro ao aplicar os filtros. Por favor, tente novamente.");
        });
}
