# Sistema de Gestión de Calificaciones Estudiantiles
**Materia:** Desarrollo de Software VII | **Grupo:** 2GS231

## Estructura del Proyecto

```
proyecto-calificaciones/
│
├── src/                          # Código fuente PHP
│   ├── classes/
│   │   └── Estudiante.php        # Clase con nombre, notas y getPromedio()
│   │
│   ├── index.php                 # Página 1 — Formulario de ingreso de datos
│   └── resultados.php            # Página 2 — Reporte visual de calificaciones
│
├── Dockerfile                    # Imagen de producción (Apache + PHP 8.2)
└── README.md
```

## Flujo de datos entre páginas

```
index.php  ──POST──►  index.php (procesarYRedirigir)
                           │
                    serialize($estudiantes)
                    $_SESSION['estudiantes']
                           │
                    header('Location: resultados.php')
                           │
                           ▼
                    resultados.php
                    unserialize($_SESSION['estudiantes'])
```

## Despliegue con Dokploy

1. Sube el proyecto a un repositorio Git.
2. En Dokploy → **New Application** → selecciona el repo.
3. Dokploy detecta el `Dockerfile` automáticamente.
4. Configura el puerto expuesto: **80**.
5. Deploy ✓

## Ejecución local

```bash
docker build -t calificaciones .
docker run -p 8080:80 calificaciones
# Abrir: http://localhost:8080
```

## Módulos implementados (esqueleto base)

| Módulo | Archivo | Responsable |
|--------|---------|-------------|
| 1 — Registro de datos | `index.php` | Integrante 1 |
| 2 — Condicionales if/switch | `resultados.php` | Integrante 2 |
| 3 — Bucles foreach/for/while/do-while | `resultados.php` | Integrante 3 |
| 4 — Control de flujo break/continue/return/exit | `index.php` + `resultados.php` | Todos |
| 5 — Reporte visual Tailwind | `resultados.php` | Todos |
