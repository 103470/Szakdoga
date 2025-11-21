describe('Bentley card navigation', () => {
  it('should navigate to the Bentley page when clicking the Bentley card', () => {
    cy.visit('/')

    cy.contains('#rareBrandCarousel a', 'Bentley')
      .should('be.visible')  
      .click()

    cy.url().should('include', '/bentley')
  })
})
