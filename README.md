# Mapa Regional de Establecimientos Hospitalarios

Este proyecto muestra un mapa interactivo con los establecimientos de salud de la Región del Maule. Está diseñado para ser fácilmente instalable y operativo en menos de 15 minutos.

## Beneficios del Sistema

- **Rápida Implementación:** El código está optimizado para que los usuarios puedan tener un mapa funcional en su página web en pocos minutos.
- **Tecnología Open Source:** Utiliza tecnologías como Leaflet.js y OpenStreetMap, garantizando que la solución sea gratuita y accesible para todos.
- **Fácil de Usar:** El sistema es muy intuitivo y no requiere conocimientos avanzados para su implementación.
- **Código Reducido y 100% Útil:** El proyecto se enfoca en proporcionar solo lo esencial para visualizar los establecimientos, sin sobrecargar el sistema.
- **Responsivo:** La interfaz está diseñada para adaptarse a diferentes tamaños de pantalla, ofreciendo una experiencia óptima tanto en dispositivos móviles como en computadoras de escritorio.
- **Adaptable:** Aunque está enfocado en los establecimientos de salud de la red MINSAL, el código puede adaptarse fácilmente para otros proyectos con requisitos similares.

## Descarga y Filtrado de Datos

1. Visita [datos.gob.cl](https://datos.gob.cl/dataset/establecimientos-de-salud-vigentes) para descargar el archivo `EstablecimientosSaludVigentes.csv`.
2. Filtra los datos para incluir solo los establecimientos de la Región o comuna, los demas hay que borrarlos.
3. Convierte el archivo filtrado a formato JSON para integrarlo en el proyecto [datos.gob.cl](https://csvjson.com/csv2json)
4. Reemplaza el archivo `mapa.json` en este proyecto con el JSON que generaste.

## Operatividad

1. Clona el repositorio en tu máquina local.
2. Modifica el archivo JSON con los datos de los establecimientos.
3. Abre el archivo `mapa.php` en tu navegador o intégralo en tu proyecto web.
4. ¡Listo! Tu mapa interactivo estará funcionando.

## Tecnología Utilizada

- **Leaflet.js:** Para la creación de mapas interactivos.
- **OpenStreetMap:** Para los datos del mapa base.
- **HTML, CSS, JavaScript:** Para la estructura y estilo de la página.
- **JSON:** Para la gestión y filtrado de los datos de los establecimientos.

## Instalación

1. Clona el repositorio.
2. Asegúrate de tener los datos en formato JSON listos.
3. Integra el código en tu página web.

## Requisitos

Para usar este sistema, necesitas un entorno que soporte HTML, CSS, y JavaScript, como un servidor web básico. No se requieren instalaciones adicionales.

## Adaptabilidad

Aunque está diseñado para la red de establecimientos MINSAL, el sistema puede adaptarse para otros proyectos que necesiten visualización de datos en mapas. Solo es necesario ajustar el archivo JSON con los datos requeridos.
