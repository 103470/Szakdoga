const { defineConfig } = require('cypress')

module.exports = defineConfig({
  e2e: {
    baseUrl: 'http://nginx',     
    specPattern: 'e2e/**/*.cy.js',  
    supportFile: false, 
  },
})
