# Omsguru AI Coding Conventions

This document outlines the architecture, conventions, and workflows for this Laravel and Vue.js codebase to help AI coding agents be more effective.

## 🚀 Architecture Overview

This is a modern, full-stack application built with the TALL stack philosophy, but with Vue.js instead of Livewire.

- **Backend**: Laravel (PHP)
- **Frontend**: Vue.js 3 with TypeScript
- **Connector**: Inertia.js
- **Styling**: Tailwind CSS
- **UI Components**: shadcn-vue

The core architectural pattern is that **Laravel controllers render Vue components directly**. There is no separate API. Data is passed from the backend to the frontend as props.

### Key Directories

- `app/Http/Controllers`: Laravel controllers that fetch data and render Vue pages.
- `routes/web.php`: Defines the main application routes that map to controller actions.
- `resources/js/pages`: Contains the top-level Vue components that act as Inertia pages. Each file in here corresponds to a page rendered by a controller.
- `resources/js/components`: Contains reusable Vue components.
- `resources/js/layouts`: Vue components that provide the application's layout structure.
- `resources/js/lib/utils.ts`: Utility functions, particularly for `cn` (class name merging) from `tailwind-merge`.

##  workflows

### Development

To run the application locally, you need to start both the PHP server and the Vite development server.

1.  **Start the Laravel server**:
    ```bash
    php artisan serve
    ```
2.  **Start the Vite server for frontend assets**:
    ```bash
    npm run dev
    ```

### Testing

This project uses PHPUnit for backend testing.

- **Run all tests**:
  ```bash
  php artisan test
  ```

## 🎨 Frontend Development

### Inertia.js

- **Rendering Pages**: Laravel controllers use `Inertia::render('PageName', ['propName' => $data])` to render a Vue component from `resources/js/pages`.
- **Props**: Data passed from the controller is available as props in the Vue page component. Always define types for these props using TypeScript.
- **Routing**: Use the `<Link>` component from `@inertiajs/vue3` for navigation. This creates client-side transitions without a full page reload. Do not install or use `vue-router`.

### Components & Styling

- **UI Components**: We use `shadcn-vue`. To add new components, use the CLI: `npx shadcn-vue@latest add <component-name>`.
- **Styling**: Use Tailwind CSS utility classes for all styling. The `cn` utility function in `resources/js/lib/utils.ts` is available to merge Tailwind classes, which is useful for creating variants of components.

### TypeScript

- **Type Safety**: The frontend is fully typed. Define interfaces for props passed from Laravel and for any complex objects.
- **Path Aliases**: The `tsconfig.json` file defines the `@` alias for `resources/js`. Use this for cleaner imports: `import MyComponent from '@/components/MyComponent.vue'`.

##  php Backend Development

- **Routes**: Define all web-facing routes in `routes/web.php`. These routes should return Inertia responses.
- **Controllers**: Keep controllers lean. Their primary job is to handle requests, perform authorization, fetch data, and render the appropriate Vue page via Inertia.
- **Models**: Use standard Laravel Eloquent models.
- **Authentication**: Authentication is handled by Laravel Fortify. The routes are in `routes/auth.php`.
