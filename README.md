# ![Boot](https://github.com/xlthlx/boot/blob/main/assets/img/banner.png "WordPress Starter Theme")

Starter theme based on the [official Timber starter theme](https://github.com/laras126/timber-base-theme), and using [John Billion's Extended CPTS library](https://github.com/johnbillion/extended-cpts) for registering post types and taxonomies, [CMB2](https://github.com/CMB2/CMB2) for managing custom fields, and [Bootstrap 5](https://github.com/twbs/bootstrap/tree/v5.0.0-beta1).

## Setup

If you are manually deploying this theme for the first time, you will need to run `composer install` to include Timber, Extended CPTs and CMB2. To include Bootstrap 5, you will need to run `composer install` also under the folder `assets/`.

## Structure

`assets/` CSS, JS, SASS, images etc for the theme.

`inc/` key functionality - registering post types, custom fields, template tag and functions, etc.

`views/` contains all the Twig templates.

## Included example code

#### `inc/custom-fields.php`
* Register custom meta fields, based on CMB2 example functions.

#### `inc/custom-post-taxonomies.php`
* Register custom post types and taxonomies using Extended CPTS library.

#### `inc/custom-user-roles.php`
* Register custom user roles examples.

#### `inc/template-functions.php`
* Hooks some useful functions, like additional columns into the admin.

#### `inc/template-options.php`
* Register a Theme Options page using CMB2.

#### `inc/template-tags.php`
* Add some tags to use into the theme, like the breadcrumbs.


