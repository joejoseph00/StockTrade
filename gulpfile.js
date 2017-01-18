const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
|--------------------------------------------------------------------------
| Elixir Asset Management
|--------------------------------------------------------------------------
|
| Elixir provides a clean, fluent API for defining some basic Gulp tasks
| for your Laravel application. By default, we are compiling the Sass
| file for your application as well as publishing vendor resources.
|
*/

elixir((mix) => {
    mix.sass('app.scss')
    .sass('stocktrade-main.scss')
    .webpack('app.js')
    .webpack( 'stocktrade.js' ,'./public/api/v1/js')
    .webpack( 'docs.js' ,'./public/api/v1/js');

});
