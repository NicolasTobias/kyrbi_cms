# Handoff: nicotobias.com rediseño (dirección 1a, v2)

## Overview
Rediseño del portfolio de Nico Tobias (fotografía de calle y documental en color, Madrid y Cantabria). Dirección **1a Galería minimal**: foto a sangre, casi sin interfaz, Archivo grande, reglas de 2px, **fotos siempre a color**. Es una web personal, no la de un colectivo. Bilingüe ES (principal) / EN. Sitio en **Kirby CMS**.

## About the Design Files
`reference/nicotobias-1a-v2.dc.html` es una **referencia de diseño en HTML** (prototipo, no código de producción). Recrearlo en Kirby con sus plantillas/snippets. Estilos inline; abrir en navegador o leer el marcado para medidas. Artboards fijos (1200 y 375 px) solo por presentación: la implementación es fluida.

## Fidelity
Alta fidelidad. Los textos entre `[corchetes]` son marcadores por rellenar.

## Cambios respecto a la versión anterior
1. "Colectivo" se sustituye por **"Quedadas"** (registro en primera persona de paseos fotográficos). La rejilla de miembros **no se implementa**.
2. "Sobre" pasa a **"Quién soy"** (EN: **About**), con texto definitivo (ES).
3. Sitio **bilingüe** ES/EN. Quedadas solo existe en español.
4. Fotos **en color**; se eliminó `grayscale`. Revisión de paleta (ver Tokens).

## Sitemap y navegación
```
/                    Home
/proyectos           Índice de proyectos
/proyectos/{slug}    Proyecto (visor)
/quedadas            Quedadas (próxima + archivo)
/quien-soy           Quién soy
```
- ES: **Proyectos · Quedadas · Quién soy · ES / EN**
- EN: **Projects · About · ES / EN** (sin Quedadas)
- Inglés bajo prefijo: `/en`, `/en/projects`, `/en/about`. Español sin prefijo.
- Selector de idioma: al final de la nav, gap 32px, `ES / EN` 14px; activo peso 600 (sobre foto: subrayado 2px `#ec3013`), inactivo `#605d5d` (sobre foto `#d7d3d3`). Sin globo ni desplegable. Lleva a la página equivalente; desde `/quedadas` lleva a `/en`. En el menú móvil: fila propia bajo los enlaces, borde inferior 2px.

## Design Tokens
- bg `#f3f2f2` (verificar con fotos reales; si tira a crema, neutralizar), surface `#eae9e9`, texto `#201e1d`, gris secundario `#605d5d`
- acento `#ec3013`, acento oscuro `#ae1800` (hover y texto pequeño)
- divisor `#201e1d66`, 2px entre secciones
- **El acento ya no rellena superficies.** Solo detalles: elemento activo de nav, foco, hover, flechas del visor, enlace de contacto, enlace al grupo.
- Fila activa del índice: **invertida**, fondo `#201e1d`, texto `#f3f2f2`.
- Radio 0, sin sombras, **fotos sin filtros ni tintes**.
- Placeholder de imagen: `repeating-linear-gradient(135deg,#eae9e9 0 12px,#d7d3d3 12px 13px)` + etiqueta mono 11–12px.
- Tipografía Archivo (400/600/800): marca 18px/800 (16 móvil); nav 14px (activo `#ec3013`/600); título Home 104px/800 -0.03em lh .95 (52 móvil); títulos de página 88px/800 (52 móvil); índice 56px/800; kicker 12px mayúsculas `.08em` 600; lead 20px/1.5; cuerpo 15px/1.6; menú móvil 44px/800.
- Espaciado: página 32px (20 móvil); nav `16px 32px`; filas `28px 32px`.

## Screens

### Home
Foto a sangre en color. Nav superpuesta (`24px 32px`), texto claro. **Degradado atenuado**: superior `#201e1d66 → transparente al 14%`, inferior `transparente al 68% → #201e1d99` (o scrim local solo tras el texto). Abajo izquierda: kicker "Proyecto actual" + título del proyecto 104px; abajo derecha (max 260px, alineado a la derecha): "Fotografía de calle y documental en color. Madrid y Cantabria." Móvil: foto a sangre, botón "Menú" (borde 2px claro).

### Proyecto (visor)
Nav clara / foto centrada (`max 100%`, padding 24px, formato original) / barra inferior (borde superior 2px): "**Madrid 25-26**" + leyenda; contador "02 / 24" y "← →" en acento 800. Teclado, click y swipe. Estado sincronizado en URL.

### Proyectos (índice)
Grid 1fr/1fr, borde central 2px. Izquierda: kicker + filas (título 56px, meta 14px a la derecha, borde inferior 2px). Fila activa/hover **invertida**. Derecha: foto del proyecto activo, a sangre (cambia en hover, fundido 150ms; no en táctil). Fila sin contenido: opacidad .35, "Próximamente".

