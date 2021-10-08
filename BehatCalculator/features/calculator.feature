Feature: Calculator

Scenario Outline: Faire une addition
    When j'additionne <nombre1> <nombre2>
    Then le résultat <resultat> est mémorisé

    Examples:
        | nombre1 | nombre2 | resultat |
        |12|5|17|
        |20|5|25|
        |4|5|9|


Scenario: Get memory
    Given the following memoryDatas:
      | nombre1 | nombre2|
      | 1 | 1|
      | 2 | 1|
      | 3 | 1|
      | 4 | 1|
    When I ask the memory
    Then I get "2,3,4,5"

Scenario: reset memory
    Given the following memoryDatas:
      | nombre1 | nombre2|
      | 1 | 1|
      | 2 | 1|
      | 3 | 1|
      | 4 | 1|
    When I reset the memory
    Then I get an empty array

Scenario: exception memory
    When the result is greater than two hundred with 100 101
    Then I get an exception





