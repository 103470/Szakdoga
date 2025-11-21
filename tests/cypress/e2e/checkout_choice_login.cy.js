describe('Checkout Choice → Login navigáció', () => {

    it('Bejelentkezés gomb kattintás', () => {
        cy.visit('/checkout/choice');

        cy.contains('a.btn', 'Bejelentkezés')
          .should('be.visible');

        cy.contains('a.btn', 'Bejelentkezés')
          .click();

        cy.url().should('include', '/login');
    });

});
