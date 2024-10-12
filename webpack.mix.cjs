const mix = require('laravel-mix');

// mix.styles([
//     'resources/css/navbar.css',
//     'resources/css/sidebar.css',
// ], 'public/css/partial.css'); // Compiled into a single file named 'all.css'

// // Compile each CSS file
// mix.styles([
//     'resources/css/grades.css',
//     'resources/css/lteinfo.css',
//     'resources/css/schumanities.css',
//     'resources/css/overview.css',
//     'resources/css/profile.css'
// ], 'public/css/scholar.css'); // Compiled into a single file named 'all.css'

// // Compile each CSS file
// mix.styles([
//     'resources/css/home.css',
//     'resources/css/sccommunity.css'
// ], 'public/css/scholar2.css');

// mix.styles([
//     'resources/css/sccommunity.css'
// ], 'public/css/scholar3.css'); //// Compiled into a single file named 'all.css'

// mix.styles([
//    'resources/css/overview.css',
//     'resources/css/sccommunity.css',
//     'resources/css/schumanities.css'
// ], 'public/css/cs.css'); // Compiled into a single file named 'all.css'

// mix.styles([
//     'resources/css/home.css',
//     'resources/css/screnewal.css',
// ], 'public/css/home.css'); // Compiled into a single file named 'all.css'

// // Compile each CSS file
// mix.styles([
//     'resources/css/mainhome.css',
// ], 'public/css/view.css'); // Compiled into a single file named 'all.css'

// mix.styles([
//     'resources/css/registration.css'
// ], 'public/css/Reg.css'); // Compiled into a single file named 'all.css'

// mix.styles([
//     'resources/css/role.css',
// ], 'public/css/role.css');

mix.scripts([
    'resources/js/toggleprofile.js',
    'resources/js/togglesidebar.js',
    'resources/js/csdialog.js',
    'resources/js/chart.js'
], 'public/js/scholar.js').version();

// mix.copyDirectory('resources/images', 'public/images');
