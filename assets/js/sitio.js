/*
  Mejora progresiva. Sin este fichero todo sigue funcionando: el visor navega
  con enlaces (?foto=N), el índice enseña la foto del primer proyecto y el menú
  móvil se abre con un checkbox. Aquí solo se añade lo que un enlace no da.
*/
(function () {
  'use strict';

  // Visor de proyecto: flechas del teclado y deslizamiento en táctil.
  var visor = document.querySelector('[data-visor]');
  if (visor) {
    var anterior = document.querySelector('[data-anterior]');
    var siguiente = document.querySelector('[data-siguiente]');

    document.addEventListener('keydown', function (e) {
      if (e.altKey || e.ctrlKey || e.metaKey || e.shiftKey) return;
      if (e.key === 'ArrowLeft' && anterior) window.location.href = anterior.href;
      if (e.key === 'ArrowRight' && siguiente) window.location.href = siguiente.href;
    });

    var x0 = null;
    var y0 = null;
    visor.addEventListener('touchstart', function (e) {
      x0 = e.touches[0].clientX;
      y0 = e.touches[0].clientY;
    }, { passive: true });
    visor.addEventListener('touchend', function (e) {
      if (x0 === null) return;
      var dx = e.changedTouches[0].clientX - x0;
      var dy = e.changedTouches[0].clientY - y0;
      x0 = y0 = null;
      // Solo un gesto claramente horizontal, para no romper el scroll vertical.
      if (Math.abs(dx) < 50 || Math.abs(dx) < Math.abs(dy) * 2) return;
      var destino = dx < 0 ? siguiente : anterior;
      if (destino) window.location.href = destino.href;
    }, { passive: true });
  }

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
