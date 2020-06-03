Requeriments:

    - docker version 19.03.9
    - docker-compose version 1.25.5
    
Setup:

    - make prepare-local-env-dev
    - make build
    
Test:

    - make test
    
Example:

    - make run 0.10, 0.10, RETURN-COIN
    - docker-compose run php php bin/console vending_machine:shell_input_command 0.10, 0.10, RETURN-COIN
    
Implementation Comments:

    - A lots of assumtions on how the machine should behave and what restrictions we have, here some of them
            - The project is designed to run one vending machine per docker 
            - We want all info persited in one file to avoid need for external dependencies outside the docker
            - If file not found a brand new vending machine will be initialized
            - we will persist on each success command execution
            - Default coin is EUR , the vending machine work with one currency at time
            - we have infinite wallets and item slot storage
            - service command wipes all status and creates a new vending machine 
            - will not allow buy an item if there is not enough exchange 
            - coins are rearanged to exchange wallet afer buy but first exchange is given  
            ...
            
     - Implementation follow this rough structure       
  
    
  <pre>
   +-------------+
   | command line|            +----------+
   |             |            | Message  |            +----------------------+
   +-----+-------+            | Handlers +----------->| Load Status Service  |
         |                    |          |            +-----------+----------+
         |                    |          |                        |
    +----v-------+            |          |                        |
    | Parse Args |            |          |                        |
    |            |            |Command   |                        |
    +----+-------+            |          |              +---------v-------------------+
         |                    |   &      |              | VendingMachine Domain Logic |
         |                    |          |              +---------+-------------------+
    +----v-------------+      |Query     |                        |
    | Message Factory  +----->|          |                        |
    |                  |      |          |            +-----------v-----------+
    +------------------+      |          |            | Persit Status Service |
                              |          |            +-----------+-----------+
                              |          |                        |
    +-----------------+       |          |                        v
    |Sys Output print |<-----+|          |                   +--------+
    |     service     |       +----------+                   |  END   |
    +-----------------+                                      +--------+
    
    </pre>
    
    Load and Persist service are injected or not in the handler depending if they need the last known status perfom it's task,
    on query handlers only the loader is (or should due none implemented)  
    
Commands:

    - a number represents the value of an inserted coin
    - RETURN-COIN  return already inserted coins
    - GET-XXXXX  buys XXXX where XXXX is the code of the slot containing a type of item
    - SERVICE currency:[currency code] id:[machine id] coin:[value]:qty item:[slot_code]:[qty]:[unitPrice]
            -example: SERVICE currency:EUR coin:0.10:1 coin:0.25:1 item:WATER:1:0.65 item:SODA:3:1.50
            -You can repeat item and coin arguments as needs, if no id provide a random one will be generated and current vending machine will be wipe out.
    - you can chain commads with ' ,', each success command execution will persist.
    - SERVICE command cannot be chained.