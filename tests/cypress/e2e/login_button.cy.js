describe('Header Bejelentkezés navigáció', () => {

    it('A header Bejelentkezés gomb kattintás /login oldalra visz', () => {
        cy.visit('/');

        cy.get('a.btn.theme-orange-btn')
          .contains('Bejelentkezés')
          .should('be.visible')
          .click();

        cy.location('pathname').should('include', '/login');
    });

});
