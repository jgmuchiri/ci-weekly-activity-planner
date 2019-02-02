CREATE TABLE activity_plan
(
    id int(11) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title varchar(255) NOT NULL,
    notes text,
    activity_date date NOT NULL,
    activity_start time NOT NULL,
    activity_end time NOT NULL,
    created_at datetime NOT NULL,
    updated_at datetime
);