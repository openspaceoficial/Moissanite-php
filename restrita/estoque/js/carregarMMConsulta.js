function carregarMMConsulta() {
    const pedra = document.getElementById('pedraConsulta').value;
    const formato = document.getElementById('formatoConsulta').value;

    if (!pedra) {
        alert("Por favor, selecione um tipo de pedra.");
        return;
    }

    if (!formato) {
        alert("Por favor, selecione um formato.");
        return;
    }

    fetch(`../estoque/php/carregar_mm.php?pedra=${encodeURIComponent(pedra)}&formato=${encodeURIComponent(formato)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erro ao carregar MM: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            const mmSelect = document.getElementById('mmConsulta');
            mmSelect.innerHTML = '<option value="">Todos</option>';

            data.forEach(mm => {
                const option = document.createElement('option');
                option.value = mm;
                option.textContent = mm;
                mmSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Erro ao carregar MM:", error);
            alert("Não foi possível carregar os valores de MM. Por favor, tente novamente.");
        });
}
