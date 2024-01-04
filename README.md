# The assignment
Please implement a software solution that will enable various types of vehicle to park
in a parking garage.

When a vehicle arrives at the parking garage, and if there is space available, we
should see a message on the screen saying “Welcome, please go in”.
When a vehicle arrives at the entrance to the parking garage, and it's full, we
should see a message saying “Sorry, no spaces left”.

Must accept multiple types of vehicles: cars, vans, motorcycles

Each type will have different size (e.g. 2 motorcycles can park in 1 car spot, a
van takes 1.5 car spots).

Must have 3 floors, each floor has different capacity.

A Van can only park in the ground floor.

# Instruction

To run the application just execute console command "**make run**".

This command will: 
- Up Docker container
- Run `composer install` command
- Run Unit tests
- Run `php app emulate` console command. It will provide questions for initial
parking spots amount and number of incoming random vehicles. After that command 
will run emulation of incoming vehicles and visualization of parking process. 

# Assumptions and design decisions

For user interaction I chose console command. 
The Application uses bare minimum of vendor packages:
- symfony/console
- symfony/dependency-injection
- symfony/config
- monolog/monolog

I made the assumption that this application will expand in the future. 
According to this assumption I use DDD approach for the folder structure. 
For more scalability and maintainability of the code, as well as to separate business 
logic from the external framework components.
In some scenarios DDD may be considered as over-engineering (for example microservices).

I created 4 entities 
- Floor
- ParkingGarage
- ParkingSpot
- Vehicle

This approach allows easily extend the application (add new parking garages, 
close floors for reconstruction e.t.c). ParkingSpot entity is required because we must know whether 
a parking space is available as half or full. (we can not park car on 2 half's for example) 

For the parking algorithm, I used the strategy design pattern. This approach allows easily extend
the codebase in case if new transport types will appear.

# Possible future improvements

Currently, floors and parking spots are stored in array properties of their
owners. Which is fine for "in-memory" storage. In the future, we can introduce IDs for the entities 
to achieve the possibility to store Entities and their relations in persistent storages (DB).

Thank you for the opportunity!