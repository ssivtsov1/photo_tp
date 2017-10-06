create view vw_spr_tp as
select a.*,b.nazv as nazv_res
from spr_tp a 
left join spr_res b on
a.id_res=b.id

create view vw_photo as
select a.*,b.nazv as nazv_res,c.nazv as nazv_tp,
d.file_path,d.is_main,d.model_name,d.url_alias
from photo a 
left join spr_res b on
a.id_res=b.id
left join spr_tp c on
a.id_tp=c.id
inner join image d on
a.id = d.item_id
