describe('BMW full navigation to specific product', () => {
  it('navigates all the way to the Maxgear fékbetét product page', () => {
    cy.visit('/')

    cy.contains('.brand-card h6', 'BMW').click()
    cy.url().should('include', '/bmw')

    cy.contains('.type-card-title', '3')
      .parents('.type-card')
      .find('a.stretched-link')
      .click({ force: true })
    cy.url().should('include', '/bmw/3')

    cy.get('table.vintage-table tbody tr', { timeout: 30000 }).then(($rows) => {
      let found = false
      $rows.each((index, row) => {
        const $tds = Cypress.$(row).find('td')
        const name = $tds.eq(0).text().trim()
        const range = $tds.eq(1).text().trim()
        const frame = $tds.eq(2).text().trim()

        if (name === '3' && range === '1997/12 - 2005/05' && frame === 'e46') {
          cy.wrap(row).click({ force: true })
          found = true
          return false
        }
      })
      if (!found) throw new Error('Nem találtuk meg a megfelelő vintage sort')
    })

    cy.url({ timeout: 30000 }).should('include', '/bmw/3/3_e46_1997_12_2005_05')

    cy.get('table.vintage-table tbody tr', { timeout: 30000 })
      .not('tr:has(td[colspan="5"])')
      .first()
      .click({ force: true })
    cy.url({ timeout: 30000 }).should('match', /\/bmw\/3\/3_e46_1997_12_2005_05\/\d+/)

    cy.get('.type-card-title', { timeout: 20000 })
      .contains(/fék mechanika/i)
      .scrollIntoView({ duration: 500 })
      .parents('.type-card')
      .find('a.stretched-link')
      .click({ force: true })
    cy.url({ timeout: 30000 }).should('include', '/fek_mechanika')

    cy.get('.type-card-title', { timeout: 20000 })
      .contains(/fékbetét/i)
      .scrollIntoView({ duration: 500 })
      .parents('.type-card')
      .find('a.stretched-link')
      .click({ force: true })
    cy.url({ timeout: 30000 }).should('include', '/fekbetet')

    cy.get('.type-card-title', { timeout: 20000 })
      .contains(/Beépítési oldal: elsőtengely/i)
      .scrollIntoView({ duration: 500 })
      .parents('.type-card')
      .find('a.stretched-link')
      .click({ force: true })
    cy.url({ timeout: 30000 }).should('include', '/beepitesi_oldal_elsotengely')

    cy.get('.product-list', { timeout: 20000 })
    .contains('a.text-danger.text-decoration-none', /Maxgear fékbetét/i)
    .scrollIntoView({ duration: 500 })
    .click({ force: true })

    cy.url({ timeout: 30000 }).should('include', '/termek/19-0623')




  })
})