describe('Homepage', () => {
  it('should load the homepage', () => {
    cy.visit('/')
    cy.contains('B+M Webáruház') 
  })
})
