EXPLAIN QUERY PLAN SELECT "stop_time".*, "route".*

FROM "stop_time"

INNER JOIN "trip" ON "trip".trip_id = "stop_time".trip_id 
INNER JOIN "route" ON "route".route_id = "trip".route_id

WHERE 
 
(trip.route_id = '10726' ) 
AND (trip.service_id = '1') 
AND (trip.direction_id = '0' )
AND (stop_time.stop_id = '220')
AND (time( stop_time.departure_time ) > time('08:30:45')) 
AND (time( stop_time.departure_time ) < time('09:30:45'))
;

SELECT "stop_time".*, "route".*

FROM "stop_time"

INNER JOIN "trip" ON "trip".trip_id = "stop_time".trip_id 
INNER JOIN "route" ON "route".route_id = "trip".route_id

WHERE 
 
(route.route_short_name = 'K' )
AND (trip.service_id = '1') 
AND (trip.direction_id = '0' )
AND (stop_time.stop_id = '220')
AND (time( stop_time.departure_time ) > time('08:30:45')) 
AND (time( stop_time.departure_time ) < time('09:30:45'))
;