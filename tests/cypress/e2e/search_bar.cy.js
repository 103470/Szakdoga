describe('Kereső teszt', () => {

    it('Keresés "fékbetét" kulcsszóval', () => {
        cy.visit('/');

        cy.get('input[name="q"]')
          .should('be.visible')
          .type('fékbetét');

        cy.get('form').within(() => {
            cy.get('button[type="submit"]').click();
        });

        cy.url().then(url => {
            expect(decodeURIComponent(url)).to.include('/search?q=fékbetét');
        });

        cy.contains('h3', 'Keresési eredmények: "fékbetét"')
          .should('be.visible');
    });

});
