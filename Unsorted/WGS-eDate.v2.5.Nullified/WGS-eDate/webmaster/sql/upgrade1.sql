CREATE TABLE chatmessages (
  id int(11) NOT NULL auto_increment,
  member int(11) NOT NULL default '0',
  channel varchar(255) NOT NULL default '',
  target int(11) NOT NULL default '0',
  line varchar(255) NOT NULL default '',
  rdate int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY member (member),
  KEY channel (channel),
  KEY target (target),
  KEY rdate (rdate)
) TYPE=MyISAM COMMENT='Chat messages.';

DELETE FROM sysvars WHERE 1;
INSERT INTO sysvars VALUES ('','Email sender address','PAID_MAIL','contact@yourdomain.com','email');
INSERT INTO sysvars VALUES ('','Admin notification sender address','ADM_MAIL','contact@yourdomain.com','email');
INSERT INTO sysvars VALUES ('','Admin register sender address','ADMIN_MAIL','contact@yourdomain.com','email');
INSERT INTO sysvars VALUES ('','Table header color','color_head','#F0F0F0','color');
INSERT INTO sysvars VALUES ('','Seconds of inactivity to set the user offline','onlinetimeout','180','timing');
INSERT INTO sysvars VALUES ('','Seconds before chat messages are deleted','chatmessagetimeout','240','timing');
INSERT INTO sysvars VALUES ('','Silver membership cost','mem_silver_cost','10','paid');
INSERT INTO sysvars VALUES ('','Gold membership cost','mem_gold_cost','15','paid');
INSERT INTO sysvars VALUES ('','Platinum membership cost','mem_platinum_cost','20','paid');
INSERT INTO sysvars VALUES ('','Free maximum pictures','mem_free_pics','4','paid');
INSERT INTO sysvars VALUES ('','Silver maximum pictures','mem_silver_pics','20','paid');
INSERT INTO sysvars VALUES ('','Gold maximum pictures','mem_gold_pics','50','paid');
INSERT INTO sysvars VALUES ('','Platinum maximum pictures','mem_platinum_pics','150','paid');
INSERT INTO sysvars VALUES ('','Free members can send messages','mem_free_message','1','feature');
INSERT INTO sysvars VALUES ('','Free members can chat','mem_free_chat','1','feature');
INSERT INTO sysvars VALUES ('','Silver, Gold and Platinum members can see web identity','mem_silver_web','1','feature');
INSERT INTO sysvars VALUES ('','Gold and Platinum members can contact by email','mem_gold_emails','1','feature');
INSERT INTO sysvars VALUES ('','Platinum members can contact by fax/phone','mem_platinum_phone','1','feature');

DELETE FROM menus WHERE 1;
INSERT INTO menus VALUES ('','Members','','10');
INSERT INTO menus VALUES ('','Search members','searchmembers.php','50');
INSERT INTO menus VALUES ('','Marketing','','10');
INSERT INTO menus VALUES ('','Process payments','process.php','50');
INSERT INTO menus VALUES ('','System Configuration','','10');
INSERT INTO menus VALUES ('','Settings','sqledit.php?table=sysvars&cond=WHERE 1 ORDER BY vtype DESC','30');
INSERT INTO menus VALUES ('','Development','','100');
INSERT INTO menus VALUES ('','Reset tables','install.php','100');