CREATE VIEW v_tolgate AS 
select
	b.*, xgd2.ftgate_name as exit_gatename,
	xgd2.fflat_pos1 as exit_lat_gatepos1, 
	xgd2.fflon_pos1 as exit_lon_gatepos1,
	xgd2.fflat_pos2 as exit_lat_gatepos2, 
	xgd2.fflon_pos2 as exit_lon_gatepos2
from (
	select
		a.*, xgd1.ftgate_name as entry_gatename,
		xgd1.fflat_pos1 as entry_lat_gatepos1, 
		xgd1.fflon_pos1 as entry_lon_gatepos1,
		xgd1.fflat_pos2 as entry_lat_gatepos2, 
		xgd1.fflon_pos2 as entry_lon_gatepos2
	from (
		select
			gd1.id as entry_id, gd1.seq as entry_seq, gd1."time" as entry_time, gd1.imei as entry_imei, gd1."event" as entry_event, 
			gd1.geoid as entry_geoid, gd1.long as entry_long, gd1.lat as entry_lat, gd1.direct as entry_direct,
			gd1.speed as entry_speed, gd1.bat as entry_bat ,gd1.sat  as entry_sat,
			gd2."time" as exit_time, gd2.id as exit_id ,gd2.geoid as exit_geoid, gd2.long as exit_long, gd2.lat as exit_lat, gd2.direct as exit_direct, 
			gd2.speed as exit_speed, gd2.bat as exit_bat, gd2.sat as exit_sat
		from geo_declare2 gd1 left join geo_declare2 gd2
			on (gd1.id = gd2.g1id) where gd1."event" = 'G1'
		order by gd1.id desc
	) a left join x_geo_declare xgd1 
		on (a.entry_geoid = xgd1.id::text)
) b left join x_geo_declare xgd2
	on (b.exit_geoid = xgd2.id::text)
;

	
	