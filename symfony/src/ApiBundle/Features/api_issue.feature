Feature: Check all issue actions.
  Background:
    Given there are projects with the following details:
      | uid  | title       | hash          | customer    | description       |
      | u1   | Some title  | kdkjkzejf     | Belgacom    | Some description  |

    Given there is a issue with the following details:
      | uid  | title       | hash            | description       |
      | u1   | Some title  | sfddsfsdffsd    | Some description  |
      | u1   | issue 2     | sdklfl234       | A description     |


  Scenario: get issue by hash code
    Given I login the user "test" "test"
    When I request "GET" "/v1/issues/" with one parameter "sfddsfsdffsd" to get or add a "issue"
    Then the response code should be 200
    And the response should contain a "id"


  Scenario: get issues for a project by hash code
    Given I login the user "test" "test"
    When I request "GET" "/v1/projects/" with one nested parameter "kdkjkzejf" "/issues" to get or add a "issue"
    Then the response code should be 200
    And the response should be an array

  Scenario: create a issue for a project
    Given I have the following payload
    """
    {"issue":{
      "description":"Fix the issue behat test",
      "title":"Behat test",
      "estimatePomodoros":"1",
      "solved": false,
      "solvedBy": "",
      "inProgress": true,
      "urgent": true,
      "isWorking":"lottverw"}}
    """
    And  I login the user "test" "test"
    When I request "POST" "/v1/issues/" with one parameter "kdkjkzejf" to get or add a "issue"
    Then the response code should be 201
    And the response should be an object with name "issue"
