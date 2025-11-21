describe('Kosár → Checkout navigáció', () => {

    it('Folytatás gomb kattintás a /checkout/choice oldalra', () => {
        cy.visit('/cart');

        cy.contains('a.btn', 'Folytatás')
          .should('be.visible')
          .and('have.attr', 'href')  
          .then(href => {
              cy.log('Folytatás link:', href);
          });

        cy.contains('a.btn', 'Folytatás')
          .click();

        cy.url().should('include', '/checkout/choice');
    });

});
