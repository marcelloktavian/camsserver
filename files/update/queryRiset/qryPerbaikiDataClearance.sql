-- data buraken bermasalah
SELECT * FROM olnso WHERE id_trans='OLN201009662'
SELECT * FROM olnsodetail WHERE id_trans='OLN201009662'
UPDATE olnsodetail SET harga_satuan=123750,harga_act=123750,subtotal=123750,subtotal_act=123750 WHERE id_trans='OLN201009662'

-- data amronstore bermasalah
SELECT * FROM olnso WHERE id_trans='OLN201010269'
SELECT * FROM olnsodetail WHERE id_trans='OLN201010269'
UPDATE olnsodetail SET harga_satuan=123750,harga_act=123750,subtotal=123750,subtotal_act=123750 WHERE id_trans='OLN201010269'

-- data amkhashop bermasalah
SELECT * FROM olnso WHERE id_trans='OLN201012606'
SELECT * FROM olnsodetail WHERE id_trans='OLN201012606'
UPDATE olnsodetail SET harga_satuan=123750,harga_act=123750,subtotal=123750,subtotal_act=123750 WHERE id_trans='OLN201012606'


-- data mochastore bermasalah
SELECT * FROM olnso WHERE id_trans='OLN201012371'
SELECT * FROM olnsodetail WHERE id_trans='OLN201012371'
UPDATE olnsodetail SET harga_satuan=123750,harga_act=123750,subtotal=123750,subtotal_act=123750 WHERE id_so_d='154785'

-- data buraken bermasalah
SELECT * FROM olnso WHERE id_trans='OLN200904538'
SELECT * FROM olnsodetail WHERE id_trans='OLN200904538'
UPDATE olnsodetail SET harga_satuan=149875,harga_act=149875,subtotal=149875,subtotal_act=149875 WHERE id_trans='OLN200904538'


-- qry buat ngeceknya 
SELECT m.lastmodified,m.id_trans,d.nama,m.faktur,(SUM(dt.subtotal)*(1-d.disc)) AS nett,((SUM(dt.subtotal)*(1-d.disc))-m.faktur) AS saldo,m.total,d.disc FROM olnso m INNER JOIN olnsodetail dt ON m.id_trans=dt.id_trans LEFT JOIN mst_dropshipper d ON m.id_dropshipper = d.id 
WHERE (m.deleted=0) AND (m.state='1') 
AND DATE(m.lastmodified) BETWEEN STR_TO_DATE('01/09/2020','%d/%m/%Y') AND STR_TO_DATE('15/09/2020','%d/%m/%Y')
-- AND m.id_dropshipper=181
GROUP BY m.id_trans

-- data MAS TURSIN bermasalah
SELECT * FROM olnso WHERE id_trans='OLN200807104'
SELECT * FROM olnsodetail WHERE id_trans='OLN200807104'
UPDATE olnsodetail SET harga_satuan=149875,harga_act=149875,subtotal=149875,subtotal_act=149875 WHERE id_trans='OLN200807104'

SELECT * FROM olnso WHERE id_trans='OLN200906175'
SELECT * FROM olnsodetail WHERE id_trans='OLN200906175'
UPDATE olnsodetail SET harga_satuan=123750,harga_act=123750,subtotal=123750,subtotal_act=123750 WHERE id_trans='OLN200906175'

-- data HOLMES bermasalah
SELECT * FROM olnso WHERE id_trans='OLN200906287'
SELECT * FROM olnsodetail WHERE id_trans='OLN200906287'
UPDATE olnsodetail SET harga_satuan=123750,harga_act=123750,subtotal=123750,subtotal_act=123750 WHERE id_trans='OLN200906287'

-- data SWEET CHERRY bermasalah
SELECT * FROM olnso WHERE id_trans='OLN200906162'
SELECT * FROM olnsodetail WHERE id_trans='OLN200906162'
UPDATE olnsodetail SET harga_satuan=123750,harga_act=123750,subtotal=123750,subtotal_act=123750 WHERE id_trans='OLN200906162'

 
