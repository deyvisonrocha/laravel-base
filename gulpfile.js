const elixir = require('laravel-elixir');

var paths = {
    'multiselect': './node_modules/multiselect',
    'fontawesome': './node_modules/font-awesome',
    'select2': './node_modules/select2/dist',
    'icheck': './node_modules/icheck',
    'ionicons': './node_modules/ionicons-npm',
    'slimscroll': './node_modules/jquery-slimscroll',
    'bootstrapdatepicker': './node_modules/bootstrap-datepicker',
    'maskedinput': './node_modules/jquery.maskedinput',
}

elixir(function(mix) {
    mix
    .copy(paths.bootstrap + '/fonts/**', 'public/fonts')
    .copy(paths.fontawesome + '/fonts/**', 'public/fonts')
    .copy(paths.ionicons + '/fonts/**', 'public/fonts')

    .scripts([
        paths.select2 + "/js/select2.min.js",
        paths.icheck + "/icheck.min.js",
        paths.bootstrapdatepicker + "/dist/js/bootstrap-datepicker.min.js",
        paths.bootstrapdatepicker + "/dist/locales/bootstrap-datepicker.pt-BR.min.js",
        paths.maskedinput + "/src/jquery.maskedinput.js",
    ],'public/js/all.js', './')

    .styles([
        paths.select2 + "/css/select2.min.css",
        paths.fontawesome + "/css/font-awesome.css",
        paths.ionicons + "/css/ionicons.css",
        paths.bootstrapdatepicker + "/dist/css/bootstrap-datepicker3.standalone.min.css",
    ],'public/css/all.css', './');

    mix.copy(paths.multiselect + "/js/jquery.multi-select.js", 'public/js');
    mix.copy(paths.multiselect + "/css/multi-select.css", 'public/css');

    //version control & cache
    mix.version('public/js/final.js');
    mix.version('public/css/final.css');
});