### Quedadas (nueva, solo ES)
Registro en primera persona; no vende plazas, sin miembros. Columna única:
1. Título 88px/800, padding 32px, borde inferior 2px.
2. Lead 20px (max 520px, `#605d5d`), 2–3 líneas, texto pendiente de Nico.
3. **La próxima**: fondo `#eae9e9`, bordes 2px arriba/abajo, padding `28px 32px`. Grid de campos (fecha 28px/800; barrio, punto de encuentro, hora 20px/800; etiquetas 12px `#605d5d`) y enlace "Grupo de la quedada →" (acento, 18px/800). Único elemento destacado de la página.
   - Junto al enlace al grupo, un segundo enlace **"Añadir al calendario (.ics)"** en `#605d5d` (peso 600, mismo tamaño, no acento) para que no compita con el del grupo. En móvil se apilan.
   - **Estado vacío** (habitual entre quedadas): "Aún no hay fecha para la siguiente." + el mismo enlace. Nunca desaparece el bloque.
4. **Archivo**: kicker; filas cronológicas inversas, `28px 32px`, borde inferior 2px. Izquierda: "04 · Barrio" 28px/800 y `fecha completa · n asistentes` 13px `#605d5d`. Derecha: de **1 a 3** fotos en fila, todas iguales (4:3, ~125px cada una, ~380px el conjunto), gap 2px. Con una sola no se estira para rellenar.
- **Foto de quedada = acta, no obra**: nunca a sangre, máximo 3 por quedada (tope, no objetivo; 4 o más parece galería), recorte fijo 4:3, pie con fecha siempre visible (kicker 12px mayúsculas), sin visor, lightbox ni enlace a galería. Debe distinguirse de un proyecto en dos segundos.
- Móvil: filas apiladas, foto arriba (ancho de columna, no a sangre), texto debajo; "La próxima" mantiene fondo surface en una columna.

### Quién soy / About
Grid **0.8fr/1.2fr** (propuesta; alternativa 1fr/1fr), borde central 2px. Izquierda: retrato (marcador) con `position: sticky; top: 0` mientras se lee (alto ~640px o 100vh menos nav). Derecha (padding `32px 32px 40px`, gap 32px): título 88px/800; lead 20px/1.5 max 560px; regla 2px; **columna de texto de 6 párrafos**, 17px/1.6, max-width 560px, gap 20px entre párrafos, color `#201e1d`; regla 2px y contacto. La página crece con el texto (sin alto fijo). Móvil: título, lead, retrato (~45vh, no sticky), texto a 16px/1.6.

**ES** — Título: Quién soy. Lead (situar, sin hablar de Nico): "Fotografía de calle y documental en color. Madrid y Cantabria." Cuerpo **verbatim, no editar** (el cambio de persona de tercera a primera entre párrafos es intencionado):

1. Corrían los 2000 en Madrid y un chico introvertido y solitario, que jugaba solo en su casa casi todo el día, descubrió a través de su hermana que la música electrónica podría ser la balsa que lo sacara de esa isla.

2. Cuatro años en los que sería DJ en un Madrid que acababa de dejar de ser un lugar donde las jeringuillas en los parques, la basura por las calles y los atracos a plena luz del día eran normales.

3. Esa pasión por la música y el fracaso en los estudios lo fueron llevando a buscarse la vida trabajando. Trabajos como el de reportero de la primera web española de clubes. Ahí comenzó mi flirteo con la fotografía, de la mano de mi hermana, Lucila Bristow, que me enseñó los conceptos básicos de fotografiar en negativo.

4. Vino el digital y la vida me fue empujando hacia lugares más mundanos y menos artísticos. Casi veinte años después, con el nacimiento de mis hijos, abandoné las fotos con el móvil y retomé la fotografía como un acto consciente de preservar los recuerdos inmateriales de su infancia: vídeos, vídeos y pocas fotos.

5. Septiembre de 2025 marca un hito: el hartazgo de intentar durante años obtener un estilo al que nunca le puse más interés que el de alguien sacando otra foto con el móvil, y pasar a intentar descubrir quién soy, cómo miro y cómo veo el mundo.

6. A partir de ahí descubrí la atracción por el color y la soledad, espacios vacíos que estuvieron o estarán llenos de humanidad. A retratar las cosas desde el límite, donde yo siempre me he sentido cómodo. Con la mirada de alguien que siente que no acaba de pertenecer a nada pero habita un mundo en el que siempre se es parte de algo.

Contacto: hola@nicotobias.com

**EN** — Título: About. Lead: "Street and documentary photography in colour. Madrid and Cantabria." Cuerpo: **pendiente**. No traducir literalmente: hay que escribirlo (el juego de personas, "jeringuillas en los parques" y el Madrid de los 2000 necesitan decisión de contexto). Hasta entonces, mostrar el marcador. Contact: hola@nicotobias.com (ortografía británica).

### Menú móvil
Cabecera con marca + "Cerrar" (fondo `#201e1d`, texto claro). Filas 44px/800, `24px 20px`, borde inferior 2px; activa en acento. Fila de idioma (`ES / EN`) tras los enlaces. Email fijo abajo. Bloquear scroll del fondo.

