document.getElementById('buscar').addEventListener('keyup', function () {

    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll('#tablaIncidencias tr');

    filas.forEach((fila, indice) => {

        if (indice === 0) return;

        let problema = fila.cells[1].textContent.toLowerCase();

        fila.style.display = problema.includes(filtro)
            ? ''
            : 'none';
    });

});
