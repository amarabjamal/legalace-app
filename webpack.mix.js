let mix = require('laravel-mix');
require('dotenv').config();

mix.js('resources/js/app.js', 'public/js')
 .vue(3)
 .postCss('resources/css/app.css', 'public/css', [
    require("tailwindcss"),
 ])
 .version();