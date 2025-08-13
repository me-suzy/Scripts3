PRINT 'dbo.qCart'
GO

IF NOT OBJECT_ID('dbo.qCart') IS NULL
    DROP VIEW dbo.qCart
GO

CREATE VIEW dbo.qCart
AS

SELECT
	TBLCART.CartItemID,
	TBLCART.COST,
	TBLCART.CUSTID,
	TBLCART.EXPIRE,
	TBLCART.ITEMID,
	TBLCART.ITEMNAME,
	TBLCART.QUANTITY,
	TBLCART.SKU,
	TBLINVENTORY.ITEMID AS InventoryItemID,
	TBLINVENTORY.ITEMNAME AS InvItemName,
	TBLINVENTORY.MANUF,
	TBLINVENTORY.SHIPTIME,
	TBLINVENTORY.ONSALE,
	TBLINVENTORY.SHOWIT AS InvShowIt,
	TBLSHIPTIME.IMG AS SHIPTIMEIMG,
	TBLMANUFACTURERS.DESCRIP,
	TBLMANUFACTURERS.IMG,
	TBLMANUFACTURERS.MANUFID,
	TBLMANUFACTURERS.NAME,
	TBLMANUFACTURERS.SHOWIT,
	tblModifierGroups.GroupName,
	tblModifiers.Name AS ModifierName,
	tblModifiers.ModifierID,
	tblModifierGroups.ID AS ModifierGroupID,
	TBLINVENTORY.WEIGHT,
	TBLINVENTORY.AltWeight
FROM
    TBLCART
    LEFT JOIN TBLINVENTORY
        ON TBLCART.ITEMID = TBLINVENTORY.ITEMID
    LEFT JOIN TBLMANUFACTURERS
        ON TBLINVENTORY.MANUF = TBLMANUFACTURERS.MANUFID
    LEFT JOIN TBLSHIPTIME
        ON TBLINVENTORY.SHIPTIME = TBLSHIPTIME.ID
    LEFT JOIN tblCartModifiers
        ON TBLCART.CartItemID = tblCartModifiers.CartItemID
    LEFT JOIN tblModifiers
        ON tblCartModifiers.ModifierID = tblModifiers.ModifierID
    LEFT JOIN tblModifierGroups
        ON tblModifiers.ModifierGroup = tblModifierGroups.ID

GO

PRINT 'dbo.qCartRelatedItems'
GO

IF NOT OBJECT_ID('dbo.qCartRelatedItems') IS NULL
    DROP VIEW dbo.qCartRelatedItems
GO

CREATE VIEW dbo.qCartRelatedItems
AS

SELECT DISTINCT
    TBLCART.CUSTID,
    TBLINVENTORY.ITEMNAME,
    TBLINVENTORY.ITEMID
FROM
    TBLCART
    INNER JOIN tblRelatedItems
        ON TBLCART.ITEMID = tblRelatedItems.ITEMID
    INNER JOIN tblINVENTORY
        ON TBLINVENTORY.ITEMID = tblRelatedItems.RelatedITEMID

GO

PRINT 'dbo.qCustomerCartModifiers'
GO

IF NOT OBJECT_ID('dbo.qCustomerCartModifiers') IS NULL
    DROP VIEW dbo.qCustomerCartModifiers
GO

CREATE VIEW dbo.qCustomerCartModifiers
AS

SELECT
    tblCartCoupons.CustID,
    tblCoupons.COUPONID,
    tblCartCoupons.CouponID AS CartCouponsCouponID,
    tblCoupons.CouponItemID,
    tblCoupons.PD,
    tblCoupons.MAXQTY,
    tblCoupons.EXPIREDATE,
    tblCoupons.Used,
    TBLINVENTORY.*,
    tblModifiers.ModifierID,
    tblModifiers.Name AS ModifierName,
    tblModifierGroups.GroupName,
    tblModifierGroups.ID AS ModifierGroup,
    TBLMANUFACTURERS.NAME AS ManufName
