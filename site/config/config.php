<?php

/*
  Config base. NO fijar 'url' aquí: Kirby la detecta sola, y así el mismo
  código sirve en local (localhost:8080) y en producción.

  Ojo: en el clúster, este fichero lo tapa el ConfigMap
  website-photo-kirby-config, que sí fija la URL. Ver deployment.yaml.
*/
return [
    'panel' => [
        'install' => false,
    ],
];
