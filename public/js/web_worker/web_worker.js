self.onmessage = function(e) {
    try {
        const data = e.data;
        if (!Array.isArray(data)) throw new Error("Datos recibidos no son válidos");

        // Ordenar el array
        const sorted = data.sort((a, b) => a - b);

        // Enviamos solo los primeros 50 números
        self.postMessage(sorted.slice(0, 50));
    } catch (error) {
        self.postMessage({ error: error.message });
    }
};
