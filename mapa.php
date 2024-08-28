<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Establecimientos de Salud - Región del Maule</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
    body {
        margin: 0;
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow: hidden;
        font-family: Arial, sans-serif;
    }

    @media (min-width: 768px) {
        body {
            flex-direction: row;
        }
    }

    #sidebar {
        width: 100%;
        padding: 20px;
        background-color: #f8f9fa;
        border-right: 1px solid #ddd;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
    }

    @media (min-width: 768px) {
        #sidebar {
            width: 300px;
        }
    }

    #map {
        flex-grow: 1;
        height: 100vh;
    }

    h2 {
        font-size: 1.5em;
        color: #333;
        margin-bottom: 20px;
    }

    .filter-group {
        margin-bottom: 20px;
    }

    .filter-group label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
        color: #555;
    }

    .filter-group select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
        font-size: 1em;
        color: #333;
        appearance: none;
    }

    .filter-group select:focus {
        border-color: #007bff;
        outline: none;
    }

    .color-box {
        display: inline-block;
        width: 15px;
        height: 15px;
        margin-right: 5px;
    }

    .legend {
        margin-top: 20px;
        padding: 10px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .legend-item {
        margin-bottom: 5px;
    }

    @media (max-width: 768px) {
        h2 {
            font-size: 1.2em;
        }
        
        .filter-group select {
            font-size: 0.9em;
            padding: 8px;
        }
    }

    @media (max-width: 480px) {
        #sidebar {
            padding: 15px;
        }
        
        h2 {
            font-size: 1em;
        }
        
        .filter-group select {
            font-size: 0.8em;
            padding: 6px;
        }
    }

    </style>
