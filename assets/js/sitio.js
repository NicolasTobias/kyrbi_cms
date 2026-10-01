/*
  Mejora progresiva. Sin este fichero todo sigue funcionando: el índice enseña la foto del primer proyecto y el menú
  móvil se abre con un checkbox. Aquí solo se añade lo que un enlace no da.
*/
(function () {
  'use strict';

  // Índice de proyectos: la foto de la derecha sigue a la fila señalada.
  // En táctil no cambia (no hay "señalar").
  var indice = document.querySelector('[data-indice]');
  if (indice && window.matchMedia('(hover: hover)').matches) {
    var filas = indice.querySelectorAll('[data-fila]');
    var fotos = document.querySelectorAll('[data-portada]');

    var activar = function (n) {
      filas.forEach(function (f) { f.classList.toggle('activa', f.dataset.fila === n); });
      fotos.forEach(function (f) { f.classList.toggle('activa', f.dataset.portada === n); });
    };

    filas.forEach(function (fila) {
      fila.addEventListener('mouseenter', function () { activar(fila.dataset.fila); });
      fila.addEventListener('focus', function () { activar(fila.dataset.fila); });
    });
  }

  // Menú móvil: Escape lo cierra, y elegir un enlace también.
  var menu = document.getElementById('menu-abierto');
  if (menu) {
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') menu.checked = false;
    });
    document.querySelectorAll('.menu a').forEach(function (a) {
      a.addEventListener('click', function () { menu.checked = false; });
    });
  }
})();
