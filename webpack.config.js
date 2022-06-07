const path = require('path');

module.exports = {
    entry: {
        'widgets': path.resolve(__dirname, './src/widgets.js'),
        'settings': path.resolve(__dirname, './src/settings.js'),
    },
    output: {
        path: path.resolve(__dirname, 'assets'),
        filename: 'js/[name].js',
    },
    module: {
        rules: [{
            test: /\.js$/,
            use: 'babel-loader',
            exclude: '/node_modules/'
        }]
    }
}