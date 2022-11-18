const host = 'https://loyalist.lib.unb.ca'
describe('Loyalist Collection', {baseUrl: host, groups: ['sites']}, () => {

  describe('Front page', {baseUrl: host}, () => {
    beforeEach(() => {
      cy.visit('/')
      cy.title()
        .should('contain', 'The Loyalist Collection')
    })

    specify('Search for "Archives of Ontario" should find 7+ results', () => {
      cy.get('.content form').within(() => {
        cy.get('input[name="search_api_fulltext"]')
          .type('Archives of Ontario')
      }).submit()
      cy.url()
        .should('match', /\/loyalist-search/)
      cy.get('.views-row')
        .should('have.lengthOf.at.least', 7)
    });
  })

})
