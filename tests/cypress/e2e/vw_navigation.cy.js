describe('VW card navigation', () => {
  it('should navigate to the VW page when clicking the VW card', () => {
    cy.visit('/')

    cy.get('#brandCarousel .carousel-control-next').click() 
    cy.contains('.brand-card h6', 'VW')
    .should('be.visible') 
    .click()

    cy.url().should('include', '/vw')

  })
})
