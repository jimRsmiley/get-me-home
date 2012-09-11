.separator ","

/*
* agency TABLE
*/
CREATE TABLE agency (   agency_name,
                        agency_url,
                        agency_timezone,
                        agency_lang
                    );

.import ./google_bus/agency.txt agency

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
.import ./google_bus/calendar.txt calendar

create table calendar_date ( service_id,date,exception_type );
.import ./google_bus/calendar_dates.txt calendar_date

create table fare_attribute ( fare_id,price,currency_type,payment_method,
            transfers,transfer_duration );
.import ./google_bus/fare_attributes.txt fare_attribute

create table fare_rule ( fare_id,origin_id,destination_id );
.import ./google_bus/fare_rules.txt fare_rule

create table route ( route_id,route_short_name,route_long_name,
            route_type,route_color,route_text_color,route_url );
.import ./google_bus/routes.txt route

create table shape ( shape_id,shape_pt_lat,shape_pt_lon,shape_pt_sequence );
.import ./google_bus/shapes.txt shape

create table stop ( stop_id,stop_name,stop_lat,stop_lon,location_type,
            parent_station,zone_id );
.import ./google_bus/stops.txt stop

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
.import ./google_bus/stop_times.txt stop_time

BEGIN TRANSACTION;
CREATE TEMPORARY TABLE stop_time_backup (stop_time_id INTEGER PRIMARY KEY AUTOINCREMENT,trip_id,arrival_time,departure_time, stop_id,stop_sequence);
INSERT INTO stop_time_backup ( trip_id,arrival_time,departure_time, stop_id,stop_sequence ) SELECT trip_id,arrival_time,departure_time, stop_id,stop_sequence FROM stop_time;
DROP TABLE stop_time;
CREATE TABLE stop_time (stop_time_id INTEGER PRIMARY KEY AUTOINCREMENT,trip_id,arrival_time,departure_time, stop_id,stop_sequence);
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

.import ./google_bus/trips.txt trip



/*
*
* transfer TABLE
*
*/
create table transfer ( from_stop_id,to_stop_id,
            transfer_type,min_transfer_time );
.import ./google_bus/transfers.txt transfer

.schema