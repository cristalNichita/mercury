const path = require('path');
module.exports = {
  resolve: {
    alias: {
      '@modules': path.resolve('Modules'),
      '@': path.resolve('resources/js'),
      '@css': path.resolve('resources/css'),
      '@assets': path.resolve('resources/assets'),
    },
  },
};
