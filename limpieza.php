<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Limpieza - Cine</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            padding: 20px;
            font-size: 2.5em;
            background-color: #4CAF50;
            color: white;
        }
        .container {
            width: 80%;
            margin: auto;
        }
        .table {
            width: 100%;
            margin: 30px 0;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.1);
        }
        .table th, .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #4CAF50;
            color: white;
            font-size: 1.2em;
        }
        .table td {
            font-size: 1em;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .table tr:hover {
            background-color: #f1f1f1;
        }
        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 40%;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
        }
        .modal-header {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h1>Panel de Limpieza</h1>
    <div class="container">
        <!-- Botón para abrir el modal de agregar sala -->
        <button onclick="abrirAgregarModal()">Agregar Nueva Sala</button>

        <!-- Tabla de salas existentes -->
        <h2>Salas de Limpieza</h2>
        <table class="table" id="tablaSalas">
            <thead>
                <tr>
                    <th>Sala</th>
                    <th>Encargado</th>
                    <th>Estado</th>
                    <th>Cambiar Estado</th>
                    <th>Reporte de Problemas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sala 1</td>
                    <td>Juan Pérez</td>
                    <td>Pendiente</td>
                    <td>
                        <select onchange="actualizarEstado(this)">
                            <option value="Pendiente">Pendiente</option>
                            <option value="En Proceso">En Proceso</option>
                            <option value="Limpia">Limpia</option>
                        </select>
                    </td>
                    <td><input type="text" placeholder="Describa el problema"><button>Reportar</button></td>
                    <td>
                        <button onclick="abrirEditarModal('Sala 1', 'Juan Pérez')">Editar</button>
                        <button onclick="eliminarSala(this)">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal para agregar una sala -->
    <div id="agregarModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('agregarModal')">&times;</span>
            <div class="modal-header">Agregar Nueva Sala</div>
            <form id="agregarSalaForm">
                <label for="nuevaSala">Número de Sala:</label>
                <input type="text" id="nuevaSala" placeholder="Ej. Sala 4">
                <label for="nuevoEncargado">Encargado:</label>
                <input type="text" id="nuevoEncargado" placeholder="Nombre del encargado">
                <button type="button" onclick="agregarSala()">Agregar Sala</button>
            </form>
        </div>
    </div>

    <!-- Modal para editar una sala -->
    <div id="editarModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('editarModal')">&times;</span>
            <div class="modal-header">Editar Sala</div>
            <form id="editarSalaForm">
                <label for="editarSalaNombre">Sala:</label>
                <input type="text" id="editarSalaNombre" readonly>
                <label for="editarEncargado">Encargado:</label>
                <input type="text" id="editarEncargado">
                <button type="button" onclick="guardarEdicion()">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <script>
        // Abrir el modal de agregar sala
        function abrirAgregarModal() {
            document.getElementById("agregarModal").style.display = "block";
        }

        // Abrir el modal de editar sala
        function abrirEditarModal(sala, encargado) {
            document.getElementById("editarSalaNombre").value = sala;
            document.getElementById("editarEncargado").value = encargado;
            document.getElementById("editarModal").style.display = "block";
        }

        // Cerrar el modal especificado
        function cerrarModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }

        // Agregar una nueva sala a la tabla
        function agregarSala() {
            const sala = document.getElementById("nuevaSala").value;
            const encargado = document.getElementById("nuevoEncargado").value;

            if (sala && encargado) {
                const tabla = document.getElementById("tablaSalas").querySelector("tbody");
                const nuevaFila = document.createElement("tr");
                nuevaFila.innerHTML = `
                    <td>${sala}</td>
                    <td>${encargado}</td>
                    <td>Pendiente</td>
                    <td>
                        <select onchange="actualizarEstado(this)">
                            <option value="Pendiente">Pendiente</option>
                            <option value="En Proceso">En Proceso</option>
                            <option value="Limpia">Limpia</option>
                        </select>
                    </td>
                    <td><input type="text" placeholder="Describa el problema"><button>Reportar</button></td>
                    <td>
                        <button onclick="abrirEditarModal('${sala}', '${encargado}')">Editar</button>
                        <button onclick="eliminarSala(this)">Eliminar</button>
                    </td>
                `;
                tabla.appendChild(nuevaFila);
                
                // Limpiar y cerrar modal
                document.getElementById("nuevaSala").value = "";
                document.getElementById("nuevoEncargado").value = "";
                cerrarModal('agregarModal');
            } else {
                alert("Por favor, ingrese el número de sala y el encargado.");
            }
        }

        // Guardar cambios del modal de edición
        function guardarEdicion() {
            const sala = document.getElementById("editarSalaNombre").value;
            const nuevoEncargado = document.getElementById("editarEncargado").value;
            const filas = document.getElementById("tablaSalas").querySelectorAll("tbody tr");

            // Buscar la sala y actualizar encargado
            filas.forEach((fila) => {
                if (fila.cells[0].innerText === sala) {
                    fila.cells[1].innerText = nuevoEncargado;
                }
            });

            cerrarModal('editarModal');
        }

                // Eliminar una fila de la tabla
                function eliminarSala(boton) {
            const fila = boton.parentNode.parentNode;
            fila.remove();
        }

        // Actualizar el estado de la sala en la tabla
        function actualizarEstado(select) {
            const estado = select.value;
            const fila = select.parentNode.parentNode;
            fila.cells[2].innerText = estado;
        }
    </script>
</body>
</html>
