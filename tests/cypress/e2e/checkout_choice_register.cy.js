describe('Checkout Choice navigáció', () => {

      it('Bejelentkezés gomb kattintás', () => {
        cy.visit('/checkout/choice');

        cy.contains('a.btn', 'Regisztráció')
          .should('be.visible');

        cy.contains('a.btn', 'Regisztráció')
          .click();

        cy.url().should('include', '/register');
    });

});