FROM
    tblCartCoupons
    INNER JOIN tblCoupons
        ON tblCartCoupons.CouponID = tblCoupons.COUPONID
           AND tblCoupons.EXPIREDATE > GETDATE()
    INNER JOIN TBLINVENTORY
        ON tblCoupons.ITEMID = TBLINVENTORY.ITEMID
    LEFT JOIN (
                  tblCouponModifiers
                  LEFT JOIN tblModifiers
                      ON tblCouponModifiers.ModifierID = tblModifiers.ModifierID
                  LEFT JOIN tblModifierGroups
                      ON tblModifiers.ModifierGroup = tblModifierGroups.ID
              )
        ON tblCoupons.CouponItemID = tblCouponModifiers.CouponItemID
    LEFT JOIN TBLMANUFACTURERS
        ON TBLINVENTORY.MANUF = TBLMANUFACTURERS.MANUFID

GO

PRINT 'dbo.qGroupsParents'
GO

IF NOT OBJECT_ID('dbo.qGroupsParents') IS NULL
    DROP VIEW dbo.qGroupsParents
GO

CREATE VIEW dbo.qGroupsParents
AS

SELECT
    tblGroups.GROUPID,
    tblGroups.NAME,
    tblGroups.DESCRIP,
    tblGroups.IMG,
    tblParent.GROUPID AS ParentID,
    tblParent.NAME AS ParentName,
    tblGroups.SHOWIT
FROM
    tblGroups
    LEFT JOIN tblGroupsParents
        ON tblGroups.GROUPID = tblGroupsParents.GroupID
    LEFT JOIN tblGroups AS tblParent
        ON tblGroupsParents.ParentID = tblParent.GROUPID
WHERE
    tblGroups.SHOWIT = -1

GO

PRINT 'dbo.qInventory'
GO

IF NOT OBJECT_ID('dbo.qInventory') IS NULL
    DROP VIEW dbo.qInventory
GO

CREATE VIEW dbo.qInventory
AS

SELECT
    TBLINVENTORY.*,
    TBLMANUFACTURERS.NAME AS ManufName,
    TBLGROUPS.GROUPID,
    TBLGROUPS.NAME AS GroupName,
    TBLSHIPTIME.SHIPTIME AS ShipTimeDesc,
    TBLSHIPTIME.IMG AS ShipTimeImg,
    TBLGROUPS.DESCRIP AS GroupDescrip,
    TBLINV_UNITS.UNIT AS UNITNAME
FROM
    TBLGROUPS
    INNER JOIN tblInventoryGroups
        ON TBLGROUPS.GROUPID = tblInventoryGroups.GroupID
    INNER JOIN TBLINVENTORY
        ON TBLINVENTORY.ITEMID = tblInventoryGroups.ItemID
           AND TBLINVENTORY.SHOWIT = -1
    INNER JOIN TBLMANUFACTURERS
        ON TBLMANUFACTURERS.MANUFID = TBLINVENTORY.MANUF
    LEFT JOIN TBLSHIPTIME
        ON TBLSHIPTIME.ID = TBLINVENTORY.SHIPTIME
    LEFT JOIN TBLINV_UNITS
        ON TBLINV_UNITS.ID = TBLINVENTORY.UNIT

GO

PRINT 'dbo.qInventoryByGroup'
GO

IF NOT OBJECT_ID('dbo.qInventoryByGroup') IS NULL
    DROP VIEW dbo.qInventoryByGroup
GO

CREATE VIEW dbo.qInventoryByGroup
AS

SELECT
    TBLINVENTORY.ITEMID,
    TBLINVENTORY.SKU,
    TBLINVENTORY.ITEMNAME,
    TBLINVENTORY.DESCRIP,
    TBLINVENTORY.ONSALE,
    TBLINVENTORY.SALEDISCOUNT,
    TBLINVENTORY.COST,
    tblInv_Units.UNIT,
    TBLINVENTORY.SHOWIT,
    TBLMANUFACTURERS.NAME AS ManufName,
    TBLGROUPS.NAME AS GroupName,
    tblInventoryGroups.GroupID,
    TBLINVENTORY.MANUF,
    TBLINVENTORY.IMG_SMALL AS Thumbnail
FROM
    TBLMANUFACTURERS
    INNER JOIN TBLINVENTORY
        ON TBLINVENTORY.MANUF = tblManufacturers.ManufID
           AND TBLINVENTORY.SHOWIT = -1
    INNER JOIN tblInv_Units
        ON TBLINVENTORY.UNIT = tblInv_Units.ID
    INNER JOIN tblInventoryGroups
        ON tblInventoryGroups.ItemID = tblInventory.ItemID
    INNER JOIN TBLGROUPS
        ON TBLGROUPS.GROUPID = tblInventoryGroups.GroupID

