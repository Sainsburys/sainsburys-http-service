Feature: Batching DB queries, and limiting them to only that data which is needed
  In order to not find the Ebooks Landing Page annoying
  As a customer in a hurry
  I want the Collections Library to retrieve the data I want quickly


  Scenario: Getting the Collections and only making a single, batched query for Ebooks
    Given there are 2 collections
    And all the collections have 3 books in them
    When I start counting DB queries
    And I look at the collections on the page
    Then there should only have been one database query to retrieve books

  Scenario: Only retrieving those Ebooks in a Collection which will actually be displayed
    Given there is 1 collection
    And the collection has 7 books in it
    When I start counting DB queries
    And I look at the collections on the page
    Then only 6 books should have been retrieved from the database

  Scenario: Caching the Collections
    Given there is 1 collection
    And the collection has 2 books in it
    When I start counting DB queries
    And I look at the collections on the page
    And I look at the collections on the page again
    Then there should only have been one database query to retrieve books
