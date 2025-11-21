describe('Kosár gomb működése', () => {
  it('Kosárba gomb kattintás megjeleníti az alertet, majd lenyitja a kosár dropdown-t', () => {

    cy.intercept('POST', '/cart/add/*', {
      statusCode: 200,
      body: {
        success: true,
        message: 'Termék sikeresen a kosárhoz adva'
      }
    }).as('addToCart');

    cy.visit('/tipus/bmw/3/3_e46_1997_12_2005_05/5405287/fek_mechanika/fekbetet/beepitesi_oldal_elsotengely'); 


    cy.get('.add-to-cart-btn').first().scrollIntoView().click();

    cy.wait('@addToCart');

    cy.get('.alert', { timeout: 5000 })
      .should('be.visible')
      .and('contain.text', 'Termék sikeresen a kosárhoz adva')
      .within(() => {
        cy.get('button.btn-close').click();
      });

    cy.get('.cart-toggle')
      .scrollIntoView()
      .click({ force: true }); 

    cy.get('#cart-dropdown')
      .should('have.class', 'show'); 
  });
});