GO

PRINT 'dbo.qInventoryByManuf'
GO

IF NOT OBJECT_ID('dbo.qInventoryByManuf') IS NULL
    DROP VIEW dbo.qInventoryByManuf
GO

CREATE VIEW dbo.qInventoryByManuf
AS

SELECT
    TBLINVENTORY.ITEMID,
    TBLINVENTORY.SKU,
    TBLINVENTORY.ITEMNAME,
    TBLINVENTORY.DESCRIP,
    TBLINVENTORY.ONSALE,
    TBLINVENTORY.SALEDISCOUNT,
    TBLINVENTORY.COST,
    tblInv_Units.UNIT,
    TBLINVENTORY.SHOWIT,
    TBLMANUFACTURERS.NAME AS ManufName,
    TBLINVENTORY.MANUF,
    TBLINVENTORY.IMG_SMALL AS Thumbnail
FROM
    TBLMANUFACTURERS
    INNER JOIN TBLINVENTORY
        ON TBLINVENTORY.MANUF = tblManufacturers.ManufID
           AND TBLINVENTORY.SHOWIT = -1
    INNER JOIN tblInv_Units
        ON TBLINVENTORY.UNIT = tblInv_Units.ID

GO

PRINT 'dbo.qInventoryModifiers'
GO

IF NOT OBJECT_ID('dbo.qInventoryModifiers') IS NULL
    DROP VIEW dbo.qInventoryModifiers
GO

CREATE VIEW dbo.qInventoryModifiers
AS

SELECT
    tblInvModifiers.ItemID,
    tblInvModifiers.AltImg,
    tblInvModifiers.AdditionalCost,
    tblModifiers.Name,
    tblModifierGroups.GroupName,
    tblModifierGroups.Description,
    tblModifiers.ModifierGroup,
    tblModifiers.ModifierID,
    tblInvModifiers.ModifierID AS SecondModifierID,
    tblInventory.Cost + tblInvModifiers.AdditionalCost AS FinalCost,
    TBLINVENTORY.ITEMNAME
FROM
    tblModifierGroups
    INNER JOIN tblModifiers
        ON tblModifierGroups.ID = tblModifiers.ModifierGroup
    INNER JOIN tblInvModifiers
        ON tblModifiers.ModifierID = tblInvModifiers.ModifierID
    INNER JOIN TBLINVENTORY
        ON TBLINVENTORY.ITEMID = tblInvModifiers.ItemID

GO

PRINT 'dbo.qOrders'
GO

IF NOT OBJECT_ID('dbo.qOrders') IS NULL
    DROP VIEW dbo.qOrders
GO

CREATE VIEW dbo.qOrders
AS

SELECT DISTINCT
    tblOrders.OrderID,
    tblOrders.CCFailed,
    tblOrders.CustID AS OrdersCustID,
    tblOrders.ShipToAddressID,
    tblCreditCardInfo.CCNum AS Pay_CCNum,
    tblCreditCardInfo.NameOnCard AS Pay_Name,
    tblCreditCardInfo.CardType AS Pay_Type,
    tblCreditCardInfo.ExpireMonth AS Pay_Expire_M,
    tblCreditCardInfo.ExpireYear AS Pay_Expire_Y,
    tblOrders.Tax,
    tblOrders.Shipping,
    tblOrders.SelShipCost,
    tblOrders.HandlingCost,
    tblOrdersItems.OrderItemID,
    tblOrdersItems.ItemID,
    tblOrdersItems.SKU,
    tblManufacturers.NAME AS ManufName,
    tblOrdersItems.ItemName,
    tblOrdersItems.Cost,
    tblOrdersItems.Quantity,
    tblOrdersItems.ShipDate,
    tblOrdersItems.Status,
    tblOrdersItems.CARRIER,
    tblOrdersItems.TRACKINGNUMBER,
    tblOrdersItems.StatusDate,
    tblContacts.Password,
    tblContacts.FName AS Bill_FName,
    tblContacts.LName AS Bill_LName,
    tblContacts.Address1 AS Bill_ADDR1,
    tblContacts.Address2 AS Bill_Addr2,
    tblContacts.City AS Bill_City,
    tblContacts.State AS Bill_state,
    tblContacts.Zip AS Bill_Zip,
    tblContacts.Country AS BILL_COUNTRY,
    tblContacts.Phone AS Bill_Phone,
    tblContacts.EMail AS Bill_EMail,
    tblOrders.TheDate,
    tblContacts_1.FName AS Ship_FName,
    tblContacts_1.LName AS Ship_LName,
    tblContacts_1.Address1 AS Ship_ADDR1,
    tblContacts_1.Address2 AS Ship_ADDR2,
    tblContacts_1.City AS Ship_City,
    tblContacts_1.State AS Ship_State,
    tblContacts_1.Zip AS Ship_Zip,
    tblContacts_1.Country AS SHIP_COUNTRY,
    tblContacts_1.Phone AS Ship_Phone,
    tblContacts_1.EMail AS Ship_EMail,
    tblOrdersItems.Cost * CONVERT(MONEY, tblOrdersItems.Quantity) AS TotalItemCost,
    tblModifiers.Name AS ModifierName,
    tblModifierGroups.GroupName AS ModifierGroupName,
    tblInventory.DOWNLOADABLE,
    tblInventory.WEIGHT
