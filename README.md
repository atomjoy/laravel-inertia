# Laravel 12 Inertia Vue

How to create shadcn DataTable server side pagination in Vue
with filters search, amount, status, orderby and sorting with slider.

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

## Screen

![DataTable shadcn components in Vue and Inertia](https://raw.githubusercontent.com/atomjoy/laravel-inertia/refs/heads/main/screen.webp)

## Links

```sh
https://tanstack.com/table/latest/docs/guide/pagination#should-you-use-client-side-pagination
https://tanstack.com/table/latest/docs/framework/vue/guide/table-state
https://www.shadcn-vue.com/docs/components/data-table#pagination
https://github.com/unovue/shadcn-vue/tree/dev/apps/v4/components/examples
https://github.com/makara-dev369/shadcn-project
https://github.com/sadmann7/tablecn

# Query functions
https://vue-query.vercel.app/#/getting-started/installation
https://tanstack.com/query/latest/docs/framework/vue/guides/queries
https://tanstack.com/query/latest/docs/framework/vue/guides/query-keys
https://tanstack.com/query/latest/docs/framework/vue/reference/useQuery
https://tanstack.com/query/latest/docs/framework/vue/guides/default-query-function
https://tanstack.com/query/latest/docs/framework/vue/guides/query-keys
https://tanstack.com/table/latest/docs/framework/vue/guide/table-state
```

## Dev

```js
// OK: Use this in table
rowCount: props.data?.total ?? 0,

// Error: When paginate from page url query param
// (not refreshing pages after page={nr} in pagination).
pageCount: props.data?.last_page ?? 1,
```

### Git

```sh
# Add tag
git tag 1.0
# With comment
git tag <tagname> -a
# Push to repo
git push origin --tags
git push origin <tagname>
```