## Cadenas de interfaz (archivos de idioma, no en plantillas)
Proyectos/Projects · Quién soy/About · Proyecto actual/Current project · Menú/Menu · Cerrar/Close · Contacto/Contact · Próximamente/Coming soon · fotos/photographs. Contador y flechas idénticos en ambos idiomas.

## Metadatos
- ES: `Nico Tobias — Fotografía` / `Fotografía de calle y documental en color, en Madrid y Cantabria.`
- EN: `Nico Tobias — Photography` / `Street and documentary photography in colour, in Madrid and Cantabria.`
- `hreflang` cruzado, `x-default` → español, `lang` correcto en `<html>`. og:image 1200×630.

## Interacciones
Hover de enlaces `#ae1800`; foco `outline: 2px solid #ec3013; outline-offset: 2px`; selección de texto acento al 30%. Carga de imagen: fondo `#eae9e9`; error: marcador rayado. Cambio de foto del visor: corte o fundido ≤200ms, precargar la siguiente.

## Responsive
Breakpoint ≈ 800px: nav → botón "Menú"; índice y Quién soy se apilan (foto arriba ~45vh); títulos con `clamp`. `srcset` con los tamaños del CMS.

## Implementación en Kirby
- **Multiidioma**: `languages => true`; `site/languages/es.php` (default, sin `url`) y `en.php` (`'url' => '/en'`). Slugs traducidos (`proyectos`/`projects`, `quien-soy`/`about`). Contenido por idioma (`quien-soy.es.txt`, `quien-soy.en.txt`, igual proyectos). Títulos, leyendas y `alt` traducibles; si falta traducción, mostrar la versión ES. `quedadas` solo ES: sin enlace en nav EN, su ruta en EN redirige a `/en`.
- **Templates**: `home.php`, `projects.php`, `project.php`, `meetups.php`, `about.php`. Sin plantilla de detalle de quedada.
- **Snippets**: `header.php` (nav + selector de idioma, sección activa con `$page->isOpen()`), `footer.php`, `menu-mobile.php`, `photo.php` (`srcset` vía `$file->srcset()`, sin filtro).
- Blueprint `quedada` como hijas de `quedadas`: `fecha` (date), `barrio`, `punto_encuentro`, `hora` (time), `duracion` (number, horas, defecto 3), `asistentes` (number, se rellena a mano después, no es contador de inscripciones), `enlace_grupo` (url, heredable) y `files` con `max: 3` y `sortable: true`.
- **`.ics`**: ruta `quedadas/(:any).ics` en `config.php` que lo genera al vuelo, sin librerías. `VEVENT` con `DTSTART` de `fecha`+`hora` en `Europe/Madrid`, `DTEND` desde `duracion`, `SUMMARY` "Quedada fotográfica — {barrio}", `LOCATION` = punto de encuentro, `URL` a `/quedadas`, `UID` estable desde el `uuid` de la página. `Content-Type: text/calendar; charset=utf-8`.
- **Alcance**: el panel de Kirby es toda la gestión. Sin inscripciones, aforo, lista de asistentes ni confirmaciones (viven en el grupo de mensajería; recoger datos personales implicaría obligaciones RGPD).
- **Quedadas**: "la próxima" = hija con `fecha` ≥ hoy más cercana; si no hay, estado vacío. Archivo = `fecha` < hoy, `fecha desc`. Preset de thumbs propio (recorte 4:3, máx. 800px), no reutilizar el de proyectos.
- **Home**: proyecto "actual" desde un campo del CMS, no hardcodeado.
- **Thumbs**: presets 800/1200/1500/2000 px.
- **Redirecciones 301**: `/about`, `/sobre` → `/quien-soy`; `/photography/*` → `/proyectos/*`; `/colectivo` → `/quedadas`.

## Pendiente de contenido
Texto EN de About (se escribe aparte, no se traduce literal); Selección de fotos y títulos definitivos por proyecto; leyendas y `alt` en ambos idiomas; retrato de Quién soy (o alternativa); lead de Quedadas; datos de la primera quedada (10 oct 2026: barrio, punto, hora); si "Cantabria" es proyecto y su contenido.

## Revisión antes de cerrar
El prototipo se evaluó antes con fotos en gris. Volver a montar Home y visor con 2–3 fotos reales en color y revisar: fondo `#f3f2f2` (¿cálido?), legibilidad del titular de 104px sobre la foto y el degradado atenuado.

## Assets
Fotos de muestra (hotlink en el prototipo): `https://nicotobias.com/media/pages/proyectos/madrid-25-26/255bf2e35a-1790681220/001-1500x.jpg` ("chicos juegan con un colchon en un parque de patinetes") y `.../3da5fafa94-1790681347/002-1200x.jpg` ("sombra de padre e hijo contra verja roja y muro de piedra"). Fuente: Archivo (Google Fonts). Iconos: solo texto "← →".

## Files
- `reference/nicotobias-1a-v2.dc.html`: Home, Proyecto, Proyectos (índice), Móvil, Quedadas (con datos, estado vacío, móvil), Quién soy, About, menú móvil.
