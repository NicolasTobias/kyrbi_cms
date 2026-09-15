# Rediseño de nicotobias.com — diseño

**Fecha:** 2026-09-15
**Estado:** BORRADOR — pendiente de aprobación. No se ha implementado nada.

## Contexto verificado

Comprobado contra el clúster (`kubectl --context arenero -n websites`), no supuesto:

| Dato | Valor |
|---|---|
| Kirby en producción | **5.3.2** |
| Imagen | `ghcr.io/nicolastobias/kyrbi_cms:latest` |
| Origen del código del sitio | `composer create-project getkirby/starterkit` en build — **sin pinear, no versionado** |
| Contenido | PVC NFS 2Gi en `/var/www/html/content`; hoy es el demo del starterkit (8 álbumes, ~14 notas, 62 imágenes) |
| Usuarios del panel | **0** |
| Licencia Kirby | **ninguna** |
| Reinicios del pod | 42 en 10 días (estable desde 2026-09-11) |

## Bloqueo: Miron no es instalable

MIRON (getkirby-themes.com, 15 $) es un tema de **Kirby 2**. Producción corre **Kirby 5.3.2**.
Kirby 3 (2019) reescribió la API por completo: plantillas, objetos `$site`/`$page`, kirbytags y
plugins de Kirby 2 no se ejecutan en 5.x. Comprarlo no sirve: habría que reescribirlo entero.

**Decisión propuesta:** usar Miron como *referencia visual* (one-page, tipografía grande,
color plano conmutable) y escribir plantillas propias sobre Kirby 5.

## Hallazgos de seguridad y operación (independientes del rediseño)

1. **Panel abierto.** `panel.install: true` en `configmap-kirby.yaml` + **0 usuarios** +
   dominio público. Kirby bloquea el instalador fuera de localhost *salvo* que `install` sea
   `true`. Hoy, quien entre en `nicotobias.com/panel` puede crearse la cuenta de admin.
   → Crear el usuario y poner `install: false`.
2. **Los usuarios no persisten.** `site/accounts/` vive en la imagen, no en la PVC, y el
   Deployment usa `imagePullPolicy: Always`. Cada rollout borra las cuentas. Probablemente por
   esto hay 0 usuarios. → Montar `site/accounts` en la PVC.
3. **El contenido demo resucita.** El initContainer hace `cp -rn` del contenido de la imagen a
   la PVC. Si se borran los álbumes demo desde el panel, el siguiente arranque los vuelve a
   copiar. → Quitar el contenido demo de la imagen.
4. **Licencia.** Kirby exige licencia para un sitio público. Sin ella el panel muestra aviso
   permanente. Coste ~99 €.
5. **Build no reproducible.** Sin versión pineada, cada rebuild puede traer otro Kirby.

## Enfoques considerados

| | Enfoque | Coste | Control | Veredicto |
|---|---|---|---|---|
| A | Plantillas propias en Kirby 5, Miron como referencia visual | medio | total | **Recomendado** |
| B | Comprar Miron y portarlo de Kirby 2 a 5 | alto | medio | Descartado: es reescribirlo pagando |
| C | Buscar tema Kirby 5 de fotografía y recolorearlo | bajo | bajo | Descartado: "Miron + amarillo legal pad" es demasiado específico |

## Diseño propuesto (A)

### Estructura — híbrido, no one-pager puro

Miron es de una sola página. La fotografía necesita galerías profundas. Propuesta:

- `/` — hero con la foto *featured* a sangre, intro breve, rejilla de proyectos,
  bloque «about» resumido, pie con contacto. Se lee como un one-pager.
- `/proyectos/<slug>` — página propia por proyecto con su galería.
- `/about` — página completa.

### Modelo de contenido

- `home.yml`: `featured` (selector de 1 imagen), `headline`, `intro`.
- `proyectos.yml` (índice) + `proyecto.yml`: `cover`, `year`, `location`, `text`, `files`.
  Mapea 1:1 sobre la estructura actual `1_photography/<álbum>` → renombrado a proyectos.
- `about.yml`: retrato, texto, contacto, redes.

### Paleta «legal pad» (valores de partida, a afinar)

| Token | Valor | Uso |
|---|---|---|
| `--paper` | `#FBF19E` | Fondo canary del bloc |
| `--rule` | `#A8C0D6` | Líneas horizontales |
| `--margin` | `#E05A4F` | Línea de margen, acentos |
| `--ink` | `#1F2933` | Texto |
| `--ink-pen` | `#27408B` | Enlaces (boli azul) |

**Regla de uso, importante:** el amarillo va en el *chrome* (cabecera, pie, about, índice de
proyectos). Las fotos se montan sobre superficie neutra (blanco o casi negro). Un fondo
saturado detrás de una imagen le desplaza el balance de color al ojo y ensucia los grises.

Tipografía: monoespaciada tipo máquina de escribir para titulares, sans limpia para texto.

### Build y despliegue

- Dockerfile: fuera `composer create-project`. `composer.json` propio con
  `getkirby/cms: 5.3.2` pineado + `COPY site/ assets/ index.php` desde el repo.
- `site/accounts` a la PVC.
- Sin contenido demo en la imagen.
- Sigue por GHCR + ArgoCD.

## Decisiones abiertas (requieren respuesta)

1. ¿Híbrido o one-pager puro?
2. Las ~14 «Notes» del demo: ¿se archivan, o quieres blog/diario?
3. ¿Tienes ya fotos reales y cuántos proyectos? Hoy todo es demo.
4. Contacto: ¿formulario o solo email/redes?
5. ¿Compras la licencia de Kirby?
6. Amarillo: ¿solo chrome (recomendado) o fondo dominante?

## Fuera de alcance

Migración de contenido real (fotos propias), SEO, analítica, i18n.
