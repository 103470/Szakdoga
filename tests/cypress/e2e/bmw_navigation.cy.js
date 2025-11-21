describe('BMW card navigation', () => {
  it('should navigate to the type selector when clicking BMW card', () => {
    cy.visit('/')

    cy.contains('.brand-card h6', 'BMW')
      .click()

    cy.url().should('include', '/bmw') 
  })
})
