SELECT "stop_time".*, "trip".*, "route".*, "stop".* FROM "stop_time"
 INNER JOIN "trip" ON trip.trip_id = stop_time.trip_id
 INNER JOIN "route" ON route.route_id = trip.route_id
 INNER JOIN "stop" ON stop.stop_id = stop_time.stop_id 

WHERE (stop.stop_name = 'Arrott Transportation Center') 
AND (trip.service_id = '1') 
AND (route.route_short_name = 'K' OR route.route_short_name = '75' OR route.route_short_name = 'J' OR route.route_short_name = '89') 
AND (trip.direction_id = '0') 
AND (time( stop_time.departure_time ) > time('16:50:00')) 
AND (time( stop_time.departure_time ) < time('17:30:00'))
;