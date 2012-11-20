/*EXPLAIN QUERY PLAN SELECT "stop_time".*, "trip".*, "route".*, "stop".* FROM "stop_time"
 INNER JOIN "trip" ON trip.trip_id = stop_time.trip_id
 INNER JOIN "route" ON route.route_id = trip.route_id
 INNER JOIN "stop" ON stop.stop_id = stop_time.stop_id 
WHERE (stop.stop_name = 'Arrott Transportation Center') 
AND (trip.service_id = '1') 
AND (trip.trip_headsign = 'Ridge - Midvale') 
;*/

EXPLAIN QUERY PLAN SELECT "stop_time".trip_id FROM "stop_time" 
INNER JOIN "trip" ON "trip".trip_id = "stop_time".trip_id
where trip.trip_id = '1234234';