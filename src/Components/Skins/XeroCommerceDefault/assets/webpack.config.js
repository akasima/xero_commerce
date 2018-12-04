var path = require('path');
const VueLoader = require('vue-loader/lib/plugin')

module.exports = [{
    mode: 'development',
    entry: './src/app.js',
    output: {
        path: path.resolve(__dirname, './js'),
        filename: 'index.js'
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
              test: /\.js$/,
              loader: 'babel-loader'
            },
            {
                test: /\.css$/,
                use: [
                    {
                        loader: 'style-loader',
                        options: {
                            transform: './src/transform.js'
                        }
                    },
                    'css-loader',
                ]
            },
            {
                test: /\.(woff|woff2|eot|ttf|otf|png|svg|jpg|gif)$/,
                use: [
                    'file-loader'
                ]
            }
        ]
    },
    resolve: {
        alias: {
            'vue': 'vue/dist/vue.js'
        },
        extensions: ['*', '.js', '.vue', '.json']
    },
    plugins: [
        new VueLoader()
    ]
}
]
