Feature: Check all project actions.
  Background:
    Given there are projects with the following details:
      | uid  | title       | hash          | customer    | description       |
      | u1   | Some title  | kdkjkzejf     | Belgacom    | Some description  |

    Given I login the user "test" "test"

  Scenario: get project by hash code
    Given I login the user "test" "test"
    When I request "GET" "/v1/projects/" with one parameter "kdkjkzejf" to get or add a "project"
    Then the response code should be 200
    And the response should contain a "id"

  Scenario: create a project for a user
    Given I have the following payload
    """
    {"project":{"title":"Once again","description":"Project for behat testing -345","customer":"Behat","deadline":"2017-10-31"}}
    """
    When I request "POST" "/v1/project/" with one parameter "test" to get or add a "project"
    Then the response code should be 201
    And the response should contain a "id"

    Scenario: Get all project for one user
      Given I login the user "test" "test"
      When I request "GET" "/v1/users/" with one nested parameter "test" "/projects" to get or add a "project"
      Then the response code should be 200
      And the response should be an array

  Scenario: Get all team members for one project
    Given I login the user "test" "test"
    When I request "GET" "/v1/projects/" with one nested parameter "kdkjkzejf" "/team" to get or add a "project"
    Then the response code should be 200
    And the response should be an array

  Scenario: Get project statistics
    Given I login the user "test" "test"
    When I request "GET" "/v1/admin/projects/stats"
    Then the response code should be 200
    And the response should contain a "red"

  Scenario: Get all team members for one project with there coin status
    Given I login the user "test" "test"
    When I request "GET" "/v1/projects/" with one nested parameter "kdkjkzejf" "/team/pomodoro" to get or add a "project"
    Then the response code should be 200
    And the response should be an array
