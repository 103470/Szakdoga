describe('Termékcsoportok teszt', () => {

    it('Fék mechanika → Fékbetét → Beépítési oldal: elsőtengely navigáció', () => {
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
          .click({ force: true });
        cy.url().should('include', '/termekcsoport/fek_mechanika/fekbetet');

        cy.contains('.type-card-title', 'Beépítési oldal: elsőtengely')
          .should('be.visible')
          .parent()
          .find('a.stretched-link')
          .click({ force: true });
        cy.url().should('include', '/termekcsoport/fek_mechanika/fekbetet/beepitesi_oldal_elsotengely');
    });

});
