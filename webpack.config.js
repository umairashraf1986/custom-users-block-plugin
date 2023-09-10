const path = require('path');

module.exports = {
    mode: 'development',
    entry: './public/js/custom-users-block.js',
    output: {
        path: path.resolve(__dirname, 'public/js/build'),
        filename: 'custom-users-block-build.js',
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                },
            },
        ],
    },
};
