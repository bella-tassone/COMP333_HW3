# COMP333_HW3

## How to Run our App

Before attempting to run the app, please make sure that you have XAMPP installed and running. Within the myphpAdmin backend, you should create a database with both a users and ratings table. You can run the commands below to do so.

```sql
CREATE DATABASE music_db;
USE music_db;
CREATE TABLE users (username VARCHAR(255) PRIMARY KEY, password VARCHAR(255));
CREATE TABLE ratings (id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255),
    artist VARCHAR(255),
    song VARCHAR(255),
    rating INT(1) CHECK (rating BETWEEN 1 AND 5),
    FOREIGN KEY (username) REFERENCES users(username)
     );
```

Similarly, you will want to install node.js. You can do so by first installing [homebrew](https://brew.sh/) and then running the command `brew install node`.

1. Clone this repository into your preferred directory. You may do so with the command `git clone https://github.com/bella-tassone/COMP333_HW3.git`.

2. You will want to alter the contents of COMP333_HW3/Backend/inc/config.php to match your database credentials. Here is what ours looks like, for reference.

```php
<?php
define("DB_HOST", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_DATABASE_NAME", "music_db");
?>
```

3. Copy the contents of the Backend folder (Controller/Api, Model, inc, index.php) into the htdocs folder of your XXAMP application.

4. Use the `cd` command in your terminal to navigate into COMP333_HW3/Frontend/comp333-hw3. This is where you'll start the react app.

5. In our app, we have various resources installed (reactstrap, icons, etc). They should already be contained within the repository, but if you want to confirm that everything is up-to-date, take a look at the following websites: [reactstrap](https://reactstrap.github.io/?path=/story/home-installation--page), [react icons](https://react-icons.github.io/react-icons/), [react router](https://www.w3schools.com/react/react_router.asp), and [axios](https://axios-http.com/) (which you can install by running `npm add axios`). All of the other links should easily explain how to install their various resources.

6. Finally, within the comp333-hw3 directory, run the command `npm start`. The app should start up on a new browser window momentarily, and you should be directed to the home ratings page.

## File Directory

- `.gitignore`
- `LICENSE`
- `README.md`

### Backend

- `index.php`: Used to create controller instances, and contains the logic for calling action functions in the user and rating controllers

#### Controller/Api

- `BaseController.php`: Contains basic logic used in user and rating controllers via function call
- `RatingController.php`: Contains the get, create, update, and delete actions for /ratings API calls
- `UserController.php`: Contains the get, create, and login actions for /user API calls

#### Model

- `Database.php`: Basic wrapper for interacting with the SQL database, and provides methods for performing CRUD operations
- `UserModel.php`: Holds SQL queries in functions called in the userController and returns data from the SQL database to be processed into the view (frontend)
- `RatingModel.php`: Holds SQL queries in functions called in the ratingController and returns data from the SQL database to be processed into the view (frontend)

#### inc

- `bootstrap.php`: Contains paths for the controllers and models to be referenced throughout the backend code files
- `config.php`: Contains specifics relating to establishing database connection

### Frontend/comp333-hw3

For brevity, we will only go over files that have been actively worked on.

- `package.json`
- `package-lock.json`
- `README.md`

#### node_modules

#### public

#### src

- `App.js`: Contains the main component of the website, as well as crucial logic for logging in / displaying components to improve UX
- `App.css`: Styling for app.js
- `index.js`: contains the reactDOM and "root" of the application

#### views

- `AddRating.js`: Logic and API integration for a user adding a rating, and component form for a user to input a rating to add
- `DeleteRating.js`: Logic and API integration for a user adding a rating, and component for a user to submit a delete request
- `Login.js`: Login form component and API integration for user login
- `NoPage.js`: Used when a URL not found is requested (i.e. "/thispagedoesnotexist")
- `Ratings.css`: Styles the rating.js component
- `Ratings.js`: Logic for displaying the ratings and user interaction with their own ratings, and uses function calls from Add, Delete, Update rating.js files
- `Registration.js`: Registration form component and API integration for user login
- `UpdateRating.js`: Logic and API integration for a user adding a rating, and component for a user to submit an update request

#### Additional Feature
- `ToolTips`: We have implemented ToolTips, or pop-ups displayed when a user's mouse hovers over a certain element. Those tooltips are integrated via UncontrolledTooltip tags in the react.js code. They not only improve the UX, but do so in an instructive manner, as the tips assist with navigation and functionality.

Developers🧑‍🔬:
Nate Levinson and Bella Tassone

HW3 participation📝: 50/50 split
