# PHP Testing Example

Proyecto de ejemplo completo para aprender y practicar testing en PHP con GitHub Actions.

## Estructura del proyecto

```
php-testing-example/
├── src/
│   ├── Calculator.php        # Clase para cálculos matemáticos
│   ├── User.php              # Modelo de datos User
│   └── UserRepository.php    # Repositorio para persistencia
├── tests/
│   ├── Unit/
│   │   └── CalculatorTest.php         # Tests unitarios
│   └── Integration/
│       └── UserRepositoryTest.php     # Tests de integración
├── .github/
│   └── workflows/
│       └── tests.yml                  # Workflow de CI/CD
├── composer.json
├── phpunit.xml
├── phpstan.neon
├── .php-cs-fixer.php
└── README.md
```

## Instalación

```bash
# Clonar el proyecto
cd php-testing-example

# Instalar dependencias
composer install

# Ejecutar los tests
composer test
```

## Comandos disponibles

```bash
# Ejecutar todos los tests
composer test

# Ejecutar solo tests unitarios
composer run-script test:unit

# Ejecutar solo tests de integración
composer run-script test:integration

# Generar reporte de cobertura
composer run-script test:coverage

# Ejecutar análisis estático (PHPStan)
composer run-script phpstan

# Verificar estilo de código
composer run-script cs-check

# Auto-corregir estilo de código
composer run-script cs-fix
```

## Componentes principales

### 1. Calculator.php

Clase simple con operaciones matemáticas:

- `sum(int $a, int $b): int` - Suma dos números
- `subtract(int $a, int $b): int` - Resta dos números
- `multiply(int $a, int $b): int` - Multiplica dos números
- `divide(float $a, float $b): float` - Divide dos números
- `factorial(int $n): int` - Calcula factorial

**Tests**: `tests/Unit/CalculatorTest.php`

- Tests básicos de operaciones
- Tests de excepciones
- Tests con data providers

### 2. User & UserRepository

Modelo de usuario con repositorio para persistencia:

**User.php**: Entidad simple

- `id`, `email`, `password`, `createdAt`

**UserRepository.php**: Acceso a datos

- `create(email, password)` - Crear usuario
- `findById(id)` - Buscar por ID
- `findByEmail(email)` - Buscar por email
- `findAll()` - Obtener todos
- `update(user)` - Actualizar
- `delete(id)` - Eliminar
- `count()` - Contar usuarios
- `validateCredentials(email, password)` - Validar login

**Tests**: `tests/Integration/UserRepositoryTest.php`

- Tests con SQLite en memoria (sin servidor)
- Validación de datos
- Operaciones CRUD
- Validación de credenciales

## GitHub Actions Workflow

El workflow `.github/workflows/tests.yml` ejecuta 4 jobs:

1. **code-style**: Verifica estilo de código con PHP CS Fixer
2. **phpstan**: Análisis estático de tipos (nivel 8)
3. **unit-tests**: Ejecuta tests unitarios en PHP 8.1, 8.2, 8.3
4. **integration-tests**: Ejecuta tests de integración

Dependencias:

```plaintext
code-style ─┐
            ├─→ unit-tests ─→ integration-tests
phpstan ────┘
```

## Code Style

El formateo y las normas de estilo se validan con **PHP CS Fixer**:

- Herramienta: [friendsofphp/php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) instalada como `require-dev` en [composer.json](composer.json).
- Configuración: reglas PSR-12 más ajustes específicos en [.php-cs-fixer.php](.php-cs-fixer.php) (array corto, imports ordenados alfabéticamente, eliminación de imports sin usar, comillas simples, trailing commas en multilínea). El `Finder` limita el análisis a `src/` y `tests/`.
- Ejecución local: `composer run-script cs-check` para comprobar en modo dry-run y `composer run-script cs-fix` para autocorregir.
- CI: el job **code-style** en [.github/workflows/tests.yml](.github/workflows/tests.yml) ejecuta `cs-check` y bloquea el pipeline si hay violaciones.

## Análisis estático PHPStan

El análisis de tipos se realiza con **PHPStan** a nivel 8:

- Herramienta: [phpstan/phpstan](https://phpstan.org/) definida en `require-dev` en [composer.json](composer.json).
- Configuración: [phpstan.neon](phpstan.neon) establece `level: 8`, analiza `src/` y `tests/`, y excluye `vendor/` para evitar falsos positivos en dependencias.
- Ejecución local: `composer run-script phpstan` o directamente `phpstan analyse --level=8` (coherente con el script).
- CI: el job **phpstan** en [.github/workflows/tests.yml](.github/workflows/tests.yml) ejecuta el análisis y debe pasar antes de los tests.

## Testing local

### Ejecutar tests

```bash
# Todos
composer test

# Solo unitarios (más rápido)
composer run-script test:unit

# Con cobertura
composer run-script test:coverage
```

### Output esperado

```
PHPUnit 10.0.0 by Sebastian Bergmann and contributors.

RC....................                                    22 tests, 0 failures
Code Coverage Report:
  Classes: 100.00% (3/3)
  Methods: 100.00% (15/15)
  Lines:   100.00% (68/68)
```

## Casos de prueba incluidos

### Tests unitarios (Calculator)

- Suma, resta, multiplicación, división
- Excepciones (división por cero, factorial negativo)
- Data providers para múltiples casos
- Cobertura: 100%

### Tests de integración (UserRepository)

- Creación de usuarios con validación
- CRUD operations
- Búsquedas por ID y email
- Validación de credenciales
- Uso de SQLite en memoria (no requiere MySQL)
- Cobertura: 100%

## Extensiones del proyecto

### Añadir más clases

1. Crear clase en `src/`
2. Crear test correspondiente en `tests/Unit/` o `tests/Integration/`
3. Ejecutar `composer test`

### Mejorar análisis estático

Cambiar nivel de PHPStan en `phpstan.neon`:

```neon
parameters:
    level: 9  # Máximo nivel
```

### Integrar Codecov

Si subes a GitHub, el workflow automáticamente sube cobertura a Codecov.

Copia el badge en tu README:
```markdown
[![codecov](https://codecov.io/gh/usuario/repo/branch/main/graph/badge.svg)](https://codecov.io/gh/usuario/repo)
```

## Recursos

- [PHPUnit Docs](https://phpunit.de/)
- [PHPStan Docs](https://phpstan.org/)
- [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)
- [GitHub Actions PHP](https://docs.github.com/en/actions/guides/building-and-testing-php)

## Licencia

MIT
