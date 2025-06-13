<p align="center">
  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Escudo_de_la_Universidad_de_El_Salvador.svg/1200px-Escudo_de_la_Universidad_de_El_Salvador.svg.png" alt="LogoUes" width="20%" height="40%">
</p>
<p align="center">
    <img src="https://drive.google.com/uc?export=view&id=1K45h2JPReuWVNaSC_PmiPYmEIyGLCqeF" alt="LogoIDS" width="50%" height="100%">
</p>

# Examen Parcial 3 - Desarrollo y Técnicas de Aplicaciones Web DTW135 - GT01

## 📘 Tema: APIs y Web Workers
Este proyecto corresponde al tercer examen parcial del curso **Desarrollo y Técnicas de Aplicaciones Web**, y tiene como objetivo integrar el uso de **APIs modernas del navegador** y **Web Workers** en un proyecto web existente desarrollado con Laravel.

## 📂 Estructura del Proyecto

Este proyecto fue desarrollado a partir del repositorio base proporcionado y bifurcado (fork) en la cuenta GitHub de uno de los miembros del grupo, siguiendo las instrucciones brindadas:
- Geolocalización del usuario y visualización en mapa.
- Dibujo libre con Canvas y descarga del resultado.
- Captura de imagen desde la cámara web.
- Cálculos intensivos en segundo plano utilizando Web Workers para no bloquear la interfaz.

## 🔑 Credenciales para iniciar sesión:
**Usuario:** admin <br>
**Contraseña:** 1234

## 🛠️ Tecnologías Utilizadas

- **Backend:** Laravel (PHP)
- **Frontend:** HTML, Blade, JavaScript
- **APIs del navegador:** 
  - Geolocation API
  - Canvas API
  - MediaDevices (getUserMedia) API
- **Librerías externas:**
  - LeafletJS + OpenStreetMap (para mapas)
- **Web Workers:** para procesamiento intensivo (ordenamiento de arrays grandes)

## ⚙️ Funcionalidades implementadas

### 📍 Geolocalización
- Se muestran las coordenadas actuales del usuario (latitud y longitud).
- Se muestra la ubicación en un mapa con LeafletJS.

### 🖌️ Canvas
- Zona de dibujo libre con el mouse (líneas negras simples).
- Botón para descargar el dibujo en formato JPG (solo del lado cliente).

### 📷 Captura de Video
- Visualización de la cámara web del usuario.
- Botón para tomar una foto.
- Imagen capturada se guarda como archivo (cliente).

### 🧠 Web Worker
- Generación de 100,000 números aleatorios.
- Envío de datos al Web Worker para ordenarlos sin bloquear la UI.
- Se muestran los primeros 50 números ya ordenados.
- Uso de `try...catch` en todas las funciones críticas para manejo de errores.

## 👥 Integrantes del Grupo

1. BA22025 | Fernando José Barraza Álvarez
2. JQ22003 | Axel Rodrigo Juarez Quevedo
3. MM18069 | Wendy Carolina Mejía Martínez
4. MR21082 | Reyna Guadalupe Miranda Rivas
5. PM18077 | Francisco Javier Peraza Martínez
