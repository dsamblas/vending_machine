Feature: Vending Machine feature

  Scenario: Buy Soda with exact change
    Given I run vending machine command with arguments "1, 0.25, 0.25, GET-SODA"
    Then I should exactly see "SODA" in the output

  Scenario: Start adding money, but user ask for return coin
    Given I run vending machine command with arguments "0.10, 0.10, RETURN-COIN"
    Then I should exactly see "0.10, 0.10" in the output

  Scenario: Buy Water without exact change
    Given I run vending machine command with arguments "1, GET-WATER"
    Then I should exactly see "WATER, 0.25, 0.10" in the output

  Scenario: Set vending machine status by service operator
    Given I run vending machine command with arguments "SERVICE c:0.10:1:EUR c:0.25:1 i:WATER:1:0.65:EUR i:SODA:1.50"
    Then I should exactly see "OK" in the output