describe('Termékcsoportok teszt', () => {

    it('Fék mechanika majd Fékbetét alkategória teszt', () => {
        cy.visit('/'); 

        cy.contains('h2', 'Termékcsoportok')
            .scrollIntoView()
            .should('be.visible');

        cy.contains('.card h6', 'Fék mechanika')
            .should('be.visible')
            .click();

        cy.url().should('include', '/termekcsoport/fek_mechanika');

        cy.contains('.type-card-title', 'Fékbetét')
          .should('be.visible')
          .parent()
          .find('a.stretched-link')
          .should('have.attr', 'href'); 
          
        cy.contains('.type-card-title', 'Fékbetét')
          .parent()
          .find('a.stretched-link')
          .click({ force: true });

        cy.url().should('include', '/termekcsoport/fek_mechanika/fekbetet');
    });

});
