describe('Checkout Choice → Checkout Details navigáció', () => {

    it('Vendégként vásárolok gomb kattintás', () => {
        cy.visit('/checkout/choice');

        cy.contains('a.btn', 'Vendégként vásárolok')
          .should('be.visible');

        cy.contains('a.btn', 'Vendégként vásárolok')
          .click();

        cy.url().should('include', '/checkout/details');
    });

});
