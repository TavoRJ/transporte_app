/** @type {import('tailwindcss').Config} */
module.exports = {
  //IMPORTANTE: Aquí le decimos dónde buscar las clases
  content: ["./**/*.php", "./js/**/*.js"],
  
  theme: {
    extend: {
      //Aquí defines la PALETA DE COLORES
      colors: {
        'marca': {
          DEFAULT: '#0024eeff', // El Azul Principal (antes blue-600)
          oscuro: '#1E40AF',  // Para el Hover (antes blue-800)
          claro: '#60A5FA',
          subcolor: '#001275', // Para los botones secundarios (antes blue-500)
        },
        'estado': {
          exito: '#10B981',   // Verde (Entrada)
          alerta: '#F59E0B',  // Naranja (Salida)
          error: '#EF4444',   // Rojo (Errores)
        },
        'fondo': '#f8f8f8ff',   // El gris de fondo
      }
    },
  },
  plugins: [],
}