describe('BMW navigation to type 3', () => {
  it('should navigate from BMW card to /bmw and then click the type card labeled "3"', () => {
    cy.visit('/')

    cy.contains('.brand-card h6', 'BMW').click()
    cy.url().should('include', '/bmw')

    cy.get('.type-card', { timeout: 5000 }).should('exist')

    cy.contains('.type-card-title', '3')
      .parents('.type-card')              
      .find('a.stretched-link')           
      .click({ force: true })

    cy.url().should('include', '/bmw/3')
  })
})
