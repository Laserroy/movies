CREATE TABLE users (
                       id int AUTO_INCREMENT PRIMARY KEY,
                       name varchar(100) NOT NULL,
                       email varchar(50) UNIQUE NOT NULL,
                       password varchar(255) NOT NULL
);

CREATE TABLE movies (
                        id bigint AUTO_INCREMENT PRIMARY KEY,
                        title varchar(100) UNIQUE NOT NULL,
                        release_year int(4) NOT NULL,
                        format ENUM('VHS', 'DVD', 'Blu-Ray') NOT NULL
);

CREATE TABLE stars (
    id bigint PRIMARY KEY AUTO_INCREMENT,
    full_name varchar(150) NOT NULL UNIQUE
);

CREATE TABLE movie_stars (
    id bigint PRIMARY KEY AUTO_INCREMENT,
    movie_id bigint NOT NULL REFERENCES movies (id) ON DELETE CASCADE,
    star_id bigint NOT NULL REFERENCES stars (id) ON DELETE CASCADE
);
