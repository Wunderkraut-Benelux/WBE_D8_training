#features/ownership.feature
@api
Feature: Ownership
  As a site administrator
  I should be able to configure the Ownership information

  Scenario: As an anonymous user, I should not have access to the Ownership config form
    Given I am an anonymous user
    And I am on "/ownership"
    Then I should see "Access Denied"

  @javascript
  Scenario: As a site admin, I can fill in the ownership form with the basic fields
    Given I am logged in as a user with the "administrator" role
    And the configuration of "training_ownership.ownership" is empty
    And I am on "/ownership"
    Then I should see "Ownership settings"
    And the "form #edit-first-name" element should be visible
    And the "form #edit-last-name" element should be visible
    And the "form #edit-email" element should be visible
    And the "form #edit-company-name" element should be visible
    And the "form #edit-company-vat" element should not be visible
    Then I fill in "First name" with "My first name"
    And I fill in "Last name" with "My last name"
    And I fill in "Email address" with "name@example.com"
    And I press the "Save configuration" button
    And I wait until the page loads
    Then I should see "The configuration options have been saved."
    And the "First name" field should contain "My first name"
    Then I restore the configuration "training_ownership.ownership"