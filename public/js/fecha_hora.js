// ===== FECHA Y HORA =====
function actualizarFechaHora() {
    const ahora = new Date();
    const opciones = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    };
    const fecha = ahora.toLocaleDateString("es-MX", opciones);
    const hora = ahora.toLocaleTimeString("es-MX", {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });

    document.getElementById("fecha").textContent =
        fecha.charAt(0).toUpperCase() + fecha.slice(1);
    document.getElementById("hora").textContent = hora;
}

actualizarFechaHora();
setInterval(actualizarFechaHora, 1000);
