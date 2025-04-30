CREATE DATABASE list_todo;
USE list_todo;

CREATE TABLE Users (
    user_id int NOT NULL,
    full_name varchar(250),
    email varchar(250),
    password varchar(250),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
    );
    
   
CREATE TABLE Tasks (
    task_id INT NOT NULL,
    list_id INT,
    task_name VARCHAR(250),
    status VARCHAR(50),
    attachement_name VARCHAR(250),
    attachement LONGBLOB,
    subtask VARCHAR(250),
    notes VARCHAR(250),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (task_id)
);


CREATE TABLE Lists (
    list_id int NOT NULL,
    task_id int,
    title varchar(250),
    description varchar(500),
    PRIMARY KEY (list_id),
    FOREIGN KEY (task_id) REFERENCES Tasks(task_id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
    );