FROM
    tblOrders
    INNER JOIN tblOrdersItems
        ON tblOrders.OrderID = tblOrdersItems.OrderID
    INNER JOIN tblInventory
        ON tblOrdersItems.ItemID = tblInventory.ITEMID
    INNER JOIN tblManufacturers
        ON tblInventory.MANUF = tblManufacturers.MANUFID
    LEFT JOIN tblContacts
        ON tblOrders.CustID = tblContacts.CustID
    LEFT JOIN tblContacts AS tblContacts_1
        ON tblOrders.ShipToAddressID = tblContacts_1.ContactID
    LEFT JOIN tblOrdersItemsModifiers
        ON tblOrdersItems.OrderItemID = tblOrdersItemsModifiers.OrderItemID
    LEFT JOIN tblModifiers
        ON tblOrdersItemsModifiers.ModifierID = tblModifiers.ModifierID
    LEFT JOIN tblModifierGroups
        ON tblModifiers.ModifierGroup = tblModifierGroups.ID
    LEFT JOIN tblCreditCardInfo
        ON tblOrders.CCID = tblCreditCardInfo.CCID

GO

PRINT 'dbo.qOrderSubtotal'
GO

IF NOT OBJECT_ID('dbo.qOrderSubtotal') IS NULL
    DROP VIEW dbo.qOrderSubtotal
GO

CREATE VIEW dbo.qOrderSubtotal
AS

SELECT
    tblOrders.OrderID,
    SUM(tblOrdersItems.Cost * CONVERT(MONEY, tblOrdersItems.Quantity)) AS SubTotal
FROM
    tblOrders
    INNER JOIN tblOrdersItems
        ON tblOrders.OrderID = tblOrdersItems.OrderID
GROUP BY
    tblOrders.OrderID

GO

PRINT 'dbo.qWebCustomers'
GO

IF NOT OBJECT_ID('dbo.qWebCustomers') IS NULL
    DROP VIEW dbo.qWebCustomers
GO

CREATE VIEW dbo.qWebCustomers
AS

SELECT
    tblContacts.*,
    tblContactTypeInfo.ContactID AS ContactTypeCustID,
    tblContactTypeInfo.ContactTypeID
FROM
    tblContacts
    INNER JOIN tblContactTypeInfo
        ON tblContacts.ContactID = tblContactTypeInfo.ContactID
           AND tblContactTypeInfo.ContactTypeID = 1

GO

PRINT 'dbo.qWebCustomersShipTo'
GO

IF NOT OBJECT_ID('dbo.qWebCustomersShipTo') IS NULL
    DROP VIEW dbo.qWebCustomersShipTo
GO

CREATE VIEW dbo.qWebCustomersShipTo
AS

SELECT
    tblContacts.*,
    tblContactTypeInfo.ContactID AS CCTIContactID,
    tblContactTypeInfo.ContactTypeID
FROM
    tblContacts
    INNER JOIN tblContactTypeInfo
        ON tblContacts.ContactID = tblContactTypeInfo.ContactID
           AND tblContactTypeInfo.ContactTypeID IN (1, 2)

GO