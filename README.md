# Laravel 12 Inertia Vue

How to create shadcn DataTable server side pagination in Vue
with filters search, amount, status, orderby and sorting with slider.

## Install

```sh
npm install
npm run build
composer update
php artisan config:clear
php artisan migrate:fresh --seed
php artisan serve
```

## Login seeder

```sh
super@example.com
password

admin@example.com
password

test@example.com
password
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
# Show tags
git tag
# Add tag
git tag 1.1
# With comment
git tag -a <tagname> -m "Your message here"
# Push to repo
git push origin --tags
git push origin <tagname>
```

### Config php.ini for xampp

```ini
; On windows:
;extension_dir="C:\xampp\php\ext"
extension_dir="ext"

; Enable extensions
extension=bz2
extension=curl
extension=fileinfo
extension=gd
extension=gettext
extension=intl
extension=mbstring
extension=exif      ; Must be after mbstring as it depends on it
extension=mysqli
extension=openssl
extension=pdo_mysql
extension=pdo_pgsql
extension=pdo_sqlite

;Allow cache header from laravel 'Cache-Control: public, max-age=3600'
;session.cache_limiter=nocache
session.cache_limiter=

[opcache]
;Disable php 8.5 errors xampp
;zend_extension=opcache

; Config cache
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000

[curl]
curl.cainfo="C:\xampp\apache\bin\curl-ca-bundle.crt"

[openssl]
openssl.cafile="C:\xampp\apache\bin\curl-ca-bundle.crt"
```

### Display images

```php
<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Display image from html
// <img src="image/media/avatars/donkey.webp">

// Image path media/avatars/donkey.webp
// file in private/media/avatars/donkey.webp directory
// for storage disk default settings (FILESYSTEM_DISK=local).
// In php.ini change session.cache_limiter = nocache
// to empty string session.cache_limiter = ''.
Route::get('/image/{path}', function ($path) {
	// Check path
	if (Storage::has($path)) {
		// Get file content from cache
		$content = Cache::store('file')->remember(
			md5($path),
			now()->addMinutes(15),
			function () use ($path) {
				return Storage::get($path);
			}
		);
		// Display image
		return response($content)->header('Content-Type', 'image/webp');
	} else {
		// Default image
		return response()->file(public_path('/default/avatar.webp'), [
			'Content-Type' => 'image/webp',
			'Cache-Control' => 'public, max-age=3600'
		]);
	}
})->where('path', '.*');
```
