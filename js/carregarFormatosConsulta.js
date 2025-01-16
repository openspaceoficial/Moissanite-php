function carregarFormatosConsulta() {
    const pedra = document.getElementById('pedraConsulta').value;

    if (!pedra) {
        alert("Por favor, selecione um tipo de pedra.");
        return;
    }

    fetch(`../estoque/php/carregar_formatos.php?pedra=${encodeURIComponent(pedra)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erro ao carregar formatos: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            const formatoSelect = document.getElementById('formatoConsulta');
            formatoSelect.innerHTML = '<option value="">Todos</option>';

            data.forEach(formato => {
                const option = document.createElement('option');
                option.value = formato;
                option.textContent = formato;
                formatoSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Erro ao carregar formatos:", error);
            alert("Não foi possível carregar os formatos. Por favor, tente novamente.");
        });
}
