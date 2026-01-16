# Laravel 12 Inertia Vue

How to create shadcn DataTable with server side pagination (filters, sorting, search, slider).

## Install

```sh
npm install
npm run build
composer update
php artisan migrate:fresh --seed
php artisan serve
```

## Examples

```sh
# Client-side pagination url: /users (data from file, array)
pages/Users.vue

# Server-side pagination url: /payments (data from database)
pages/Payments/Index.vue
```

## Links

```sh
https://tanstack.com/table/latest/docs/guide/pagination#should-you-use-client-side-pagination
https://tanstack.com/table/latest/docs/framework/vue/guide/table-state
https://www.shadcn-vue.com/docs/components/data-table#pagination
https://github.com/unovue/shadcn-vue/tree/dev/apps/v4/components/examples
https://github.com/makara-dev369/shadcn-project

# Query functions
https://vue-query.vercel.app/#/getting-started/installation
https://tanstack.com/query/latest/docs/framework/vue/guides/queries
https://tanstack.com/query/latest/docs/framework/vue/guides/query-keys
https://tanstack.com/query/latest/docs/framework/vue/reference/useQuery
https://tanstack.com/query/latest/docs/framework/vue/guides/default-query-function
https://tanstack.com/query/latest/docs/framework/vue/guides/query-keys
https://tanstack.com/table/latest/docs/framework/vue/guide/table-state
```
