CREATE OR REPLACE VIEW public.v_geo_declare_adv
 AS
select
	xgdd.id,xgdd.fnchkpoint,xgdd.fnindex, xgdd.x_geo_declare_id,xgdd.fflat,xgdd.fflon, xgd.ftstate 
from x_geo_declare_det xgdd join x_geo_declare xgd 
	on (xgdd.x_geo_declare_id = xgd.id )