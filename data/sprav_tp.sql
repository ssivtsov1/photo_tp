select eq.id, eq.name_eqp,eq.num_eqp,eq.dt_install, eq.id_addres ,eqa.id_client, a.adr::varchar, u.eqp_cnt 
, s.power, s.comp_cnt, s.p_regday,s.date_regday 
from eqm_equipment_tbl AS eq join eqm_area_tbl as eqa on (eqa.code_eqp=eq.id) 
left join adv_address_tbl as a on (eq.id_addres=a.id) 
join eqm_compens_station_tbl as s on (eq.id=s.code_eqp) 
left join ( select code_eqp_inst, count(*)::integer as eqp_cnt from eqm_compens_station_inst_tbl group by code_eqp_inst order by code_eqp_inst) as u on (eq.id=u.code_eqp_inst) 
where (eq.type_eqp = 8) 
 order by eq.name_eqp; 