</head>
<body>
    <div id="sidebar">
        <h2>Filtrar Establecimientos</h2>
        <div class="filter-group">
            <label for="comuna">Comuna:</label>
            <select id="comuna">
                <option value="todas">Todas</option>
                <!-- Las comunas se llenarán dinámicamente -->
            </select>
        </div>
        <div class="filter-group">
            <label for="tipo">Tipo Establecimiento (Unidad):</label>
            <select id="tipo">
                <option value="todos">Todos</option>
                <!-- Los tipos de establecimientos se llenarán dinámicamente -->
            </select>
        </div>
        <div class="legend">
            <h3>Colores de los Establecimientos</h3>
            <div id="legend-content">
                <!-- La leyenda se llenará dinámicamente -->
            </div>
        </div>
    </div>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-35.426, -71.655], 9);

        // Capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var markers = [];

        // Función para determinar el color del marcador según el tipo de establecimiento
        function obtenerColor(tipo) {
            switch (tipo) {
                case 'Dirección Servicio de Salud': return '#FF4500';
                case 'Programa de Reparación y Atención Integral de Salud (PRAIS)': return '#32CD32';
                case 'Unidad de Procedimientos Móvil': return '#8A2BE2';
                case 'Unidad de Salud Funcionarios': return '#FF1493';
                case 'Dispositivo Incorporado por Crisis Sanitaria': return '#00FFFF';
                case 'Hospital': return '#FF0000';
                case 'Centro de Especialidad': return '#FFD700';
                case 'Centro de Salud Privado': return '#ADFF2F';
                case 'Clínica': return '#FF4500';
                case 'Vacunatorio': return '#DC143C';
                case 'Centro Médico y Dental': return '#FF69B4';
                case 'Centro de Diagnóstico y Terapéutico (CDT)': return '#800080';
                case 'Centro de Salud Familiar (CESFAM)': return '#0000FF';
                case 'Posta de Salud Rural (PSR)': return '#00FA9A';
                case 'Centro Comunitario de Salud Familiar (CECOSF)': return '#FFA500';
                case 'Centro Comunitario de Salud Mental (COSAM)': return '#BA55D3';
                case 'Servicio de Atención Primaria de Urgencia de Alta Resolutividad (SAR)': return '#DA70D6';
                case 'Servicio de Atención Primaria de Urgencia (SAPU)': return '#32CD32';
                case 'Centro de Salud de Atención Primaria': return '#4682B4';
                case 'Laboratorio Clínico': return '#40E0D0';
                case 'Centro de Rehabilitación': return '#2E8B57';
                case 'Centro de Diálisis': return '#FF6347';
                case 'Clínica Dental': return '#9932CC';
                case 'Servicio Médico Legal': return '#708090';
                case 'Servicio de Urgencia Rural (SUR)': return '#FFA07A';
                default: return 'gray';
            }
        }

        // Función para crear un ícono de pin con el color dinámico
        function crearIconoPin(color) {
            return L.divIcon({
                html: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="${color}" d="M12 2C8.14 2 5 5.14 5 9c0 4.24 4.68 11.54 6.59 14.4.18.25.53.25.71 0C14.32 20.54 19 13.24 19 9c0-3.86-3.14-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>`,
                className: '',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34]
            });
        }

        // Función para agregar marcadores al mapa
        function agregarMarcadores(establecimientos, filtroComuna, filtroTipo) {
            // Eliminar marcadores anteriores
            markers.forEach(marker => map.removeLayer(marker));
            markers = [];

            establecimientos.forEach(function(est) {
                // Filtrar establecimientos según los criterios proporcionados
                if (est["Nombre Dependencia Jerárquica (SEREMI / Servicio de Salud)"] === "SEREMI Del Maule" ||
                    est["Tipo de Prestador Sistema de Salud"] === "Privado" ||
                    est["Dependencia Administrativa"] === "Ministerio de Justicia" ||
                    est["Dependencia Administrativa"] === "Otra Institución" ||
                    est["Dependencia Administrativa"] === "SEREMI Del Maule") {
                    return; // Si cumple con alguno de estos criterios, se excluye
        }


                // Validar que las coordenadas son números válidos
                var lat = parseFloat(est["LATITUD      [Grados decimales]"]);
                var lng = parseFloat(est["LONGITUD [Grados decimales]"]);

                if (!isNaN(lat) && !isNaN(lng)) {
                    if ((filtroComuna === "todas" || est["Nombre Comuna"] === filtroComuna) &&
                        (filtroTipo === "todos" || est["Tipo Establecimiento (Unidad)"] === filtroTipo)) {
                        var popupContent = "<b>" + est["Nombre Oficial"] + "</b><br>" +
                            "Código DEIS: " + est["Código Vigente"] + "<br>" +
                            "Dirección: " + est["Dirección"] + " " + est["Número"] + "<br>" +
                            "Comuna: " + est["Nombre Comuna"] + "<br>" +
                            "Tipo: " + est["Tipo Establecimiento (Unidad)"] + "<br>" +
                            "Nivel de Atención: " + est["Nivel de Atención"] + "<br>" +
                            "Teléfono: " + est["Teléfono"] + "<br>" +
                            "Nivel de Complejidad: " + est["Nivel de Complejidad"] + "<br>" +
                            "Tipo de Atención: " + est["Tipo de Atención "] + "<br>" +
                            "Tiene Servicio de Urgencia: " + est["Tiene Servicio de Urgencia"] + "<br>" +
                            "Clasificación Tipo de SAPU: " + est["Clasificcion Tipo de SAPU"];

                        var marker = L.marker([lat, lng], { icon: crearIconoPin(obtenerColor(est["Tipo Establecimiento (Unidad)"])) }).addTo(map);
                        marker.bindPopup(popupContent);
                        markers.push(marker);
                    }
                } else {
                    console.warn(`Establecimiento con coordenadas inválidas: ${est["Nombre Oficial"]}`);
                }
            });
        }

        // Función para crear la leyenda de colores
        function crearLeyenda(tipos) {
            var legendContent = document.getElementById('legend-content');
            legendContent.innerHTML = '';

            tipos.forEach(function(tipo) {
                var item = document.createElement('div');
                item.className = 'legend-item';
                item.innerHTML = `<span class="color-box" style="background-color:${obtenerColor(tipo)};"></span>${tipo}`;
                legendContent.appendChild(item);
            });
        }

        // Cargar los datos desde el JSON
        fetch('mapa.json')
            .then(response => response.json())
            .then(establecimientos => {
                // Inicializar los selectores de comuna y tipo
                function initFiltros() {
                    var comunas = new Set();
                    var tipos = new Set();

                    establecimientos.forEach(function(est) {
                        comunas.add(est["Nombre Comuna"]);
                        tipos.add(est["Tipo Establecimiento (Unidad)"]);
                    });

                    var comunaSelect = document.getElementById('comuna');
                    comunas.forEach(function(comuna) {
                        var option = document.createElement('option');
                        option.value = comuna;
                        option.text = comuna;
                        comunaSelect.add(option);
                    });

                    var tipoSelect = document.getElementById('tipo');
                    tipos.forEach(function(tipo) {
                        var option = document.createElement('option');
                        option.value = tipo;
                        option.text = tipo;
                        tipoSelect.add(option);
                    });

                    // Crear la leyenda
                    crearLeyenda(tipos);
                }

                // Inicializar los filtros
                initFiltros();

                // Inicializar el mapa con todos los marcadores
                agregarMarcadores(establecimientos, "todas", "todos");

                // Actualizar marcadores al cambiar el filtro de comuna o tipo
                document.getElementById('comuna').addEventListener('change', function() {
                    var comuna = this.value;
                    var tipo = document.getElementById('tipo').value;
                    agregarMarcadores(establecimientos, comuna, tipo);
                });

                document.getElementById('tipo').addEventListener('change', function() {
                    var comuna = document.getElementById('comuna').value;
                    var tipo = this.value;
                    agregarMarcadores(establecimientos, comuna, tipo);
                });
            });
    </script>
</body>
</html>
