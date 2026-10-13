# QuickAuth

**Autenticación social para PHP 8.2+ y Vue 3.** Proyecto inicial en desarrollo; aún no es un paquete publicado ni está auditado para producción.

## Objetivo

Unificar el inicio de sesión con Google y Facebook detrás de una API sencilla, con OAuth 2.0, `state` anti-CSRF, sesiones PHP seguras y una futura SDK de Vue.

## Estructura

```text
quickauth/
├── packages/
│   ├── php-core/
│   │   ├── composer.json
│   │   └── src/
│   └── vue/
│       ├── package.json
│       └── src/
├── examples/
│   └── php-public/
├── .env.example
└── README.md
```

## Requisitos

- PHP 8.2+
- Composer 2
- Node.js 20+
- Vue 3 para el SDK
- Credenciales OAuth creadas en Google Cloud Console y Meta for Developers

## Instalación local

Desde la raíz:

```bash
cd packages/php-core
composer install
```

Para el SDK Vue:

```bash
cd ../vue
npm install
npm run typecheck
```

## Configuración

Copia `.env.example` a `.env` y completa los valores de cada proveedor. No subas `.env` al repositorio.

```env
QUICKAUTH_BASE_URL=http://localhost:8000
QUICKAUTH_GOOGLE_CLIENT_ID=
QUICKAUTH_GOOGLE_CLIENT_SECRET=
QUICKAUTH_GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
QUICKAUTH_FACEBOOK_CLIENT_ID=
QUICKAUTH_FACEBOOK_CLIENT_SECRET=
QUICKAUTH_FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback
```

Registra las URL de callback exactas en cada consola de proveedor.

## Estado del proyecto

- [x] Estructura inicial del monorepo
- [x] Contratos de configuración y usuario
- [x] API de cliente Vue conceptual tipada
- [ ] Implementar adaptadores OAuth completos
- [ ] Persistencia/gestión de usuarios según aplicación
- [ ] Suite de pruebas de integración y seguridad
- [ ] Auditoría de seguridad antes de producción

## Seguridad

Esta plantilla es un punto de partida, no una implementación terminada. No confíes en datos de perfil enviados por el navegador. El backend debe validar el callback, el `state`, el emisor/audiencia/firmas de tokens cuando corresponda, y crear su propia sesión. Usa HTTPS en producción, cookies `HttpOnly`, `Secure` y `SameSite`, y nunca expongas secretos OAuth en Vue.
