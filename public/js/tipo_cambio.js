// ===== TIPO DE CAMBIO =====
async function obtenerTipoCambio() {
    try {
        const response = await fetch(
            "https://api.exchangerate-api.com/v4/latest/USD"
        );

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        const tipoCambio = data.rates.MXN.toFixed(2);

        document.getElementById("tipoCambio").innerHTML = `
            <span class="font-bold">$${tipoCambio}</span>
            <span class="text-xs text-gray-500 ml-1">MXN</span>
        `;
    } catch (error) {
        console.error("Error al obtener tipo de cambio:", error);
        document.getElementById("tipoCambio").innerHTML =
            '<span class="text-xs text-red-500">No disponible</span>';
    }
}

obtenerTipoCambio();
setInterval(obtenerTipoCambio, 3600000); // Actualizar cada hora
