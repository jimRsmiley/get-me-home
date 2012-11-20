/*
* agency TABLE
*/
CREATE TABLE agency (   agency_name,
                        agency_url,
                        agency_timezone,
                        agency_lang
                    );

BEGIN TRANSACTION;

CREATE TEMPORARY TABLE agency_backup (agency_id INTEGER PRIMARY KEY AUTOINCREMENT,agency_name, agency_url,agency_timezone,agency_lang);

INSERT INTO agency_backup ( agency_name,agency_url, agency_timezone, agency_lang ) SELECT agency_name,agency_url, agency_timezone, agency_lang FROM agency;
DROP TABLE agency;

CREATE TABLE agency (agency_id INTEGER PRIMARY KEY AUTOINCREMENT,agency_name, agency_url,agency_timezone,agency_lang);

INSERT INTO agency SELECT agency_id,agency_name, agency_url,agency_timezone,agency_lang FROM agency_backup;
DROP TABLE agency_backup;
COMMIT;

create table calendar ( service_id,monday,tuesday,wednesday,
            thursday,friday,saturday,sunday,start_date,end_date);

create table calendar_date ( service_id,date,exception_type );

create table fare_attribute ( fare_id,price,currency_type,payment_method,
            transfers,transfer_duration );

create table fare_rule ( fare_id,origin_id,destination_id );

create table route ( route_id INTEGER PRIMARY KEY,route_short_name,route_long_name,
            route_type,route_color,route_text_color,route_url );

create table shape ( shape_id,shape_pt_lat,shape_pt_lon,shape_pt_sequence );

create table stop ( stop_id INTEGER PRIMARY KEY,stop_name,stop_lat,stop_lon,location_type,
            parent_station,zone_id );

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

BEGIN TRANSACTION;
CREATE TEMPORARY TABLE stop_time_backup (stop_time_id INTEGER PRIMARY KEY AUTOINCREMENT,trip_id,arrival_time,departure_time, stop_id,stop_sequence);
INSERT INTO stop_time_backup ( trip_id,arrival_time,departure_time, stop_id,stop_sequence ) SELECT trip_id,arrival_time,departure_time, stop_id,stop_sequence FROM stop_time;
DROP TABLE stop_time;
CREATE TABLE stop_time (stop_time_id INTEGER PRIMARY KEY AUTOINCREMENT,trip_id,arrival_time,departure_time, stop_id,stop_sequence);

CREATE UNIQUE INDEX i1 ON stop_time(departure_time);
CREATE UNIQUE INDEX i2 ON stop_time(stop_id);
CREATE UNIQUE INDEX i3 ON stop_time(trip_id);

CREATE UNIQUE INDEX i4 ON stop_time(departure_time,stop_id);
CREATE UNIQUE INDEX i5 ON stop_time(departure_time,trip_id);

CREATE UNIQUE INDEX i6 ON stop_time(stop_id,departure_time);
CREATE UNIQUE INDEX i7 ON stop_time(stop_id,trip_id);

CREATE UNIQUE INDEX i8 ON stop_time(trip_id,departure_time);
CREATE UNIQUE INDEX i9 ON stop_time(trip_id,stop_id);

CREATE UNIQUE INDEX i10 ON stop_time(departure_time,stop_id,trip_id);
CREATE UNIQUE INDEX i11 ON stop_time(departure_time,trip_id,stop_id);
CREATE UNIQUE INDEX i12 ON stop_time(stop_id,departure_time,trip_id);
CREATE UNIQUE INDEX i13 ON stop_time(stop_id,trip_id,departure_time);
CREATE UNIQUE INDEX i14 ON stop_time(trip_id,departure_time,stop_id);
CREATE UNIQUE INDEX i15 ON stop_time(trip_id,stop_id,departure_time);




INSERT INTO stop_time SELECT stop_time_id,trip_id,arrival_time,departure_time, stop_id,stop_sequence FROM stop_time_backup;
DROP TABLE stop_time_backup;
COMMIT;
/*
*
*
* trip
*
*/
create table trip ( route_id,service_id,trip_id INTEGER PRIMARY KEY,trip_headsign,
            block_id,direction_id,shape_id );




/*
*
* transfer TABLE
*
*/
create table transfer ( from_stop_id,to_stop_id,
            transfer_type,min_transfer_time );


EXPLAIN QUERY PLAN 

SELECT "stop_time".stop_id FROM "route"
 INNER JOIN "trip" ON route.route_id = trip.route_id
 INNER JOIN "stop" ON stop.stop_id = stop_time.stop_id 
 INNER JOIN "stop_time" ON stop_time.trip_id = trip.trip_id
;