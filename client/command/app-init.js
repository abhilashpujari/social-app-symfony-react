var fs = require('fs');

let content = `let config = {};
export default config;`;

fs.appendFile('../config/local.js', content, function (err) {
  if (err) throw err;
  console.log('Saved!');
});