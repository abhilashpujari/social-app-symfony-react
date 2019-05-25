const program = require('commander');
const fs = require('fs');

program
  .command('config-init')
  .description('command to create local.js config file')
  .action(() => {
    const fileContent = `let config = {}; export default config;`;

    const filePath = 'src/config/local.js';
    fs.exists(filePath, (exists) => {
      if (!exists) {
        fs.writeFile(filePath, fileContent, (err) => {
          if (err) throw err;

          console.log("local.js config file created!!!");
        });
      }
    });
  });

program.parse(process.argv);