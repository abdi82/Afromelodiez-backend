const path = require('path');

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
        },
    },
    mix : 
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');
    

};

