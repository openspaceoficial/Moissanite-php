function carregarMM() {
    const pedra = document.getElementById('pedra').value;
    const formato = document.getElementById('formato').value;

    if (!pedra || !formato) {
        alert("Por favor, selecione a pedra e o formato antes de continuar.");
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
            const mmSelect = document.getElementById('mm');
            mmSelect.innerHTML = '<option value="">Selecione o MM</option>';

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
