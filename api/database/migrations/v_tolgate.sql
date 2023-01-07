CREATE VIEW v_tolgate AS 
select
	gd1.id as entry_id, gd1.seq as entry_seq, gd1."time" as entry_time, gd1.imei as entry_imei, gd1."event" as entry_event, 
	gd1.geoid as entry_geoid, gd1.long as entry_long, gd1.lat as entry_lat, gd1.direct as entry_direct,
	gd1.speed as entry_speed, gd1.bat as entry_bat ,gd1.sat  as entry_sat,
	gd2.id as exit_id ,gd2.geoid as exit_geoid, gd2.long as exit_long, gd2.lat as exit_lat, gd2.direct as exit_direct, 
	gd2.speed as exit_speed, gd2.bat as exit_bat, gd2.sat as exit_sat
from geo_declare2 gd1 left join geo_declare2 gd2
	on (gd1.id = gd2.g1id) where gd1."event" = 'G1'
order by gd1.id desc;