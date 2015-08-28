CREATE table inquiry(
	id int not null auto_increment PRIMARY KEY,
	name varchar(255) not null,
	company varchar(255) not null,
	email varchar(255),
	phone varchar(255),
	memo varchar(2550),
	created DATETIME,
	modified DATETIME
	)

