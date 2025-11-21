describe('Termékcsoportok teszt', () => {

    it('Legörget és rákattint a Fék mechanika kártyára', () => {
        cy.visit('/'); 

        cy.contains('h2', 'Termékcsoportok')
            .scrollIntoView()
            .should('be.visible');

        cy.contains('.card h6', 'Fék mechanika')
            .should('be.visible')
            .click();

        cy.url().should('include', '/termekcsoport/fek_mechanika');
    });

});
