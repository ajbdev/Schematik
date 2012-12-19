# Schematik
Database defined data governance (for MySQL)

### What's that mean?
Define the characteristics of your data once: in the database. By sniffing out the metadata of your database (and using the COMMENTS field for annotations), Schematix takes your database constraints and hints and turns them into data validators and filters.

### How it works
Schematik works by parsing the schema of your database and using the definitions defined in your database to control the logic of the database abstraction layer.

Silex+Schematik skeleton app example forthcoming. 

#### Features
 - Validates max length of column
 - Validates type of data going into a column
 - Support for email validation, password hashing

#### Upcoming features
 - Database level ACL's for different user types (Concept: create a different DB username for each user type and lock down SELECT/UPDATE/INSERT privileges on the database level to affect what will be queried for from the app)


Why?
----
With PHP annotations being so popular these days, I wanted to experiment with assigning the data governance directly in the database. In theory, one could do this more efficiently using database triggers, but

What are the potential shortcomings?
Knowing the metadata of a database to perform a simple insert or update is costly. For one, there is a certain amount of time that is required to parse this data, and second, holding the entire object graph representation of the database
is memory cumbersome. At this point in time, it would be simple to remove the parsing load time by caching the object graph entirely to a cache mechanism, however this still leaves holding the object graph in memory on each load. For this
reason, Schematik becomes larger based on the size of your database.

This performance hit may be negligible.
Here are some performance benchmarks for your consideration:

 - 10 tables, 10 columns per table, 5 attributes per table:
 -
 -
