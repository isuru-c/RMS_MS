
CREATE TABLE IF NOT EXISTS student (
   id varchar(8) NOT NULL,
   first_name varchar(30) NOT NULL,
   second_name varchar(30) DEFAULT NULL,
   faculty varchar(20) NOT NULL,
   department varchar(30) NOT NULL,
   gender varchar(6) NOT NULL,
   birthday date NOT NULL,
   contact_number varchar(10) NOT NULL,
   e_mail varchar(50) DEFAULT NULL,
   address varchar(200) DEFAULT NULL,
   PRIMARY KEY ( id ),
   KEY id ( id )
);

CREATE TABLE IF NOT EXISTS user (
   username varchar(10) NOT NULL,
   password varchar(150) NOT NULL,
   role varchar(20) NOT NULL,
   PRIMARY KEY ( username )
);

CREATE TABLE IF NOT EXISTS sport (
   id int(5) NOT NULL AUTO_INCREMENT,
   name varchar(50) NOT NULL,
   gender varchar(6) NOT NULL,
   minimum_age int(2) DEFAULT NULL,
   description varchar(500) DEFAULT NULL,
   PRIMARY KEY ( id )
);

CREATE TABLE IF NOT EXISTS play (
   id int(5) NOT NULL AUTO_INCREMENT,
   student_id varchar(8) NOT NULL,
   sport_id int(5) NOT NULL,
   start_date date NOT NULL,
   PRIMARY KEY ( id ),
   FOREIGN KEY ( student_id ) REFERENCES student ( id )
      ON UPDATE CASCADE ON DELETE RESTRICT,
   FOREIGN KEY ( sport_id ) REFERENCES sport ( id )
      ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS coach (
   id int(5) NOT NULL AUTO_INCREMENT,
   first_name varchar(30) NOT NULL,
   second_name varchar(30) DEFAULT NULL,
   sport_id int(5) NOT NULL,
   contact_number varchar(10) NOT NULL,
   e_mail varchar(50) DEFAULT NULL,
   address varchar(200) DEFAULT NULL,
   PRIMARY KEY ( id )
);

CREATE TABLE IF NOT EXISTS instruct (
   id int(5) NOT NULL AUTO_INCREMENT,
   coach_id int(8) NOT NULL,
   sport_id int(5) NOT NULL,
   start_date date NOT NULL,
   PRIMARY KEY ( id ),
   FOREIGN KEY ( coach_id ) REFERENCES coach ( id )
      ON UPDATE CASCADE ON DELETE RESTRICT,
   FOREIGN KEY ( sport_id ) REFERENCES sport ( id )
      ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS equipment_category (
   id int(5) NOT NULL AUTO_INCREMENT,
   lend_time int(2) NOT NULL,
   name varchar(50) NOT NULL,
   sport_id int(5) DEFAULT NULL,
   PRIMARY KEY ( id ),
   FOREIGN KEY ( sport_id ) REFERENCES sport ( id )
      ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS equipment (
   id int(5) NOT NULL AUTO_INCREMENT,
   equipment_category_id int(5) DEFAULT NULL,
   available int(1) NOT NULL,
   reserved int(1) NOT NULL,
   PRIMARY KEY ( id ),
   FOREIGN KEY ( equipment_category_id ) REFERENCES equipment_category ( id )
      ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS equipment_reserve (
   id int(5) NOT NULL AUTO_INCREMENT,
   reserved_date date NOT NULL,
   student_id varchar(8) DEFAULT NULL,
   equipment_id int(5) DEFAULT NULL,
   state int(1) NOT NULL,
   PRIMARY KEY ( id ),
   FOREIGN KEY ( student_id ) REFERENCES student ( id )
      ON UPDATE CASCADE ON DELETE RESTRICT,
   FOREIGN KEY ( equipment_id ) REFERENCES equipment ( id )
      ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS equipment_lend (
   id int(5) NOT NULL AUTO_INCREMENT,
   equipment_reserve_id int(5) DEFAULT NULL,
   lend_date date NOT NULL,
   due_date date NOT NULL,
   state int(1) NOT NULL,
   returned_date date DEFAULT NULL,
   PRIMARY KEY ( id ),
   FOREIGN KEY ( equipment_reserve_id ) REFERENCES equipment_reserve ( id )
      ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS event (
   id int(5) NOT NULL AUTO_INCREMENT,
   sport_id int(5) DEFAULT NULL,
   type int(1) NOT NULL,
   title varchar(50) NOT NULL,
   start_date date NOT NULL,
   end_date date NOT NULL,
   state int(1) NOT NULL,
   opponent varchar(100) DEFAULT NULL,
   remarks varchar(500) DEFAULT NULL,
   PRIMARY KEY ( id ),
   FOREIGN KEY ( sport_id ) REFERENCES sport ( id )
      ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS event_member (
   id int(5) NOT NULL AUTO_INCREMENT,
   event_id int(5) DEFAULT NULL,
   student_id varchar(8) DEFAULT NULL,
   remarks varchar(100) DEFAULT NULL,
   PRIMARY KEY ( id ),
   FOREIGN KEY ( event_id ) REFERENCES event ( id )
      ON UPDATE CASCADE ON DELETE CASCADE,
   FOREIGN KEY ( student_id ) REFERENCES student ( id )
      ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS awards (
   id int(5) NOT NULL AUTO_INCREMENT,
   event_id int(5) DEFAULT NULL,
   description varchar(100) DEFAULT NULL,
   student_id varchar(8) DEFAULT NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (event_id) REFERENCES event (id)
      ON UPDATE CASCADE ON DELETE SET NULL,
   FOREIGN KEY (student_id) REFERENCES student (id)
      ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS practice_schedule (
   id int(5) NOT NULL AUTO_INCREMENT,
   sport_id int(11) DEFAULT NULL,
   date date NOT NULL,
   start_time time NOT NULL,
   end_time time NOT NULL,
   description varchar(200) DEFAULT NULL,
   PRIMARY KEY ( id ),
   FOREIGN KEY ( sport_id ) REFERENCES sport ( id )
      ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS fac_dep (
   faculty varchar(20) NOT NULL,
   department varchar(40) NOT NULL,
   PRIMARY KEY ( faculty , department )
);




-- Insert few basic data

INSERT INTO fac_dep ( faculty , department ) VALUES
   ('Engineering', 'Bio Medical'),
   ('Engineering', 'Chemical'),
   ('Engineering', 'Civil'),
   ('Engineering', 'Computer Science'),
   ('Engineering', 'Earth Resources'),
   ('Engineering', 'Electricle'),
   ('Engineering', 'Electronic'),
   ('Engineering', 'Fashion Design'),
   ('Engineering', 'Mechanical'),
   ('Engineering', 'Meterial');

INSERT INTO user ( username , password , role ) VALUES
   ('admin', '$2a$12$cyTWeE9kpq1PjqKFiWUZFuCRPwVyAZwm4XzMZ1qPUFl7/flCM3V0G', 'ROLE_ADMIN'),
   ('choda', '$2a$12$cyTWeE9kpq1PjqKFiWUZFuCRPwVyAZwm4XzMZ1qPUFl7/flCM3V0G', 'ROLE_COACH'),
   ('isuru', '$2a$12$LCY0MefVIEc3TYPHV9SNnuzOfyr2p/AXIGoQJEDs4am4JwhNz/jli', 'ROLE_STUDENT');

