.separator ","

DROP TABLE IF EXISTS stop_time;
DROP TABLE IF EXISTS trip;

/*
*
* stop_time
*
* need to rebuild table with a stop_time_id
*/
create table stop_time (  
                trip_id,
                arrival_time,
                departure_time, 
                stop_id,
                stop_sequence 
        );

 -- must run before we recreate the table
--.import ./google_bus/stop_times.txt stop_time

BEGIN TRANSACTION;
CREATE TEMPORARY TABLE stop_time_backup (stop_time_id INTEGER PRIMARY KEY AUTOINCREMENT,trip_id,arrival_time,departure_time, stop_id,stop_sequence);
INSERT INTO stop_time_backup ( trip_id,arrival_time,departure_time, stop_id,stop_sequence ) SELECT trip_id,arrival_time,departure_time, stop_id,stop_sequence FROM stop_time;
DROP TABLE stop_time;
CREATE TABLE stop_time (stop_time_id INTEGER PRIMARY KEY AUTOINCREMENT,trip_id,arrival_time,departure_time, stop_id,stop_sequence);
INSERT INTO stop_time SELECT stop_time_id,trip_id,arrival_time,departure_time, stop_id,stop_sequence FROM stop_time_backup;
DROP TABLE stop_time_backup;
COMMIT;

-- CREATE INDEX stop_index1 ON stop_time ( stop_id );
/*
*
*
* trip
*
*/
create table trip ( route_id,service_id,trip_id,trip_headsign,
            block_id,direction_id,shape_id );


--.import ./google_bus/trips.txt trip

EXPLAIN QUERY PLAN SELECT "stop_time".departure_time 

FROM "stop_time"

INNER JOIN "trip" ON "trip".trip_id = "stop_time".trip_id 
 
 WHERE 
 
(trip.route_id = '10726' ) 
AND (trip.service_id = '1') 
AND (stop_time.stop_id = '220') 
AND (time( stop_time.departure_time ) > time('08:30:45')) 
AND (time( stop_time.departure_time ) < time('09:30:45'))
;

DROP TABLE IF EXISTS stop_time;
DROP TABLE IF EXISTS trip;

/*
*
* stop_time
*
* need to rebuild table with a stop_time_id
*/
create table stop_time (  
                trip_id,
                arrival_time,
                departure_time, 
                stop_id,
                stop_sequence 
        );

 -- must run before we recreate the table
--.import ./google_bus/stop_times.txt stop_time

BEGIN TRANSACTION;
CREATE TEMPORARY TABLE stop_time_backup (stop_time_id INTEGER PRIMARY KEY AUTOINCREMENT,trip_id,arrival_time,departure_time, stop_id,stop_sequence);
INSERT INTO stop_time_backup ( trip_id,arrival_time,departure_time, stop_id,stop_sequence ) SELECT trip_id,arrival_time,departure_time, stop_id,stop_sequence FROM stop_time;
DROP TABLE stop_time;
CREATE TABLE stop_time (stop_time_id INTEGER PRIMARY KEY AUTOINCREMENT,trip_id,arrival_time,departure_time, stop_id,stop_sequence);
INSERT INTO stop_time SELECT stop_time_id,trip_id,arrival_time,departure_time, stop_id,stop_sequence FROM stop_time_backup;
DROP TABLE stop_time_backup;
COMMIT;

-- CREATE INDEX stop_index1 ON stop_time ( stop_id );
/*
*
*
* trip
*
*/
create table trip ( route_id,service_id,trip_id INTEGER PRIMARY KEY,trip_headsign,
            block_id,direction_id,shape_id );


--.import ./google_bus/trips.txt trip

EXPLAIN QUERY PLAN SELECT "stop_time".departure_time 

FROM "stop_time"

INNER JOIN "trip" ON "trip".trip_id = "stop_time".trip_id 
 
 WHERE 
 
(trip.route_id = '10726' ) 
AND (trip.service_id = '1') 
AND (stop_time.stop_id = '220') 
AND (time( stop_time.departure_time ) > time('08:30:45')) 
AND (time( stop_time.departure_time ) < time('09:30:45'))
;