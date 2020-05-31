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